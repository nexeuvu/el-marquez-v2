<!-- Total de Huéspedes -->
<div class="bg-zinc-800 ...">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h2 class="text-md font-semibold text-zinc-300">Total de Huéspedes</h2>
            <p class="text-xs text-zinc-500">Registrados</p>
        </div>
        <div class="p-2 bg-blue-900/50 rounded-lg">
            <svg ... class="w-5 h-5 text-blue-500">...</svg>
        </div>
    </div>
    <div class="mt-auto">
        <p class="text-xl font-bold text-blue-500 mb-1">{{ $totalGuests }}</p>
        <div class="flex justify-between items-center text-xs">
            <span class="text-zinc-400">+{{ $newGuestsThisMonth }} este mes</span>
            <span class="text-white bg-blue-900/30 px-2 py-1 rounded">
                @if ($guestGrowthPercentage >= 0)
                    <span class="text-green-400">↑{{ $guestGrowthPercentage }}%</span>
                @else
                    <span class="text-red-400">↓{{ abs($guestGrowthPercentage) }}%</span>
                @endif
            </span>
        </div>
    </div>
</div>

<!-- Total de Servicios del Mes -->
<div class="bg-zinc-800 ...">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h2 class="text-md font-semibold text-zinc-300">Servicios Registrados</h2>
            <p class="text-xs text-zinc-500">Este mes</p>
        </div>
        <div class="p-2 bg-green-900/50 rounded-lg">
            <svg ... class="w-5 h-5 text-green-500">...</svg>
        </div>
    </div>
    <div class="mt-auto">
        <p class="text-xl font-bold text-green-500 mb-1">{{ $totalServices }}</p>
        <div class="text-xs text-zinc-400">Servicios de habitaciones, SPA, etc.</div>
    </div>
</div>

<!-- Empleados activos -->
<div class="bg-zinc-800 ...">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h2 class="text-md font-semibold text-zinc-300">Empleados Activos</h2>
            <p class="text-xs text-zinc-500">En servicio</p>
        </div>
        <div class="p-2 bg-yellow-900/50 rounded-lg">
            <svg ... class="w-5 h-5 text-yellow-500">...</svg>
        </div>
    </div>
    <div class="mt-auto">
        <p class="text-xl font-bold text-yellow-500 mb-1">{{ $activeEmployees }}</p>
    </div>
</div>

<!-- Pagos Totales del Mes -->
<div class="bg-zinc-800 ...">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h2 class="text-md font-semibold text-zinc-300">Pagos del Mes</h2>
            <p class="text-xs text-zinc-500">Total recibido</p>
        </div>
        <div class="p-2 bg-purple-900/50 rounded-lg">
            <svg ... class="w-5 h-5 text-purple-500">...</svg>
        </div>
    </div>
    <div class="mt-auto">
        <p class="text-xl font-bold text-purple-500 mb-1">S/ {{ $formattedTotalPayments }}</p>
        <div class="text-xs text-white bg-purple-900/30 px-2 py-1 rounded">
            @if ($paymentGrowthPercentage >= 0)
                ↑{{ $paymentGrowthPercentage }}%
            @else
                ↓{{ abs($paymentGrowthPercentage) }}%
            @endif
            vs mes anterior
        </div>
    </div>
</div>
