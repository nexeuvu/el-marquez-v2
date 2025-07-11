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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->default('DNI'); // DNI, CE (Carné de Extranjería), etc.
            $table->string('dni')->unique(); // Número de documento
            $table->string('name'); // Nombres
            $table->string('last_name'); // Apellidos
            $table->string('phone'); // Teléfono
            $table->string('email')->nullable(); // Email (opcional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
