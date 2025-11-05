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
        Schema::create('upd_trnsfr_rcpt_info_p', function (Blueprint $table) {
            $table->id();
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->string('reg_num');
            $table->string('split_num')->nullable();
            $table->string('app_num')->nullable();
            $table->string('mrgn_info_upd_ymd')->nullable();
            $table->string('mu_num');
            $table->text('trnsfr_rcpt_info')->nullable();

            $table->unique(['law_cd', 'reg_num', 'mu_num']);
            $table->index('reg_num', 'idx_reg_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_trnsfr_rcpt_info_p');
    }
};

