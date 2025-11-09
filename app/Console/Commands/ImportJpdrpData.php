<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\CrawlerHistory;
use ZipArchive;
use PharData;

class ImportJpdrpData extends Command
{
    protected $signature = 'import:jpdrp:data';

    protected $description = 'Import JPDRP data';

    public function handle()
    {
        $this->info('Started at to import JPDRP data: ' . now()->format('Y-m-d H:i:s'));

        try {
            $history = CrawlerHistory::where('status', CrawlerHistory::STATUS_NEW)
                ->orderBy('release_date')
                ->first();

            if (!$history) {
                $this->info('No records with status "new" found.');
                return Command::SUCCESS;
            }

            $this->info("Processing record ID: {$history->id}, File: {$history->file_name}");
            $history->update(['status' => CrawlerHistory::STATUS_PROCESSING]);

            // Download and extract the TSV file based on history record
            $jpdapFolder = $this->downloadAndExtractFile($history);

            if (!$jpdapFolder) {
                $this->error("Failed to download and extract file from: {$history->bulkdata_url}");
                $history->update(['status' => CrawlerHistory::STATUS_ERROR]);
                return Command::FAILURE;
            }

            // Get all TSV files in the extracted folder
            $tsvFiles = $this->getTsvFiles($jpdapFolder);

            if (empty($tsvFiles)) {
                $this->error("No TSV files found in folder: {$jpdapFolder}");
                return Command::FAILURE;
            }

            $this->info("Found " . count($tsvFiles) . " TSV files to process:");
            foreach ($tsvFiles as $file) {
                $this->info("- " . basename($file));
            }

            $successCount = 0;
            $errorCount = 0;

            // Process each TSV file
            foreach ($tsvFiles as $tsvFilePath) {
                $tableName = $this->getTableNameFromFile($tsvFilePath);

                $this->info("Processing file: " . basename($tsvFilePath) . " → table: {$tableName}");

                try {
                    $result = $this->processTsvFile($tsvFilePath, $tableName);
                    if ($result) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                } catch (\Exception $e) {
                    $this->error("Error processing {$tsvFilePath}: " . $e->getMessage());
                    $errorCount++;
                }
            }

            // Update final status based on results
            if ($errorCount === 0) {
                $history->update(['status' => CrawlerHistory::STATUS_COMPLETED]);
                $this->info("All files processed successfully: {$successCount} succeeded, {$errorCount} failed");
            } else {
                $history->update(['status' => CrawlerHistory::STATUS_ERROR]);
                $this->error("Some files failed to process: {$successCount} succeeded, {$errorCount} failed");
            }

            $this->info('Finished at: ' . now()->format('Y-m-d H:i:s'));

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Fatal error: ' . $e->getMessage());
            Log::error('Fatal error in ImportJpdrpData: ' . $e->getMessage());

            // Update status to error if history exists
            if (isset($history)) {
                $history->update(['status' => CrawlerHistory::STATUS_ERROR, 'error_logs' => [$e->getMessage()]]);
            }

            return Command::FAILURE;
        }
    }

    /**
     * Get unique constraint columns for a table
     *
     * @param string $tableName
     * @return array
     */
    private function getUniqueConstraints($tableName)
    {
        try {
            // Query PostgreSQL system tables to get unique constraints
            $sql = "
                SELECT
                    a.attname as column_name
                FROM
                    pg_constraint c
                    JOIN pg_class t ON c.conrelid = t.oid
                    JOIN pg_attribute a ON a.attrelid = t.oid AND a.attnum = ANY(c.conkey)
                WHERE
                    t.relname = ?
                    AND c.contype = 'u'
                ORDER BY
                    array_position(c.conkey, a.attnum)
            ";

            $results = DB::select($sql, [$tableName]);

            return array_map(function($result) {
                return $result->column_name;
            }, $results);

        } catch (\Exception $e) {
            $this->warn("Could not retrieve unique constraints: " . $e->getMessage());

            // Fallback: Return known unique columns for this specific table
            if ($tableName === 'upd_cmbi_g_bul_info') {
                return ['law_cd', 'app_num'];
            }

            return [];
        }
    }

