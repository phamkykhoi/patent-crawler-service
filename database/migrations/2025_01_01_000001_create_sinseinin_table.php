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
        Schema::create('upd_sinseinin', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('sinseinin_code');
            $table->text('version_no')->nullable();
            $table->text('kuni_code')->nullable();
            $table->text('todohuken_code')->nullable();
            $table->text('kohokan_sikibetu')->nullable();
            $table->text('masshoriyu_mark')->nullable();
            $table->text('togosinseinin_code')->nullable();

            $table->unique('sinseinin_code', 'upd_sinseinin_main_ids');
            $table->index('sinseinin_code', 'idx_upd_sinseinin_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upd_sinseinin');
    }
};

