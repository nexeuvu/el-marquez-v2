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
        <h1 class="text-2xl font-bold text-white mb-6">
            Registrar Nueva Reserva
        </h1>

        <form action="{{ route('admin.booking.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Guest --}}
                <div>
                    <label for="guest_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Huésped <span class="text-red-500">*</span>
                    </label>
                    <select id="guest_id" name="guest_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                        <option value="" disabled selected>Seleccione un huésped</option>
                        @foreach ($guests as $guest)
                            <option value="{{ $guest->id }}">{{ $guest->name }} {{ $guest->last_name }}</option>
                        @endforeach
                    </select>
                    @error('guest_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

               {{-- Room --}}
                <div>
                    <label for="room_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Habitación <span class="text-red-500">*</span>
                    </label>
                    <select id="room_id" name="room_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required onchange="calculatePrice()">
                        <option value="" disabled selected>Seleccione una habitación</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" data-price="{{ $room->price }}">
                                {{ $room->number }} - {{ $room->room_type }} (S/. {{ number_format($room->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Start Date --}}
                <div>
                    <label for="start_date" class="block text-sm font-medium text-zinc-300 mb-1">Fecha de Inicio <span class="text-red-500">*</span></label>
                    <input type="date" id="start_date" name="start_date"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required onchange="calculatePrice()">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <label for="end_date" class="block text-sm font-medium text-zinc-300 mb-1">Fecha de Fin <span class="text-red-500">*</span></label>
                    <input type="date" id="end_date" name="end_date"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required onchange="calculatePrice()">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Precio --}}
                <div class="md:col-span-2">
                    <label for="price_pay" class="block text-sm font-medium text-zinc-300 mb-1">
                        Precio a Pagar (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="price_pay" name="price_pay"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: 250.00" step="0.01" min="0" required>
                    @error('price_pay')
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
                Los campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios.
            </div>

            {{-- Botón de envío --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg">
                    Registrar Reserva
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function calculatePrice() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const roomSelect = document.getElementById('room_id');
        const priceField = document.getElementById('price_pay');

        if (!startDate || !endDate) return;

        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffTime = end - start;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const roomPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;

        if (diffDays > 0 && roomPrice > 0) {
            priceField.value = (diffDays * roomPrice).toFixed(2);
        } else {
            priceField.value = '';
        }
    }
</script>
