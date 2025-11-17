<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias_viales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('marca');
            $table->string('modelo');
            $table->string('placa');
            $table->enum('tipo_problema', ['pinchazo', 'bateria_descargada', 'motor_no_enciende', 'problemas_frenos', 'suspension_danada', 'perdida_aceite'])->default('pinchazo');
            $table->text('descripcion');
            $table->enum('estado', ['solicitada', 'asignada', 'en_atencion', 'resuelta', 'cancelada'])->default('solicitada');
            $table->string('ubicacion');
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 10, 8)->nullable();
            $table->foreignId('mecanico_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->text('solucion_aplicada')->nullable();
            $table->dateTime('fecha_resolucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias_viales');
    }
};
