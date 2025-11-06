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
        Schema::create('pmos_g_old_case_stat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->char('os_delete_flg', 1)->nullable();
            $table->char('osos_delete_flg', 1)->nullable();
            $table->string('osos_opp_cnt', 10)->nullable();
            $table->string('osos_opp_valid_cnt', 10)->nullable();
            
            $table->unique(['law_cd', 'app_num']);
            $table->index('app_num', 'idx_pmos_g_old_case_stat_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmos_g_old_case_stat');
    }
};

