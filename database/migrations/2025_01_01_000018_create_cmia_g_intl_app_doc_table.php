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
        Schema::create('cmia_g_intl_app_doc', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('intl_app_num', 50);
            $table->integer('storing_seq_num');
            $table->string('article_id', 10);
            $table->char('ia_delete_flg', 1)->nullable();
            $table->string('ia_update_dttm', 14)->nullable();
            $table->char('iaba_delete_flg', 1)->nullable();
            $table->string('iaba_create_dt', 8)->nullable();
            $table->char('iaba_valid_flg', 1)->nullable();
            $table->string('iaba_intrmd_doc_cd', 10)->nullable();
            $table->string('iaba_rcpt_dt', 8)->nullable();
            $table->char('iaba_checked_flg', 1)->nullable();
            $table->string('iaba_dspst_dt', 8)->nullable();
            $table->string('iaba_crrspnd_doc_num', 50)->nullable();
            $table->string('iaba_rcpt_num', 50)->nullable();
            $table->string('iaba_doc_typ_cd', 10)->nullable();
            $table->string('iaba_ver_num', 10)->nullable();
            $table->string('iaba_descript_ver_num', 10)->nullable();
            $table->char('iaba_dna_flg', 1)->nullable();
            $table->string('iaba_description_page', 10)->nullable();
            $table->char('iaba_descript_flg', 1)->nullable();
            $table->string('iaba_drawing_page', 10)->nullable();
            $table->char('iaba_drawing_flg', 1)->nullable();
            $table->string('iaba_abstrct_doc_page', 10)->nullable();
            $table->char('iaba_abstrct_flg', 1)->nullable();
            $table->string('iaba_attchd_doc_page', 10)->nullable();
            $table->string('iaba_doc_size', 10)->nullable();
            $table->char('iabd_delete_flg', 1)->nullable();
            $table->char('iabd_valid_flg', 1)->nullable();
            $table->string('iabd_intrmd_doc_cd', 10)->nullable();
            $table->string('iabd_dsptch_dt', 8)->nullable();
            $table->string('iabd_crrspnd_doc_num', 50)->nullable();
            $table->string('iabd_dsptch_doc_num', 50)->nullable();
            $table->string('iabd_doc_typ_cd', 10)->nullable();
            $table->string('iabd_ver_num', 10)->nullable();
            $table->char('iabd_invalid_doc_flg', 1)->nullable();
            $table->string('iabd_doc_frmt_typ', 10)->nullable();
            $table->string('iabd_dsptch_doc_image_page', 10)->nullable();
            $table->string('iabd_doc_size', 10)->nullable();
            $table->char('iabj_delete_flg', 1)->nullable();
            $table->string('iabj_create_dt', 8)->nullable();
            $table->char('iabj_valid_flg', 1)->nullable();
            $table->string('iabj_intrmd_doc_cd', 10)->nullable();
            $table->string('iabj_dspst_dt', 8)->nullable();
            $table->string('iabj_crrspnd_doc_num', 50)->nullable();
            $table->string('iabj_jpo_doc_num', 50)->nullable();
            $table->string('iabj_doc_typ_cd', 10)->nullable();
            $table->string('iabj_doc_frmt_typ', 10)->nullable();
            $table->string('iabj_jpo_doc_image_page', 10)->nullable();
            $table->string('iabj_doc_size', 10)->nullable();
            
            $table->unique(['intl_app_num', 'storing_seq_num', 'article_id']);
            $table->index('intl_app_num', 'idx_intl_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cmia_g_intl_app_doc');
    }
};

