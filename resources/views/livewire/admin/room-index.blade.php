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

    {{-- Alerta de error --}}
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
            Registrar Nueva Habitación
        </h1>

        <form action="{{ route('admin.room.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Número de Habitación -->
            <div>
                <label for="number" class="block text-sm font-medium text-zinc-300 mb-1">
                    Número de Habitación <span class="text-red-500">*</span>
                </label>
                <input type="text" id="number" name="number"
                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Ej: 101, A202" required>
                @error('number')
                    <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="roomForm()">

            <!-- Tipo de Habitación -->
            <div class="mb-6">
                <label for="room_type" class="block text-sm font-medium text-zinc-300 mb-1">
                    Tipo de Habitación <span class="text-red-500">*</span>
                </label>
                <select id="room_type" name="room_type" x-model="roomType" @change="updatePrice()"
                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="Habitación individual">Habitación individual</option>
                    <option value="Habitación doble estándar (una cama doble)">Habitación doble estándar (una cama doble)</option>
                    <option value="Habitación doble estándar (dos camas separadas)">Habitación doble estándar (dos camas separadas)</option>
                    <option value="Habitación doble deluxe">Habitación doble deluxe</option>
                </select>
                @error('room_type')
                    <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Precio -->
            <div>
                <label for="price" class="block text-sm font-medium text-zinc-300 mb-1">
                    Precio por noche (S/) <span class="text-red-500">*</span>
                </label>
                <input type="number" step="0.01" id="price" name="price" x-model="price"
                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white placeholder-zinc-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Ej: 150.00" required>
                @error('price')
                    <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>


            <!-- Separador -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-800"></div>
                </div>
            </div>

            <!-- Nota de campos obligatorios -->
            <div class="text-sm text-zinc-500 mb-6">
                Los campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios.
            </div>

            <!-- Botón de Enviar -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Registrar Habitación
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function roomForm() {
        return {
            roomType: '',
            price: '',

            updatePrice() {
                const prices = {
                    'Habitación individual': 80.00,
                    'Habitación doble estándar (una cama doble)': 100.00,
                    'Habitación doble estándar (dos camas separadas)': 120.00,
                    'Habitación doble deluxe': 150.00,
                };

                this.price = prices[this.roomType] || '';
            }
        };
    }
</script>
