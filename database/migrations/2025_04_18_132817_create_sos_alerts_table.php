<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_create_sos_alerts_table.php

public function up()
{
    Schema::create('sos_alerts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // quem emitiu
        $table->boolean('atendida')->default(false);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sos_alerts');
    }
};
