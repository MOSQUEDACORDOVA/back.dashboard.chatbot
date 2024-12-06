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
        Schema::table('conversation_configurations', function (Blueprint $table) {
            $table->string('thread_id')->nullable(); // Nueva columna para thread_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_configurations', function (Blueprint $table) {
            $table->dropColumn('thread_id'); // Eliminar la columna al revertir
        });
    }
};
