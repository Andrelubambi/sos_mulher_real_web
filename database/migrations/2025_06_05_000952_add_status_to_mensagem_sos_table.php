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
        Schema::table('mensagem_sos', function (Blueprint $table) {
            $table->enum('status',['lido','nao_lido'])->default('nao_lido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mensagem_sos', function (Blueprint $table) {
            $table->dropColumn('status');  
        });
    }
};
