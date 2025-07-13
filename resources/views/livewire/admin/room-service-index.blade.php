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
            Registrar Servicio de Habitación
        </h1>

        <form action="{{ route('admin.room_service.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Empleado --}}
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Empleado <span class="text-red-500">*</span>
                    </label>
                    <select id="employee_id" name="employee_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione un empleado</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Habitación --}}
                <div>
                    <label for="room_id" class="block text-sm font-medium text-zinc-300 mb-1">
                        Habitación <span class="text-red-500">*</span>
                    </label>
                    <select id="room_id" name="room_id"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="" disabled selected>Seleccione una habitación</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">
                                {{ $room->number }} - {{ $room->room_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Servicio --}}
                <div>
                    <label for="service_type" class="block text-sm font-medium text-zinc-300 mb-1">
                        Tipo de Servicio <span class="text-red-500">*</span>
                    </label>
                    <select id="service_type" name="service_type"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required onchange="toggleOtherServiceInput(this)">
                        <option value="" disabled selected>Seleccione un tipo de servicio</option>
                        <option value="Limpieza">Limpieza</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Cambio de sábanas">Cambio de sábanas</option>
                        <option value="Room Service (comida)">Room Service (comida)</option>
                        <option value="Revisión de aire acondicionado">Revisión de aire acondicionado</option>
                        <option value="Otros">Otros</option>
                    </select>
                    @error('service_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo adicional para "Otros" --}}
                <div id="other_service_container" class="mt-4 hidden">
                    <label for="other_service" class="block text-sm font-medium text-zinc-300 mb-1">
                        Especifique el servicio
                    </label>
                    <input type="text" id="other_service" name="other_service"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ej: Desinfección, Decoración...">
                </div>


                {{-- Fecha del Servicio --}}
                <div>
                    <label for="service_date" class="block text-sm font-medium text-zinc-300 mb-1">
                        Fecha del Servicio <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="service_date" name="service_date"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                    @error('service_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Observaciones --}}
                <div class="md:col-span-2">
                    <label for="observations" class="block text-sm font-medium text-zinc-300 mb-1">
                        Observaciones
                    </label>
                    <textarea id="observations" name="observations" rows="4"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        placeholder="Ingrese observaciones adicionales..."></textarea>
                    @error('observations')
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

            {{-- Botón --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-lg">
                    Registrar Servicio
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleOtherServiceInput(select) {
        const container = document.getElementById('other_service_container');
        const input = document.getElementById('other_service');

        if (select.value === 'Otros') {
            container.classList.remove('hidden');
            input.required = true;
        } else {
            container.classList.add('hidden');
            input.required = false;
            input.value = ''; // Limpia el campo si se cambia de "Otros"
        }
    }
</script>