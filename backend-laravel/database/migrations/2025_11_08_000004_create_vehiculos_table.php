<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('USUARIO_ID')->nullable();

            // ✅ Llaves foráneas corregidas
            $table->foreignId('MARCA_ID')->constrained('marcas');
            $table->foreignId('MODELO_ID')->constrained('modelos');

            $table->year('ANIO');
            $table->string('MEDIDA_RIN');
            $table->string('MEDIDA_LLANTA');
            $table->string('MARCA_LLANTA');
            $table->timestamps();

            $table->foreign('USUARIO_ID')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('vehiculos');
    }
};
