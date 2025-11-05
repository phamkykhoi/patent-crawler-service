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
        Schema::create('upd_mgt_info_u', function (Blueprint $table) {
            $table->id();
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->string('reg_num');
            $table->string('split_num')->nullable();
            $table->string('mstr_updt_year_month_day')->nullable();
            $table->string('tscript_inspct_prhbt_flg')->nullable();
            $table->string('conti_prd_expire_ymd')->nullable();
            $table->string('next_pen_pymnt_tm_lmt_ymd')->nullable();
            $table->string('last_pymnt_yearly')->nullable();
            $table->string('share_rate')->nullable();
            $table->string('pblc_prvt_trnsfr_reg_ymd')->nullable();
            $table->string('right_ersr_id')->nullable();
            $table->string('right_disppr_year_month_day')->nullable();
            $table->string('close_orgnl_reg_trnsfr_rec_flg')->nullable();
            $table->string('close_reg_year_month_day')->nullable();
            $table->string('gvrnmnt_relation_id_flg')->nullable();
            $table->string('pen_suppl_flg')->nullable();
            $table->string('trust_reg_flg')->nullable();
            $table->string('app_num')->nullable();
            $table->string('recvry_num')->nullable();
            $table->string('app_year_month_day')->nullable();
            $table->string('app_exam_pub_num')->nullable();
            $table->string('app_exam_pub_year_month_day')->nullable();
            $table->string('finl_dcsn_year_month_day')->nullable();
            $table->string('trial_dcsn_year_month_day')->nullable();
            $table->string('set_reg_year_month_day')->nullable();
            $table->string('invent_cnt_claim_cnt_cls_cnt')->nullable();
            $table->string('invent_title_etc_len')->nullable();
            $table->text('invent_title_etc')->nullable();
            $table->string('pri_cntry_name_cd')->nullable();
            $table->string('pri_clim_year_month_day')->nullable();
            $table->string('pri_clim_cnt')->nullable();

            $table->unique(['law_cd', 'reg_num']);
            $table->index('reg_num', 'idx_upd_mgt_info_u_reg_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_mgt_info_u');
    }
};

