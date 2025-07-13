<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    {{-- Alerta --}}
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
            Registrar Nuevo Empleado
        </h1>

        <form action="{{ route('admin.employee.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de documento -->
                <div>
                    <label for="document_type" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Tipo de Documento <span class="text-red-500">*</span>
                    </label>
                    <select id="document_type" name="document_type"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                        <option value="DNI">DNI</option>
                        <option value="CE">Carné de Extranjería</option>
                    </select>
                </div>

                <!-- DNI -->
                <div>
                    <label for="dni" class="text-sm text-zinc-300 font-medium mb-1 block">
                        DNI <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="dni" name="dni" maxlength="8"
                            class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                            placeholder="Ej: 12345678" required pattern="\d{8}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <button type="button" id="consultar-dni"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                            Consultar
                        </button>
                    </div>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="name" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Nombres <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required maxlength="100">
                </div>

                <!-- Apellidos -->
                <div>
                    <label for="last_name" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Apellidos <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required maxlength="100">
                </div>

                <!-- Rol -->
                <div>
                    <label for="role" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Rol <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                        <option value="" disabled selected>Seleccione un rol</option>
                        <option value="Recepcionista">Recepcionista</option>
                        <option value="Cocinero">Cocinero</option>
                        <option value="Camarero">Camarero</option>
                        <option value="Limpieza">Limpieza</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Seguridad">Seguridad</option>
                        <option value="Botones">Botones</option>
                        <option value="Conserje">Conserje</option>
                        <option value="Gerente">Gerente</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Turno -->
                <div>
                    <label for="shift" class="text-sm text-zinc-300 font-medium mb-1 block">
                        Turno <span class="text-red-500">*</span>
                    </label>
                    <select id="shift" name="shift"
                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white"
                        required>
                        <option value="" disabled selected>Seleccione un turno</option>
                        <option value="Mañana">Mañana</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noche">Noche</option>
                        <option value="Rotativo">Rotativo</option>
                    </select>
                    @error('shift')
                        <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Nota de campos obligatorios -->
                <div class="text-sm text-zinc-500 mb-6">
                    Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
                </div>

            <!-- Botón de acción principal -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-zinc-900 transition-all duration-200 shadow-lg hover:shadow-xl"
                    data-flux-component="button">
                    Registrar Empleado
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#consultar-dni').on('click', function () {
            const dni = $('#dni').val();
            const documentType = $('#document_type').val();

            if (!dni.match(/^\d{8}$/)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El DNI debe tener exactamente 8 dígitos',
                    background: '#18181b',
                    color: '#f4f4f5',
                    iconColor: '#ef4444',
                });
                return;
            }

            $.ajax({
                url: '{{ route('admin.employee.consultar-dni') }}',
                method: 'GET',
                data: {
                    dni: dni,
                    document_type: documentType
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                            background: '#18181b',
                            color: '#f4f4f5',
                            iconColor: '#ef4444',
                        });
                    } else {
                        $('#name').val(data.name || '');
                        $('#last_name').val(data.last_name || '');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Consulta exitosa!',
                            text: 'Datos cargados automáticamente.',
                            background: '#18181b',
                            color: '#f4f4f5',
                            iconColor: '#22c55e',
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.error || 'Error al consultar la API',
                        background: '#18181b',
                        color: '#f4f4f5',
                        iconColor: '#ef4444',
                    });
                }
            });
        });
    });
</script>
