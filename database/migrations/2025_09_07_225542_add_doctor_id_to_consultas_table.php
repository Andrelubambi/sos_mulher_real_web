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
        Schema::table('consultas', function (Blueprint $table) {
            // Adiciona a coluna 'doutor_id' como uma chave estrangeira
            $table->foreignId('doutor_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            // Remove a chave estrangeira e a coluna
            $table->dropForeign(['doutor_id']);
            $table->dropColumn('doutor_id');
        });
    }
};
