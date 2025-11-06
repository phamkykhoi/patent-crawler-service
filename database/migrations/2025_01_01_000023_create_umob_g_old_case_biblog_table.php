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
        Schema::create('umob_g_old_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->char('ob_delete_flg', 1)->nullable();
            $table->string('ob_update_dttm', 14)->nullable();
            $table->char('obpr_delete_flg', 1)->nullable();
            $table->char('obpr_pllt_ctrl_relate_tech_mk', 1)->nullable();
            $table->char('obei_delete_flg', 1)->nullable();
            $table->char('obpd_delete_flg', 1)->nullable();
            $table->char('obnp_delete_flg', 1)->nullable();
            
            $table->unique(['law_cd', 'app_num']);
            $table->index('app_num', 'idx_umob_g_old_case_biblog_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umob_g_old_case_biblog');
    }
};

