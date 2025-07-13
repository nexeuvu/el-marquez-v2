<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Agregamos jQuery para facilitar las llamadas AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    {{-- Alertas --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "{{ session('success') }}",
                background: '#18181b',
                color: '#f4f4f5',
                iconColor: '#22c55e',
                confirmButtonColor: '#3b82f6',
                customClass: {
                    popup: 'rounded-lg shadow-lg'
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                background: '#18181b',
                color: '#f4f4f5',
                iconColor: '#ef4444',
                confirmButtonColor: '#3b82f6',
                customClass: {
                    popup: 'rounded-lg shadow-lg text-left'
                }
            });
        </script>
    @endif

    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <h1 class="text-2xl font-bold text-white mb-6">
            Registrar Nuevo Pago
        </h1>

        <form action="{{ route('admin.payment.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Guest --}}
                <div>
                    <label for="guest_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Huésped <span class="text-red-500">*</span>
                    </label>
                    <select id="guest_id" name="guest_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione un huésped</option>
                        @foreach ($guests as $guest)
                            <option value="{{ $guest->id }}">{{ $guest->name }} {{ $guest->last_name }}</option>
                        @endforeach
                    </select>
                    @error('guest_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Booking --}}
                <div>
                    <label for="booking_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Reserva (opcional)
                    </label>
                    <select id="booking_id" name="booking_id" 
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white booking-select">
                        <option value="" selected data-price="0">No asociar</option>
                       @foreach ($bookings as $booking)
                            <option value="{{ $booking->id }}" data-price="{{ $booking->price_pay }}">
                                {{ $booking->id }} - {{ $booking->guest->name ?? 'N/D' }} {{ $booking->guest->last_name ?? 'N/D' }}
                            </option>
                        @endforeach
                    </select>
                    @error('booking_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Room --}}
                <div>
                    <label for="room_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Habitación (opcional)
                    </label>
                    <select id="room_id" name="room_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white room-select">
                        <option value="" selected data-price="0">No asociar</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" data-price="{{ $room->price ?? 0 }}">
                                {{ $room->number }} - {{ $room->room_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Servicio --}}
                <div>
                    <label for="service_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Servicio (opcional)
                    </label>
                    <select id="service_id" name="service_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white service-select">
                        <option value="" selected data-price="0">No asociar</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price ?? 0 }}">
                                ID: {{ $service->id }} - {{ $service->name ?? 'Sin nombre' }} - {{ $service->service_type }} 
                (S/. {{ number_format($service->price ?? 0, 2) }}) 
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de pago --}}
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-zinc-300 mb-1">
                        Fecha de Pago <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="payment_date" name="payment_date"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Monto total --}}
                <div>
                    <label for="total_amount" class="block text-sm font-medium text-zinc-300 mb-1">
                        Monto Total (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="total_amount" name="total_amount" step="0.01" min="0"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: 150.00" required readonly>
                    @error('total_amount')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Método de pago --}}
                <div class="md:col-span-2">
                    <label for="payment_method" class="block text-sm font-medium text-zinc-300 mb-1">
                        Método de Pago <span class="text-red-500">*</span>
                    </label>
                    <select id="payment_method" name="payment_method"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione un método</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Yape">Yape</option>
                        <option value="Plin">Plin</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="text-sm text-zinc-500 mt-8 mb-4">
                Los campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios.
            </div>

            {{-- Botón --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg">
                    Registrar Pago
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        function calculateTotal() {
            // Obtener precios asegurando que son números
            let bookingPrice = parseFloat($('#booking_id option:selected').data('price')) || 0;
            let roomPrice = parseFloat($('#room_id option:selected').data('price')) || 0;
            let servicePrice = parseFloat($('#service_id option:selected').data('price')) || 0;
            
            // Sumar y mostrar con 2 decimales
            let total = (bookingPrice + roomPrice + servicePrice).toFixed(2);
            $('#total_amount').val(total);
        }

        // Eventos para todos los selects
        $('#booking_id, #room_id, #service_id').on('change', calculateTotal);
        
        // Calcular inicialmente
        calculateTotal();
    });
</script>
