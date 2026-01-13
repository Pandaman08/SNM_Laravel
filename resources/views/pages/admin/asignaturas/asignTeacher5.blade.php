@extends('layout.admin.plantilla')

@section('titulo','Asignar docente')

@section('contenido')

<div class="w-full bg-gray-50 min-h-screen p-6">
    <!-- Header -->
    <div class="bg-white border-l-4 border-blue-500 shadow-sm px-6 py-4 mb-6 rounded-r-md">
        <h1 class="text-2xl font-semibold text-gray-800">Gestionar Docentes</h1>
        <p class="text-sm text-gray-600 mt-1">Gestione la asignación de múltiples docentes a las asignaturas</p>
    </div>

    <!-- Notificaciones de Éxito/Error -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6 flex items-center">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Dropdown de Nivel Educativo -->
                <div>
                    <label for="nivelEducativo" class="block text-sm font-medium text-gray-700 mb-2">
                        Nivel Educativo
                    </label>
                    <select id="nivelEducativo" name="nivelEducativo"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900">
                        <option value="">Seleccione un nivel</option>
                        @foreach($nivelesEducativos as $nivel)
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
                        @if(request('nivelEducativo'))
                            @foreach($grados->where('nivel_educativo_id', request('nivelEducativo')) as $gradoItem)
                                <option value="{{ $gradoItem->id_grado }}" 
                                    {{ request('grado') == $gradoItem->id_grado ? 'selected' : '' }}>
                                    {{ $gradoItem->grado }}°
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" id="buscarpor" name="buscarpor"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Buscar Asignaturas
                </button>
                @if(request('nivelEducativo') || request('grado'))
                    <a href="{{ route('asignaturas.asignar.docentes') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-gray-600 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Limpiar Filtros
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Asignaturas -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Asignaturas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CÓDIGO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ASIGNATURA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GRADO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DOCENTES ASIGNADOS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ESTADO</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($asignaturas as $a)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <!-- Columna: Código -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $a->codigo_asignatura }}
                        </td>

                        <!-- Columna: Asignatura -->
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $a->nombre }}
                        </td>

                        <!-- Columna: Grado/Seccion -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $a->grado->grado ?? 'NA'}}
                        </td>

                        <!-- Columna: Docentes Asignados -->
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if ($a->docentes->isEmpty())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Sin asignar
                                </span>
                            @else
                                <div class="space-y-1">
                                    @foreach ($a->docentes->take(2) as $docente)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-6 w-6">
                                                <div class="h-6 w-6 rounded-full bg-blue-500 flex items-center justify-center">
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
                                    @endforeach
                                    @if($a->docentes->count() > 2)
                                        <span class="text-xs text-gray-500 ml-8">
                                            +{{ $a->docentes->count() - 2 }} más
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </td>                                    
                        
                        <!-- Columna: Estado -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if ($a->docentes->isEmpty())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Pendiente
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $a->docentes->count() }} Docente{{ $a->docentes->count() > 1 ? 's' : '' }}
                                </span>
                            @endif
                        </td>
                        
                        <!-- Columna: Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button 
                                onclick="openManageModal('{{ $a->codigo_asignatura }}')"
                                data-asignatura="{{ json_encode($a) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-md hover:bg-indigo-700 transition duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Gestionar ({{ $a->docentes->count() }})
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No hay asignaturas registradas</p>
                                <p class="text-gray-400 text-sm">Los registros aparecerán aquí cuando se agreguen</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Gestionar Docentes (Mejorado con búsqueda por DNI) -->
