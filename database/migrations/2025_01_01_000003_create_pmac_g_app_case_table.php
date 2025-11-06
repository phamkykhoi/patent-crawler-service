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
        Schema::create('pmac_g_app_case', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->char('ac_delete_flg', 1)->nullable();
            $table->string('ac_update_dttm', 14)->nullable();
            $table->char('acai_delete_flg', 1)->nullable();
            $table->string('acai_app_dt', 8)->nullable();
            $table->string('acai_app_typ_1', 2)->nullable();
            $table->string('acai_app_typ_2', 2)->nullable();
            $table->string('acai_app_typ_3', 2)->nullable();
            $table->string('acai_app_typ_4', 2)->nullable();
            $table->string('acai_app_typ_5', 2)->nullable();
            $table->string('acai_refer_num', 50)->nullable();
            $table->char('acai_org_lang_app_flg', 1)->nullable();
            $table->char('acup_delete_flg', 1)->nullable();
            $table->string('acup_pub_num', 50)->nullable();
            $table->string('acup_pub_dt', 8)->nullable();
            $table->char('actp_delete_flg', 1)->nullable();
            $table->string('actp_trnsl_pub_num', 50)->nullable();
            $table->string('actp_trnsl_pub_dt', 8)->nullable();
            $table->string('actp_trnsl_repub_dt', 8)->nullable();
            $table->char('acap_delete_flg', 1)->nullable();
            $table->char('acld_delete_flg', 1)->nullable();
            $table->string('acld_final_dspst_typ', 3)->nullable();
            $table->string('acld_final_dspst_dt', 8)->nullable();
            $table->char('acrg_delete_flg', 1)->nullable();
            $table->string('acrg_reg_num', 50)->nullable();
            $table->string('acrg_reg_dt', 8)->nullable();
            $table->char('acrb_delete_flg', 1)->nullable();
            $table->string('acrb_reg_bul_publish_dt', 8)->nullable();
            $table->char('acia_delete_flg', 1)->nullable();
            $table->string('acia_intl_app_num', 50)->nullable();
            $table->string('acia_intl_pub_num', 50)->nullable();
            $table->string('acia_intl_pub_dt', 8)->nullable();
            $table->string('acia_trnsl_submit_dt', 8)->nullable();
            $table->char('acia_lang_flg', 1)->nullable();
            
            $table->unique(['law_cd', 'app_num']);
            $table->index('app_num', 'idx_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmac_g_app_case');
    }
};

