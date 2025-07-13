<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <h1 class="text-2xl font-bold text-white mb-6">
            Editar Pago #{{ $payment->id }}
        </h1>

        <form action="{{ route('admin.payment.update', $payment->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Copia el mismo formulario que usas en create, pero con valores actualizados -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Campos del formulario -->
                <div>
                    <label for="guest_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Huésped <span class="text-red-500">*</span>
                    </label>
                    <select id="guest_id" name="guest_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        @foreach ($guests as $guest)
                            <option value="{{ $guest->id }}" {{ $payment->guest_id == $guest->id ? 'selected' : '' }}>
                                {{ $guest->name }} {{ $guest->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Resto de campos del formulario -->
                <!-- Select de Huéspedes -->
<select id="guest_id" name="guest_id" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
    @foreach ($guests as $guest)
        <option value="{{ $guest->id }}" {{ $payment->guest_id == $guest->id ? 'selected' : '' }}>
            {{ $guest->name }} {{ $guest->last_name }}
        </option>
    @endforeach
</select>

<!-- Select de Habitaciones -->
<select id="room_id" name="room_id" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
    <option value="">No asociar</option>
    @foreach ($rooms as $room)
        <option value="{{ $room->id }}" {{ $payment->room_id == $room->id ? 'selected' : '' }}>
            {{ $room->number }} - {{ $room->room_type }}
        </option>
    @endforeach
</select>

<!-- Select de Servicios -->
<select id="service_id" name="service_id" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
    <option value="">No asociar</option>
    @foreach ($services as $service)
        <option value="{{ $service->id }}" {{ $payment->service_id == $service->id ? 'selected' : '' }}>
            {{ $service->service_type }}
        </option>
    @endforeach
</select>

<!-- Select de Reservas -->
<select id="booking_id" name="booking_id" class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white">
    <option value="">No asociar</option>
    @foreach ($bookings as $booking)
        <option value="{{ $booking->id }}" {{ $payment->booking_id == $booking->id ? 'selected' : '' }}>
            #{{ $booking->id }} - {{ $booking->guest->name ?? 'N/D' }}
        </option>
    @endforeach
</select>
            </div>

            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('admin.payment.index') }}"
                    class="px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                    Actualizar Pago
                </button>
            </div>
        </form>
    </div>
</div>