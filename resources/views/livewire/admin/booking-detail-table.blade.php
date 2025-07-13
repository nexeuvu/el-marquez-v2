<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="detailTable()">
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
                customClass: { popup: 'rounded-lg shadow-lg' }
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
                customClass: { popup: 'rounded-lg shadow-lg text-left' }
            });
        </script>
    @endif

    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Lista de Detalles de Reserva</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.booking_detail.export-pdf') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Exportar PDF</a>
                <a href="{{ route('admin.booking_detail.export-excel') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Exportar Excel</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-sm text-zinc-300">#</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Reserva</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Habitación</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Noches</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Precio por Noche</th>
                        <th class="px-4 py-3 text-sm text-zinc-300">Total</th>
                        <th class="px-4 py-3 text-sm text-right text-zinc-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($bookingDetails as $booking_detail)
                        <tr>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">
                                {{ $booking_detail->booking->id }} - 
                                {{ $booking_detail->booking->guest->name }} {{ $booking_detail->booking->guest->last_name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-300">
                                Habitación {{ $booking_detail->room->number }}<br>
                                <span class="text-xs text-zinc-400">{{ $booking_detail->room->room_type }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-zinc-300">{{ $booking_detail->number_night }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300">S/. {{ number_format($booking_detail->unit_price, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-zinc-300 font-semibold">S/. {{ number_format($booking_detail->unit_price, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-right space-x-2">
                                {{-- Botón eliminar --}}
                                <button onclick="confirmDelete({{ $booking_detail->id }})" class="text-red-500 hover:text-red-400">
                                    🗑️
                                </button>
                                <form id="delete-form-{{ $booking_detail->id }}" action="{{ route('admin.booking_detail.destroy', $booking_detail->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($bookingDetails->hasPages())
            <div class="mt-6">{{ $bookingDetails->links() }}</div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar detalle de reserva?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            background: '#18181b',
            color: '#f4f4f5',
            iconColor: '#ef4444',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-lg shadow-lg' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function detailTable() {
        return {
            // puedes implementar funciones para modal si agregas edición
        }
    }
</script>
