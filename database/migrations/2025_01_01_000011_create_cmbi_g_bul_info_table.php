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
        Schema::create('upd_cmbi_g_bul_info', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('bi_delete_flg')->nullable();
            $table->text('bi_update_dttm')->nullable();
            $table->text('biub_delete_flg')->nullable();
            $table->text('biub_total_vol_num')->nullable();
            $table->text('biub_annual_vol_num')->nullable();
            $table->text('biub_each_fld_vol_num')->nullable();
            $table->text('biub_each_fld_annual_vol_num')->nullable();
            $table->text('biub_publish_class')->nullable();
            $table->text('bipb_delete_flg')->nullable();
            $table->text('bipb_total_vol_num')->nullable();
            $table->text('bipb_annual_vol_num')->nullable();
            $table->text('bipb_each_fld_vol_num')->nullable();
            $table->text('bipb_each_fld_annual_vol_num')->nullable();
            $table->text('bipb_publish_class')->nullable();
            $table->text('birw_delete_flg')->nullable();
            $table->text('birw_total_vol_num')->nullable();
            $table->text('birw_annual_vol_num')->nullable();
            $table->text('birw_each_law_vol_num')->nullable();
            $table->text('birw_each_law_annual_vol_num')->nullable();
            $table->text('biab_delete_flg')->nullable();
            $table->text('biab_total_vol_num')->nullable();
            $table->text('biab_annual_vol_num')->nullable();
            $table->text('biab_each_fld_vol_num')->nullable();
            $table->text('biab_each_fld_annual_vol_num')->nullable();
            $table->text('biab_publish_class')->nullable();
            $table->text('biab_bul_publish_dt')->nullable();
            $table->text('biab_crrct_id')->nullable();
            $table->text('biau_delete_flg')->nullable();
            $table->text('biau_total_vol_num')->nullable();
            $table->text('biau_annual_vol_num')->nullable();
            $table->text('biau_each_fld_vol_num')->nullable();
            $table->text('biau_each_fld_annual_vol_num')->nullable();
            $table->text('biau_publish_class')->nullable();
            $table->text('biau_bul_publish_dt')->nullable();
            $table->text('biau_crrct_id')->nullable();
            $table->text('bipe_delete_flg')->nullable();
            $table->text('bipe_total_vol_num')->nullable();
            $table->text('bipe_annual_vol_num')->nullable();
            $table->text('bipe_each_fld_vol_num')->nullable();
            $table->text('bipe_each_fld_annual_vol_num')->nullable();
            $table->text('bipe_publish_class')->nullable();
            $table->text('bipe_bul_publish_dt')->nullable();
            $table->text('bipe_crrct_id')->nullable();
            $table->text('bire_delete_flg')->nullable();
            $table->text('bire_total_vol_num')->nullable();
            $table->text('bire_annual_vol_num')->nullable();
            $table->text('bire_each_fld_vol_num')->nullable();
            $table->text('bire_each_fld_annual_vol_num')->nullable();
            $table->text('bire_publish_class')->nullable();
            $table->text('bire_bul_publish_dt')->nullable();
            $table->text('bire_crrct_id')->nullable();

            $table->unique(['law_cd', 'app_num'], 'upd_cmbi_g_bul_info_main_ids');
            $table->index('app_num', 'idx_upd_cmbi_g_bul_info_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_cmbi_g_bul_info');
    }
};

