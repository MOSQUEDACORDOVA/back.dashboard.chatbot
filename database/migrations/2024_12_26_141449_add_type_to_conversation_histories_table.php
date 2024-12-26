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
        Schema::table('conversation_histories', function (Blueprint $table) {
            $table->enum('type', ['text', 'audio', 'image', 'video'])->default('text')->after('message'); // Agregado 'video'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_histories', function (Blueprint $table) {
            $table->dropColumn('type'); // Eliminar la columna si se revierte la migración
        });
    }
};
