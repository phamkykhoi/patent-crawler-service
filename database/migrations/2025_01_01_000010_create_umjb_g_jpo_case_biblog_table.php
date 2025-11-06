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
        Schema::create('umjb_g_jpo_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->char('jb_delete_flg', 1)->nullable();
            $table->string('jb_update_dttm', 14)->nullable();
            $table->char('jbui_delete_flg', 1)->nullable();
            $table->char('jbri_delete_flg', 1)->nullable();
            $table->char('jbdc_delete_flg', 1)->nullable();
            $table->string('jbdc_desig_class_ipc', 50)->nullable();
            $table->char('jboi_delete_flg', 1)->nullable();
            $table->string('jboi_staff_id', 10)->nullable();
            $table->string('jboi_div_cd', 10)->nullable();
            $table->char('jbpo_delete_flg', 1)->nullable();
            $table->char('jbpo_goodmoral_mk', 1)->nullable();
            $table->char('jbuf_delete_flg', 1)->nullable();
            $table->char('jbrf_delete_flg', 1)->nullable();
            $table->char('jbdf_delete_flg', 1)->nullable();
            $table->char('jbdf_fi_section', 1)->nullable();
            $table->string('jbdf_fi_class', 2)->nullable();
            $table->char('jbdf_fi_subclass', 1)->nullable();
            $table->string('jbdf_fi_main_grp', 5)->nullable();
            $table->char('jbdf_fi_separator', 1)->nullable();
            $table->string('jbdf_fi_sub_grp', 5)->nullable();
            $table->string('jbdf_fi_subdiv_sign', 10)->nullable();
            $table->string('jbdf_fi_separate_vol_class', 50)->nullable();
            $table->char('jbdf_fi_facet', 1)->nullable();
            
            $table->unique(['law_cd', 'app_num']);
            $table->index('app_num', 'idx_umjb_g_jpo_case_biblog_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umjb_g_jpo_case_biblog');
    }
};

