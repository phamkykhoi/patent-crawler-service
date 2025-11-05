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
        Schema::create('upd_prog_info_div_u', function (Blueprint $table) {
            $table->id();
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->string('reg_num');
            $table->string('split_num')->nullable();
            $table->string('app_num')->nullable();
            $table->string('rec_num')->nullable();
            $table->string('pe_num');
            $table->string('prog_info_upd_ymd')->nullable();
            $table->string('reg_intrmd_cd')->nullable();
            $table->string('crrspnd_mk')->nullable();
            $table->string('rcpt_pymnt_dsptch_ymd')->nullable();
            $table->string('rcpt_num_common_use')->nullable();
            
            $table->unique(['law_cd', 'reg_num', 'pe_num']);
            $table->index('reg_num', 'idx_reg_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_prog_info_div_u');
    }
};

