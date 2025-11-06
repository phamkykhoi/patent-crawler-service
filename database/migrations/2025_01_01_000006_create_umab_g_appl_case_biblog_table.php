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
        Schema::create('umab_g_appl_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->char('ab_delete_flg', 1)->nullable();
            $table->string('ab_update_dttm', 14)->nullable();
            $table->char('abcn_delete_flg', 1)->nullable();
            $table->string('abcn_app_claim_cnt', 10)->nullable();
            $table->string('abcn_exam_pub_claim_cnt', 10)->nullable();
            $table->string('abcn_reg_claim_cnt', 10)->nullable();
            $table->char('abrt_delete_flg', 1)->nullable();
            $table->string('abrt_right_trf', 50)->nullable();
            $table->string('abrt_license_permission', 50)->nullable();
            $table->char('abpp_delete_flg', 1)->nullable();
            $table->char('abip_delete_flg', 1)->nullable();
            $table->char('abna_delete_flg', 1)->nullable();
            $table->char('abpa_delete_flg', 1)->nullable();
            $table->string('abpa_parent_app_typ', 2)->nullable();
            $table->string('abpa_parent_app_law_cd', 2)->nullable();
            $table->string('abpa_parent_app_num', 50)->nullable();
            $table->string('abpa_retroacted_dt', 8)->nullable();
            $table->char('abdn_delete_flg', 1)->nullable();
            $table->text('abdn_device_title')->nullable();
            $table->char('abaa_delete_flg', 1)->nullable();
            $table->char('abde_delete_flg', 1)->nullable();
            $table->char('abti_delete_flg', 1)->nullable();
            $table->char('abds_delete_flg', 1)->nullable();
            $table->char('abli_delete_flg', 1)->nullable();
            $table->char('abdp_delete_flg', 1)->nullable();
            $table->char('abnl_delete_flg', 1)->nullable();
            $table->string('abnl_novelty_lack_class', 10)->nullable();
            $table->char('abae_delete_flg', 1)->nullable();
            $table->char('abct_delete_flg', 1)->nullable();
            $table->char('aban_delete_flg', 1)->nullable();
            
            $table->unique(['law_cd', 'app_num']);
            $table->index('app_num', 'idx_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umab_g_appl_case_biblog');
    }
};

