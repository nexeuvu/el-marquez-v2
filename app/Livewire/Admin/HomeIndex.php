<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    <div class="w-full bg-zinc-900 rounded-xl shadow-2xl overflow-hidden p-6 border border-zinc-800">
        <!-- Encabezado -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                Panel de Estadísticas del Hotel
            </h1>
            <div class="text-sm text-zinc-400 bg-zinc-800 px-3 py-1 rounded-lg">
                Actualizado: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- Grid de Estadísticas - Diseño Mejorado -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Habitación más reservada -->
            <div
                class="bg-zinc-800 border border-zinc-700 rounded-xl p-4 shadow-lg hover:bg-zinc-750 transition-all duration-300 flex flex-col h-full">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h2 class="text-md font-semibold text-zinc-300">Habitación Más Reservada</h2>
                        <p class="text-xs text-zinc-500">Este mes</p>
                    </div>
                    <div class="p-2 bg-green-900/50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                    </div>
                </div>

                <div class="mt-auto">
                    @if ($mostBookedRoom)
                        <p class="text-xl font-bold text-green-500 truncate mb-1"
                            title="{{ $mostBookedRoom['number'] }}">Habitación {{ $mostBookedRoom['number'] }}</p>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-zinc-400 truncate">Tipo: {{ $mostBookedRoom['room_type'] }}</span>
                            <span
                                class="font-medium text-white bg-green-900/30 px-2 py-1 rounded whitespace-nowrap">{{ $mostBookedRoom['bookings_count'] }}
                                reservas</span>
                        </div>
                    @else
                        <p class="text-zinc-400 text-sm py-2">No hay datos</p>
                    @endif
                </div>
            </div>

            <!-- Servicio más solicitado -->
            <div
                class="bg-zinc-800 border border-zinc-700 rounded-xl p-4 shadow-lg hover:bg-zinc-750 transition-all duration-300 flex flex-col h-full">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h2 class="text-md font-semibold text-zinc-300">Servicio Más Solicitado</h2>
                        <p class="text-xs text-zinc-500">Este mes</p>
                    </div>
                    <div class="p-2 bg-blue-900/50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                        </svg>
                    </div>
                </div>

                <div class="mt-auto">
                    @if ($mostRequestedService)
                        <p class="text-xl font-bold text-blue-500 truncate mb-1" title="{{ $mostRequestedService['name'] }}">
                            {{ $mostRequestedService['name'] }}</p>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-zinc-400 truncate">Precio: S/ {{ number_format($mostRequestedService['price'], 2) }}</span>
                            <span
                                class="font-medium text-white bg-blue-900/30 px-2 py-1 rounded whitespace-nowrap">{{ $mostRequestedService['requests_count'] }}
                                solicitudes</span>
                        </div>
                    @else
                        <p class="text-zinc-400 text-sm py-2">No hay datos</p>
                    @endif
                </div>
            </div>

            <!-- Total de huéspedes -->
            <div
                class="bg-zinc-800 border border-zinc-700 rounded-xl p-4 shadow-lg hover:bg-zinc-750 transition-all duration-300 flex flex-col h-full">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h2 class="text-md font-semibold text-zinc-300">Total de Huéspedes</h2>
                        <p class="text-xs text-zinc-500">Registrados</p>
                    </div>
                    <div class="p-2 bg-purple-900/50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-purple-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                </div>

                <div class="mt-auto">
                    <p class="text-xl font-bold text-purple-500 mb-1">{{ $totalGuests }}</p>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-zinc-400">Huéspedes activos</span>
                        <span class="font-medium text-white bg-purple-900/30 px-2 py-1 rounded whitespace-nowrap">
                            +{{ $newGuestsThisMonth }} este mes
                            @if ($guestGrowthPercentage >= 0)
                                (<span class="text-green-400">↑{{ $guestGrowthPercentage }}%</span>)
                            @else
                                (<span class="text-red-400">↓{{ abs($guestGrowthPercentage) }}%</span>)
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ingresos totales -->
            <div
                class="bg-zinc-800 border border-zinc-700 rounded-xl p-4 shadow-lg hover:bg-zinc-750 transition-all duration-300 flex flex-col h-full">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h2 class="text-md font-semibold text-zinc-300">Ingresos Totales</h2>
                        <p class="text-xs text-zinc-500">Este mes</p>
                    </div>
                    <div class="p-2 bg-yellow-900/50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-yellow-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <div class="mt-auto">
                    <p class="text-xl font-bold text-yellow-500 mb-1">S/ {{ $formattedTotalIncome }}</p>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-zinc-400">Ingresos</span>
                        <span class="font-medium text-white bg-yellow-900/30 px-2 py-1 rounded whitespace-nowrap">
                            @if ($incomeGrowthPercentage >= 0)
                                <span class="text-green-400">↑{{ abs($incomeGrowthPercentage) }}%</span>
                            @else
                                <span class="text-red-400">↓{{ abs($incomeGrowthPercentage) }}%</span>
                            @endif
                            vs mes anterior
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección inferior - Gráfico y últimas reservas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Gráfico de ocupación -->
            <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-4 shadow-lg lg:col-span-2">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                    <h2 class="text-md font-semibold text-zinc-300 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        Ocupación Mensual
                    </h2>
                    <div class="flex gap-2">
                        <button wire:click="setChartType('bar')" class="text-xs px-2 py-1 rounded {{ $chartType === 'bar' ? 'bg-blue-900/50 text-blue-400' : 'bg-zinc-700 text-zinc-400' }}">
                            Barras
                        </button>
                        <button wire:click="setChartType('line')" class="text-xs px-2 py-1 rounded {{ $chartType === 'line' ? 'bg-blue-900/50 text-blue-400' : 'bg-zinc-700 text-zinc-400' }}">
                            Líneas
                        </button>
                    </div>
                </div>
                <div
                    class="bg-zinc-900/50 rounded-lg h-64 flex items-center justify-center text-zinc-500 border border-zinc-700">
                    <canvas id="occupancyChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Últimas reservas -->
            <div class="bg-zinc-800 border border-zinc-700 rounded-xl p-4 shadow-lg">
                <h2 class="text-md font-semibold text-zinc-300 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 text-green-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Últimas Reservas
                </h2>
                <div class="space-y-3">
                    @forelse($latestBookings as $booking)
                        <div
                            class="flex justify-between items-center py-2 px-2 bg-zinc-900/20 rounded-lg hover:bg-zinc-900/40 transition-colors">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="p-1.5 bg-blue-900/20 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $booking['guest_name'] }}
                                    </p>
                                    <p class="text-xs text-zinc-400 truncate">Hab. {{ $booking['room_number'] }} • {{ $booking['time_ago'] }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-green-500 whitespace-nowrap ml-2">S/
                                {{ number_format($booking['total'], 2) }}</span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-zinc-400 text-sm">
                            No hay reservas recientes
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('admin.booking.index') }}"
                    class="mt-3 inline-flex items-center text-xs text-blue-500 hover:text-blue-400">
                    Ver todas las reservas
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-3 h-3 ml-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

