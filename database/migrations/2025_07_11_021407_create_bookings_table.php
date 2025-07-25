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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->unsignedBigInteger('guest_id');
            $table->foreign('guest_id')
                ->references('id')
                ->on('guests')
                ->onDelete('cascade');

            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');

            // Fechas de la reserva
            $table->date('start_date');
            $table->date('end_date');

            // Precio total pagado
            $table->decimal('price_pay', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
