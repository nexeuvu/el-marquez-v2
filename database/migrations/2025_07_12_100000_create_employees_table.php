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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->default('DNI'); // DNI, CE, etc.
            $table->string('dni')->unique();                // Número de documento
            $table->string('name');                         // Nombres
            $table->string('last_name');                    // Apellidos
            $table->string('role');                         // Cargo/puesto del empleado
            $table->string('shift');                        // Turno (mañana/tarde/noche)
            $table->boolean('status')->default(true);       // Activo/Inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
