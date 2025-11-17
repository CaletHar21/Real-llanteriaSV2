<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); // conductor
            $table->enum('estado', ['asignada', 'en_transito', 'entregada', 'fallida'])->default('asignada');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('telefono');
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 10, 8)->nullable();
            $table->text('observaciones')->nullable();
            $table->dateTime('fecha_entrega_realizada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
