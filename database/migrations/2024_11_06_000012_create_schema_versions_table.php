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
        Schema::create('schema_versions', function (Blueprint $table) {
            $table->text('table_name');
            $table->integer('version');
            $table->text('columns');
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['table_name', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schema_versions');
    }
};

