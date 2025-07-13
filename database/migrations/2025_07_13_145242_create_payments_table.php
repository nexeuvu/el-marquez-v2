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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->unsignedBigInteger('guest_id');
            $table->foreign('guest_id')
                ->references('id')
                ->on('guests')
                ->onDelete('cascade');

            $table->unsignedBigInteger('booking_id')->nullable();
            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onDelete('set null');

            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')
                ->references('id')
                ->on('services') // O 'room_services' si aplica
                ->onDelete('set null');

            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('set null');

            // Detalles del pago
            $table->date('payment_date');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method', 50); // Ej: efectivo, tarjeta, Yape, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
