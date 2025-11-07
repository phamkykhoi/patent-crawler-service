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
        Schema::create('upd_trnsfr_rcpt_info_u', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->text('reg_num');
            $table->text('split_num')->nullable();
            $table->text('app_num')->nullable();
            $table->text('mrgn_info_upd_ymd')->nullable();
            $table->text('mu_num');
            $table->text('trnsfr_rcpt_info')->nullable();

            $table->unique(['law_cd', 'reg_num', 'mu_num'], 'upd_trnsfr_rcpt_info_u_main_ids');
            $table->index('reg_num', 'idx_upd_trnsfr_rcpt_info_u_reg_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_trnsfr_rcpt_info_u');
    }
};

