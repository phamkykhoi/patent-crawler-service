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
        Schema::create('upd_cmis_g_intl_app_case_stat', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('intl_app_num');
            $table->text('is_delete_flg')->nullable();
            $table->text('is_update_dttm')->nullable();
            $table->text('isic_delete_flg')->nullable();
            $table->text('isic_isr_uncreated_flg')->nullable();
            $table->text('isic_inspect_prhbt_flg')->nullable();
            $table->text('isic_dmy_flg')->nullable();
            $table->text('isic_prlmnry_exam_mk')->nullable();

            $table->unique('intl_app_num', 'upd_cmis_g_intl_app_case_stat_main_ids');
            $table->index('intl_app_num', 'idx_upd_cmis_g_intl_app_case_stat_intl_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_cmis_g_intl_app_case_stat');
    }
};

