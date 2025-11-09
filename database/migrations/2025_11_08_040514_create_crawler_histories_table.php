<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crawler_histories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->date('release_date');
            $table->string('data_bunrui_name');
            $table->string('accumulation_time')->nullable();
            $table->string('checksum_value');
            $table->string('file_name');
            $table->bigInteger('file_size');
            $table->integer('download')->default(0);
            $table->text('bulkdata_url');
            $table->string('data_group_name');
            $table->integer('status')->default(1)->comment('1: new, 2: processing, 3: completed, 4: Error');
            $table->jsonb('error_logs')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('release_date', 'idx_bulk_download_data_release_date');
            $table->index('data_bunrui_name', 'idx_bulk_download_data_bunrui_name');
            $table->index('file_name', 'idx_bulk_download_data_file_name');

            // Unique constraint: same release_date + file_name should not be duplicated
            $table->unique(['release_date', 'file_name'], 'bulk_download_data_unique_release_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crawler_histories');
    }
};