<div id="manageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-lg bg-white">
        <!-- Header del Modal -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Gestionar Docentes</h3>
                <p id="modalAsignaturaInfo" class="text-sm text-gray-600 mt-1"></p>
                <p id="modalGradoSeccionInfo" class="text-sm text-gray-600 mt-1"></p>
            </div>
            <button onclick="closeManageModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Panel Izquierdo: Agregar Docente -->
            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-lg font-medium text-blue-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Agregar Docente
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
                            <input 
                                type="text" 
                                id="buscar_dni_docente" 
                                placeholder="Ingrese DNI del docente"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                autocomplete="off">
                            
                            <!-- Tabla de resultados de búsqueda -->
                            <div id="resultados_busqueda_docente" class="mt-2 hidden">
                                <div class="border border-gray-300 rounded-md max-h-60 overflow-y-auto bg-white shadow-sm">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">DNI</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabla_docentes_encontrados" class="bg-white divide-y divide-gray-200">
                                            <!-- Los resultados se cargarán aquí dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Mensaje cuando no hay resultados -->
                            <div id="sin_resultados_busqueda" class="mt-2 hidden text-sm text-gray-500 text-center py-2">
                                No se encontraron docentes con ese DNI
                            </div>

                            <!-- Docente seleccionado -->
                            <div id="docente_seleccionado_info" class="mt-3 hidden">
                                <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                    <p class="text-sm font-medium text-green-900">Docente seleccionado:</p>
                                    <p id="info_docente_seleccionado" class="text-sm text-green-700 mt-1"></p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="btn_agregar_docente" disabled class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 flex items-center justify-center disabled:bg-gray-300 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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
                            <span class="text-gray-600">Última actualización:</span>
                            <span id="ultimaActualizacion" class="font-medium text-gray-900">--</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho: Lista de Docentes Asignados -->
            <div class="space-y-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="text-lg font-medium text-green-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Docentes Asignados
                    </h4>
                    
                    <div id="listaDocentesAsignados" class="space-y-3 max-h-96 overflow-y-auto">
                        <!-- Los docentes asignados se mostrarán aquí -->
                    </div>
                    
                    <div id="sinDocentes" class="text-center py-8 text-gray-500 hidden">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p>No hay docentes asignados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
            <button onclick="closeManageModal()" 
                    class="px-6 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition duration-200">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cerrar
            </button>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar -->
