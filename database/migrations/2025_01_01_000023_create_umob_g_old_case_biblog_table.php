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
        Schema::create('upd_umob_g_old_case_biblog', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('ob_delete_flg')->nullable();
            $table->text('ob_update_dttm')->nullable();
            $table->text('obpr_delete_flg')->nullable();
            $table->text('obpr_pllt_ctrl_relate_tech_mk')->nullable();
            $table->text('obei_delete_flg')->nullable();
            $table->text('obpd_delete_flg')->nullable();
            $table->text('obnp_delete_flg')->nullable();

            $table->unique(['law_cd', 'app_num'], 'upd_umob_g_old_case_biblog_main_ids');
            $table->index('app_num', 'idx_upd_umob_g_old_case_biblog_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_umob_g_old_case_biblog');
    }
};

