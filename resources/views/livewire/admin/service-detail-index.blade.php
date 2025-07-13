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
                customClass: { popup: 'rounded-lg shadow-lg' }
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
                customClass: { popup: 'rounded-lg shadow-lg text-left' }
            });
        </script>
    @endif

    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <h1 class="text-2xl font-bold text-white mb-6">Registrar Detalle de Servicio</h1>

        <form action="{{ route('admin.service_detail.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Servicio --}}
                <div>
                    <label for="service_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Servicio <span class="text-red-500">*</span>
                    </label>
                    <select id="service_id" name="service_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required onchange="updateTotal()">
                        <option value="" disabled selected>Seleccione un servicio</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                {{ $service->name }} (S/. {{ number_format($service->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Empleado --}}
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Empleado <span class="text-red-500">*</span>
                    </label>
                    <select id="employee_id" name="employee_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                        <option value="" disabled selected>Seleccione un empleado</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} {{ $employee->last_name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Reserva --}}
                <div>
                    <label for="booking_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Reserva <span class="text-red-500">*</span>
                    </label>
                    <select id="booking_id" name="booking_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required onchange="updateTotal()">
                        <option value="" disabled selected>Seleccione una reserva</option>
                        @foreach ($bookings as $booking)
                            <option value="{{ $booking->id }}"
                                data-room-price="{{ $booking->price_pay }}">
                                {{ $booking->guest->name }} {{ $booking->guest->last_name }} - Habitación {{ $booking->room->number }} - Precio S/. {{ number_format($booking->price_pay, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('booking_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha de consumo --}}
                <div>
                    <label for="consumption_date" class="block text-sm font-medium text-zinc-300 mb-1">
                        Fecha de Consumo <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="consumption_date" name="consumption_date"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                    @error('consumption_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cantidad --}}
                <div>
                    <label for="quantity" class="block text-sm font-medium text-zinc-300 mb-1">
                        Cantidad <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="quantity" name="quantity"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required min="1" step="1" onchange="updateTotal()">
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Total --}}
                <div>
                    <label for="total" class="block text-sm font-medium text-zinc-300 mb-1">
                        Total (S/.) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="total" name="total"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        readonly step="0.01">
                    @error('total')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="text-sm text-zinc-500 my-6">
                Los campos con <span class="text-red-500 font-bold">*</span> son obligatorios.
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg">
                    Registrar Detalle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateTotal() {
        const quantity = parseInt(document.getElementById('quantity').value) || 0;

        const serviceSelect = document.getElementById('service_id');
        const selectedService = serviceSelect.options[serviceSelect.selectedIndex];
        const servicePrice = parseFloat(selectedService?.getAttribute('data-price')) || 0;

        const bookingSelect = document.getElementById('booking_id');
        const selectedBooking = bookingSelect.options[bookingSelect.selectedIndex];
        const roomPrice = parseFloat(selectedBooking?.getAttribute('data-room-price')) || 0;

        const total = (quantity * servicePrice) + roomPrice;
        document.getElementById('total').value = total.toFixed(2);
    }

    document.getElementById('service_id').addEventListener('change', updateTotal);
    document.getElementById('booking_id').addEventListener('change', updateTotal);
    document.getElementById('quantity').addEventListener('input', updateTotal);
</script>


