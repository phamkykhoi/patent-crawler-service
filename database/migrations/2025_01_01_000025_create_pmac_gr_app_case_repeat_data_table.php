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
        Schema::create('upd_pmac_gr_app_case_repeat_data', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('law_cd');
            $table->text('app_num');
            $table->text('article_id');
            $table->integer('repeat_num');
            $table->text('acap_appeal_num')->nullable();

            $table->unique(['law_cd', 'app_num', 'article_id', 'repeat_num'], 'upd_pmac_gr_app_case_repeat_data_main_ids');
            $table->index('app_num', 'idx_upd_pmac_gr_app_case_repeat_data_app_num');
            $table->index('article_id', 'idx_upd_pmac_gr_app_case_repeat_data_article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_pmac_gr_app_case_repeat_data');
    }
};

