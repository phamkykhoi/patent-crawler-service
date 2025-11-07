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
        Schema::create('upd_pmjb_g_jpo_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('jb_delete_flg')->nullable();
            $table->text('jb_update_dttm')->nullable();
            $table->text('jbui_delete_flg')->nullable();
            $table->text('jbri_delete_flg')->nullable();
            $table->text('jbdc_delete_flg')->nullable();
            $table->text('jbdc_desig_class_ipc')->nullable();
            $table->text('jboi_delete_flg')->nullable();
            $table->text('jboi_staff_id')->nullable();
            $table->text('jboi_div_cd')->nullable();
            $table->text('jbpo_delete_flg')->nullable();
            $table->text('jbpo_goodmoral_mk')->nullable();
            $table->text('jbuf_delete_flg')->nullable();
            $table->text('jbrf_delete_flg')->nullable();
            $table->text('jbdf_delete_flg')->nullable();
            $table->text('jbdf_fi_section')->nullable();
            $table->text('jbdf_fi_class')->nullable();
            $table->text('jbdf_fi_subclass')->nullable();
            $table->text('jbdf_fi_main_grp')->nullable();
            $table->text('jbdf_fi_separator')->nullable();
            $table->text('jbdf_fi_sub_grp')->nullable();
            $table->text('jbdf_fi_subdiv_sign')->nullable();
            $table->text('jbdf_fi_separate_vol_class')->nullable();
            $table->text('jbdf_fi_facet')->nullable();

            $table->unique(['law_cd', 'app_num'], 'upd_pmjb_g_jpo_case_biblog_main_ids');
            $table->index('app_num', 'idx_upd_pmjb_g_jpo_case_biblog_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_pmjb_g_jpo_case_biblog');
    }
};

