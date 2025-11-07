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
        Schema::create('upd_prog_info_div_u', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->text('reg_num');
            $table->text('split_num')->nullable();
            $table->text('app_num')->nullable();
            $table->text('rec_num')->nullable();
            $table->text('pe_num');
            $table->text('prog_info_upd_ymd')->nullable();
            $table->text('reg_intrmd_cd')->nullable();
            $table->text('crrspnd_mk')->nullable();
            $table->text('rcpt_pymnt_dsptch_ymd')->nullable();
            $table->text('rcpt_num_common_use')->nullable();

            $table->unique(['law_cd', 'reg_num', 'pe_num'], 'upd_prog_info_div_u_main_ids');
            $table->index('reg_num', 'idx_upd_prog_info_div_u_reg_num');
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

