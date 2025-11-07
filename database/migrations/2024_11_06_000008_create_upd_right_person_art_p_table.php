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
        Schema::create('upd_right_person_art_p', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->integer('processing_type')->nullable();
            $table->integer('law_cd');
            $table->text('reg_num');
            $table->text('split_num')->nullable();
            $table->text('app_num')->nullable();
            $table->text('rec_num')->nullable();
            $table->text('pe_num');
            $table->text('right_psn_art_upd_ymd')->nullable();
            $table->text('right_person_appl_id')->nullable();
            $table->text('right_person_addr_len')->nullable();
            $table->text('right_person_addr')->nullable();
            $table->text('right_person_name_len')->nullable();
            $table->text('right_person_name')->nullable();

            $table->unique(['law_cd', 'reg_num', 'pe_num'], 'upd_right_person_art_p_main_ids');
            $table->index('reg_num', 'idx_upd_right_person_art_p_reg_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_right_person_art_p');
    }
};

