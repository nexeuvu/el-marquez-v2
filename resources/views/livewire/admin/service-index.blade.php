<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h1 class="text-2xl font-bold text-white mb-6" data-flux-component="heading">
            Registrar Nuevo Servicio
        </h1>
        <form action="{{ route('admin.service.store') }}" method="POST" class="space-y-6" data-flux-component="form">
            @csrf
            <!-- Campo Tipos de Servicio -->
            <div data-flux-field>
                <label for="name" class="block text-sm font-medium text-zinc-300 mb-1" data-flux-label>
                    Tipos de servicio <span class="text-red-500">*</span>
                </label>
                <select name="name[]" id="service-type" multiple
                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-white placeholder-zinc-500"
                    data-flux-control required>
                    <option value="Bebidas">Bebidas</option>
                    <option value="Comida">Comida</option>
                    <option value="Reservas">Reservas</option>
                    <option value="Spa">Spa</option>
                    <option value="Limpieza">Limpieza</option>
                    <option value="Lavandería">Lavandería</option>
                    <option value="Room Service">Room Service</option>
                    <option value="Transporte">Transporte</option>
                    <option value="Eventos">Eventos</option>
                    <option value="Gimnasio">Gimnasio</option>
                    <option value="Turismo">Turismo</option>
                    <option value="Conserjería">Conserjería</option>
                </select>
                <p class="text-sm text-zinc-500 mt-1">Usa Ctrl/Cmd para seleccionar múltiples servicios.</p>
            </div>

            <!-- Campo Descripción -->
            <div data-flux-field>
                <label for="description" class="block text-sm font-medium text-zinc-300 mb-1" data-flux-label>
                    Descripción <span class="text-red-500">*</span>
                </label>
                <textarea id="description" name="description" rows="5" readonly
                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-white placeholder-zinc-500"
                    placeholder="Describe brevemente el servicio" required data-flux-control></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Precio -->
            <div data-flux-field>
                <label for="price" class="block text-sm font-medium text-zinc-300 mb-1" data-flux-label>
                    Precio total (S/) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="price" name="price" step="0.01" min="0" readonly
                    class="w-full px-4 py-3 bg-zinc-800 border border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-white placeholder-zinc-500"
                    placeholder="Se calculará automáticamente" required data-flux-control>
                @error('price')
                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-800"></div>
                </div>
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
                    Registrar Servicio
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para actualizar descripción y precio -->
<script>
    const servicios = {
        "Bebidas": {
            descripcion: "• Bebidas: Servicio de refrescos, jugos y bebidas alcohólicas.",
            precio: 150.00
        },
        "Comida": {
            descripcion: "• Comida: Platos disponibles en el restaurante o servicio a la habitación.",
            precio: 120.00
        },
        "Reservas": {
            descripcion: "• Reservas: Administración de reservas internas y externas.",
            precio: 10.00
        },
        "Spa": {
            descripcion: "• Spa: Tratamientos de relajación y masajes terapéuticos.",
            precio: 85.00
        },
        "Limpieza": {
            descripcion: "• Limpieza: Limpieza de habitación estándar o bajo pedido.",
            precio: 20.00
        },
        "Lavandería": {
            descripcion: "• Lavandería: Lavado y planchado de ropa personal.",
            precio: 25.00
        },
        "Room Service": {
            descripcion: "• Room Service: Entrega directa de alimentos y bebidas a la habitación.",
            precio: 10.00
        },
        "Transporte": {
            descripcion: "• Transporte: Traslado al aeropuerto o destinos turísticos.",
            precio: 50.00
        },
        "Eventos": {
            descripcion: "• Eventos: Organización de bodas, conferencias o banquetes.",
            precio: 120.00
        },
        "Gimnasio": {
            descripcion: "• Gimnasio: Acceso a equipos de entrenamiento y pesas.",
            precio: 50.00
        },
        "Turismo": {
            descripcion: "• Turismo: Tours guiados a sitios turísticos locales.",
            precio: 100.00
        },
        "Conserjería": {
            descripcion: "• Conserjería: Asistencia personalizada para reservas y dudas.",
            precio: 60.00
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('service-type');
        const descripcion = document.getElementById('description');
        const precio = document.getElementById('price');

        select.addEventListener('change', function () {
            const seleccionados = Array.from(select.selectedOptions).map(opt => opt.value);

            let total = 0;
            let textoDescripcion = '';

            seleccionados.forEach(serv => {
                if (servicios[serv]) {
                    textoDescripcion += servicios[serv].descripcion + '\n';
                    total += servicios[serv].precio;
                }
            });

            descripcion.value = textoDescripcion.trim();
            precio.value = total.toFixed(2);
        });
    });
</script>