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
        Schema::create('upd_umos_g_old_case_stat', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('os_delete_flg')->nullable();
            $table->text('os_update_dttm')->nullable();
            $table->text('osos_delete_flg')->nullable();
            $table->text('osos_opp_cnt')->nullable();
            $table->text('osos_opp_valid_cnt')->nullable();

            $table->unique(['law_cd', 'app_num'], 'upd_umos_g_old_case_stat_main_ids');
            $table->index('app_num', 'idx_upd_umos_g_old_case_stat_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_umos_g_old_case_stat');
    }
};

