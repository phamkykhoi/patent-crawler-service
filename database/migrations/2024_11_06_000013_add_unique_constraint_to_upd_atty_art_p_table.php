<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            // PostgreSQL: Add unique constraint if it doesn't exist
            try {
                DB::statement('ALTER TABLE upd_atty_art_p ADD CONSTRAINT upd_atty_art_p_law_cd_reg_num_pe_num_unique UNIQUE (law_cd, reg_num, pe_num)');
            } catch (\Exception $e) {
                // Constraint might already exist, ignore
                if (strpos($e->getMessage(), 'already exists') === false && 
                    strpos($e->getMessage(), 'duplicate') === false) {
                    throw $e;
                }
            }
            
            // Add index if it doesn't exist
            try {
                DB::statement('CREATE INDEX IF NOT EXISTS idx_upd_atty_art_p_reg_num ON upd_atty_art_p (reg_num)');
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
        } else {
            // MySQL/MariaDB: Use Schema builder
            Schema::table('upd_atty_art_p', function (Blueprint $table) {
                try {
                    $table->unique(['law_cd', 'reg_num', 'pe_num']);
                } catch (\Exception $e) {
                    // Constraint might already exist, ignore
                }
                
                try {
                    $table->index('reg_num', 'idx_upd_atty_art_p_reg_num');
                } catch (\Exception $e) {
                    // Index might already exist, ignore
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE upd_atty_art_p DROP CONSTRAINT IF EXISTS upd_atty_art_p_law_cd_reg_num_pe_num_unique');
            DB::statement('DROP INDEX IF EXISTS idx_upd_atty_art_p_reg_num');
        } else {
            Schema::table('upd_atty_art_p', function (Blueprint $table) {
                $table->dropUnique(['law_cd', 'reg_num', 'pe_num']);
                $table->dropIndex('idx_upd_atty_art_p_reg_num');
            });
        }
    }
};

