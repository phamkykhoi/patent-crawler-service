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
        Schema::create('cmbi_g_bul_info', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->char('bi_delete_flg', 1)->nullable();
            $table->string('bi_update_dttm', 14)->nullable();
            $table->char('biub_delete_flg', 1)->nullable();
            $table->string('biub_total_vol_num', 10)->nullable();
            $table->string('biub_annual_vol_num', 10)->nullable();
            $table->string('biub_each_fld_vol_num', 10)->nullable();
            $table->string('biub_each_fld_annual_vol_num', 10)->nullable();
            $table->string('biub_publish_class', 50)->nullable();
            $table->char('bipb_delete_flg', 1)->nullable();
            $table->string('bipb_total_vol_num', 10)->nullable();
            $table->string('bipb_annual_vol_num', 10)->nullable();
            $table->string('bipb_each_fld_vol_num', 10)->nullable();
            $table->string('bipb_each_fld_annual_vol_num', 10)->nullable();
            $table->string('bipb_publish_class', 50)->nullable();
            $table->char('birw_delete_flg', 1)->nullable();
            $table->string('birw_total_vol_num', 10)->nullable();
            $table->string('birw_annual_vol_num', 10)->nullable();
            $table->string('birw_each_law_vol_num', 10)->nullable();
            $table->string('birw_each_law_annual_vol_num', 10)->nullable();
            $table->char('biab_delete_flg', 1)->nullable();
            $table->string('biab_total_vol_num', 10)->nullable();
            $table->string('biab_annual_vol_num', 10)->nullable();
            $table->string('biab_each_fld_vol_num', 10)->nullable();
            $table->string('biab_each_fld_annual_vol_num', 10)->nullable();
            $table->string('biab_publish_class', 50)->nullable();
            $table->string('biab_bul_publish_dt', 8)->nullable();
            $table->string('biab_crrct_id', 50)->nullable();
            $table->char('biau_delete_flg', 1)->nullable();
            $table->string('biau_total_vol_num', 10)->nullable();
            $table->string('biau_annual_vol_num', 10)->nullable();
            $table->string('biau_each_fld_vol_num', 10)->nullable();
            $table->string('biau_each_fld_annual_vol_num', 10)->nullable();
            $table->string('biau_publish_class', 50)->nullable();
            $table->string('biau_bul_publish_dt', 8)->nullable();
            $table->string('biau_crrct_id', 50)->nullable();
            $table->char('bipe_delete_flg', 1)->nullable();
            $table->string('bipe_total_vol_num', 10)->nullable();
            $table->string('bipe_annual_vol_num', 10)->nullable();
            $table->string('bipe_each_fld_vol_num', 10)->nullable();
            $table->string('bipe_each_fld_annual_vol_num', 10)->nullable();
            $table->string('bipe_publish_class', 50)->nullable();
            $table->string('bipe_bul_publish_dt', 8)->nullable();
            $table->string('bipe_crrct_id', 50)->nullable();
            $table->char('bire_delete_flg', 1)->nullable();
            $table->string('bire_total_vol_num', 10)->nullable();
            $table->string('bire_annual_vol_num', 10)->nullable();
            $table->string('bire_each_fld_vol_num', 10)->nullable();
            $table->string('bire_each_fld_annual_vol_num', 10)->nullable();
            $table->string('bire_publish_class', 50)->nullable();
            $table->string('bire_bul_publish_dt', 8)->nullable();
            $table->string('bire_crrct_id', 50)->nullable();
            
            $table->unique(['law_cd', 'app_num']);
            $table->index('app_num', 'idx_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cmbi_g_bul_info');
    }
};

