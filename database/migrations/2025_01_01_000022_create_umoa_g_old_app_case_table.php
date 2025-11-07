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
        Schema::create('upd_umoa_g_old_app_case', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('oa_delete_flg')->nullable();
            $table->text('oa_update_dttm')->nullable();
            $table->text('oaep_delete_flg')->nullable();
            $table->text('oaep_exam_pub_num')->nullable();
            $table->text('oaep_exam_pub_dt')->nullable();

            $table->unique(['law_cd', 'app_num'], 'upd_umoa_g_old_app_case_main_ids');
            $table->index('app_num', 'idx_upd_umoa_g_old_app_case_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_umoa_g_old_app_case');
    }
};

