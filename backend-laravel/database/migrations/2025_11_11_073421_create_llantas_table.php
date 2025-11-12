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
        Schema::create('llantas', function (Blueprint $table) {
            $table->id();
            $table->string('MARCA')->nullable();
            $table->string('MODELO_LLANTA')->nullable();
            $table->string('MEDIDA_RIN')->nullable();
            $table->string('MEDIDA_LLANTA')->nullable();
            $table->decimal('PRECIO', 10, 2)->default(0);
            $table->string('IMAGEN')->nullable();
            $table->enum('CONDICION', ['nueva', 'reacondicionada'])->default('nueva');
            $table->string('TIPO_VEHICULO')->nullable(); // autos, motos, camiones, etc
            $table->integer('STOCK')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llantas');
    }
};
