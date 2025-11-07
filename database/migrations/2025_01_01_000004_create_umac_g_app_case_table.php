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
        Schema::create('upd_umac_g_app_case', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('ac_delete_flg')->nullable();
            $table->text('ac_update_dttm')->nullable();
            $table->text('acai_delete_flg')->nullable();
            $table->text('acai_app_dt')->nullable();
            $table->text('acai_app_typ_1')->nullable();
            $table->text('acai_app_typ_2')->nullable();
            $table->text('acai_app_typ_3')->nullable();
            $table->text('acai_app_typ_4')->nullable();
            $table->text('acai_app_typ_5')->nullable();
            $table->text('acai_refer_num')->nullable();
            $table->text('acai_org_lang_app_flg')->nullable();
            $table->text('acup_delete_flg')->nullable();
            $table->text('acup_pub_num')->nullable();
            $table->text('acup_pub_dt')->nullable();
            $table->text('actp_delete_flg')->nullable();
            $table->text('actp_trnsl_pub_num')->nullable();
            $table->text('actp_trnsl_pub_dt')->nullable();
            $table->text('actp_trnsl_repub_dt')->nullable();
            $table->text('acap_delete_flg')->nullable();
            $table->text('acld_delete_flg')->nullable();
            $table->text('acld_final_dspst_typ')->nullable();
            $table->text('acld_final_dspst_dt')->nullable();
            $table->text('acrg_delete_flg')->nullable();
            $table->text('acrg_reg_num')->nullable();
            $table->text('acrg_reg_dt')->nullable();
            $table->text('acrb_delete_flg')->nullable();
            $table->text('acrb_reg_bul_publish_dt')->nullable();
            $table->text('acia_delete_flg')->nullable();
            $table->text('acia_intl_app_num')->nullable();
            $table->text('acia_intl_pub_num')->nullable();
            $table->text('acia_intl_pub_dt')->nullable();
            $table->text('acia_trnsl_submit_dt')->nullable();
            $table->text('acia_lang_flg')->nullable();

            $table->unique(['law_cd', 'app_num'], 'upd_umac_g_app_case_main_ids');
            $table->index('app_num', 'idx_upd_umac_g_app_case_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_umac_g_app_case');
    }
};

