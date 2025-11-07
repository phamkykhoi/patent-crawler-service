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
        Schema::create('upd_umcs_g_case_stat', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('cs_delete_flg')->nullable();
            $table->text('cs_update_dttm')->nullable();
            $table->text('cscs_delete_flg')->nullable();
            $table->text('cscs_exam_claim_list_mk')->nullable();
            $table->text('cscs_final_dspst_dt')->nullable();
            $table->text('cscs_acclrtd_exam_mk')->nullable();
            $table->text('cscs_pub_prep_flg')->nullable();
            $table->text('cscs_applicable_law_class')->nullable();
            $table->text('cscs_exam_typ')->nullable();
            $table->text('cscs_litigate_cd')->nullable();
            $table->text('cscs_final_decision_typ_cd')->nullable();
            $table->text('cscs_exam_claim_cnt')->nullable();
            $table->text('cscs_newapp_flg')->nullable();
            $table->text('cscs_later_intnl_pri_flg')->nullable();
            $table->text('cscs_citd_others_mk')->nullable();

            $table->unique(['law_cd', 'app_num'], 'upd_umcs_g_case_stat_main_ids');
            $table->index('app_num', 'idx_upd_umcs_g_case_stat_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_umcs_g_case_stat');
    }
};

