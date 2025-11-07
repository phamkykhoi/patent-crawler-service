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
        Schema::create('upd_pmab_gr_appl_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('article_id');
            $table->integer('repeat_num');
            $table->text('abpp_pri_app_num')->nullable();
            $table->text('abpp_pri_claim_dt')->nullable();
            $table->text('abpp_pri_cntry_cd')->nullable();
            $table->text('abip_intnl_pri_law_cd')->nullable();
            $table->text('abip_intnl_pri_app_num')->nullable();
            $table->text('abip_intl_app_num')->nullable();
            $table->text('abip_claim_dt')->nullable();
            $table->text('abna_newapp_app_typ')->nullable();
            $table->text('abna_newapp_law_cd')->nullable();
            $table->text('abna_newapp_app_num')->nullable();
            $table->text('abaa_appl_atty_class')->nullable();
            $table->text('abaa_appl_atty_id')->nullable();
            $table->text('abaa_change_num')->nullable();
            $table->text('abaa_req_typ')->nullable();
            $table->text('abaa_nationality_cd')->nullable();
            $table->text('abaa_pref_cd')->nullable();
            $table->text('abaa_rep_appl_id')->nullable();
            $table->text('abaa_above_appl_cnt')->nullable();
            $table->text('abaa_atty_other_cnt')->nullable();
            $table->text('abaa_atty_typ_cd')->nullable();
            $table->text('abaa_atty_qualify_cd')->nullable();
            $table->text('abaa_crrspnd_num')->nullable();
            $table->text('abii_inventor_name')->nullable();
            $table->text('abii_inventor_addr')->nullable();
            $table->text('abti_trust_typ')->nullable();
            $table->text('abti_nationality_cd')->nullable();
            $table->text('abti_name')->nullable();
            $table->text('abti_addr')->nullable();
            $table->text('abds_design_state_cd')->nullable();
            $table->text('abds_regional_patent_mk')->nullable();
            $table->text('abli_later_pri_law_cd')->nullable();
            $table->text('abli_later_pri_app_num')->nullable();
            $table->text('abli_later_pri_app_dt')->nullable();
            $table->text('abdp_mcrb_dpst_instt_id')->nullable();
            $table->text('abdp_mcrb_dpst_num')->nullable();
            $table->text('abnl_novelty_lack_art_cd')->nullable();
            $table->text('abnl_novelty_lack_content')->nullable();
            $table->text('abae_crrspnd_num')->nullable();
            $table->text('abae_appl_atty_addr')->nullable();
            $table->text('abae_appl_atty_name')->nullable();
            $table->text('abct_clmt_atty_class')->nullable();
            $table->text('abct_clmt_atty_id')->nullable();
            $table->text('abct_change_num')->nullable();
            $table->text('abct_req_typ')->nullable();
            $table->text('abct_pref_cd')->nullable();
            $table->text('abct_rep_clmt_id')->nullable();
            $table->text('abct_atty_typ_cd')->nullable();
            $table->text('abct_crrspnd_num')->nullable();
            $table->text('aban_crrspnd_num')->nullable();
            $table->text('aban_clmt_atty_addr')->nullable();
            $table->text('aban_clmt_atty_name')->nullable();

            $table->unique(['law_cd', 'app_num', 'article_id', 'repeat_num'], 'upd_pmab_gr_appl_case_biblog_main_ids');
            $table->index('app_num', 'idx_upd_pmab_gr_appl_case_biblog_app_num');
            $table->index('article_id', 'idx_upd_pmab_gr_appl_case_biblog_article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_pmab_gr_appl_case_biblog');
    }
};

