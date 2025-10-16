<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mensagens', function (Blueprint $table) {
            $table->id();
    
            $table->unsignedBigInteger('de');
            $table->unsignedBigInteger('para');
            $table->text('conteudo');
            $table->timestamps();
            $table->foreign('de')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('para')->references('id')->on('users')->onDelete('cascade');
        });
    }
    


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensagens');
    }
};
