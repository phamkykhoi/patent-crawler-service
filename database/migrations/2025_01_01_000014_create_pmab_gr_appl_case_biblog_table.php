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
        Schema::create('pmab_gr_appl_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->string('article_id', 10);
            $table->integer('repeat_num');
            $table->string('abpp_pri_app_num', 50)->nullable();
            $table->string('abpp_pri_claim_dt', 8)->nullable();
            $table->string('abpp_pri_cntry_cd', 10)->nullable();
            $table->string('abip_intnl_pri_law_cd', 10)->nullable();
            $table->string('abip_intnl_pri_app_num', 50)->nullable();
            $table->string('abip_intl_app_num', 50)->nullable();
            $table->string('abip_claim_dt', 8)->nullable();
            $table->string('abna_newapp_app_typ', 10)->nullable();
            $table->string('abna_newapp_law_cd', 2)->nullable();
            $table->string('abna_newapp_app_num', 50)->nullable();
            $table->string('abaa_appl_atty_class', 10)->nullable();
            $table->string('abaa_appl_atty_id', 50)->nullable();
            $table->string('abaa_change_num', 10)->nullable();
            $table->string('abaa_req_typ', 10)->nullable();
            $table->string('abaa_nationality_cd', 10)->nullable();
            $table->string('abaa_pref_cd', 10)->nullable();
            $table->string('abaa_rep_appl_id', 50)->nullable();
            $table->string('abaa_above_appl_cnt', 10)->nullable();
            $table->string('abaa_atty_other_cnt', 10)->nullable();
            $table->string('abaa_atty_typ_cd', 10)->nullable();
            $table->string('abaa_atty_qualify_cd', 10)->nullable();
            $table->string('abaa_crrspnd_num', 50)->nullable();
            $table->text('abii_inventor_name')->nullable();
            $table->text('abii_inventor_addr')->nullable();
            $table->string('abti_trust_typ', 10)->nullable();
            $table->string('abti_nationality_cd', 10)->nullable();
            $table->text('abti_name')->nullable();
            $table->text('abti_addr')->nullable();
            $table->string('abds_design_state_cd', 10)->nullable();
            $table->char('abds_regional_patent_mk', 1)->nullable();
            $table->string('abli_later_pri_law_cd', 10)->nullable();
            $table->string('abli_later_pri_app_num', 50)->nullable();
            $table->string('abli_later_pri_app_dt', 8)->nullable();
            $table->string('abdp_mcrb_dpst_instt_id', 50)->nullable();
            $table->string('abdp_mcrb_dpst_num', 50)->nullable();
            $table->string('abnl_novelty_lack_art_cd', 10)->nullable();
            $table->text('abnl_novelty_lack_content')->nullable();
            $table->string('abae_crrspnd_num', 50)->nullable();
            $table->text('abae_appl_atty_addr')->nullable();
            $table->text('abae_appl_atty_name')->nullable();
            $table->string('abct_clmt_atty_class', 10)->nullable();
            $table->string('abct_clmt_atty_id', 50)->nullable();
            $table->string('abct_change_num', 10)->nullable();
            $table->string('abct_req_typ', 10)->nullable();
            $table->string('abct_pref_cd', 10)->nullable();
            $table->string('abct_rep_clmt_id', 50)->nullable();
            $table->string('abct_atty_typ_cd', 10)->nullable();
            $table->string('abct_crrspnd_num', 50)->nullable();
            $table->string('aban_crrspnd_num', 50)->nullable();
            $table->text('aban_clmt_atty_addr')->nullable();
            $table->text('aban_clmt_atty_name')->nullable();
            
            $table->unique(['law_cd', 'app_num', 'article_id', 'repeat_num']);
            $table->index('app_num', 'idx_pmab_gr_appl_case_biblog_app_num');
            $table->index('article_id', 'idx_pmab_gr_appl_case_biblog_article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmab_gr_appl_case_biblog');
    }
};

