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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('USUARIO_ID')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('LLANTA_ID')->constrained('llantas')->onDelete('cascade');
            $table->integer('CANTIDAD');
            $table->decimal('PRECIO_UNITARIO', 10, 2);
            $table->decimal('SUBTOTAL', 10, 2);
            $table->string('ESTADO')->default('PENDIENTE'); // PENDIENTE, CONTACTADO, CANCELADA
            $table->text('NOTAS')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
