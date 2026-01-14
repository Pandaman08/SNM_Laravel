@extends('layout.admin.plantilla')

@section('titulo', 'Asignar docente')

@section('contenido')

    <div class="w-full bg-gray-50 min-h-screen p-6">
        <!-- Header -->
        <div class="bg-white border-l-4 border-blue-500 shadow-sm px-6 py-4 mb-6 rounded-r-md">
            <h1 class="text-2xl font-semibold text-gray-800">Gestionar Docentes</h1>
            <p class="text-sm text-gray-600 mt-1">Gestione la asignación de múltiples docentes a las asignaturas</p>
        </div>

        <!-- Notificaciones de Éxito/Error -->
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center fade-in">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center fade-in">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div
                class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6 flex items-center fade-in">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('warning') }}
            </div>
        @endif

        <!-- Formulario de Filtros -->
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Filtros de Búsqueda</h3>
            </div>
            <form method="GET" action="{{ route('asignaturas.asignar.docentes') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Dropdown de Nivel Educativo -->
                    <div>
                        <label for="nivelEducativo" class="block text-sm font-medium text-gray-700 mb-2">
                            Nivel Educativo
                        </label>
                        <select id="nivelEducativo" name="nivelEducativo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900">
                            <option value="">Seleccione un nivel</option>
                            @foreach ($nivelesEducativos as $nivel)
                                <option value="{{ $nivel->id_nivel_educativo }}"
                                    {{ request('nivelEducativo') == $nivel->id_nivel_educativo ? 'selected' : '' }}>
                                    {{ $nivel->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown de Grado -->
                    <div>
                        <label for="grado" class="block text-sm font-medium text-gray-700 mb-2">
                            Grado
                        </label>
                        <select id="grado" name="grado"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 disabled:bg-gray-100 disabled:cursor-not-allowed"
                            {{ !request('nivelEducativo') ? 'disabled' : '' }}>
                            <option value="">Primero seleccione un nivel educativo</option>
                            @if (request('nivelEducativo'))
                                @foreach ($grados->where('nivel_educativo_id', request('nivelEducativo')) as $gradoItem)
                                    <option value="{{ $gradoItem->id_grado }}"
                                        {{ request('grado') == $gradoItem->id_grado ? 'selected' : '' }}>
                                        {{ $gradoItem->grado }}°
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Dropdown de Especialidad -->
                    <div>
                        <label for="especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                            Especialidad
                        </label>
                        <select id="especialidad" name="especialidad"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900">
                            <option value="">Todas las especialidades</option>
                            @foreach ($especialidades as $especialidad)
                                <option value="{{ $especialidad->id_especialidad }}"
                                    {{ request('especialidad') == $especialidad->id_especialidad ? 'selected' : '' }}>
                                    {{ $especialidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" id="buscarpor" name="buscarpor"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar Asignaturas
                    </button>
                    @if (request('nivelEducativo') || request('grado') || request('especialidad'))
                        <a href="{{ route('asignaturas.asignar.docentes') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-gray-600 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Limpiar Filtros
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabla de Asignaturas -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Asignaturas</h3>
                <div class="text-sm text-gray-600">
                    Mostrando {{ $asignaturas->firstItem() ?? 0 }} - {{ $asignaturas->lastItem() ?? 0 }} de
                    {{ $asignaturas->total() }} asignaturas
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                CÓDIGO</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ASIGNATURA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GRADO
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ESPECIALIDADES REQUERIDAS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                DOCENTES ASIGNADOS</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ESTADO</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($asignaturas as $a)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 fade-in">
                                <!-- Columna: Código -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $a->codigo_asignatura }}
                                </td>

                                <!-- Columna: Asignatura -->
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $a->nombre }}
                                </td>

                                <!-- Columna: Grado -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $a->grado->grado ?? 'NA' }}°
                                </td>

                                <!-- Columna: Especialidades Requeridas -->
                                <td class="px-6 py-4 text-sm">
                                    @if ($a->especialidadesPermitidas && $a->especialidadesPermitidas->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($a->especialidadesPermitidas as $especialidad)
                                                @if ($especialidad->pivot->estado == 'Activo')
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $especialidad->nombre }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            Sin restricciones
                                        </span>
                                    @endif
                                </td>

                                <!-- Columna: Docentes Asignados -->
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if ($a->docentes->isEmpty())
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Sin asignar
                                        </span>
                                    @else
                                        <div class="space-y-1">
                                            @foreach ($a->docentes->take(2) as $docente)
                                                <div class="flex items-center justify-between group">
                                                    <div class="flex items-center flex-1">
                                                        <div class="flex-shrink-0 h-6 w-6">
                                                            <div
                                                                class="h-6 w-6 rounded-full bg-blue-500 flex items-center justify-center">
                                                                <span class="text-xs font-medium text-white">
                                                                    {{ substr($docente->user->persona->name ?? 'N', 0, 1) }}{{ substr($docente->user->persona->lastname ?? 'A', 0, 1) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <p class="text-xs font-medium text-gray-900">
                                                                {{ $docente->user->persona->name ?? 'Nombre no disponible' }}
                                                                {{ $docente->user->persona->lastname ?? '' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <button
                                                        onclick="removerDocenteDirecto('{{ $docente->codigo_docente }}', '{{ $a->codigo_asignatura }}', '{{ $docente->user->persona->name ?? 'Docente' }} {{ $docente->user->persona->lastname ?? '' }}')"
                                                        class="ml-2 text-red-400 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-all duration-200"
                                                        title="Remover asignación">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                            @if ($a->docentes->count() > 2)
                                                <button onclick="openManageModal('{{ $a->codigo_asignatura }}', event)"
                                                    data-asignatura="{{ json_encode($a) }}"
                                                    class="text-xs text-blue-600 hover:text-blue-800 ml-8 hover:underline">
                                                    +{{ $a->docentes->count() - 2 }} más (ver todos)
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <!-- Columna: Estado -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($a->docentes->isEmpty())
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Pendiente
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $a->docentes->count() }} Docente{{ $a->docentes->count() > 1 ? 's' : '' }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Columna: Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button onclick="openManageModal('{{ $a->codigo_asignatura }}', event)"
                                        data-asignatura="{{ json_encode($a) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-md hover:bg-indigo-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        Gestionar ({{ $a->docentes->count() }})
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No hay asignaturas registradas</p>
                                        <p class="text-gray-400 text-sm">Los registros aparecerán aquí cuando se agreguen
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($asignaturas->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($asignaturas->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-50 cursor-not-allowed">
                                Anterior
                            </span>
                        @else
                            <a href="{{ $asignaturas->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Anterior
                            </a>
                        @endif

                        @if ($asignaturas->hasMorePages())
                            <a href="{{ $asignaturas->nextPageUrl() }}"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Siguiente
                            </a>
                        @else
                            <span
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-50 cursor-not-allowed">
                                Siguiente
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando
                                <span class="font-medium">{{ $asignaturas->firstItem() }}</span>
                                a
                                <span class="font-medium">{{ $asignaturas->lastItem() }}</span>
                                de
                                <span class="font-medium">{{ $asignaturas->total() }}</span>
                                resultados
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                @if ($asignaturas->onFirstPage())
                                    <span
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                        <span class="sr-only">Anterior</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $asignaturas->previousPageUrl() }}"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Anterior</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif

                                @foreach (range(1, min(5, $asignaturas->lastPage())) as $page)
                                    @if ($page == $asignaturas->currentPage())
                                        <span aria-current="page"
                                            class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $asignaturas->url($page) }}"
                                            class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                @if ($asignaturas->currentPage() < $asignaturas->lastPage() - 2)
                                    <span
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                        ...
                                    </span>
                                    <a href="{{ $asignaturas->url($asignaturas->lastPage()) }}"
                                        class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        {{ $asignaturas->lastPage() }}
                                    </a>
                                @endif

                                @if ($asignaturas->hasMorePages())
                                    <a href="{{ $asignaturas->nextPageUrl() }}"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Siguiente</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                        <span class="sr-only">Siguiente</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para Gestionar Docentes -->
    <div id="manageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-lg bg-white max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Gestionar Docentes</h3>
                    <p id="modalAsignaturaInfo" class="text-sm text-gray-600 mt-1"></p>
                    <p id="modalGradoSeccionInfo" class="text-sm text-gray-600 mt-1"></p>
                    <p id="modalEspecialidadesInfo" class="text-sm text-blue-600 mt-1"></p>
                </div>
                <button onclick="closeManageModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6 flex-1 overflow-auto">
                <!-- Panel Izquierdo: Agregar Docente -->
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-blue-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Agregar Docente Compatible
                        </h4>

                        <form id="addDocenteForm">
                            @csrf
                            <input type="hidden" id="modal_codigo_asignatura" name="codigo_asignatura">
                            <input type="hidden" id="modal_id_grado" name="id_grado">
                            <input type="hidden" id="modal_codigo_docente_seleccionado" name="codigo_docente">

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Sección:</label>
                                <select id="modal_seccion" name="id_seccion" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Seleccione una sección --</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Docente por DNI:</label>
                                <input type="text" id="buscar_dni_docente" placeholder="Ingrese DNI del docente"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    autocomplete="off">

                                <div id="resultados_container" class="mt-2">
                                    <!-- Loader para búsqueda -->
                                    <div id="loader_busqueda" class="hidden text-center py-4">
                                        <div
                                            class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600">
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600">Buscando docentes compatibles...</p>
                                    </div>

                                    <!-- Tabla de resultados de búsqueda -->
                                    <div id="resultados_busqueda_docente"
                                        class="hidden border border-gray-300 rounded-md overflow-hidden bg-white shadow-sm">
                                        <div class="overflow-x-auto max-h-60">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th
                                                            class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                            DNI</th>
                                                        <th
                                                            class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                            Docente</th>
                                                        <th
                                                            class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                            Especialidades</th>
                                                        <th
                                                            class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                                            Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tabla_docentes_encontrados"
                                                    class="bg-white divide-y divide-gray-200">
                                                    <!-- Los resultados se cargarán aquí dinámicamente -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Paginación -->
                                        <div id="paginacion_docentes" class="border-t border-gray-200"></div>
                                    </div>

                                    <!-- Mensaje cuando no hay resultados -->
                                    <div id="sin_resultados_busqueda"
                                        class="hidden text-sm text-gray-500 text-center py-4">
                                        <!-- Contenido dinámico -->
                                    </div>
                                </div>

                                <!-- Docente seleccionado -->
                                <div id="docente_seleccionado_info" class="mt-3 hidden">
                                    <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                        <p class="text-sm font-medium text-green-900">Docente seleccionado:</p>
                                        <p id="info_docente_seleccionado" class="text-sm text-green-700 mt-1"></p>
                                        <div id="info_especialidades_docente" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="btn_agregar_docente" disabled
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 flex items-center justify-center disabled:bg-gray-300 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Agregar Docente
                            </button>
                        </form>
                    </div>

                    <!-- Estadísticas -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Estadísticas</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total docentes asignados:</span>
                                <span id="totalDocentes" class="font-medium text-gray-900">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Docentes compatibles:</span>
                                <span id="docentesCompatibles" class="font-medium text-green-600">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Docentes incompatibles:</span>
                                <span id="docentesIncompatibles" class="font-medium text-red-600">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Última actualización:</span>
                                <span id="ultimaActualizacion" class="font-medium text-gray-900">--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Derecho: Lista de Docentes Asignados -->
                <div class="space-y-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 h-full">
                        <h4 class="text-lg font-medium text-green-900 mb-4 flex items-center justify-between">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Docentes Asignados
                            </span>
                            <span id="contadorDocentes"
                                class="text-sm font-normal bg-green-200 text-green-800 px-2 py-1 rounded-full">0</span>
                        </h4>

                        <div id="listaDocentesAsignados" class="space-y-3 max-h-64 overflow-y-auto pr-2">
                            <!-- Los docentes asignados se mostrarán aquí -->
                        </div>

                        <div id="sinDocentes" class="text-center py-8 text-gray-500 hidden">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <p class="text-gray-600 font-medium">No hay docentes asignados</p>
                            <p class="text-sm text-gray-500 mt-1">Agrega docentes compatibles usando el panel izquierdo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                <button onclick="closeManageModal()"
                    class="px-6 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Eliminar -->
    <div id="confirmarEliminarModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto mb-4 text-red-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                    </path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmar Eliminación</h3>
                <p id="mensajeConfirmacion" class="text-sm text-gray-600 mb-6"></p>
                <div class="flex justify-center space-x-3">
                    <button onclick="cancelarEliminacion()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancelar
                    </button>
                    <button onclick="confirmarEliminacion()"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast {
            animation: slideIn 0.3s ease-out, fadeOut 0.3s ease-in 4.7s forwards;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>

    <script>
        const grados = @json($grados);
        const secciones = @json($secciones ?? []);
        let asignaturaActual = null;
        let docenteAEliminar = null;
        let docenteSeleccionado = null;
        let currentPage = 1;
        let totalPages = 1;
        let currentSearchTerm = '';
        let seccionSeleccionadaId = '';
        let especialidadesRequeridas = [];

        // Función para mostrar notificaciones toast
        function mostrarNotificacion(mensaje, tipo = 'success') {
            const tipos = {
                success: {
                    bg: 'bg-green-100',
                    text: 'text-green-800',
                    border: 'border-green-400',
                    icon: 'fas fa-check-circle'
                },
                error: {
                    bg: 'bg-red-100',
                    text: 'text-red-800',
                    border: 'border-red-400',
                    icon: 'fas fa-exclamation-circle'
                },
                warning: {
                    bg: 'bg-yellow-100',
                    text: 'text-yellow-800',
                    border: 'border-yellow-400',
                    icon: 'fas fa-exclamation-triangle'
                },
                info: {
                    bg: 'bg-blue-100',
                    text: 'text-blue-800',
                    border: 'border-blue-400',
                    icon: 'fas fa-info-circle'
                }
            };

            const toast = document.createElement('div');
            toast.className =
                `toast ${tipos[tipo].bg} ${tipos[tipo].border} ${tipos[tipo].text} px-4 py-3 rounded-lg shadow-lg flex items-center max-w-md`;
            toast.innerHTML = `
            <i class="${tipos[tipo].icon} mr-3"></i>
            <span class="flex-1">${mensaje}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        `;

            document.getElementById('toastContainer').appendChild(toast);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);
        }

        // Debounce helper function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Búsqueda dinámica de docentes por DNI con filtro por especialidades
        const buscarInput = document.getElementById('buscar_dni_docente');
        if (buscarInput) {
            buscarInput.addEventListener('input', debounce(function(e) {
                const valorBusqueda = e.target.value || '';
                console.log('Valor de búsqueda:', valorBusqueda);
                currentSearchTerm = valorBusqueda.trim();
                currentPage = 1;

                if (currentSearchTerm.length >= 1 || currentSearchTerm.length === 0) {
                    buscarDocentesCompatibles();
                }
            }, 300));
        }

        // Función para buscar docentes compatibles
        function buscarDocentesCompatibles(page = 1) {
            if (!asignaturaActual) return;

            currentPage = page;

            // Mostrar loader
            const resultadosDiv = document.getElementById('resultados_busqueda_docente');
            const sinResultadosDiv = document.getElementById('sin_resultados_busqueda');
            resultadosDiv.classList.add('hidden');
            sinResultadosDiv.classList.add('hidden');

            const loader = document.getElementById('loader_busqueda');
            if (loader) loader.classList.remove('hidden');

            // Construir URL con parámetros
            const params = new URLSearchParams({
                page: currentPage,
                search: currentSearchTerm,
                seccion_id: seccionSeleccionadaId
            });
            console.log('URL de búsqueda:', `/asignaturas/${asignaturaActual.codigo}/docentes-disponibles?${params}`);
            fetch(`/asignaturas/${asignaturaActual.codigo}/docentes-disponibles?${params}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    // Verificar si la respuesta es JSON válido
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('La respuesta del servidor no es JSON');
                    }
                    return response.json();
                })
                .then(data => {
                    // Ocultar loader
                    const loader = document.getElementById('loader_busqueda');
                    if (loader) loader.classList.add('hidden');

                    if (data && data.success && data.docentes && data.docentes.length > 0) {
                        mostrarResultadosBusqueda(data);
                        if (data.especialidades_requeridas) {
                            especialidadesRequeridas = data.especialidades_requeridas;
                            mostrarInfoEspecialidades();
                        }
                    } else {
                        sinResultadosDiv.classList.remove('hidden');
                        sinResultadosDiv.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-user-slash text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-500">${data && data.message ? data.message : 'No se encontraron docentes compatibles'}</p>
                    ${currentSearchTerm ? '<p class="text-sm text-gray-400 mt-1">Intente con otro criterio de búsqueda</p>' : ''}
                </div>
            `;
                    }
                })
                .catch(error => {
                    console.error('Error en búsqueda:', error);
                    const loader = document.getElementById('loader_busqueda');
                    if (loader) loader.classList.add('hidden');

                    sinResultadosDiv.classList.remove('hidden');
                    sinResultadosDiv.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl mb-2"></i>
                <p class="text-gray-700">Error al conectar con el servidor</p>
                <p class="text-sm text-gray-500 mt-1">Por favor, intente nuevamente</p>
            </div>
        `;
                });
        }


        // Mostrar información de especialidades requeridas
        function mostrarInfoEspecialidades() {
            const especialidadesInfo = document.getElementById('modalEspecialidadesInfo');
            if (especialidadesRequeridas.length > 0) {
                especialidadesInfo.innerHTML = `
                <span class="font-medium">Especialidades requeridas:</span>
                ${especialidadesRequeridas.map(id => {
                    const especialidad = @json($especialidades).find(e => e.id_especialidad == id);
                    return especialidad ? `<span class="ml-1 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">${especialidad.nombre}</span>` : '';
                }).join('')}
            `;
            } else {
                especialidadesInfo.innerHTML =
                    '<span class="text-green-600">No se requieren especialidades específicas</span>';
            }
        }

        // Mostrar resultados de búsqueda
        function mostrarResultadosBusqueda(data) {
            const tablaBody = document.getElementById('tabla_docentes_encontrados');
            const resultadosDiv = document.getElementById('resultados_busqueda_docente');
            const paginationDiv = document.getElementById('paginacion_docentes');

             console.log('Respuesta completa del servidor:', data); // AGREGAR ESTO
    console.log('¿Tiene docentes?', data.docentes); // AGREGAR ESTO
    console.log('Cantidad de docentes:', data.docentes?.length);

            // Mostrar docentes
            tablaBody.innerHTML = data.docentes.map(docente => {
                const nombreCompleto = `${docente.nombre || ''} ${docente.apellido || ''}`.trim();
                const especialidadesJSON = JSON.stringify(docente.especialidades || []).replace(/"/g, '&quot;');
                return `
            <tr class="hover:bg-gray-50 transition-colors duration-150 ${docente.es_compatible ? '' : 'opacity-60'}">

                <td class="px-3 py-2 text-sm font-medium text-gray-900">${docente.dni || 'N/A'}</td>
                <td class="px-3 py-2">
                    <p class="text-sm text-gray-900">${docente.nombre} ${docente.apellido}</p>
                    <p class="text-xs text-gray-500">${docente.email || 'N/A'}</p>
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">
                    ${docente.especialidades && docente.especialidades.length > 0 ? 
                        docente.especialidades.map(esp => 
                            `<span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded mr-1 mb-1">${esp.nombre}</span>`
                        ).join('') : 
                        '<span class="text-gray-400 text-xs">Sin especialidades</span>'
                    }
                </td>
                <td class="px-3 py-2 text-center">
                    <button 
                        onclick="seleccionarDocente('${docente.codigo_docente}', '${docente.dni || ''}', '${nombreCompleto}', ${especialidadesJSON})"
                        class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition duration-200 ${!docente.es_compatible ? 'opacity-50 cursor-not-allowed' : ''}"
                        ${!docente.es_compatible ? 'disabled title="Este docente no tiene especialidades compatibles"' : ''}>
                        <i class="fas fa-plus mr-1"></i> Seleccionar
                    </button>
                </td>
            </tr>
        `
            }).join('');

            // Actualizar paginación
            if (data.pagination && data.pagination.last_page > 1) {
                paginationDiv.innerHTML = crearPaginacion(data.pagination);
                paginationDiv.classList.remove('hidden');
            } else {
                paginationDiv.classList.add('hidden');
            }

            resultadosDiv.classList.remove('hidden');
        }

        // Crear paginación
        function crearPaginacion(pagination) {
            let html = `
            <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                <div class="flex flex-1 justify-between sm:hidden">
                    <button onclick="cambiarPagina(${pagination.current_page - 1})" 
                            ${pagination.current_page <= 1 ? 'disabled' : ''}
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Anterior
                    </button>
                    <span class="text-sm text-gray-700 px-4 py-2">
                        Página ${pagination.current_page} de ${pagination.last_page}
                    </span>
                    <button onclick="cambiarPagina(${pagination.current_page + 1})"
                            ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Siguiente
                    </button>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Mostrando <span class="font-medium">${pagination.from}</span> a 
                            <span class="font-medium">${pagination.to}</span> de 
                            <span class="font-medium">${pagination.total}</span> docentes
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
        `;

            // Botón anterior
            html += `
            <button onclick="cambiarPagina(${pagination.current_page - 1})"
                    ${pagination.current_page <= 1 ? 'disabled' : ''}
                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="sr-only">Anterior</span>
                <i class="fas fa-chevron-left h-5 w-5"></i>
            </button>
        `;

            // Números de página
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    html += `
                    <button aria-current="page"
                            class="relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        ${i}
                    </button>
                `;
                } else if (i === 1 || i === pagination.last_page ||
                    (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)) {
                    html += `
                    <button onclick="cambiarPagina(${i})"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        ${i}
                    </button>
                `;
                } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
                    html +=
                        `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700">...</span>`;
                }
            }

            // Botón siguiente
            html += `
            <button onclick="cambiarPagina(${pagination.current_page + 1})"
                    ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}
                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="sr-only">Siguiente</span>
                <i class="fas fa-chevron-right h-5 w-5"></i>
            </button>
        `;

            html += `
                        </nav>
                    </div>
                </div>
            </div>
        `;

            return html;
        }

        // Cambiar página
        function cambiarPagina(page) {
            if (page < 1 || page > totalPages) return;
            buscarDocentesCompatibles(page);
        }

        // Seleccionar docente
        function seleccionarDocente(codigoDocente, dni, nombreCompleto, especialidades) {
            docenteSeleccionado = {
                codigo: codigoDocente,
                dni: dni,
                nombre: nombreCompleto,
                especialidades: especialidades
            };

            // Actualizar UI
            document.getElementById('modal_codigo_docente_seleccionado').value = codigoDocente;
            document.getElementById('info_docente_seleccionado').innerHTML = `
            <strong>${nombreCompleto}</strong><br>
            <small class="text-gray-600">DNI: ${dni}</small>
        `;

            // Mostrar especialidades del docente
            const infoEspecialidades = document.getElementById('info_especialidades_docente');
            if (especialidades && especialidades.length > 0) {
                infoEspecialidades.innerHTML = `
                <p class="text-xs font-medium text-gray-700 mb-1">Especialidades:</p>
                <div class="flex flex-wrap gap-1">
                    ${especialidades.map(esp => 
                        `<span class="inline-block px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">${esp.nombre}</span>`
                    ).join('')}
                </div>
            `;
            } else {
                infoEspecialidades.innerHTML = '<p class="text-xs text-gray-500">Sin especialidades registradas</p>';
            }

            document.getElementById('docente_seleccionado_info').classList.remove('hidden');
            document.getElementById('btn_agregar_docente').disabled = false;

            // Ocultar resultados de búsqueda
            document.getElementById('resultados_busqueda_docente').classList.add('hidden');
            document.getElementById('buscar_dni_docente').value = dni;

            // Mostrar notificación
            mostrarNotificacion(`Docente ${nombreCompleto} seleccionado`, 'success');
        }

        // Abrir modal
        function openManageModal(codigoAsignatura, event) {
            event.preventDefault();
            const button = event.currentTarget;
            const asignaturaData = JSON.parse(button.getAttribute('data-asignatura'));

            asignaturaActual = {
                codigo: asignaturaData.codigo_asignatura,
                nombre: asignaturaData.nombre,
                id_grado: asignaturaData.id_grado,
                docentes: [],
                seccion: null
            };

            // Reiniciar búsqueda
            docenteSeleccionado = null;
            seccionSeleccionadaId = '';
            currentSearchTerm = '';
            currentPage = 1;
            especialidadesRequeridas = [];

            document.getElementById('buscar_dni_docente').value = '';
            document.getElementById('resultados_busqueda_docente').classList.add('hidden');
            document.getElementById('sin_resultados_busqueda').classList.add('hidden');
            document.getElementById('docente_seleccionado_info').classList.add('hidden');
            document.getElementById('btn_agregar_docente').disabled = true;
            document.getElementById('paginacion_docentes').innerHTML = '';
            document.getElementById('paginacion_docentes').classList.add('hidden');
            document.getElementById('modalEspecialidadesInfo').innerHTML = '';

            document.getElementById('modal_codigo_asignatura').value = asignaturaActual.codigo;
            document.getElementById('modalAsignaturaInfo').textContent =
                `${asignaturaActual.codigo} - ${asignaturaActual.nombre}`;

            const gradoNombre = asignaturaData.grado?.grado || 'N/A';
            document.getElementById('modalGradoSeccionInfo').textContent = `Grado: ${gradoNombre}°`;

            cargarSecciones(asignaturaActual.id_grado);
            cargarDocentesActivos(asignaturaActual.codigo);

            document.getElementById('manageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Cerrar modal
        function closeManageModal() {
            document.getElementById('manageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            asignaturaActual = null;
            docenteSeleccionado = null;
            cancelarEliminacion();
        }

        // Cargar secciones
        function cargarSecciones(idGrado) {
            const select = document.getElementById('modal_seccion');
            select.innerHTML = '<option value="">-- Seleccione una sección --</option>';

            const seccionesDelGrado = secciones.filter(s => s.id_grado == idGrado);
            seccionesDelGrado.forEach(seccion => {
                const option = document.createElement('option');
                option.value = seccion.id_seccion;
                option.textContent = `${seccion.seccion} (${seccion.grado?.grado || 'N/A'}°)`;
                select.appendChild(option);
            });

            select.onchange = function() {
                seccionSeleccionadaId = this.value;
                if (seccionSeleccionadaId) {
                    mostrarNotificacion('Mostrando solo docentes compatibles con esta sección', 'info');
                    cargarDocentesDeSeccion(seccionSeleccionadaId);
                    buscarDocentesCompatibles(1);
                } else {
                    cargarDocentesActivos(asignaturaActual.codigo);
                }
            };
        }

        // Cargar docentes de sección
        function cargarDocentesDeSeccion(idSeccion) {
            if (!asignaturaActual || !idSeccion) {
                document.getElementById('listaDocentesAsignados').innerHTML = '';
                document.getElementById('sinDocentes').classList.remove('hidden');
                actualizarEstadisticas();
                return;
            }

            // Mostrar loader
            const loader = document.createElement('div');
            loader.id = 'loader_docentes';
            loader.className = 'text-center py-8';
            loader.innerHTML = `
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <p class="mt-2 text-sm text-gray-600">Cargando docentes de la sección...</p>
    `;
            document.getElementById('listaDocentesAsignados').innerHTML = '';
            document.getElementById('listaDocentesAsignados').appendChild(loader);

            fetch(`/asignaturas/${asignaturaActual.codigo}/seccion/${idSeccion}/docentes-activos`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    // Verificar si la respuesta es JSON válido
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('La respuesta del servidor no es JSON');
                    }
                    return response.json();
                })
                .then(data => {
                    // Remover loader
                    const loader = document.getElementById('loader_docentes');
                    if (loader) loader.remove();

                    if (data && data.success) {
                        asignaturaActual.docentes = data.docentes || [];
                        mostrarDocentesAsignados();
                        actualizarEstadisticas();
                    } else {
                        mostrarNotificacion(data && data.message ? data.message : 'Error al cargar docentes', 'error');
                        asignaturaActual.docentes = [];
                        mostrarDocentesAsignados();
                    }
                })
                .catch(error => {
                    console.error('Error al cargar docentes:', error);
                    // Remover loader
                    const loader = document.getElementById('loader_docentes');
                    if (loader) loader.remove();

                    mostrarNotificacion('Error de conexión al cargar docentes', 'error');
                    asignaturaActual.docentes = [];
                    mostrarDocentesAsignados();
                });
        }

        // Mostrar docentes asignados
        function mostrarDocentesAsignados() {
            const container = document.getElementById('listaDocentesAsignados');
            const sinDocentes = document.getElementById('sinDocentes');

            // Remover loader si existe
            const loader = document.getElementById('loader_docentes');
            if (loader) loader.remove();

            if (!asignaturaActual.docentes || asignaturaActual.docentes.length === 0) {
                container.innerHTML = '';
                sinDocentes.classList.remove('hidden');
                document.getElementById('contadorDocentes').textContent = '0';
                return;
            }

            sinDocentes.classList.add('hidden');
            document.getElementById('contadorDocentes').textContent = asignaturaActual.docentes.length;

            container.innerHTML = asignaturaActual.docentes.map(docente => `
            <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition duration-200 ${docente.es_compatible ? '' : 'border-red-300 bg-red-50'}">
                <div class="flex items-center flex-1 min-w-0">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full ${docente.es_compatible ? 'bg-blue-500' : 'bg-red-500'} flex items-center justify-center">
                            <span class="text-sm font-medium text-white">
                                ${(docente.nombre || 'N').charAt(0)}${(docente.apellido || 'A').charAt(0)}
                            </span>
                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium ${docente.es_compatible ? 'text-gray-900' : 'text-red-700'} truncate">
                            ${docente.nombre || 'Nombre no disponible'} 
                            ${docente.apellido || ''}
                            ${!docente.es_compatible ? '<span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Incompatible</span>' : ''}
                        </p>
                        <p class="text-xs ${docente.es_compatible ? 'text-gray-500' : 'text-red-600'} truncate">
                            ${docente.codigo_docente} • Asignado: ${docente.fecha_asignacion ? new Date(docente.fecha_asignacion).toLocaleDateString() : 'N/A'}
                            ${docente.secciones_activas ? `• Secciones: ${docente.secciones_activas}` : ''}
                        </p>
                        ${docente.especialidades && docente.especialidades.length > 0 ? `
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                ${docente.especialidades.map(esp => 
                                                    `<span class="inline-block px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded truncate max-w-[120px]">${esp.nombre}</span>`
                                                ).join('')}
                                            </div>
                                        ` : ''}
                    </div>
                </div>
                <button onclick="prepararEliminacion('${docente.codigo_docente}', '${(docente.nombre || 'Nombre no disponible')} ${docente.apellido || ''}')" 
                        class="text-red-500 hover:text-red-700 transition duration-200 p-2 rounded-full hover:bg-red-50 ml-2 flex-shrink-0" 
                        title="Eliminar docente">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `).join('');
        }

        // Actualizar estadísticas
        function actualizarEstadisticas() {
            if (!asignaturaActual.docentes) {
                document.getElementById('totalDocentes').textContent = '0';
                document.getElementById('docentesCompatibles').textContent = '0';
                document.getElementById('docentesIncompatibles').textContent = '0';
                document.getElementById('ultimaActualizacion').textContent = '--';
                return;
            }

            const total = asignaturaActual.docentes.length;
            const compatibles = asignaturaActual.docentes.filter(d => d.es_compatible).length;
            const incompatibles = total - compatibles;

            document.getElementById('totalDocentes').textContent = total;
            document.getElementById('docentesCompatibles').textContent = compatibles;
            document.getElementById('docentesIncompatibles').textContent = incompatibles;

            if (total > 0) {
                const fechas = asignaturaActual.docentes
                    .map(d => d.fecha_asignacion ? new Date(d.fecha_asignacion) : null)
                    .filter(date => date && !isNaN(date));

                if (fechas.length > 0) {
                    const ultimaFecha = new Date(Math.max(...fechas));
                    document.getElementById('ultimaActualizacion').textContent = ultimaFecha.toLocaleDateString();
                } else {
                    document.getElementById('ultimaActualizacion').textContent = '--';
                }
            } else {
                document.getElementById('ultimaActualizacion').textContent = '--';
            }
        }

        // Agregar docente
        document.getElementById('addDocenteForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!docenteSeleccionado) {
                mostrarNotificacion('Por favor, seleccione un docente', 'warning');
                return;
            }

            const idSeccion = document.getElementById('modal_seccion').value;
            if (!idSeccion) {
                mostrarNotificacion('Por favor, seleccione una sección', 'warning');
                return;
            }

            const btnAgregar = document.getElementById('btn_agregar_docente');
            const originalText = btnAgregar.innerHTML;
            btnAgregar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
            btnAgregar.disabled = true;

            try {
                const formData = new FormData();
                formData.append('codigo_asignatura', document.getElementById('modal_codigo_asignatura').value);
                formData.append('id_seccion', idSeccion);
                formData.append('codigo_docente', docenteSeleccionado.codigo);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route('asignaturas.storeAsignacion') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Verificar si la respuesta es JSON válido
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('La respuesta del servidor no es JSON válido');
                }

                const data = await response.json();

                if (data && data.success) {
                    cargarDocentesDeSeccion(idSeccion);

                    // Limpiar formulario
                    docenteSeleccionado = null;
                    document.getElementById('buscar_dni_docente').value = '';
                    document.getElementById('docente_seleccionado_info').classList.add('hidden');

                    mostrarNotificacion(data.message, 'success');
                } else {
                    mostrarNotificacion(data && data.message ? data.message : 'Error al agregar docente',
                        'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión al agregar el docente', 'error');
            } finally {
                btnAgregar.innerHTML = originalText;
                btnAgregar.disabled = false;
            }
        });


        // Cargar docentes activos
        function cargarDocentesActivos(codigoAsignatura) {
            fetch(`/asignaturas/${codigoAsignatura}/docentes-activos`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    // Verificar si la respuesta es JSON válido
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('La respuesta del servidor no es JSON');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        asignaturaActual.docentes = data.docentes || [];
                    } else {
                        asignaturaActual.docentes = [];
                    }
                    mostrarDocentesAsignados();
                    actualizarEstadisticas();
                })
                .catch(error => {
                    console.error('Error al cargar docentes activos:', error);
                    asignaturaActual.docentes = [];
                    mostrarDocentesAsignados();
                });
        }


        // Preparar eliminación
        function prepararEliminacion(codigoDocente, nombreDocente) {
            const idSeccion = document.getElementById('modal_seccion').value;
            if (!idSeccion) {
                mostrarNotificacion('Por favor, seleccione una sección antes de eliminar un docente.', 'warning');
                return;
            }

            docenteAEliminar = {
                codigo: codigoDocente,
                nombre: nombreDocente,
                seccion: idSeccion
            };

            // Mostrar modal de confirmación
            const modal = document.getElementById('confirmarEliminarModal');
            document.getElementById('mensajeConfirmacion').innerHTML = `
            <p class="mb-2">¿Está seguro de eliminar al docente?</p>
            <p class="font-medium">${nombreDocente}</p>
            <p class="text-sm text-gray-600 mt-2">Esta acción no se puede deshacer.</p>
        `;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Confirmar eliminación
        async function confirmarEliminacion() {
            if (!docenteAEliminar || !asignaturaActual) return;

            const modal = document.getElementById('confirmarEliminarModal');
            const btnConfirmar = modal.querySelector('button[onclick="confirmarEliminacion()"]');
            const originalText = btnConfirmar.innerHTML;
            btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Eliminando...';
            btnConfirmar.disabled = true;

            try {
                const formData = new FormData();
                formData.append('id_seccion', docenteAEliminar.seccion);
                formData.append('codigo_docente', docenteAEliminar.codigo);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route('asignaturas.removeAsignacion') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Verificar si la respuesta es JSON válido
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('La respuesta del servidor no es JSON válido');
                }

                const data = await response.json();

                if (data && data.success) {
                    cargarDocentesDeSeccion(docenteAEliminar.seccion);
                    mostrarNotificacion(data.message, 'success');
                } else {
                    mostrarNotificacion(data && data.message ? data.message : 'Error al eliminar docente', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión al eliminar el docente', 'error');
            } finally {
                cancelarEliminacion();
                btnConfirmar.innerHTML = originalText;
                btnConfirmar.disabled = false;
            }
        }

        // Función para remover docente directamente desde la tabla
        function removerDocenteDirecto(codigoDocente, codigoAsignatura, nombreDocente) {
            if (!confirm(
                    `¿Está seguro de remover la asignación del docente ${nombreDocente}?\n\nEsta acción eliminará todas sus asignaciones en esta asignatura.`
                )) {
                return;
            }

            // Mostrar loader
            mostrarNotificacion('Removiendo asignación...', 'info');

            const formData = new FormData();
            formData.append('codigo_docente', codigoDocente);
            formData.append('codigo_asignatura', codigoAsignatura);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('asignaturas.removeAsignacion') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Respuesta no JSON:', text);
                            throw new Error('La respuesta del servidor no es JSON');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        mostrarNotificacion(data.message || 'Asignación removida exitosamente', 'success');
                        // Recargar la página después de 1 segundo
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        mostrarNotificacion(data && data.message ? data.message : 'Error al remover asignación',
                            'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarNotificacion('Error de conexión al remover la asignación', 'error');
                });
        }

        // Cancelar eliminación
        function cancelarEliminacion() {
            docenteAEliminar = null;
            document.getElementById('confirmarEliminarModal').classList.add('hidden');
            if (!document.getElementById('manageModal').classList.contains('hidden')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        // Manejo de eventos del DOM
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('confirmarEliminarModal').classList.contains('hidden')) {
                    cancelarEliminacion();
                } else if (!document.getElementById('manageModal').classList.contains('hidden')) {
                    closeManageModal();
                }
            }
        });

        document.getElementById('manageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeManageModal();
            }
        });

        document.getElementById('confirmarEliminarModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cancelarEliminacion();
            }
        });

        // Manejo del filtro de nivel educativo y grado
        document.getElementById('nivelEducativo').addEventListener('change', function() {
            const nivelId = parseInt(this.value);
            const gradoSelect = document.getElementById('grado');

            gradoSelect.innerHTML = '';

            if (isNaN(nivelId)) {
                gradoSelect.disabled = true;
                gradoSelect.innerHTML = '<option value="">Primero seleccione un nivel educativo</option>';
                return;
            }

            const gradosFiltrados = grados.filter(g => g.nivel_educativo_id === nivelId);

            if (gradosFiltrados.length > 0) {
                gradoSelect.disabled = false;
                gradoSelect.innerHTML = '<option value="">Seleccione un grado</option>';

                gradosFiltrados.forEach(grado => {
                    gradoSelect.innerHTML += `<option value="${grado.id_grado}">${grado.grado}°</option>`;
                });

                const gradoSeleccionado = '{{ request('grado') }}';
                if (gradoSeleccionado) {
                    gradoSelect.value = gradoSeleccionado;
                }
            } else {
                gradoSelect.disabled = true;
                gradoSelect.innerHTML = '<option value="">No hay grados disponibles</option>';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const nivelSelect = document.getElementById('nivelEducativo');
            if (nivelSelect.value) {
                nivelSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>

@endsection
