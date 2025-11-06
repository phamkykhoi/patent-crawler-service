<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class SyncJpdrpData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jpdrp:sync {--folder= : Sync specific folder only} {--dry-run : Run without saving to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync JPDRP TSV files from folders to database';

    /**
     * File to table mapping configuration
     */
    protected $fileConfig = [
        // Master files (1 record per reg_num)
        'upd_mgt_info_p' => [
            'table' => 'upd_mgt_info_p',
            'key' => ['law_cd', 'reg_num'],
            'type' => 'master'
        ],
        'upd_mgt_info_u' => [
            'table' => 'upd_mgt_info_u',
            'key' => ['law_cd', 'reg_num'],
            'type' => 'master'
        ],
        'upd_mrgn_ext_app_num_p' => [
            'table' => 'upd_mrgn_ext_app_num_p',
            'key' => ['law_cd', 'reg_num'],
            'type' => 'master'
        ],

        // Detail files với pe_num
        'upd_atty_art_p' => [
            'table' => 'upd_atty_art_p',
            'key' => ['law_cd', 'reg_num', 'pe_num'],
            'type' => 'detail_pe'
        ],
        'upd_atty_art_u' => [
            'table' => 'upd_atty_art_u',
            'key' => ['law_cd', 'reg_num', 'pe_num'],
            'type' => 'detail_pe'
        ],
        'upd_prog_info_div_p' => [
            'table' => 'upd_prog_info_div_p',
            'key' => ['law_cd', 'reg_num', 'pe_num'],
            'type' => 'detail_pe'
        ],
        'upd_prog_info_div_u' => [
            'table' => 'upd_prog_info_div_u',
            'key' => ['law_cd', 'reg_num', 'pe_num'],
            'type' => 'detail_pe'
        ],
        'upd_right_person_art_p' => [
            'table' => 'upd_right_person_art_p',
            'key' => ['law_cd', 'reg_num', 'pe_num'],
            'type' => 'detail_pe'
        ],
        'upd_right_person_art_u' => [
            'table' => 'upd_right_person_art_u',
            'key' => ['law_cd', 'reg_num', 'pe_num'],
            'type' => 'detail_pe'
        ],

        // Detail files với mu_num
        'upd_trnsfr_rcpt_info_p' => [
            'table' => 'upd_trnsfr_rcpt_info_p',
            'key' => ['law_cd', 'reg_num', 'mu_num'],
            'type' => 'detail_mu'
        ],
        'upd_trnsfr_rcpt_info_u' => [
            'table' => 'upd_trnsfr_rcpt_info_u',
            'key' => ['law_cd', 'reg_num', 'mu_num'],
            'type' => 'detail_mu'
        ],
    ];

    protected $stats = [
        'folders_processed' => 0,
        'files_processed' => 0,
        'rows_inserted' => 0,
        'rows_updated' => 0,
        'rows_error' => 0,
        'columns_added' => 0,
    ];

    /**
     * Get database driver name
     */
    protected function getDriver(): string
    {
        return DB::getDriverName();
    }

    /**
     * Quote identifier based on database driver
     */
    protected function quoteIdentifier(string $identifier): string
    {
        $driver = $this->getDriver();
        if ($driver === 'pgsql') {
            return '"' . $identifier . '"';
        }
        return '`' . $identifier . '`';
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting JPDRP data sync...');

        $basePath = storage_path('app/private');
        $dryRun = $this->option('dry-run');
        $specificFolder = $this->option('folder');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No data will be saved to database');
        }

        // Get all JPDRP folders
        $folders = $this->getJpdrpFolders($basePath, $specificFolder);

        if (empty($folders)) {
            $this->error('No JPDRP folders found!');
            return Command::FAILURE;
        }

        $this->info("Found " . count($folders) . " folder(s) to process");

        foreach ($folders as $index => $folderPath) {
            $folderName = basename($folderPath);
            $isFirstFolder = $index === 0;

            $this->info("\nProcessing folder: {$folderName} (" . ($index + 1) . "/" . count($folders) . ")");

            $startTime = microtime(true);
            $this->processFolder($folderPath, $isFirstFolder, $dryRun);
            $duration = round(microtime(true) - $startTime, 2);

            $this->info("  ✓ Completed in {$duration}s");

            $this->stats['folders_processed']++;
        }

        // Display summary
        $this->displaySummary();

        return Command::SUCCESS;
    }

    /**
     * Get all JPDRP folders sorted by date
     */
    protected function getJpdrpFolders(string $basePath, ?string $specificFolder = null): array
    {
        if ($specificFolder) {
            $path = $basePath . '/' . $specificFolder;
            if (is_dir($path)) {
                return [$path];
            }
            $this->error("Folder not found: {$specificFolder}");
            return [];
        }

        $folders = [];
        $items = File::directories($basePath);

        foreach ($items as $item) {
            $folderName = basename($item);
            if (preg_match('/^JPDRP_\d{8}$/', $folderName)) {
                $folders[] = $item;
            }
        }

        // Sort by folder name (date)
        sort($folders);

        return $folders;
    }

    /**
     * Process a single folder
     */
    protected function processFolder(string $folderPath, bool $isFirstFolder, bool $dryRun): void
    {
        $jpdrpPath = $folderPath . '/JPDRP';

        if (!is_dir($jpdrpPath)) {
            $this->warn("  JPDRP subfolder not found, skipping...");
            return;
        }

        $tsvFiles = File::glob($jpdrpPath . '/*.tsv');

        if (empty($tsvFiles)) {
            $this->warn("  No TSV files found in JPDRP folder");
            return;
        }

        foreach ($tsvFiles as $filePath) {
            $fileName = basename($filePath, '.tsv');

            if (!isset($this->fileConfig[$fileName])) {
                $this->warn("  Unknown file: {$fileName}, skipping...");
                continue;
            }

            $this->line("  Processing: {$fileName}");

            try {
                $this->syncFile($filePath, $fileName, $isFirstFolder, $dryRun);
                $this->stats['files_processed']++;
            } catch (\Exception $e) {
                $this->error("  Error processing {$fileName}: " . $e->getMessage());
                $this->stats['rows_error']++;
            }
        }
    }

    /**
     * Sync a single TSV file to database
     */
    protected function syncFile(string $filePath, string $fileName, bool $isFirstFolder, bool $dryRun): void
    {
        $config = $this->fileConfig[$fileName];
        $tableName = $config['table'];

        // Check if table exists
        if (!Schema::hasTable($tableName)) {
            $this->error("  Table {$tableName} does not exist!");
            return;
        }

        // Read header
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("Cannot open file: {$filePath}");
        }

        $header = fgetcsv($handle, 0, "\t");
        if (!$header) {
            fclose($handle);
            throw new \Exception("Cannot read header from file");
        }

        // Handle schema changes if not first folder
        if (!$isFirstFolder) {
            $this->handleSchemaChanges($tableName, $header, $filePath, $dryRun);
        }

        // Get current columns from database
        $dbColumns = $this->getTableColumns($tableName);

        // Filter columns to sync (only those in both file and database)
        $columnsToSync = array_filter($header, function($col) use ($dbColumns) {
            return in_array($col, $dbColumns);
        });

        // Remove columns not in file from sync
        $columnsToSync = array_values($columnsToSync);

        if (empty($columnsToSync)) {
            $this->warn("  No columns to sync for {$fileName}");
            fclose($handle);
            return;
        }

        // Prepare UPSERT statement
        $keyColumns = $config['key'];
        $updateColumns = array_diff($columnsToSync, $keyColumns);

        // Process rows in batches
        $batchSize = 1000;
        $batch = [];
        $lineNumber = 1;

        while (($row = fgetcsv($handle, 0, "\t")) !== false) {
            $lineNumber++;

            if (count($row) !== count($header)) {
                $this->warn("  Line {$lineNumber}: Column count mismatch, skipping...");
                continue;
            }

            // Build data array
            $data = [];
            foreach ($columnsToSync as $index => $col) {
                $value = $row[array_search($col, $header)] ?? null;
                $data[$col] = $value === '' ? null : $value;
            }

            // Validate required key columns
            $isValid = true;
            foreach ($keyColumns as $keyCol) {
                if (!isset($data[$keyCol]) || $data[$keyCol] === null || $data[$keyCol] === '') {
                    $this->warn("  Line {$lineNumber}: Missing key column {$keyCol}, skipping...");
                    $isValid = false;
                    break;
                }
            }

            if (!$isValid) {
                $this->stats['rows_error']++;
                continue;
            }

            $batch[] = $data;

            if (count($batch) >= $batchSize) {
                $this->processBatch($tableName, $columnsToSync, $updateColumns, $keyColumns, $batch, $dryRun);
                $batch = [];
            }
        }

        // Process remaining batch
        if (!empty($batch)) {
            $this->processBatch($tableName, $columnsToSync, $updateColumns, $keyColumns, $batch, $dryRun);
        }

        fclose($handle);
    }

    /**
     * Process a batch of rows
     */
    protected function processBatch(string $tableName, array $columnsToSync, array $updateColumns, array $keyColumns, array $batch, bool $dryRun): void
    {
        if ($dryRun) {
            $this->line("    [DRY RUN] Would insert/update " . count($batch) . " rows");
            return;
        }

        // Deduplicate batch based on key columns (PostgreSQL requirement)
        // PostgreSQL's ON CONFLICT cannot handle duplicate keys within the same INSERT statement
        $deduplicatedBatch = [];
        $keyMap = []; // Map key string to index in deduplicatedBatch
        $duplicateCount = 0;

        foreach ($batch as $data) {
            // Build key string from key columns
            $keyParts = [];
            foreach ($keyColumns as $keyCol) {
                $keyParts[] = $data[$keyCol] ?? '';
            }
            $keyString = implode('|', $keyParts);

            if (isset($keyMap[$keyString])) {
                // Duplicate found - replace with new data (keep last occurrence)
                $duplicateCount++;
                $deduplicatedBatch[$keyMap[$keyString]] = $data;
            } else {
                // New key - add to batch
                $keyMap[$keyString] = count($deduplicatedBatch);
                $deduplicatedBatch[] = $data;
            }
        }

        if ($duplicateCount > 0) {
            $this->warn("    Found {$duplicateCount} duplicate key(s) in batch, keeping last occurrence");
        }

        $batch = $deduplicatedBatch;

        if (empty($batch)) {
            return;
        }

        try {
            DB::beginTransaction();

            $driver = $this->getDriver();
            $quoteFn = [$this, 'quoteIdentifier'];

            $columnsStr = implode(',', array_map($quoteFn, $columnsToSync));
            $quotedTableName = $this->quoteIdentifier($tableName);

            // Build bulk insert values
            $valuesArray = [];
            $placeholders = '(' . implode(',', array_fill(0, count($columnsToSync), '?')) . ')';

            foreach ($batch as $data) {
                $rowValues = [];
                foreach ($columnsToSync as $col) {
                    $rowValues[] = $data[$col] ?? null;
                }
                $valuesArray[] = $rowValues;
            }

            // Build bulk insert SQL
            $allPlaceholders = [];
            $allValues = [];

            foreach ($valuesArray as $rowValues) {
                $allPlaceholders[] = $placeholders;
                $allValues = array_merge($allValues, $rowValues);
            }

            $sql = "INSERT INTO {$quotedTableName} ({$columnsStr}) VALUES " . implode(',', $allPlaceholders);

            // PostgreSQL uses ON CONFLICT instead of ON DUPLICATE KEY UPDATE
            if ($driver === 'pgsql' && !empty($updateColumns)) {
                // Get unique constraint name from key columns
                $quotedKeyColumns = array_map($quoteFn, $keyColumns);
                $keyColumnsStr = implode(',', $quotedKeyColumns);

                // Build update clauses using EXCLUDED (PostgreSQL)
                $updateClauses = [];
                foreach ($updateColumns as $col) {
                    $quotedCol = $this->quoteIdentifier($col);
                    $updateClauses[] = "{$quotedCol} = EXCLUDED.{$quotedCol}";
                }
                $updateClause = implode(', ', $updateClauses);

                $sql .= " ON CONFLICT ({$keyColumnsStr}) DO UPDATE SET {$updateClause}";

                // PostgreSQL: Use RETURNING with xmax to track INSERT vs UPDATE
                // xmax = 0 means INSERT (new row), xmax != 0 means UPDATE (existing row was updated)
                // Note: xmax is a system column that tracks transaction ID
                $sql .= " RETURNING (xmax = 0) AS inserted";

                $results = DB::select($sql, $allValues);

                // Count inserts and updates separately
                $insertCount = 0;
                $updateCount = 0;
                foreach ($results as $result) {
                    // PostgreSQL returns boolean as 't'/'f' or true/false depending on driver
                    $isInserted = is_bool($result->inserted)
                        ? $result->inserted
                        : ($result->inserted === 't' || $result->inserted === true || $result->inserted === 1);

                    if ($isInserted) {
                        $insertCount++;
                    } else {
                        $updateCount++;
                    }
                }

                $this->stats['rows_inserted'] += $insertCount;
                $this->stats['rows_updated'] += $updateCount;
            } elseif ($driver !== 'pgsql' && !empty($updateColumns)) {
                // MySQL/MariaDB syntax
                $updateClauses = [];
                foreach ($updateColumns as $col) {
                    $quotedCol = $this->quoteIdentifier($col);
                    $updateClauses[] = "{$quotedCol} = VALUES({$quotedCol})";
                }
                $updateClause = implode(', ', $updateClauses);
                $sql .= " ON DUPLICATE KEY UPDATE {$updateClause}";

                DB::statement($sql, $allValues);

                // MySQL doesn't distinguish between inserts and updates in affected rows
                // So we approximate: count all as processed (both insert and update)
                $this->stats['rows_inserted'] += count($batch);
            } else {
                // No update columns (shouldn't happen, but handle gracefully)
                DB::statement($sql, $allValues);
                $this->stats['rows_inserted'] += count($batch);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Handle schema changes - detect and add new columns
     */
    protected function handleSchemaChanges(string $tableName, array $fileColumns, string $filePath, bool $dryRun): void
    {
        $dbColumns = $this->getTableColumns($tableName);
        $newColumns = array_diff($fileColumns, $dbColumns);

        if (empty($newColumns)) {
            return;
        }

        $this->info("    Detected " . count($newColumns) . " new column(s): " . implode(', ', $newColumns));

        if ($dryRun) {
            $this->line("    [DRY RUN] Would add columns to table {$tableName}");
            return;
        }

        // Read sample data to infer column types
        $handle = fopen($filePath, 'r');
        if ($handle) {
            fgetcsv($handle, 0, "\t"); // Skip header
            $sampleRow = fgetcsv($handle, 0, "\t");
            fclose($handle);

            foreach ($newColumns as $col) {
                $sampleValue = null;
                if ($sampleRow) {
                    $colIndex = array_search($col, $fileColumns);
                    $sampleValue = $colIndex !== false ? ($sampleRow[$colIndex] ?? null) : null;
                }

                $columnType = $this->inferColumnType($col, $sampleValue);

                try {
                    $driver = $this->getDriver();
                    $quotedTableName = $this->quoteIdentifier($tableName);
                    $quotedCol = $this->quoteIdentifier($col);

                    // PostgreSQL uses slightly different syntax
                    if ($driver === 'pgsql') {
                        DB::statement("ALTER TABLE {$quotedTableName} ADD COLUMN {$quotedCol} {$columnType}");
                    } else {
                        DB::statement("ALTER TABLE {$quotedTableName} ADD COLUMN {$quotedCol} {$columnType} NULL");
                    }

                    $this->info("    ✓ Added column {$col} ({$columnType})");
                    $this->stats['columns_added']++;
                } catch (\Exception $e) {
                    $this->error("    ✗ Failed to add column {$col}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Infer column type from column name and sample data
     */
    protected function inferColumnType(string $columnName, $sampleValue = null): string
    {
        $driver = $this->getDriver();
        $colLower = strtolower($columnName);

        if (strpos($colLower, 'num') !== false || strpos($colLower, 'id') !== false) {
            return $driver === 'pgsql' ? 'VARCHAR(50)' : 'VARCHAR(50)';
        }

        if (strpos($colLower, 'date') !== false || strpos($colLower, 'ymd') !== false) {
            return $driver === 'pgsql' ? 'VARCHAR(8)' : 'VARCHAR(8)';
        }

        if (strpos($colLower, 'len') !== false) {
            return $driver === 'pgsql' ? 'VARCHAR(50)' : 'VARCHAR(50)';
        }

        if (strpos($colLower, 'name') !== false || strpos($colLower, 'addr') !== false) {
            return $driver === 'pgsql' ? 'VARCHAR(255)' : 'VARCHAR(255)';
        }

        if (strpos($colLower, 'info') !== false || strpos($colLower, 'title') !== false || strpos($colLower, 'etc') !== false) {
            return 'TEXT';
        }

        if (strpos($colLower, 'flg') !== false || strpos($colLower, 'mk') !== false) {
            return $driver === 'pgsql' ? 'VARCHAR(10)' : 'VARCHAR(10)';
        }

        if (strpos($colLower, 'typ') !== false && $sampleValue !== null && is_numeric($sampleValue)) {
            return $driver === 'pgsql' ? 'INTEGER' : 'INT';
        }

        // Default
        return $driver === 'pgsql' ? 'VARCHAR(255)' : 'VARCHAR(255)';
    }

    /**
     * Get table columns from database
     */
    protected function getTableColumns(string $tableName): array
    {
        $columns = Schema::getColumnListing($tableName);
        return $columns;
    }

    /**
     * Display summary statistics
     */
    protected function displaySummary(): void
    {
        $this->info("\n" . str_repeat('=', 50));
        $this->info("SYNC SUMMARY");
        $this->info(str_repeat('=', 50));
        $this->info("Folders processed: " . $this->stats['folders_processed']);
        $this->info("Files processed: " . $this->stats['files_processed']);
        $this->info("Rows inserted: " . $this->stats['rows_inserted']);
        $this->info("Rows updated: " . $this->stats['rows_updated']);
        $this->info("Rows error: " . $this->stats['rows_error']);
        $this->info("Columns added: " . $this->stats['columns_added']);
        $this->info(str_repeat('=', 50));
    }
}