@script
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos iniciales del gráfico de ocupación
        const initialOccupancyData = js($lastThreeMonthsOccupancy);

        function renderOccupancyChart(data, chartType = 'bar') {
            console.log('Occupancy Data for Chart:', data);

            const labels = data.map(item => item.month);
            const occupancyRates = data.map(item => item.occupancy_rate);

            const ctx = document.getElementById('occupancyChart').getContext('2d');

            // Destruir instancia existente si hay una
            if (window.occupancyChartInstance) {
                window.occupancyChartInstance.destroy();
            }

            const backgroundColors = [
                'rgba(99, 102, 241, 0.6)',  // indigo
                'rgba(139, 92, 246, 0.6)',  // purple
                'rgba(217, 70, 239, 0.6)',  // pink
                'rgba(236, 72, 153, 0.6)',  // rose
            ];

            window.occupancyChartInstance = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Tasa de Ocupación (%)',
                        data: occupancyRates,
                        backgroundColor: chartType === 'bar' ? backgroundColors : 'rgba(99, 102, 241, 0.2)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: chartType === 'line' ? 2 : 1,
                        tension: chartType === 'line' ? 0.4 : 0,
                        fill: chartType === 'line' ? true : false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Porcentaje de Ocupación',
                                color: '#a1a1aa'
                            },
                            ticks: {
                                color: '#a1a1aa',
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                color: '#3f3f46'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Mes',
                                color: '#a1a1aa'
                            },
                            ticks: {
                                color: '#a1a1aa'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#a1a1aa'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Ocupación: ' + context.parsed.y + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Renderizar gráfico inicial
        document.addEventListener('livewire:initialized', () => {
            renderOccupancyChart(initialOccupancyData, js($chartType));
        });

        // Escuchar cambios en el tipo de gráfico
        Livewire.on('chartTypeUpdated', (chartType) => {
            renderOccupancyChart(initialOccupancyData, chartType);
        });

        // Escuchar actualizaciones de datos
        Livewire.on('occupancyDataUpdated', (data) => {
            renderOccupancyChart(data.lastThreeMonthsOccupancy, js($chartType));
        });
    </script>
@endscript