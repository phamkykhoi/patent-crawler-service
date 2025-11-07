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
        Schema::create('upd_pmjb_gr_jpo_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('article_id');
            $table->integer('repeat_num');
            $table->text('jbui_amend_mk')->nullable();
            $table->text('jbui_ver_num')->nullable();
            $table->text('jbui_seq_num')->nullable();
            $table->text('jbui_pub_ipc')->nullable();
            $table->text('jbri_amend_mk')->nullable();
            $table->text('jbri_ver_num')->nullable();
            $table->text('jbri_seq_num')->nullable();
            $table->text('jbri_reg_ipc')->nullable();
            $table->text('jbuf_fi_class_typ')->nullable();
            $table->text('jbuf_fi_left_sign')->nullable();
            $table->text('jbuf_fi_section')->nullable();
            $table->text('jbuf_fi_class')->nullable();
            $table->text('jbuf_fi_subclass')->nullable();
            $table->text('jbuf_fi_main_grp')->nullable();
            $table->text('jbuf_fi_separator')->nullable();
            $table->text('jbuf_fi_sub_grp')->nullable();
            $table->text('jbuf_fi_subdiv_sign')->nullable();
            $table->text('jbuf_fi_separate_vol_class')->nullable();
            $table->text('jbuf_fi_facet')->nullable();
            $table->text('jbuf_fi_right_sign')->nullable();
            $table->text('jbuf_fi_jpo_refer_num')->nullable();
            $table->text('jbuf_fi_amend_mk')->nullable();
            $table->text('jbuf_fi_prlmnry')->nullable();
            $table->text('jbrf_fi_class_typ')->nullable();
            $table->text('jbrf_fi_left_sign')->nullable();
            $table->text('jbrf_fi_section')->nullable();
            $table->text('jbrf_fi_class')->nullable();
            $table->text('jbrf_fi_subclass')->nullable();
            $table->text('jbrf_fi_main_grp')->nullable();
            $table->text('jbrf_fi_separator')->nullable();
            $table->text('jbrf_fi_sub_grp')->nullable();
            $table->text('jbrf_fi_subdiv_sign')->nullable();
            $table->text('jbrf_fi_separate_vol_class')->nullable();
            $table->text('jbrf_fi_facet')->nullable();
            $table->text('jbrf_fi_right_sign')->nullable();
            $table->text('jbrf_fi_jpo_refer_num')->nullable();
            $table->text('jbrf_fi_amend_mk')->nullable();
            $table->text('jbrf_fi_prlmnry')->nullable();

            $table->unique(['law_cd', 'app_num', 'article_id', 'repeat_num'], 'upd_pmjb_gr_jpo_case_biblog_main_ids');
            $table->index('app_num', 'idx_upd_pmjb_gr_jpo_case_biblog_app_num');
            $table->index('article_id', 'idx_upd_pmjb_gr_jpo_case_biblog_article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_pmjb_gr_jpo_case_biblog');
    }
};

