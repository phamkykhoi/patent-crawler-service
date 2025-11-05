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
        Schema::create('upd_atty_art_p', function (Blueprint $table) {
            $table->id();
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->string('reg_num');
            $table->string('split_num')->nullable();
            $table->string('app_num')->nullable();
            $table->string('rec_num')->nullable();
            $table->string('pe_num');
            $table->string('atty_art_upd_ymd')->nullable();
            $table->string('atty_appl_id')->nullable();
            $table->integer('atty_typ')->nullable();
            $table->string('atty_name_len')->nullable();
            $table->string('atty_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_atty_art_p');
    }
};

