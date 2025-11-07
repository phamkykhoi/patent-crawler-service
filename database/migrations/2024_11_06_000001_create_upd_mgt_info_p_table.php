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
        Schema::create('upd_mgt_info_p', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->text('reg_num');
            $table->text('split_num')->nullable();
            $table->text('mstr_updt_year_month_day')->nullable();
            $table->text('tscript_inspct_prhbt_flg')->nullable();
            $table->text('conti_prd_expire_ymd')->nullable();
            $table->text('next_pen_pymnt_tm_lmt_ymd')->nullable();
            $table->text('last_pymnt_yearly')->nullable();
            $table->text('share_rate')->nullable();
            $table->text('pblc_prvt_trnsfr_reg_ymd')->nullable();
            $table->text('right_ersr_id')->nullable();
            $table->text('right_disppr_year_month_day')->nullable();
            $table->text('close_orgnl_reg_trnsfr_rec_flg')->nullable();
            $table->text('close_reg_year_month_day')->nullable();
            $table->text('gvrnmnt_relation_id_flg')->nullable();
            $table->text('pen_suppl_flg')->nullable();
            $table->text('trust_reg_flg')->nullable();
            $table->text('app_num')->nullable();
            $table->text('recvry_num')->nullable();
            $table->text('app_year_month_day')->nullable();
            $table->text('app_exam_pub_num')->nullable();
            $table->text('app_exam_pub_year_month_day')->nullable();
            $table->text('finl_dcsn_year_month_day')->nullable();
            $table->text('trial_dcsn_year_month_day')->nullable();
            $table->text('set_reg_year_month_day')->nullable();
            $table->text('invent_cnt_claim_cnt_cls_cnt')->nullable();
            $table->text('invent_title_etc_len')->nullable();
            $table->text('invent_title_etc')->nullable();
            $table->text('pri_cntry_name_cd')->nullable();
            $table->text('pri_clim_year_month_day')->nullable();
            $table->text('pri_clim_cnt')->nullable();
            $table->text('prnt_app_patent_no_prncpl_d_no')->nullable();
            $table->text('prnt_p_app_ymd__d_reg_ymd')->nullable();
            $table->text('prnt_p_app_exam_pub_d_del_ymd')->nullable();

            $table->unique(['law_cd', 'reg_num'], 'upd_mgt_info_p_main_ids');
            $table->index('reg_num', 'idx_upd_mgt_info_p_reg_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_mgt_info_p');
    }
};

