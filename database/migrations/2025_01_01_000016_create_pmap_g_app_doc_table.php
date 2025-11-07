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
        Schema::create('upd_pmap_g_app_doc', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->integer('storing_seq_num');
            $table->text('article_id');
            $table->text('ap_delete_flg')->nullable();
            $table->text('ap_update_dttm')->nullable();
            $table->text('apad_delete_flg')->nullable();
            $table->text('apad_update_dttm')->nullable();
            $table->text('apad_create_dt')->nullable();
            $table->text('apad_valid_flg')->nullable();
            $table->text('apad_intrmd_doc_cd')->nullable();
            $table->text('apad_crrspnd_mk')->nullable();
            $table->text('apad_submit_dt')->nullable();
            $table->text('apad_rcpt_dt')->nullable();
            $table->text('apad_inspect_prhbt_flg')->nullable();
            $table->text('apad_opp_num')->nullable();
            $table->text('apad_rcpt_num')->nullable();
            $table->text('apad_frml_chked_mk')->nullable();
            $table->text('apad_instructed_flg')->nullable();
            $table->text('apad_dspst_dt')->nullable();
            $table->text('apad_change_appl_atty_id')->nullable();
            $table->text('apad_pri_submit_cntry_cd')->nullable();
            $table->text('apad_ver_num')->nullable();
            $table->text('apad_descript_ver_num')->nullable();
            $table->text('apad_invalid_doc_flg')->nullable();
            $table->text('apad_doc_frmt_typ')->nullable();
            $table->text('apad_crrspnd_doc_num')->nullable();
            $table->text('apad_doc_typ_cd')->nullable();
            $table->text('apad_amend_doc_rcpt_num')->nullable();
            $table->text('apad_store_num')->nullable();
            $table->text('apad_dna_flg')->nullable();
            $table->text('apad_description_page')->nullable();
            $table->text('apad_descript_flg')->nullable();
            $table->text('apad_drawing_page')->nullable();
            $table->text('apad_drawing_flg')->nullable();
            $table->text('apad_abstrct_doc_page')->nullable();
            $table->text('apad_abstrct_flg')->nullable();
            $table->text('apad_attchd_doc_page')->nullable();
            $table->text('apad_doc_size')->nullable();
            $table->text('apdd_delete_flg')->nullable();
            $table->text('apdd_create_dt')->nullable();
            $table->text('apdd_valid_flg')->nullable();
            $table->text('apdd_intrmd_doc_cd')->nullable();
            $table->text('apdd_crrspnd_mk')->nullable();
            $table->text('apdd_draft_dt')->nullable();
            $table->text('apdd_dsptch_dt')->nullable();
            $table->text('apdd_inspect_prhbt_flg')->nullable();
            $table->text('apdd_opp_num')->nullable();
            $table->text('apdd_dsptch_doc_num')->nullable();
            $table->text('apdd_rjct_reason_art_cd')->nullable();
            $table->text('apdd_ver_num')->nullable();
            $table->text('apdd_invalid_doc_flg')->nullable();
            $table->text('apdd_doc_frmt_typ')->nullable();
            $table->text('apdd_crrspnd_doc_num')->nullable();
            $table->text('apdd_doc_typ_cd')->nullable();
            $table->text('apdd_dsptch_doc_image_page')->nullable();
            $table->text('apdd_doc_size')->nullable();
            $table->text('apjd_delete_flg')->nullable();
            $table->text('apjd_create_dt')->nullable();
            $table->text('apjd_valid_flg')->nullable();
            $table->text('apjd_intrmd_doc_cd')->nullable();
            $table->text('apjd_crrspnd_mk')->nullable();
            $table->text('apjd_jpo_doc_create_dt')->nullable();
            $table->text('apjd_inspect_prhbt_flg')->nullable();
            $table->text('apjd_admnst_appeal_num')->nullable();
            $table->text('apjd_litigate_num')->nullable();
            $table->text('apjd_jpo_doc_num')->nullable();
            $table->text('apjd_goodmoral_violate_cd')->nullable();
            $table->text('apjd_ver_num')->nullable();
            $table->text('apjd_invalid_doc_flg')->nullable();
            $table->text('apjd_doc_frmt_typ')->nullable();
            $table->text('apjd_crrspnd_doc_num')->nullable();
            $table->text('apjd_doc_typ_cd')->nullable();
            $table->text('apjd_jpo_doc_image_page')->nullable();
            $table->text('apjd_doc_size')->nullable();

            $table->unique(['law_cd', 'app_num', 'storing_seq_num', 'article_id'], 'upd_pmap_g_app_doc_main_ids');
            $table->index('app_num', 'idx_upd_pmap_g_app_doc_app_num');
            $table->index('storing_seq_num', 'idx_upd_pmap_g_app_doc_storing_seq_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_pmap_g_app_doc');
    }
};

