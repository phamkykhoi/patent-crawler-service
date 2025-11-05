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
        Schema::create('upd_right_person_art_u', function (Blueprint $table) {
            $table->id();
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->string('reg_num');
            $table->string('split_num')->nullable();
            $table->string('app_num')->nullable();
            $table->string('rec_num')->nullable();
            $table->string('pe_num');
            $table->string('right_psn_art_upd_ymd')->nullable();
            $table->string('right_person_appl_id')->nullable();
            $table->string('right_person_addr_len')->nullable();
            $table->string('right_person_addr')->nullable();
            $table->string('right_person_name_len')->nullable();
            $table->string('right_person_name')->nullable();

            $table->unique(['law_cd', 'reg_num', 'pe_num']);
            $table->index('reg_num', 'idx_reg_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_right_person_art_u');
    }
};

