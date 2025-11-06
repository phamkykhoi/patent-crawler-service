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
        Schema::create('pmcs_g_case_stat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->char('cs_delete_flg', 1)->nullable();
            $table->string('cs_update_dttm', 14)->nullable();
            $table->char('cscs_delete_flg', 1)->nullable();
            $table->char('cscs_exam_claim_list_mk', 1)->nullable();
            $table->string('cscs_final_dspst_dt', 8)->nullable();
            $table->char('cscs_acclrtd_exam_mk', 1)->nullable();
            $table->char('cscs_pub_prep_flg', 1)->nullable();
            $table->char('cscs_applicable_law_class', 1)->nullable();
            $table->string('cscs_exam_typ', 2)->nullable();
            $table->string('cscs_litigate_cd', 2)->nullable();
            $table->string('cscs_final_decision_typ_cd', 2)->nullable();
            $table->string('cscs_exam_claim_cnt', 10)->nullable();
            $table->char('cscs_newapp_flg', 1)->nullable();
            $table->char('cscs_later_intnl_pri_flg', 1)->nullable();
            $table->char('cscs_citd_others_mk', 1)->nullable();
            
            $table->unique(['law_cd', 'app_num']);
            $table->index('app_num', 'idx_pmcs_g_case_stat_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmcs_g_case_stat');
    }
};

