<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('NOMBRES');
            $table->string('APELLIDOS');
            $table->string('CORREO')->unique();
            $table->string('DUI')->unique();
            $table->string('TELEFONO');
            $table->string('DIRECCION');
            $table->string('PASSWORD');
            $table->enum('ROL', ['CLIENTE', 'AGENTE', 'ADMIN'])->default('CLIENTE');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('usuarios');
    }
};