<div id="confirmarEliminarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmar Eliminación</h3>
            <p id="mensajeConfirmacion" class="text-sm text-gray-600 mb-6"></p>
            <div class="flex justify-center space-x-3">
                <button onclick="cancelarEliminacion()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition duration-200">
                    Cancelar
                </button>
                <button onclick="confirmarEliminacion()" 
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-200">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    const grados = @json($grados);
    const docentes = @json($docentes ?? []);
    const secciones = @json($secciones ?? []);
    let asignaturaActual = null;
    let docenteAEliminar = null;

    // Búsqueda dinámica de docentes por DNI
    document.getElementById('buscar_dni_docente').addEventListener('input', function() {
        const dni = this.value.trim();
        const resultadosDiv = document.getElementById('resultados_busqueda_docente');
        const sinResultadosDiv = document.getElementById('sin_resultados_busqueda');
        const tablaBody = document.getElementById('tabla_docentes_encontrados');

        if (dni.length === 0) {
            resultadosDiv.classList.add('hidden');
            sinResultadosDiv.classList.add('hidden');
            return;
        }

        // Filtrar docentes que coincidan con el DNI
        const docentesEncontrados = docentes.filter(d => {
            const dniDocente = d.user?.persona?.dni || '';
            return dniDocente.includes(dni);
        });

        if (docentesEncontrados.length > 0) {
            sinResultadosDiv.classList.add('hidden');
            resultadosDiv.classList.remove('hidden');
            
            tablaBody.innerHTML = docentesEncontrados.map(docente => `
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-900">${docente.user?.persona?.dni || 'N/A'}</td>
                    <td class="px-3 py-2 text-sm text-gray-900">
                        ${docente.user?.persona?.name || 'N/A'} ${docente.user?.persona?.lastname || ''}
                    </td>
                    <td class="px-3 py-2 text-sm text-gray-500">${docente.user?.email || 'N/A'}</td>
                    <td class="px-3 py-2 text-center">
                        <button 
                            onclick="seleccionarDocente('${docente.codigo_docente}', '${docente.user?.persona?.dni || ''}', '${docente.user?.persona?.name || ''} ${docente.user?.persona?.lastname || ''}')"
                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Seleccionar
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            resultadosDiv.classList.add('hidden');
            sinResultadosDiv.classList.remove('hidden');
        }
    });

    function seleccionarDocente(codigoDocente, dni, nombreCompleto) {
        docenteSeleccionado = {
            codigo: codigoDocente,
            dni: dni,
            nombre: nombreCompleto
        };

        // Actualizar UI
        document.getElementById('modal_codigo_docente_seleccionado').value = codigoDocente;
        document.getElementById('info_docente_seleccionado').textContent = `${nombreCompleto} (DNI: ${dni})`;
        document.getElementById('docente_seleccionado_info').classList.remove('hidden');
        document.getElementById('btn_agregar_docente').disabled = false;
        // Ocultar resultados de búsqueda
    document.getElementById('resultados_busqueda_docente').classList.add('hidden');
    document.getElementById('buscar_dni_docente').value = dni;
}

function openManageModal(codigoAsignatura) {
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
    document.getElementById('buscar_dni_docente').value = '';
    document.getElementById('resultados_busqueda_docente').classList.add('hidden');
    document.getElementById('sin_resultados_busqueda').classList.add('hidden');
    document.getElementById('docente_seleccionado_info').classList.add('hidden');
    document.getElementById('btn_agregar_docente').disabled = true;

    document.getElementById('modal_codigo_asignatura').value = asignaturaActual.codigo;
    document.getElementById('modalAsignaturaInfo').textContent = `${asignaturaActual.codigo} - ${asignaturaActual.nombre}`;
    
    const gradoNombre = asignaturaData.grado?.grado || 'N/A';
    document.getElementById('modalGradoSeccionInfo').textContent = `Grado: ${gradoNombre}`;

    cargarSecciones(asignaturaActual.id_grado);
    cargarDocentesActivos(asignaturaActual.codigo);

    document.getElementById('manageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeManageModal() {
    document.getElementById('manageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    asignaturaActual = null;
    docenteSeleccionado = null;
    cancelarEliminacion();
}

function cargarSecciones(idGrado) {
    const select = document.getElementById('modal_seccion');
    select.innerHTML = '<option value="">-- Todas las secciones --</option>';

    const seccionesDelGrado = secciones.filter(s => s.id_grado == idGrado);
    seccionesDelGrado.forEach(seccion => {
        const option = document.createElement('option');
        option.value = seccion.id_seccion;
        option.textContent = `${seccion.seccion} (${seccion.grado?.grado || 'N/A'}°)`;
        select.appendChild(option);
    });

    select.onchange = function() {
        const idSeccionSeleccionada = this.value;
        if (idSeccionSeleccionada) {
            cargarDocentesDeSeccion(idSeccionSeleccionada);
        } else {
            cargarDocentesActivos(asignaturaActual.codigo);
        }
    };
}

function cargarDocentesDeSeccion(idSeccion) {
    if (!asignaturaActual || !idSeccion) {
        document.getElementById('listaDocentesAsignados').innerHTML = '';
        document.getElementById('sinDocentes').classList.remove('hidden');
        return;
    }

    fetch(`/asignaturas/${asignaturaActual.codigo}/seccion/${idSeccion}/docentes-activos`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                asignaturaActual.docentes = data.docentes;
                mostrarDocentesAsignados();
                actualizarEstadisticas();
            } else {
                console.error('Error:', data.message);
                asignaturaActual.docentes = [];
                mostrarDocentesAsignados();
            }
        })
        .catch(error => {
            console.error('Error al cargar docentes:', error);
            asignaturaActual.docentes = [];
            mostrarDocentesAsignados();
        });
}

function mostrarDocentesAsignados() {
    const container = document.getElementById('listaDocentesAsignados');
    const sinDocentes = document.getElementById('sinDocentes');

    if (!asignaturaActual.docentes || asignaturaActual.docentes.length === 0) {
        container.innerHTML = '';
        sinDocentes.classList.remove('hidden');
        return;
    }

    sinDocentes.classList.add('hidden');
    container.innerHTML = asignaturaActual.docentes.map(docente => `
        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">
                            ${(docente.nombre || 'N').charAt(0)}${(docente.apellido || 'A').charAt(0)}
                        </span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">
                        ${docente.nombre || 'Nombre no disponible'} 
                        ${docente.apellido || ''}
                    </p>
                    <p class="text-xs text-gray-500">
                        ${docente.codigo_docente} • Asignado: ${docente.fecha_asignacion ? new Date(docente.fecha_asignacion).toLocaleDateString() : 'N/A'}
                        ${docente.secciones_activas ? `• Secciones: ${docente.secciones_activas}` : ''}
                    </p>
                </div>
            </div>
            <button onclick="prepararEliminacion('${docente.codigo_docente}', '${(docente.nombre || 'Nombre no disponible')} ${docente.apellido || ''}')" 
                    class="text-red-500 hover:text-red-700 transition duration-200" 
                    title="Eliminar docente">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `).join('');
}

document.getElementById('addDocenteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!docenteSeleccionado) {
        alert('Por favor, seleccione un docente');
        return;
    }

    const formData = new FormData();
    formData.append('codigo_asignatura', document.getElementById('modal_codigo_asignatura').value);
    formData.append('id_seccion', document.getElementById('modal_seccion').value);
    formData.append('codigo_docente', docenteSeleccionado.codigo);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("asignaturas.storeAsignacion") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const idSeccion = document.getElementById('modal_seccion').value;
            cargarDocentesDeSeccion(idSeccion);
            
            // Limpiar formulario
            docenteSeleccionado = null;
            document.getElementById('buscar_dni_docente').value = '';
            document.getElementById('docente_seleccionado_info').classList.add('hidden');
            document.getElementById('btn_agregar_docente').disabled = true;
            
            alert(data.message);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al agregar el docente');
    });
});

function cargarDocentesActivos(codigoAsignatura) {
    fetch(`/asignaturas/${codigoAsignatura}/docentes-activos`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                asignaturaActual.docentes = data.docentes;
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

function prepararEliminacion(codigoDocente, nombreDocente) {
    const idSeccion = document.getElementById('modal_seccion').value;
    if (!idSeccion) {
        alert('Por favor, seleccione una sección antes de eliminar un docente.');
        return;
    }

    docenteAEliminar = {
        codigo: codigoDocente,
        nombre: nombreDocente,
        seccion: idSeccion
    };

    document.getElementById('mensajeConfirmacion').textContent = 
        `¿Está seguro de que desea eliminar a ${nombreDocente} de esta sección?`;
    document.getElementById('confirmarEliminarModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function actualizarEstadisticas() {
    document.getElementById('totalDocentes').textContent = asignaturaActual.docentes ? asignaturaActual.docentes.length : 0;
    
    if (asignaturaActual.docentes && asignaturaActual.docentes.length > 0) {
        const fechas = asignaturaActual.docentes
            .map(d => d.pivot?.fecha ? new Date(d.pivot.fecha) : new Date())
            .filter(date => !isNaN(date));
        
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

function confirmarEliminacion() {
    if (!docenteAEliminar || !asignaturaActual) return;

    const formData = new FormData();
    formData.append('id_seccion', docenteAEliminar.seccion);
    formData.append('codigo_docente', docenteAEliminar.codigo);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("asignaturas.removeAsignacion") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const idSeccion = docenteAEliminar.seccion;
            cargarDocentesDeSeccion(idSeccion);
            alert(data.message);
        } else {
            alert(data.message);
        }
        cancelarEliminacion();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el docente');
        cancelarEliminacion();
    });
}

function cancelarEliminacion() {
    docenteAEliminar = null;
    document.getElementById('confirmarEliminarModal').classList.add('hidden');
    if (!document.getElementById('manageModal').classList.contains('hidden')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
}

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

document.getElementById('nivelEducativo').addEventListener('change', function () {
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
        
        const gradoSeleccionado = '{{ request("grado") }}';
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
