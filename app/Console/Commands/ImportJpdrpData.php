<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\CrawlerHistory;
use PharData;

class ImportJpdrpData extends Command
{
    protected $signature = 'import:jpdrp:data';
    protected $description = 'Import JPDRP data';

    public function handle()
    {
        try {
            // Check if there's any record currently being processed
            $processingRecord = CrawlerHistory::where('status', CrawlerHistory::STATUS_PROCESSING)->first();

            if ($processingRecord) {
                $this->info('Another import process is already running. Skipping this execution.');
                return Command::SUCCESS;
            }

            $history = CrawlerHistory::where('status', CrawlerHistory::STATUS_NEW)
                ->orderBy('release_date')
                ->first();

            if (!$history) {
                $this->info('No records with status "new" found.');
                return Command::SUCCESS;
            }

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

            $this->info('Start import data at: ' . now()->format('Y-m-d H:i:s'));

            $successCount = 0;
            $errorCount = 0;

            // Process each TSV file
            foreach ($tsvFiles as $tsvFilePath) {
                $tableName = $this->getTableNameFromFile($tsvFilePath);
                $fileName = basename($tsvFilePath);

                try {
                    $result = $this->processTsvFile($tsvFilePath, $tableName);
                    if ($result) {
                        $this->info("Importing {$fileName} to {$tableName} -> Finished");
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
            } else {
                $history->update(['status' => CrawlerHistory::STATUS_ERROR]);
            }

            // Delete extracted folder
            $this->deleteExtractedFolder($jpdapFolder);
            // Delete downloaded file
            $this->deleteDownloadedFile($history);

            $this->info('Completed total at: ' . now()->format('Y-m-d H:i:s'));

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
        $uniqueColumns = $this->getUniqueConstraints($tableName);

        if (empty($uniqueColumns)) {
            $this->warn("  No unique constraints found for table '{$tableName}'. Skipping UPSERT.");
            return false;
        }

        $tempTableName = $tableName . '_temp_' . time();

        try {
            // Create temporary table with same structure
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

            $result = DB::statement($copyCommand);

            if (!$result) {
                $this->error("  Failed to import data to temporary table");
                return false;
            }

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

            // Prepare columns for UPSERT (exclude id, timestamps, and unique constraint columns)
            $excludeFromUpdate = array_merge(
                ['id', 'created_at', 'updated_at'],
                $uniqueColumns
            );

            $updateColumns = array_filter($columns, function($col) use ($excludeFromUpdate) {
                return !in_array(strtolower($col), array_map('strtolower', $excludeFromUpdate));
            });

            if (empty($updateColumns)) {
                $updateSetClause = "id = EXCLUDED.id"; // Dummy update to make syntax valid
            } else {
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

            $upsertResult = DB::statement($upsertSql);

            if ($upsertResult) {
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
            } catch (\Exception $e) {
                // Silent fail
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
            $this->info('Started download at: ' . now()->format('Y-m-d H:i:s'));

            // Extract filename from URL (e.g., JPDAP_20241109.tar.gz)
            $urlParts = parse_url($url);
            $pathParts = explode('/', $urlParts['path']);
            $filename = end($pathParts);

            if (empty($filename)) {
                $this->error("Could not extract filename from URL");
                return null;
            }

            $this->info("File download: {$filename}");

            // Set download directory
            $downloadDir = storage_path('app/temte_files');
            if (!File::isDirectory($downloadDir)) {
                File::makeDirectory($downloadDir, 0755, true);
            }

            // Full path to downloaded file
            $filePath = $downloadDir . '/' . $filename;

            // Download the file
            $response = Http::timeout(300)->withOptions([
                'sink' => $filePath,
            ])->get($url);

            if (!$response->successful()) {
                $this->error("Failed to download file. HTTP Status: " . $response->status());
                return null;
            }

            // Extract the file
            // Remove .tar.gz extension to get folder name (e.g., JPDAP_20241109.tar.gz -> JPDAP_20241109)
            $folderName = $filename;
            if (str_ends_with(strtolower($folderName), '.tar.gz')) {
                $folderName = substr($folderName, 0, -7); // Remove .tar.gz (7 characters)
            } else {
                $folderName = pathinfo($folderName, PATHINFO_FILENAME);
            }

            $extractDir = $downloadDir . '/' . $folderName;
            // Remove existing extraction directory if it exists
            if (File::isDirectory($extractDir)) {
                File::deleteDirectory($extractDir);
            }

            File::makeDirectory($extractDir, 0755, true);

            // Extract tar.gz file
            $extractedFolder = $this->extractTarGz($filePath, $extractDir);

            if (!$extractedFolder) {
                $this->error("Failed to extract file");
                return null;
            }

            // Verify that TSV files exist in the extracted folder
            $tsvFiles = File::glob($extractedFolder . '/*.tsv');
            if (empty($tsvFiles)) {
                $this->error("No TSV files found in extracted folder: {$extractedFolder}");
                return null;
            }

            $relativeExtractedFolder = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $extractedFolder);
            $this->info("Extracted folder: " . $relativeExtractedFolder);
            return $extractedFolder;

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
     * @return string|null Path to extracted folder or null on failure
     */
    private function extractTarGz($filePath, $extractTo)
    {
        try {
            $phar = new PharData($filePath);
            $phar->extractTo($extractTo);
            // Get the actual extracted folder path
            return $this->getExtractedFolderPath($extractTo);
        } catch (\Exception $e) {
            $this->error("Failed to extract tar.gz: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the actual extracted folder path after extraction
     * If there's a single subdirectory, return that subdirectory path
     * Otherwise, return the extractTo path (files extracted directly)
     *
     * @param string $extractTo
     * @return string
     */
    private function getExtractedFolderPath($extractTo)
    {
        if (!File::isDirectory($extractTo)) {
            return $extractTo;
        }

        // Get all items in the extract directory
        $items = File::files($extractTo);
        $directories = File::directories($extractTo);

        // If there's exactly one directory and no files at root level, return that directory
        if (count($directories) === 1 && empty($items)) {
            $folderPath = $directories[0];
            return $folderPath;
        }

        // If files are extracted directly (no single subdirectory), return extractTo
        return $extractTo;
    }

    /**
     * Delete the extracted folder after processing
     * Deletes the parent folder (one level up) to clean up the entire extraction directory
     *
     * @param string $folderPath
     * @return void
     */
    private function deleteExtractedFolder($folderPath)
    {
        try {
            // Get parent folder (one level up) and delete it
            $parentFolder = dirname($folderPath);

            if (File::isDirectory($parentFolder)) {
                File::deleteDirectory($parentFolder);
                $this->info("Deleted extracted folder: " . basename($parentFolder));
            }
        } catch (\Exception $e) {
            $this->warn("Failed to delete extracted folder: " . $e->getMessage());
            Log::warning("Failed to delete extracted folder {$folderPath}: " . $e->getMessage());
        }
    }

    /**
     * Delete the downloaded tar.gz file after processing
     *
     * @param CrawlerHistory $history
     * @return void
     */
    private function deleteDownloadedFile($history)
    {
        try {
            if (!$history->bulkdata_url) {
                return;
            }

            // Extract filename from URL (e.g., JPDRP_20241112.tar.gz)
            $urlParts = parse_url($history->bulkdata_url);
            $pathParts = explode('/', $urlParts['path']);
            $filename = end($pathParts);

            if (empty($filename)) {
                return;
            }

            // Construct the file path
            $filePath = storage_path('app/temte_files') . '/' . $filename;

            if (File::exists($filePath)) {
                File::delete($filePath);
                $this->info("Deleted downloaded file: " . $filename);
            }
        } catch (\Exception $e) {
            $this->warn("Failed to delete downloaded file: " . $e->getMessage());
            Log::warning("Failed to delete downloaded file: " . $e->getMessage());
        }
    }
}
