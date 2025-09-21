@extends('layout.admin.plantilla')

@section('title', 'Gestión de Matrículas')

@section('contenido')
            <div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 p-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Gestión de Matrículas</h1>
                            <p class="text-gray-600 mt-1">Administra todas las matrículas del sistema</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">Total de matrículas:</span>
                            <span class="font-semibold text-blue-600">{{ $matriculas->count() }}</span>
                            <a href="{{ route('matriculas.create') }}"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl flex items-center text-sm font-medium shadow-md transition duration-300 ease-in-out">
                                <i class="ri-add-line mr-2"></i>
                                Nueva Matrícula
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="ri-check-line text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Activas</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $matriculas->filter(function ($m) {return $m->estado == 'activo';})->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="ri-time-line text-orange-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pendientes</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $matriculas->filter(function ($m) {return $m->estado == 'pendiente';})->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="ri-graduation-cap-line text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Este Mes</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $matriculas->filter(function ($m) {
                                        return \Carbon\Carbon::parse($m->fecha)->isCurrentMonth();
                                    })->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="ri-calendar-line text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Hoy</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $matriculas->filter(function ($m) {
                                        return \Carbon\Carbon::parse($m->fecha)->isToday();
                                    })->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensajes -->
            @if (session('success'))
                <script>
                    Swal.fire({
                        title: "Exito!",
                        text: "{{ session('success') }}",
                        icon: "success",
                        customClass: {
                            confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                        }
                    });
                </script>
            @elseif (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: '¡Hubo un error!',
                        html: "{!! session('error') !!}",
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            confirmButton: 'bg-red-500 text-white hover:bg-red-600 focus:ring-2 focus:ring-red-300 rounded-lg py-2 px-4'
                        }
                    });
                </script>
            @endif

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="filtro_estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select id="filtro_estado"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                <option value="activo">Activos</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="rechazado">Rechazados</option>
                                <option value="finalizado">Finalizados</option>

                            </select>
                        </div>
                        <div>
                            <label for="filtro_nivel" class="block text-sm font-medium text-gray-700 mb-1">Nivel</label>
                            <select id="filtro_nivel"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                <option value="inicial">Inicial</option>
                                <option value="primaria">Primaria</option>
                                <option value="secundaria">Secundaria</option>
                            </select>
                        </div>
                        <div>
                            <label for="filtro_buscar" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                            <input type="text" id="filtro_buscar" placeholder="DNI, nombre o apellido..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button onclick="limpiarFiltros()"
                                class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                <i class="ri-refresh-line mr-2"></i>
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- Tabla de Matrículas -->
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Lista de Matrículas</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="tabla-matriculas">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estudiante
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nivel/Grado/Sección
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo Matrícula
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($matriculas as $matricula)
                                <tr class="hover:bg-gray-50 matricula-row" data-estado="{{ $matricula->estado }}"
                                    data-nivel="{{ strtolower($matricula->seccion->grado->nivelEducativo->nombre  ?? 'NA')  }}"
                                    data-buscar="{{ strtolower($matricula->estudiante->persona->dni . ' ' . $matricula->estudiante->persona->name . ' ' . $matricula->estudiante->persona->lastName) }}">

                                    <!-- Estudiante -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                   class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-blue-500 text-white flex items-center justify-center font-semibold shadow">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ substr($matricula->estudiante->persona->name, 0, 1) }}{{ substr($matricula->estudiante->persona->lastName, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $matricula->estudiante->persona->name }}
                                                    {{ $matricula->estudiante->persona->lastName }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    DNI: {{ $matricula->estudiante->persona->dni }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Código -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700 shadow-sm">
                                                <i class="ri-hashtag text-xs mr-1"></i>
                                                 {{ $matricula->codigo_matricula ?? 'Sin asignar' }}
                                                 </span>
                                                 <span class="mt-1 text-[11px] text-gray-500 tracking-wide">
                                                    <i class="ri-id-card-line mr-1 text-gray-400"></i>
                                                    Est: {{ $matricula->estudiante->codigo_estudiante ?? 'Pendiente' }}
                                                </span>
                                            </div>
                                    </td>

                                    <!-- Nivel/Grado/Sección -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-900 flex items-center">
                                                  <i class="ri-graduation-cap-line mr-2 text-blue-500"></i>
                                                  {{ $matricula->seccion->grado->nivelEducativo->nombre ?? 'NA' }}
                                                </span>
                                                 <span class="mt-1 inline-flex items-center px-2 py-0.5 text-xs rounded-lg bg-gray-100 text-gray-700">
                                                      {{ $matricula->seccion->grado->grado ?? 'NA' }}° • Sección {{ $matricula->seccion->seccion ?? 'NA' }}
                                                    </span>
                                                </div>
                                    </td>

                                    <!-- Tipo Matrícula -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="px-2 py-1 bg-purple-100 text-blue-700 text-xs font-medium rounded-lg">
                                            {{ $matricula->tipoMatricula->nombre }}
                                        </div>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $color = null;

                                            if ($matricula->estado == 'activo') {
                                                $color = 'green';
                                            } elseif ($matricula->estado == 'finalizado') {
                                                $color = 'blue';
                                            } elseif ($matricula->estado == 'rechazado') {
                                                $color = 'red';
                                            } else {
                                                $color = 'orange';
                                            }

                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{$color}}-100 text-{{$color}}-800 capitalize">
                                            <span class="w-1.5 h-1.5 bg-{{$color}}-400 rounded-full mr-1.5"></span>
                                            {{ $matricula->estado }}
                                        </span>

                                    </td>

                                    <!-- Fecha -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex flex-col">
                                            <span class="flex items-center font-medium text-gray-800">
                                                 <i class="ri-calendar-line text-blue-500 mr-2"></i>
                                                 {{ \Carbon\Carbon::parse($matricula->fecha)->format('d/m/Y') }}
                                                </span>
                                                <span class="text-xs text-gray-500 ml-6">
                                                    {{ \Carbon\Carbon::parse($matricula->fecha)->format('h:i A') }}
                                                </span>
                                            </div>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <!-- Ver -->
                                            <a href="{{ route('matriculas.show', $matricula->codigo_matricula) }}"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                <i class="ri-eye-line"></i>
                                            </a>

                                            <!-- Editar -->
                                          <a href="{{ route('matriculas.editar', $matricula->codigo_matricula) }}"
                                           class="text-indigo-600 hover:text-indigo-900 p-1 rounded">
                                           <i class="ri-edit-line"></i>
                                        </a>
                                            @if ($matricula->estado== 'pendiente')
                                                <!-- Aprobar -->
                                                <form id="aprobar-form-{{ $matricula->codigo_matricula }}"
                                                    action="{{ route('matriculas.aprobar', $matricula->codigo_matricula) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button"
                                                        class="text-green-600 hover:text-green-900 p-1 rounded"
                                                        onclick="confirmarAprobacion('{{ $matricula->codigo_matricula }}')">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                </form>
                                              
                                                    <!-- Rechazar -->
                                                    <button
                                                        onclick="mostrarModalRechazo('{{ $matricula->codigo_matricula }}')"
                                                        class="text-red-600 hover:text-red-900 p-1 rounded">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-12">
                                            <i class="ri-file-list-line text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-lg font-medium text-gray-900 mb-1">No hay matrículas registradas
                                            </p>
                                            <p class="text-gray-500">Comienza creando una nueva matrícula</p>
                                            <a href="{{ route('matriculas.create') }}"
                                                class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                                <i class="ri-add-line mr-2"></i>
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

    <!-- Modal para rechazar matrícula -->
    <div id="modal-rechazo" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rechazar Matrícula</h3>
                <form id="form-rechazo" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="motivo_rechazo" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo del rechazo *
                        </label>
                        <textarea name="motivo_rechazo" id="motivo_rechazo" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="Explique el motivo del rechazo..." required></textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="cerrarModalRechazo()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
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

        // Cerrar modal al hacer click fuera
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
                confirmButtonText: 'Sí, aprobar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 px-4 py-2 rounded',
                    cancelButton: 'bg-gray-300 text-gray-800 hover:bg-gray-400 px-4 py-2 rounded ml-2'
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
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded',
                    cancelButton: 'bg-gray-300 text-gray-800 hover:bg-gray-400 px-4 py-2 rounded ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('eliminar-form-' + codigoMatricula).submit();
                }
            });
        }
    </script>
@endsection
