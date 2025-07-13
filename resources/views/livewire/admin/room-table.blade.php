<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="roomTable()">
    {{-- Notificaciones --}}
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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Lista de Habitaciones</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.room.export-pdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Exportar PDF</a>
                <a href="{{ route('admin.room.export-excel') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Exportar Excel</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Número</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Tipo</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Precio</th>
                        <th class="px-4 py-3 text-left text-sm text-zinc-300 uppercase">Estado</th>
                        <th class="px-4 py-3 text-right text-sm text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($rooms as $room)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $room->number }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $room->room_type }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/ {{ number_format($room->price, 2) }}</td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $room->status ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                    {{ $room->status ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-right">
                                {{-- Editar --}}
                                <button
                                    @click="openModal({{ $room->id }}, '{{ $room->number }}', '{{ $room->room_type }}', '{{ $room->price }}')"
                                    class="text-blue-500 hover:text-blue-400 mr-3">
                                    ✏️
                                </button>

                                {{-- Eliminar --}}
                                <button onclick="confirmDelete({{ $room->id }})"
                                    class="text-red-500 hover:text-red-400">🗑️</button>

                                <form id="delete-form-{{ $room->id }}"
                                    action="{{ route('admin.room.destroy', $room->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if ($rooms->hasPages())
            <div class="mt-6">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>

    {{-- Modal de edición --}}
    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 bg-black bg-opacity-75" @click="closeModal"></div>

                <div class="bg-zinc-900 border border-zinc-700 rounded-lg text-left overflow-hidden shadow-xl w-full max-w-xl z-50">
                    <form :action="'/admin/room/' + currentId" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="px-6 py-6">
                            <h3 class="text-xl font-semibold text-white mb-6">Editar Habitación</h3>

                            <div class="mb-4">
                                <label class="block text-sm text-zinc-300 mb-1">Número</label>
                                <input type="text" x-model="currentNumber" name="number"
                                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm text-zinc-300 mb-1">Tipo</label>
                                <input type="text" x-model="currentRoomType" name="room_type"
                                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm text-zinc-300 mb-1">Precio (S/)</label>
                                <input type="number" step="0.01" x-model="currentPrice" name="price"
                                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg text-white" required>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-zinc-800 flex justify-end">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 text-zinc-300 hover:text-white mr-4">Cancelar</button>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

{{-- Scripts --}}
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar habitación?',
            text: "Esta acción no se puede revertir.",
            icon: 'warning',
            background: '#18181b',
            color: '#f4f4f5',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function roomTable() {
        return {
            isOpen: false,
            currentId: null,
            currentNumber: '',
            currentRoomType: '',
            currentPrice: '',

            openModal(id, number, roomType, price) {
                this.currentId = id;
                this.currentNumber = number;
                this.currentRoomType = roomType;
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
