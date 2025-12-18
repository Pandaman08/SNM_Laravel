@extends('layout.admin.plantilla')

@section('title', 'Gestión de Matrículas')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-7xl mx-auto">
        {{-- Header Principal --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="ri-file-list-3-line text-4xl text-white"></i>
                        </div>
                        <div class="text-white">
                            <h1 class="text-3xl font-bold">Gestión de Matrículas</h1>
                            <p class="text-indigo-100 mt-1">Administra todas las matrículas del sistema</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2">
                            <span class="text-sm text-indigo-100">Total de matrículas</span>
                            <p class="text-2xl font-bold text-white">{{ $matriculas->count() }}</p>
                        </div>
                        <a href="{{ route('matriculas.create') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-600 
                                   rounded-xl font-semibold shadow-lg hover:shadow-xl
                                   transition-all duration-200 transform hover:scale-105">
                            <i class="ri-add-line text-xl"></i>
                            <span>Nueva Matrícula</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estadísticas Rápidas --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Activas</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $matriculas->filter(function ($m) {return $m->estado == 'activo';})->count() }}
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-check-line text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $matriculas->filter(function ($m) {return $m->estado == 'pendiente';})->count() }}
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-amber-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-time-line text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Este Mes</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $matriculas->filter(function ($m) {
                                return \Carbon\Carbon::parse($m->fecha)->isCurrentMonth();
                            })->count() }}
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-graduation-cap-line text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Hoy</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $matriculas->filter(function ($m) {
                                return \Carbon\Carbon::parse($m->fecha)->isToday();
                            })->count() }}
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ri-calendar-line text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mensajes SweetAlert --}}
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            </script>
        @elseif (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    html: "{!! session('error') !!}",
                    showConfirmButton: true,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#ef4444'
                });
            </script>
        @endif

        {{-- Panel de Filtros --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="ri-filter-3-line text-xl text-indigo-600"></i>
                <h3 class="text-lg font-semibold text-gray-900">Filtros</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="filtro_estado" class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                    <select id="filtro_estado"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               transition-all duration-200">
                        <option value="">Todos</option>
                        <option value="activo">Activos</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="rechazado">Rechazados</option>
                        <option value="finalizado">Finalizados</option>
                    </select>
                </div>
                <div>
                    <label for="filtro_nivel" class="block text-sm font-semibold text-gray-700 mb-2">Nivel</label>
                    <select id="filtro_nivel"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                               transition-all duration-200">
                        <option value="">Todos</option>
                        <option value="inicial">Inicial</option>
                        <option value="primaria">Primaria</option>
                        <option value="secundaria">Secundaria</option>
                    </select>
                </div>
                <div>
                    <label for="filtro_buscar" class="block text-sm font-semibold text-gray-700 mb-2">Buscar</label>
                    <div class="relative">
                        <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="filtro_buscar" placeholder="DNI, nombre o apellido..."
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                   transition-all duration-200">
                    </div>
                </div>
                <div class="flex items-end">
                    <button onclick="limpiarFiltros()"
                        class="w-full px-4 py-3 border-2 border-gray-200 text-gray-700 rounded-xl font-semibold
                               hover:bg-gray-50 hover:border-gray-300 transition-all duration-200
                               flex items-center justify-center gap-2">
                        <i class="ri-refresh-line text-lg"></i>
                        <span>Limpiar</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabla de Matrículas --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="ri-list-check text-indigo-600"></i>
                    Lista de Matrículas
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tabla-matriculas">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="ri-user-line mr-1"></i> Estudiante
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="ri-barcode-line mr-1"></i> Código
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="ri-graduation-cap-line mr-1"></i> Nivel/Grado/Sección
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="ri-price-tag-3-line mr-1"></i> Tipo Matrícula
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="ri-checkbox-circle-line mr-1"></i> Estado
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="ri-calendar-line mr-1"></i> Fecha
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="ri-settings-3-line mr-1"></i> Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($matriculas as $matricula)
                            <tr class="hover:bg-indigo-50 transition-colors duration-150 matricula-row" 
                                data-estado="{{ $matricula->estado }}"
                                data-nivel="{{ strtolower($matricula->seccion->grado->nivelEducativo->nombre ?? 'NA') }}"
                                data-buscar="{{ strtolower($matricula->estudiante->persona->dni . ' ' . $matricula->estudiante->persona->name . ' ' . $matricula->estudiante->persona->lastName) }}">

                                {{-- Estudiante --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 
                                                    flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            {{ substr($matricula->estudiante->persona->name, 0, 1) }}{{ substr($matricula->estudiante->persona->lastName, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $matricula->estudiante->persona->name }}
                                                {{ $matricula->estudiante->persona->lastname }}
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="ri-id-card-line"></i>
                                                DNI: {{ $matricula->estudiante->persona->dni }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Código --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-lg 
                                                     bg-indigo-100 text-indigo-700 shadow-sm">
                                            <i class="ri-hashtag text-xs mr-1"></i>
                                            {{ $matricula->codigo_matricula ?? 'Sin asignar' }}
                                        </span>
                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="ri-user-star-line text-xs"></i>
                                            Est: {{ $matricula->estudiante->codigo_estudiante ?? 'Pendiente' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Nivel/Grado/Sección --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="text-sm font-bold text-gray-900 flex items-center gap-1.5">
                                            <i class="ri-building-line text-indigo-500"></i>
                                            {{ $matricula->seccion->grado->nivelEducativo->nombre ?? 'NA' }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg 
                                                     bg-gray-100 text-gray-700">
                                            {{ $matricula->seccion->grado->grado ?? 'NA' }}° • Sección {{ $matricula->seccion->seccion ?? 'NA' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Tipo Matrícula --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold
                                                 bg-purple-100 text-purple-700">
                                        <i class="ri-price-tag-3-line mr-1.5"></i>
                                        {{ $matricula->tipoMatricula->nombre }}
                                    </span>
                                </td>

                                {{-- Estado --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $estadoConfig = [
                                            'activo' => ['color' => 'green', 'icon' => 'ri-checkbox-circle-fill'],
                                            'finalizado' => ['color' => 'blue', 'icon' => 'ri-flag-fill'],
                                            'rechazado' => ['color' => 'red', 'icon' => 'ri-close-circle-fill'],
                                            'pendiente' => ['color' => 'orange', 'icon' => 'ri-time-fill']
                                        ];
                                        $config = $estadoConfig[$matricula->estado] ?? $estadoConfig['pendiente'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                                                 bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-700 capitalize">
                                        <i class="{{ $config['icon'] }} mr-1.5"></i>
                                        {{ $matricula->estado }}
                                    </span>
                                </td>

                                {{-- Fecha --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                            <i class="ri-calendar-2-line text-indigo-500"></i>
                                            {{ \Carbon\Carbon::parse($matricula->fecha)->format('d/m/Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500 flex items-center gap-1 ml-5">
                                            <i class="ri-time-line"></i>
                                            {{ \Carbon\Carbon::parse($matricula->fecha)->format('h:i A') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('matriculas.show', $matricula->codigo_matricula) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Ver detalles">
                                            <i class="ri-eye-line text-lg"></i>
                                        </a>

                                        <a href="{{ route('matriculas.editar', $matricula->codigo_matricula) }}"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Editar">
                                            <i class="ri-edit-line text-lg"></i>
                                        </a>

                                        @if ($matricula->estado == 'pendiente')
                                            <form id="aprobar-form-{{ $matricula->codigo_matricula }}"
                                                action="{{ route('matriculas.aprobar', $matricula->codigo_matricula) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button"
                                                    onclick="confirmarAprobacion('{{ $matricula->codigo_matricula }}')"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Aprobar">
                                                    <i class="ri-check-line text-lg"></i>
                                                </button>
                                            </form>

                                            <button onclick="mostrarModalRechazo('{{ $matricula->codigo_matricula }}')"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Rechazar">
                                                <i class="ri-close-line text-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ri-file-list-line text-4xl text-gray-400"></i>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-900 mb-2">No hay matrículas registradas</p>
                                        <p class="text-gray-500 mb-6">Comienza creando una nueva matrícula</p>
                                        <a href="{{ route('matriculas.create') }}"
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 
                                                   text-white rounded-xl font-semibold shadow-lg transition-all duration-200
                                                   transform hover:scale-105">
                                            <i class="ri-add-line text-xl"></i>
                                            Nueva Matrícula
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal para rechazar matrícula --}}
<div id="modal-rechazo" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md transform transition-all">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="ri-error-warning-line text-red-500"></i>
                        Rechazar Matrícula
                    </h3>
                    <button onclick="cerrarModalRechazo()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
            </div>
            <form id="form-rechazo" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label for="motivo_rechazo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Motivo del rechazo <span class="text-red-500">*</span>
                    </label>
                    <textarea name="motivo_rechazo" id="motivo_rechazo" rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm resize-none
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Explique el motivo del rechazo..." required></textarea>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="cerrarModalRechazo()"
                        class="px-6 py-3 border-2 border-gray-200 text-gray-700 rounded-xl font-semibold
                               hover:bg-gray-50 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold
                               shadow-lg transition-all duration-200 transform hover:scale-105">
                        Rechazar Matrícula
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Filtros de tabla
    document.getElementById('filtro_estado').addEventListener('change', filtrarTabla);
    document.getElementById('filtro_nivel').addEventListener('change', filtrarTabla);
    document.getElementById('filtro_buscar').addEventListener('input', filtrarTabla);

    function filtrarTabla() {
        const filtroEstado = document.getElementById('filtro_estado').value.toLowerCase();
        const filtroNivel = document.getElementById('filtro_nivel').value.toLowerCase();
        const filtroBuscar = document.getElementById('filtro_buscar').value.toLowerCase();
        const filas = document.querySelectorAll('.matricula-row');

        filas.forEach(fila => {
            const estado = fila.dataset.estado;
            const nivel = fila.dataset.nivel;
            const buscar = fila.dataset.buscar;
            let mostrar = true;

            if (filtroEstado && estado !== filtroEstado) mostrar = false;
            if (filtroNivel && nivel !== filtroNivel) mostrar = false;
            if (filtroBuscar && !buscar.includes(filtroBuscar)) mostrar = false;

            fila.style.display = mostrar ? '' : 'none';
        });
    }

    function limpiarFiltros() {
        document.getElementById('filtro_estado').value = '';
        document.getElementById('filtro_nivel').value = '';
        document.getElementById('filtro_buscar').value = '';
        filtrarTabla();
    }

    // Modal de rechazo
    function mostrarModalRechazo(codigoMatricula) {
        const modal = document.getElementById('modal-rechazo');
        const form = document.getElementById('form-rechazo');
        form.action = `/matriculas/${codigoMatricula}/rechazar`;
        modal.classList.remove('hidden');
    }

    function cerrarModalRechazo() {
        const modal = document.getElementById('modal-rechazo');
        const textarea = document.getElementById('motivo_rechazo');
        modal.classList.add('hidden');
        textarea.value = '';
    }

    document.getElementById('modal-rechazo').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalRechazo();
        }
    });

    function confirmarAprobacion(codigoMatricula) {
        Swal.fire({
            title: '¿Está seguro?',
            text: 'Esta acción aprobará la matrícula del estudiante.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="ri-check-line mr-1"></i> Sí, aprobar',
            cancelButtonText: '<i class="ri-close-line mr-1"></i> Cancelar',
            reverseButtons: true,
            customClass: {
                confirmButton: 'px-5 py-2.5 rounded-lg font-semibold shadow-lg',
                cancelButton: 'px-5 py-2.5 rounded-lg font-semibold shadow-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('aprobar-form-' + codigoMatricula).submit();
            }
        });
    }

    function confirmarEliminacion(codigoMatricula) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción eliminará la matrícula y no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="ri-delete-bin-line mr-1"></i> Sí, eliminar',
            cancelButtonText: '<i class="ri-close-line mr-1"></i> Cancelar',
            reverseButtons: true,
            customClass: {
                confirmButton: 'px-5 py-2.5 rounded-lg font-semibold shadow-lg',
                cancelButton: 'px-5 py-2.5 rounded-lg font-semibold shadow-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('eliminar-form-' + codigoMatricula).submit();
            }
        });
    }
</script>
@endsection