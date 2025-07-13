<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    {{-- Alertas --}}
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
            <h1 class="text-2xl font-bold text-white">Lista de Detalles de Servicio</h1>
            <div class="space-x-2">
                <a href="{{ route('admin.service_detail.export-pdf') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Exportar PDF
                </a>
                <a href="{{ route('admin.service_detail.export-excel') }}"
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
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Servicio</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Empleado</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Huésped / Habitación</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Fecha Consumo</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Cantidad</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-zinc-300 uppercase">Total (S/)</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-zinc-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @foreach ($serviceDetails as $detail)
                        <tr>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $detail->service->name ?? 'N/D' }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $detail->employee->name ?? 'N/D' }} {{ $detail->employee->last_name ?? '' }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">
                                {{ $detail->booking->guest->name ?? 'N/D' }} - Hab. {{ $detail->booking->room->number ?? 'N/D' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ \Carbon\Carbon::parse($detail->consumption_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">{{ $detail->quantity }}</td>
                            <td class="px-4 py-4 text-sm text-zinc-300">S/. {{ number_format($detail->total, 2) }}</td>
                            <td class="px-4 py-4 text-sm text-right">
                                {{-- Editar --}}
                                <a href="{{ route('admin.service_detail.update', $detail->id) }}"
                                   class="text-blue-500 hover:text-blue-400 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                {{-- Eliminar --}}
                                <button onclick="confirmDelete({{ $detail->id }})" class="text-red-500 hover:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $detail->id }}" action="{{ route('admin.service_detail.destroy', $detail->id) }}" method="POST" class="hidden">
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
        @if ($serviceDetails->hasPages())
            <div class="mt-6">
                {{ $serviceDetails->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Eliminar detalle?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            background: '#18181b',
            color: '#f4f4f5',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
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
</script>
