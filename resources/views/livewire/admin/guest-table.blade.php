<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="guestTable()">
    <!-- Notificaciones -->
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

    <!-- Tabla de Huéspedes -->
    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white" data-flux-component="heading">
                Lista de Huéspedes
            </h1>
            <div class="space-x-2">
                <a href="{{ route('admin.guest.export-pdf') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Exportar PDF
                </a>
                <a href="{{ route('admin.guest.export-excel') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Exportar Excel
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Tipo Documento</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">DNI</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Nombre</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Apellidos</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Teléfono</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Email</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($guests as $guest)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $guest->document_type }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $guest->dni }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ Str::limit($guest->name, 20) }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ Str::limit($guest->last_name, 20) }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $guest->phone }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $guest->email ?? 'N/A' }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                <!-- Botón Editar -->
                                <button
                                    @click="openModal({{ $guest->id }}, '{{ addslashes($guest->document_type) }}', '{{ addslashes($guest->dni) }}', '{{ addslashes($guest->name) }}', '{{ addslashes($guest->last_name) }}', '{{ addslashes($guest->phone) }}', '{{ addslashes($guest->email) }}')"
                                    class="text-blue-500 hover:text-blue-400 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>

                                <!-- Botón Eliminar -->
                                <button onclick="confirmDelete({{ $guest->id }})"
                                    class="text-red-500 hover:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Formulario Eliminar (oculto) -->
                                <form id="delete-form-{{ $guest->id }}"
                                    action="{{ route('admin.guest.destroy', $guest->id) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($guests->hasPages())
            <div class="mt-6">
                {{ $guests->links() }}
            </div>
        @endif
    </div>

    <template x-teleport="body">
        <div x-show="isOpen" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Fondo oscuro -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-black opacity-75" @click="closeModal"></div>
                </div>

                <!-- Contenido del Modal -->
                <div
                    class="inline-block align-bottom bg-zinc-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-zinc-800">
                    <form :action="'/admin/guest/' + currentId" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="px-8 py-8">
                            <h3 class="text-xl font-semibold text-white mb-6">Editar Huésped</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Campo Tipo de Documento -->
                                <div>
                                    <label class="block text-sm font-medium text-zinc-300 mb-2">Tipo de Documento</label>
                                    <select x-model="currentDocumentType" name="document_type"
                                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                        <option value="DNI">DNI</option>
                                        <option value="CE">Carné de Extranjería</option>
                                    </select>
                                </div>
                                <!-- Campo DNI -->
                                <div>
                                    <label class="block text-sm font-medium text-zinc-300 mb-2">DNI</label>
                                    <input type="text" x-model="currentDni" name="dni"
                                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required pattern="\d{8}" maxlength="8"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <!-- Campo Nombre -->
                                <div>
                                    <label class="block text-sm font-medium text-zinc-300 mb-2">Nombre</label>
                                    <input type="text" x-model="currentName" name="name"
                                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required maxlength="100">
                                </div>
                                <!-- Campo Apellidos -->
                                <div>
                                    <label class="block text-sm font-medium text-zinc-300 mb-2">Apellidos</label>
                                    <input type="text" x-model="currentLastName" name="last_name"
                                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required maxlength="100">
                                </div>
                                <!-- Campo Teléfono -->
                                <div>
                                    <label class="block text-sm font-medium text-zinc-300 mb-2">Teléfono</label>
                                    <input type="text" x-model="currentPhone" name="phone"
                                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required maxlength="20">
                                </div>
                                <!-- Campo Email -->
                                <div>
                                    <label class="block text-sm font-medium text-zinc-300 mb-2">Email</label>
                                    <input type="email" x-model="currentEmail" name="email"
                                        class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        maxlength="255">
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-4 bg-zinc-800 flex justify-end space-x-4">
                            <button type="button" @click="closeModal" class="px-6 py-3 text-zinc-300 hover:text-white">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    // Función para confirmar eliminación
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar huésped?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            background: '#18181b',
            color: '#f4f4f5',
            iconColor: '#ef4444',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'rounded-lg shadow-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Componente Alpine.js para la tabla
    function guestTable() {
        return {
            isOpen: false,
            currentId: null,
            currentDocumentType: '',
            currentDni: '',
            currentName: '',
            currentLastName: '',
            currentPhone: '',
            currentEmail: '',

            openModal(id, document_type, dni, name, last_name, phone, email) {
                this.currentId = id;
                this.currentDocumentType = document_type;
                this.currentDni = dni;
                this.currentName = name;
                this.currentLastName = last_name;
                this.currentPhone = phone;
                this.currentEmail = email || '';
                this.isOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeModal() {
                this.isOpen = false;
                document.body.classList.remove('overflow-hidden');
            }
        }
    }
</script>