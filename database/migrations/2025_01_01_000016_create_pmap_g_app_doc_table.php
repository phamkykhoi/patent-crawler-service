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
        Schema::create('pmap_g_app_doc', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->integer('storing_seq_num');
            $table->string('article_id', 10);
            $table->char('ap_delete_flg', 1)->nullable();
            $table->string('ap_update_dttm', 14)->nullable();
            $table->char('apad_delete_flg', 1)->nullable();
            $table->string('apad_update_dttm', 14)->nullable();
            $table->string('apad_create_dt', 8)->nullable();
            $table->char('apad_valid_flg', 1)->nullable();
            $table->string('apad_intrmd_doc_cd', 10)->nullable();
            $table->char('apad_crrspnd_mk', 1)->nullable();
            $table->string('apad_submit_dt', 8)->nullable();
            $table->string('apad_rcpt_dt', 8)->nullable();
            $table->char('apad_inspect_prhbt_flg', 1)->nullable();
            $table->string('apad_opp_num', 10)->nullable();
            $table->string('apad_rcpt_num', 50)->nullable();
            $table->char('apad_frml_chked_mk', 1)->nullable();
            $table->char('apad_instructed_flg', 1)->nullable();
            $table->string('apad_dspst_dt', 8)->nullable();
            $table->string('apad_change_appl_atty_id', 50)->nullable();
            $table->string('apad_pri_submit_cntry_cd', 10)->nullable();
            $table->string('apad_ver_num', 10)->nullable();
            $table->string('apad_descript_ver_num', 10)->nullable();
            $table->char('apad_invalid_doc_flg', 1)->nullable();
            $table->string('apad_doc_frmt_typ', 10)->nullable();
            $table->string('apad_crrspnd_doc_num', 50)->nullable();
            $table->string('apad_doc_typ_cd', 10)->nullable();
            $table->string('apad_amend_doc_rcpt_num', 50)->nullable();
            $table->string('apad_store_num', 50)->nullable();
            $table->char('apad_dna_flg', 1)->nullable();
            $table->string('apad_description_page', 10)->nullable();
            $table->char('apad_descript_flg', 1)->nullable();
            $table->string('apad_drawing_page', 10)->nullable();
            $table->char('apad_drawing_flg', 1)->nullable();
            $table->string('apad_abstrct_doc_page', 10)->nullable();
            $table->char('apad_abstrct_flg', 1)->nullable();
            $table->string('apad_attchd_doc_page', 10)->nullable();
            $table->string('apad_doc_size', 10)->nullable();
            $table->char('apdd_delete_flg', 1)->nullable();
            $table->string('apdd_create_dt', 8)->nullable();
            $table->char('apdd_valid_flg', 1)->nullable();
            $table->string('apdd_intrmd_doc_cd', 10)->nullable();
            $table->char('apdd_crrspnd_mk', 1)->nullable();
            $table->string('apdd_draft_dt', 8)->nullable();
            $table->string('apdd_dsptch_dt', 8)->nullable();
            $table->char('apdd_inspect_prhbt_flg', 1)->nullable();
            $table->string('apdd_opp_num', 10)->nullable();
            $table->string('apdd_dsptch_doc_num', 50)->nullable();
            $table->string('apdd_rjct_reason_art_cd', 10)->nullable();
            $table->string('apdd_ver_num', 10)->nullable();
            $table->char('apdd_invalid_doc_flg', 1)->nullable();
            $table->string('apdd_doc_frmt_typ', 10)->nullable();
            $table->string('apdd_crrspnd_doc_num', 50)->nullable();
            $table->string('apdd_doc_typ_cd', 10)->nullable();
            $table->string('apdd_dsptch_doc_image_page', 10)->nullable();
            $table->string('apdd_doc_size', 10)->nullable();
            $table->char('apjd_delete_flg', 1)->nullable();
            $table->string('apjd_create_dt', 8)->nullable();
            $table->char('apjd_valid_flg', 1)->nullable();
            $table->string('apjd_intrmd_doc_cd', 10)->nullable();
            $table->char('apjd_crrspnd_mk', 1)->nullable();
            $table->string('apjd_jpo_doc_create_dt', 8)->nullable();
            $table->char('apjd_inspect_prhbt_flg', 1)->nullable();
            $table->string('apjd_admnst_appeal_num', 50)->nullable();
            $table->string('apjd_litigate_num', 50)->nullable();
            $table->string('apjd_jpo_doc_num', 50)->nullable();
            $table->string('apjd_goodmoral_violate_cd', 10)->nullable();
            $table->string('apjd_ver_num', 10)->nullable();
            $table->char('apjd_invalid_doc_flg', 1)->nullable();
            $table->string('apjd_doc_frmt_typ', 10)->nullable();
            $table->string('apjd_crrspnd_doc_num', 50)->nullable();
            $table->string('apjd_doc_typ_cd', 10)->nullable();
            $table->string('apjd_jpo_doc_image_page', 10)->nullable();
            $table->string('apjd_doc_size', 10)->nullable();
            
            $table->unique(['law_cd', 'app_num', 'storing_seq_num', 'article_id']);
            $table->index('app_num', 'idx_app_num');
            $table->index('storing_seq_num', 'idx_storing_seq_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmap_g_app_doc');
    }
};

