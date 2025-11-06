<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sinseinin', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sinseinin_code', 50)->unique();
            $table->string('version_no', 10)->nullable();
            $table->string('kuni_code', 10)->nullable();
            $table->string('todohuken_code', 10)->nullable();
            $table->string('kohokan_sikibetu', 10)->nullable();
            $table->string('masshoriyu_mark', 10)->nullable();
            $table->string('togosinseinin_code', 50)->nullable();
            
            $table->index('sinseinin_code', 'idx_sinseinin_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinseinin');
    }
};

