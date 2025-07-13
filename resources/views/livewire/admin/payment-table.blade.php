<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<div class="w-full py-8 px-4 sm:px-6 lg:px-8 space-y-6">
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

    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden border border-zinc-800">
        <!-- Encabezado -->
        <div class="p-6 border-b border-zinc-800 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Historial de Pagos</h1>
                <p class="text-zinc-400 mt-1">Listado completo de transacciones registradas</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('admin.payment.export-pdf') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Exportar PDF
                </a>
                <a href="{{ route('admin.payment.export-excel') }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Exportar Excel
                </a>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="w-full text-white">
                <thead class="bg-zinc-800">
                    <tr class="border-b border-zinc-700">
                        <th class="p-4 text-left text-sm font-medium text-zinc-300">ID</th>
                        <th class="p-4 text-left text-sm font-medium text-zinc-300">Huésped</th>
                        <th class="p-4 text-left text-sm font-medium text-zinc-300">Fecha</th>
                        <th class="p-4 text-left text-sm font-medium text-zinc-300">Método</th>
                        <th class="p-4 text-left text-sm font-medium text-zinc-300">Total</th>
                        <th class="p-4 text-left text-sm font-medium text-zinc-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-zinc-800/50 transition-colors">
                            <td class="p-4 font-medium text-blue-400">#{{ $payment->id }}</td>
                            <td class="p-4">
                                <div>
                                    <p class="font-medium text-white">{{ $payment->guest->name ?? '—' }} {{ $payment->guest->last_name ?? '' }}</p>
                                    <p class="text-sm text-zinc-400">{{ $payment->guest->dni ?? '' }}</p>
                                </div>
                            </td>
                            <td class="p-4 text-zinc-300">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                            <td class="p-4 text-zinc-400">{{ $payment->payment_method }}</td>
                            <td class="p-4 font-bold text-white">S/ {{ number_format($payment->total_amount, 2) }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <button type="button" class="toggle-details text-blue-400 hover:text-blue-300 p-2 rounded-full hover:bg-blue-900/30"
                                        data-payment-id="{{ $payment->id }}" title="Ver detalles">
                                        <i class="fas fa-chevron-down text-sm"></i>
                                    </button>
                                    <a href="{{ route('admin.payment.update', $payment->id) }}" class="text-yellow-400 hover:text-yellow-300 p-2 rounded-full hover:bg-yellow-900/30"
                                        title="Editar">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.payment.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este pago?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 p-2 rounded-full hover:bg-red-900/30" title="Eliminar">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Detalles -->
                        <tr class="details-row hidden bg-zinc-800/30" id="details-{{ $payment->id }}">
                            <td colspan="6" class="p-0">
                                <div class="px-6 py-4 space-y-2 text-sm text-zinc-300">
                                    <p><strong>Reserva:</strong> {{ $payment->booking->id ?? '—' }}</p>
                                    <p><strong>Habitación:</strong> {{ $payment->room->number ?? '—' }}</p>
                                    <p><strong>Servicio:</strong> {{ $payment->service->name ?? '—' }}</p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-zinc-500">
                                <i class="fas fa-credit-card text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No hay pagos registrados</p>
                                <p class="text-sm mt-1">Cuando registres un pago, aparecerá en este listado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    @if ($payments->hasPages())
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    @endif
</div>

<script>
    $(document).ready(function () {
        $('.toggle-details').on('click', function () {
            const id = $(this).data('payment-id');
            const row = $(`#details-${id}`);
            const icon = $(this).find('i');
            row.toggleClass('hidden');
            icon.toggleClass('fa-chevron-down fa-chevron-up');
        });
    });
</script>
