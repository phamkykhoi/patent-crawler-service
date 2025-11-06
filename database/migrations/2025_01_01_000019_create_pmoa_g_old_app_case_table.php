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
        Schema::create('pmoa_g_old_app_case', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->char('oa_delete_flg', 1)->nullable();
            $table->string('oa_update_dttm', 14)->nullable();
            $table->char('oaep_delete_flg', 1)->nullable();
            $table->string('oaep_exam_pub_num', 50)->nullable();
            $table->string('oaep_exam_pub_dt', 8)->nullable();
            
            $table->unique(['law_cd', 'app_num']);
            $table->index('app_num', 'idx_app_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmoa_g_old_app_case');
    }
};

