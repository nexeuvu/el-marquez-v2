<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    {{-- Alerta de éxito --}}
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

    {{-- Errores de validación --}}
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
        <h1 class="text-2xl font-bold text-white mb-6">Registrar Detalle de Reserva</h1>

        <form action="{{ route('admin.booking_detail.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Reserva --}}
                <div>
                    <label for="booking_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Reserva <span class="text-red-500">*</span>
                    </label>
                    <select id="booking_id" name="booking_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione una reserva</option>
                        @foreach ($bookings as $booking)
                            <option value="{{ $booking->id }}">
                                {{ $booking->id }} - {{ $booking->guest->name }} {{ $booking->guest->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('booking_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Habitación --}}
                <div>
                    <label for="room_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Habitación <span class="text-red-500">*</span>
                    </label>
                    <select id="room_id" name="room_id" onchange="calculateTotal()"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione una habitación</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" data-price="{{ $room->price }}">
                                Habitación {{ $room->number }} - {{ $room->room_type }} (S/. {{ number_format($room->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Número de Noches --}}
                <div>
                    <label for="number_night" class="block text-sm font-medium text-zinc-300 mb-1">
                        Número de Noches <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="number_night" name="number_night" min="1" onchange="calculateTotal()"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: 3" required value="{{ old('number_night') }}">
                    @error('number_night')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Precio Total --}}
                <div>
                    <label for="unit_price" class="block text-sm font-medium text-zinc-300 mb-1">
                        Precio Total (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="unit_price" name="unit_price" step="0.01" min="0"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: 120.00" required value="{{ old('unit_price') }}">
                    @error('unit_price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Separador --}}
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-800"></div>
                </div>
            </div>

            {{-- Nota --}}
            <div class="text-sm text-zinc-500 mb-6">
                Los campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
            </div>

            {{-- Botón --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg">
                    Registrar Detalle
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script de cálculo --}}
<script>
    function calculateTotal() {
        const roomSelect = document.getElementById('room_id');
        const nightsInput = document.getElementById('number_night');
        const priceInput = document.getElementById('unit_price');

        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const unitNightPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const nights = parseInt(nightsInput.value) || 0;

        const total = unitNightPrice * nights;
        priceInput.value = total.toFixed(2);
    }
</script>
