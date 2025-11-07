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
        Schema::create('upd_umab_g_appl_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('ab_delete_flg')->nullable();
            $table->text('ab_update_dttm')->nullable();
            $table->text('abcn_delete_flg')->nullable();
            $table->text('abcn_app_claim_cnt')->nullable();
            $table->text('abcn_exam_pub_claim_cnt')->nullable();
            $table->text('abcn_reg_claim_cnt')->nullable();
            $table->text('abrt_delete_flg')->nullable();
            $table->text('abrt_right_trf')->nullable();
            $table->text('abrt_license_permission')->nullable();
            $table->text('abpp_delete_flg')->nullable();
            $table->text('abip_delete_flg')->nullable();
            $table->text('abna_delete_flg')->nullable();
            $table->text('abpa_delete_flg')->nullable();
            $table->text('abpa_parent_app_typ')->nullable();
            $table->text('abpa_parent_app_law_cd')->nullable();
            $table->text('abpa_parent_app_num')->nullable();
            $table->text('abpa_retroacted_dt')->nullable();
            $table->text('abdn_delete_flg')->nullable();
            $table->text('abdn_device_title')->nullable();
            $table->text('abaa_delete_flg')->nullable();
            $table->text('abde_delete_flg')->nullable();
            $table->text('abti_delete_flg')->nullable();
            $table->text('abds_delete_flg')->nullable();
            $table->text('abli_delete_flg')->nullable();
            $table->text('abdp_delete_flg')->nullable();
            $table->text('abnl_delete_flg')->nullable();
            $table->text('abnl_novelty_lack_class')->nullable();
            $table->text('abae_delete_flg')->nullable();
            $table->text('abct_delete_flg')->nullable();
            $table->text('aban_delete_flg')->nullable();

            $table->unique(['law_cd', 'app_num'], 'upd_umab_g_appl_case_biblog_main_ids');
            $table->index('app_num', 'idx_upd_umab_g_appl_case_biblog_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_umab_g_appl_case_biblog');
    }
};

