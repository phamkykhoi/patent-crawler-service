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
        Schema::create('cmis_g_intl_app_case_stat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('intl_app_num', 50)->unique();
            $table->char('is_delete_flg', 1)->nullable();
            $table->string('is_update_dttm', 14)->nullable();
            $table->char('isic_delete_flg', 1)->nullable();
            $table->char('isic_isr_uncreated_flg', 1)->nullable();
            $table->char('isic_inspect_prhbt_flg', 1)->nullable();
            $table->char('isic_dmy_flg', 1)->nullable();
            $table->char('isic_prlmnry_exam_mk', 1)->nullable();
            
            $table->index('intl_app_num', 'idx_intl_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cmis_g_intl_app_case_stat');
    }
};

