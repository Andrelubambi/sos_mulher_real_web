<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_voluntarios_table.php

public function up(): void
{
    Schema::create('voluntarios', function (Blueprint $table) {
        $table->id();
        $table->string('email')->unique(); // PRECISA DO CAMPO EMAIL
        $table->string('telefone')->unique(); // PRECISA DO CAMPO TELEFONE
        // Adicione outros campos necessários
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voluntarios');
    }
};
