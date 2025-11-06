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
        Schema::create('pmjb_gr_jpo_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->string('article_id', 10);
            $table->integer('repeat_num');
            $table->char('jbui_amend_mk', 1)->nullable();
            $table->string('jbui_ver_num', 10)->nullable();
            $table->string('jbui_seq_num', 10)->nullable();
            $table->string('jbui_pub_ipc', 50)->nullable();
            $table->char('jbri_amend_mk', 1)->nullable();
            $table->string('jbri_ver_num', 10)->nullable();
            $table->string('jbri_seq_num', 10)->nullable();
            $table->string('jbri_reg_ipc', 50)->nullable();
            $table->string('jbuf_fi_class_typ', 10)->nullable();
            $table->string('jbuf_fi_left_sign', 10)->nullable();
            $table->char('jbuf_fi_section', 1)->nullable();
            $table->string('jbuf_fi_class', 2)->nullable();
            $table->char('jbuf_fi_subclass', 1)->nullable();
            $table->string('jbuf_fi_main_grp', 5)->nullable();
            $table->char('jbuf_fi_separator', 1)->nullable();
            $table->string('jbuf_fi_sub_grp', 5)->nullable();
            $table->string('jbuf_fi_subdiv_sign', 10)->nullable();
            $table->string('jbuf_fi_separate_vol_class', 50)->nullable();
            $table->char('jbuf_fi_facet', 1)->nullable();
            $table->string('jbuf_fi_right_sign', 10)->nullable();
            $table->string('jbuf_fi_jpo_refer_num', 50)->nullable();
            $table->char('jbuf_fi_amend_mk', 1)->nullable();
            $table->char('jbuf_fi_prlmnry', 1)->nullable();
            $table->string('jbrf_fi_class_typ', 10)->nullable();
            $table->string('jbrf_fi_left_sign', 10)->nullable();
            $table->char('jbrf_fi_section', 1)->nullable();
            $table->string('jbrf_fi_class', 2)->nullable();
            $table->char('jbrf_fi_subclass', 1)->nullable();
            $table->string('jbrf_fi_main_grp', 5)->nullable();
            $table->char('jbrf_fi_separator', 1)->nullable();
            $table->string('jbrf_fi_sub_grp', 5)->nullable();
            $table->string('jbrf_fi_subdiv_sign', 10)->nullable();
            $table->string('jbrf_fi_separate_vol_class', 50)->nullable();
            $table->char('jbrf_fi_facet', 1)->nullable();
            $table->string('jbrf_fi_right_sign', 10)->nullable();
            $table->string('jbrf_fi_jpo_refer_num', 50)->nullable();
            $table->char('jbrf_fi_amend_mk', 1)->nullable();
            $table->char('jbrf_fi_prlmnry', 1)->nullable();
            
            $table->unique(['law_cd', 'app_num', 'article_id', 'repeat_num']);
            $table->index('app_num', 'idx_app_num');
            $table->index('article_id', 'idx_article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmjb_gr_jpo_case_biblog');
    }
};