    /**
     * Get TSV file path based on history record
     *
     * @param CrawlerHistory $history
     * @return string
     */
    private function getTsvFilePath($history)
    {
        // For now, use the hardcoded path, but this can be made dynamic
        // based on the history file_name or other properties
        return storage_path('app/temte_files/JPDAP/upd_cmbi_g_bul_info.tsv');
    }

    /**
     * Get all TSV files from a directory
     *
     * @param string $directory
     * @return array
     */
    private function getTsvFiles($directory)
    {
        if (!File::isDirectory($directory)) {
            return [];
        }

        $files = File::glob($directory . '/*.tsv');

        // Filter out backup files and temporary files
        return array_filter($files, function($file) {
            $basename = basename($file);
            return !str_contains($basename, 'back') &&
                   !str_contains($basename, '.clean') &&
                   !str_contains($basename, 'temp');
        });
    }

    /**
     * Get table name from TSV file path
     *
     * @param string $filePath
     * @return string
     */
    private function getTableNameFromFile($filePath)
    {
        $filename = basename($filePath, '.tsv');
        return $filename;
    }

    /**
     * Process a single TSV file
     *
     * @param string $tsvFilePath
     * @param string $tableName
     * @return bool
     */
    private function processTsvFile($tsvFilePath, $tableName)
    {
        if (!File::exists($tsvFilePath)) {
            $this->error("TSV file does not exist: {$tsvFilePath}");
            return false;
        }

        // Check if table exists
        if (!Schema::hasTable($tableName)) {
            $this->warn("Table '{$tableName}' does not exist. Skipping file: " . basename($tsvFilePath));
            return false;
        }

        // Create $columns array dynamically from the first line of the TSV file
        $firstLine = fgets(fopen($tsvFilePath, 'r'));
        $columns = array_map('trim', explode("\t", $firstLine));

        $columnList = implode(',', array_map(function ($col) {
            return '"' . $col . '"';
        }, $columns));

        /** Get unique constraints for UPSERT */
        $this->info("  Getting unique constraints for table '{$tableName}'...");
        $uniqueColumns = $this->getUniqueConstraints($tableName);

        if (empty($uniqueColumns)) {
            $this->warn("  No unique constraints found for table '{$tableName}'. Skipping UPSERT.");
            return false;
        }

        $this->info("  Found unique constraint on columns: " . implode(', ', $uniqueColumns));

        /** Import via COPY with UPSERT using temporary table */
        $this->info("  Starting COPY command with UPSERT logic...");

        $tempTableName = $tableName . '_temp_' . time();

        try {
            // Create temporary table with same structure
            $this->info("  Creating temporary table '{$tempTableName}'...");
            $createTempTableSql = sprintf(
                "CREATE TEMP TABLE %s AS SELECT * FROM %s WHERE 1=0",
                $tempTableName,
                $tableName
            );
            DB::statement($createTempTableSql);

            // Import data to temporary table first with NULL handling
            $copyCommand = sprintf(
                "COPY %s (%s) FROM '%s' WITH (FORMAT csv, DELIMITER E'\\t', HEADER true, ESCAPE '\"', NULL '')",
                $tempTableName,
                $columnList,
                $tsvFilePath
            );

            $this->info("  Importing data to temporary table...");
            $result = DB::statement($copyCommand);

            if (!$result) {
                $this->error("  Failed to import data to temporary table");
                return false;
            }

            // Get count from temp table
            $tempRowCount = DB::table($tempTableName)->count();
            $this->info("  Imported {$tempRowCount} rows to temporary table");

            // Remove duplicates within the temp table based on unique constraints
            $conflictColumns = implode(', ', array_map(function($col) {
                return '"' . $col . '"';
            }, $uniqueColumns));

            $deduplicateSql = sprintf(
                "DELETE FROM %s WHERE ctid NOT IN (
                    SELECT MIN(ctid) FROM %s GROUP BY %s
                )",
                $tempTableName,
                $tempTableName,
                $conflictColumns
            );

            DB::statement($deduplicateSql);
            $dedupRowCount = DB::table($tempTableName)->count();

            if ($dedupRowCount < $tempRowCount) {
                $this->info("  Removed " . ($tempRowCount - $dedupRowCount) . " duplicate rows from temp table");
            }

            // Prepare columns for UPSERT (exclude id, timestamps, and unique constraint columns)
            $excludeFromUpdate = array_merge(
                ['id', 'created_at', 'updated_at'],
                $uniqueColumns
            );

            $updateColumns = array_filter($columns, function($col) use ($excludeFromUpdate) {
                return !in_array(strtolower($col), array_map('strtolower', $excludeFromUpdate));
            });

            if (empty($updateColumns)) {
                $this->info("  No columns to update on conflict. Only inserting new records.");
                $updateSetClause = "id = EXCLUDED.id"; // Dummy update to make syntax valid
            } else {
                $this->info("  Columns to update on conflict: " . implode(', ', $updateColumns));
                $updateSetClause = implode(', ', array_map(function($col) {
                    return sprintf('"%s" = EXCLUDED."%s"', $col, $col);
                }, $updateColumns));
            }

            $conflictColumns = implode(', ', array_map(function($col) {
                return '"' . $col . '"';
            }, $uniqueColumns));

            // Perform UPSERT from temporary table to main table
            $upsertSql = sprintf(
                "INSERT INTO %s (%s)
                 SELECT %s FROM %s
                 ON CONFLICT (%s) DO UPDATE SET %s",
                $tableName,
                $columnList,
                $columnList,
                $tempTableName,
                $conflictColumns,
                $updateSetClause
            );

            $this->info("  Executing UPSERT operation...");
            $upsertResult = DB::statement($upsertSql);

            if ($upsertResult) {
                $this->info("  Data imported/updated successfully into table '{$tableName}'");

                // Get final row count for confirmation
                $finalRowCount = DB::table($tableName)->count();
                $this->info("  Total rows in table '{$tableName}': {$finalRowCount}");

                return true;
            } else {
                $this->error("  Failed to perform UPSERT operation");
                return false;
            }

        } catch (\Exception $e) {
            $this->error("  Error processing {$tableName}: " . $e->getMessage());
            return false;
        } finally {
            // Always cleanup temporary table
            try {
                DB::statement("DROP TABLE IF EXISTS {$tempTableName}");
                $this->info("  Temporary table dropped");
            } catch (\Exception $e) {
                $this->warn("  Could not drop temporary table: " . $e->getMessage());
            }
        }
    }

    /**
     * Download and extract file from bulkdata_url
     *
     * @param CrawlerHistory $history
     * @return string|null Path to extracted folder or null on failure
     */
    private function downloadAndExtractFile($history)
    {
        // Increase memory limit for large file processing
        ini_set('memory_limit', '1G');

        try {
            if (!$history->bulkdata_url) {
                $this->error("No bulkdata_url found in history record");
                return null;
            }

            $url = $history->bulkdata_url;
            $this->info("Downloading file from: {$url}");

            // Extract filename from URL (e.g., JPDAP_20241109.tar.gz)
            $urlParts = parse_url($url);
            $pathParts = explode('/', $urlParts['path']);
            $filename = end($pathParts);

            if (empty($filename)) {
                $this->error("Could not extract filename from URL");
                return null;
            }

            $this->info("Filename: {$filename}");

            // Set download directory
            $downloadDir = storage_path('app/temte_files');
            if (!File::isDirectory($downloadDir)) {
                File::makeDirectory($downloadDir, 0755, true);
            }

            // Full path to downloaded file
            $filePath = $downloadDir . '/' . $filename;

            // Download the file
            $this->info("Downloading to: {$filePath}");
            $response = Http::timeout(300)->withOptions([
                'sink' => $filePath,
            ])->get($url);

            if (!$response->successful()) {
                $this->error("Failed to download file. HTTP Status: " . $response->status());
                return null;
            }

            $this->info("File downloaded successfully");

            // Extract the file
            $extractDir = $downloadDir . '/' . pathinfo($filename, PATHINFO_FILENAME);

            // Remove existing extraction directory if it exists
            if (File::isDirectory($extractDir)) {
                File::deleteDirectory($extractDir);
            }
            File::makeDirectory($extractDir, 0755, true);

            $this->info("Extracting to: {$extractDir}");

            // Determine file type and extract accordingly
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $extracted = false;

            if ($extension === 'gz') {
                // Check if it's tar.gz
                $basenameWithoutGz = pathinfo($filename, PATHINFO_FILENAME);
                if (str_ends_with(strtolower($basenameWithoutGz), '.tar')) {
                    $extracted = $this->extractTarGz($filePath, $extractDir);
                } else {
                    $this->error("Unsupported .gz file format");
                    return null;
                }
            } elseif ($extension === 'tar') {
                $extracted = $this->extractTar($filePath, $extractDir);
            } elseif ($extension === 'zip') {
                $extracted = $this->extractZip($filePath, $extractDir);
            } else {
                $this->error("Unsupported file format: {$extension}");
                return null;
            }

            if (!$extracted) {
                $this->error("Failed to extract file");
                return null;
            }

            // Find the folder containing TSV files
            $tsvFolder = $this->findJpdrpFolder($extractDir);

            if (!$tsvFolder) {
                // If no specific folder found, check if TSV files are directly in extractDir
                $tsvFiles = File::glob($extractDir . '/*.tsv');
                if (!empty($tsvFiles)) {
                    $this->info("TSV files found directly in extraction directory");
                    return $extractDir;
                }
                $this->error("No TSV files found in extracted content");
                return null;
            }

            $this->info("TSV folder found: {$tsvFolder}");
            return $tsvFolder;

        } catch (\Exception $e) {
            $this->error("Error downloading/extracting file: " . $e->getMessage());
            Log::error("Download/Extract error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract tar.gz file
     *
     * @param string $filePath
     * @param string $extractTo
     * @return bool
     */
    private function extractTarGz($filePath, $extractTo)
    {
        try {
            $phar = new PharData($filePath);
            $phar->extractTo($extractTo);
            $this->info("Successfully extracted tar.gz file");
            return true;
        } catch (\Exception $e) {
            $this->error("Failed to extract tar.gz: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract tar file
     *
     * @param string $filePath
     * @param string $extractTo
     * @return bool
     */
    private function extractTar($filePath, $extractTo)
    {
        try {
            $phar = new PharData($filePath);
            $phar->extractTo($extractTo);
            $this->info("Successfully extracted tar file");
            return true;
        } catch (\Exception $e) {
            $this->error("Failed to extract tar: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract zip file
     *
     * @param string $filePath
     * @param string $extractTo
     * @return bool
     */
    private function extractZip($filePath, $extractTo)
    {
        try {
            $zip = new ZipArchive;
            $result = $zip->open($filePath);

            if ($result === TRUE) {
                $zip->extractTo($extractTo);
                $zip->close();
                $this->info("Successfully extracted zip file");
                return true;
            } else {
                $this->error("Failed to open zip file. Error code: {$result}");
                return false;
            }
        } catch (\Exception $e) {
            $this->error("Failed to extract zip: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find JPDRP folder in extracted content
     *
     * @param string $extractedPath
     * @return string|null
     */
    private function findJpdrpFolder($extractedPath)
    {
        // Look for JPDRP, JPDAP, or similar folders
        $possibleFolders = ['JPDRP', 'JPDAP', 'jpdrp', 'jpdap'];

        foreach ($possibleFolders as $folderName) {
            $folderPath = $extractedPath . '/' . $folderName;
            if (File::isDirectory($folderPath)) {
                $this->info("Found data folder: {$folderName}");
                return $folderPath;
            }
        }

        // Look for any subdirectory that contains TSV files
        $subdirs = File::directories($extractedPath);
        foreach ($subdirs as $subdir) {
            $tsvFiles = File::glob($subdir . '/*.tsv');
            if (!empty($tsvFiles)) {
                $this->info("Found TSV files in: " . basename($subdir));
                return $subdir;
            }
        }

        return null;
    }
}
