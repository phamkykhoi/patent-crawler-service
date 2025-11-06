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
        Schema::create('pmac_gr_app_case_repeat_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('law_cd');
            $table->string('app_num', 50);
            $table->string('article_id', 10);
            $table->integer('repeat_num');
            $table->string('acap_appeal_num', 50)->nullable();
            
            $table->unique(['law_cd', 'app_num', 'article_id', 'repeat_num']);
            $table->index('app_num', 'idx_app_num');
            $table->index('article_id', 'idx_article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmac_gr_app_case_repeat_data');
    }
};

