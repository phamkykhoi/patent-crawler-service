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
        Schema::create('upd_cmia_g_intl_app_doc', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('intl_app_num');
            $table->integer('storing_seq_num');
            $table->text('article_id');
            $table->text('ia_delete_flg')->nullable();
            $table->text('ia_update_dttm')->nullable();
            $table->text('iaba_delete_flg')->nullable();
            $table->text('iaba_create_dt')->nullable();
            $table->text('iaba_valid_flg')->nullable();
            $table->text('iaba_intrmd_doc_cd')->nullable();
            $table->text('iaba_rcpt_dt')->nullable();
            $table->text('iaba_checked_flg')->nullable();
            $table->text('iaba_dspst_dt')->nullable();
            $table->text('iaba_crrspnd_doc_num')->nullable();
            $table->text('iaba_rcpt_num')->nullable();
            $table->text('iaba_doc_typ_cd')->nullable();
            $table->text('iaba_ver_num')->nullable();
            $table->text('iaba_descript_ver_num')->nullable();
            $table->text('iaba_dna_flg')->nullable();
            $table->text('iaba_description_page')->nullable();
            $table->text('iaba_descript_flg')->nullable();
            $table->text('iaba_drawing_page')->nullable();
            $table->text('iaba_drawing_flg')->nullable();
            $table->text('iaba_abstrct_doc_page')->nullable();
            $table->text('iaba_abstrct_flg')->nullable();
            $table->text('iaba_attchd_doc_page')->nullable();
            $table->text('iaba_doc_size')->nullable();
            $table->text('iabd_delete_flg')->nullable();
            $table->text('iabd_valid_flg')->nullable();
            $table->text('iabd_intrmd_doc_cd')->nullable();
            $table->text('iabd_dsptch_dt')->nullable();
            $table->text('iabd_crrspnd_doc_num')->nullable();
            $table->text('iabd_dsptch_doc_num')->nullable();
            $table->text('iabd_doc_typ_cd')->nullable();
            $table->text('iabd_ver_num')->nullable();
            $table->text('iabd_invalid_doc_flg')->nullable();
            $table->text('iabd_doc_frmt_typ')->nullable();
            $table->text('iabd_dsptch_doc_image_page')->nullable();
            $table->text('iabd_doc_size')->nullable();
            $table->text('iabj_delete_flg')->nullable();
            $table->text('iabj_create_dt')->nullable();
            $table->text('iabj_valid_flg')->nullable();
            $table->text('iabj_intrmd_doc_cd')->nullable();
            $table->text('iabj_dspst_dt')->nullable();
            $table->text('iabj_crrspnd_doc_num')->nullable();
            $table->text('iabj_jpo_doc_num')->nullable();
            $table->text('iabj_doc_typ_cd')->nullable();
            $table->text('iabj_doc_frmt_typ')->nullable();
            $table->text('iabj_jpo_doc_image_page')->nullable();
            $table->text('iabj_doc_size')->nullable();

            $table->unique(['intl_app_num', 'storing_seq_num', 'article_id'], 'upd_cmia_g_intl_app_doc_main_ids');
            $table->index('intl_app_num', 'idx_upd_cmia_g_intl_app_doc_intl_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_cmia_g_intl_app_doc');
    }
};

