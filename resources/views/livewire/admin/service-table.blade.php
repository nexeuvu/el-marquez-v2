<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="serviceTable()">
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

    <!-- Tabla de Servicios -->
    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">
                Lista de Servicios
            </h1>
            <div class="space-x-2">
                <a href="{{ route('admin.service.export-pdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Exportar PDF</a>
                <a href="{{ route('admin.service.export-excel') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Exportar Excel</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Nombre</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Descripción</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Precio</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Estado</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($services as $service)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">
                                @foreach (is_array($service->name) ? $service->name : explode(',', $service->name) as $type)
                                    <span class="inline-block bg-blue-800 text-blue-200 text-xs font-medium px-2 py-1 rounded-full mr-1 mb-1">
                                        {{ trim($type) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ Str::limit($service->description, 50) }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/ {{ number_format($service->price, 2) }}</td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $service->status ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                    {{ $service->status ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-right">
                                <!-- Botón Editar -->
                                <button
                                    @click="openModal(
                                        {{ $service->id }},
                                        '{{ e($service->name) }}'.split(','),
                                        `{{ e($service->description) }}`,
                                        '{{ $service->price }}'
                                    )"
                                    class="text-blue-500 hover:text-blue-400 mr-3">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </button>

                                <!-- Botón Eliminar -->
                                <button onclick="confirmDelete({{ $service->id }})"
                                    class="text-red-500 hover:text-red-400">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                <!-- Formulario Eliminar -->
                                <form id="delete-form-{{ $service->id }}"
                                    action="{{ route('admin.service.destroy', $service->id) }}" method="POST"
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
        @if ($services->hasPages())
            <div class="mt-6">
                {{ $services->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Edición -->
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak x-transition class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
                <div class="fixed inset-0 bg-black bg-opacity-75" @click="closeModal"></div>
                <div class="inline-block bg-zinc-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full border border-zinc-800">
                    <form :action="'/admin/service/' + currentId" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="px-8 py-8">
                            <h3 class="text-xl font-semibold text-white mb-6">Editar Servicio</h3>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-zinc-300 mb-2">Nombre (servicios separados por coma)</label>
                                <input type="text" x-model="currentName" name="name"
                                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                                    placeholder="Ej: Spa, Lavandería, Eventos" required>
                            </div>


                            <div class="mb-6">
                                <label class="block text-sm font-medium text-zinc-300 mb-2">Descripción</label>
                                <textarea x-model="currentDescription" name="description" rows="4"
                                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                                    required></textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-zinc-300 mb-2">Precio (S/)</label>
                                <input type="number" x-model="currentPrice" name="price" step="0.01" min="0"
                                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
                                    required>
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
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar servicio?',
            text: "¡Esta acción no se puede deshacer!",
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

    function serviceTable() {
        return {
            isOpen: false,
            currentId: null,
            currentName: '',
            currentDescription: '',
            currentPrice: '',

            openModal(id, name, description, price) {
                this.currentId = id;
                this.currentName = Array.isArray(name) ? name.join(', ') : name;
                this.currentDescription = description;
                this.currentPrice = price;
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
