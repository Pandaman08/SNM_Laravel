@extends('layout.admin.plantilla')

@section('titulo','Asignar docente')

@section('contenido')

<div class="w-full bg-gray-50 min-h-screen p-6">
    <!-- Header -->
    <div class="bg-white border-l-4 border-blue-500 shadow-sm px-6 py-4 mb-6 rounded-r-md">
        <h1 class="text-2xl font-semibold text-gray-800">Asignar Docente</h1>
        <p class="text-sm text-gray-600 mt-1">Gestione la asignación de docentes a las asignaturas</p>
    </div>

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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asignatura</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grado/Seccion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Docente Asignado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($asignaturas as $a)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $a->codigo_asignatura }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $a->nombre }}
                        </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $a->grado->grado ?? 'NA'}} /  {{ $a->grado->seccion->seccion ?? 'NA'}}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if ($a->docentes->isEmpty())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Sin asignar
                                </span>
                            @else
                                @foreach ($a->docentes as $docente)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-xs font-medium text-white">
                                                    {{ substr($docente->user->persona->name ?? 'N', 0, 1) }}{{ substr($docente->user->persona->lastname ?? 'A', 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $docente->user->persona->name ?? 'Nombre no disponible' }} 
                                                {{ $docente->user->persona->lastname ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </td>                                    
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if ($a->docentes->isEmpty())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Pendiente
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Asignado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            @if ($a->docentes->isEmpty())
                                <a href="{{ route('asignaturas.asignar', ['id' => $a->codigo_asignatura]) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Asignar
                                </a>
                            @else
                                <button onclick="openEditModal('{{ $a->codigo_asignatura }}', '{{ $a->nombre }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-md hover:bg-indigo-700 transition duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
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

<!-- Modal para Editar Docente -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Header del Modal -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Actualizar Docente</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Contenido del Modal -->
        <div class="mt-4">
            <form id="updateForm" method="POST" action="{{ route('asignaturas.updateAsignacion') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="modal_codigo_asignatura" name="codigo_asignatura">
                
                <!-- Información de la Asignatura -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Asignatura Seleccionada:</h4>
                    <p id="modal_asignatura_info" class="text-lg font-semibold text-gray-900"></p>
                </div>

                <!-- Selección de Docente -->
                <div class="mb-6">
                    <label for="modal_codigo_docente" class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Nuevo Docente:
                    </label>
                    <select id="modal_codigo_docente" name="codigo_docente" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Seleccione un docente</option>
                        @foreach($docentes ?? [] as $docente)
                            <option value="{{ $docente->codigo_docente }}">
                                {{ $docente->user->persona->name ?? 'Nombre no disponible' }} 
                                {{ $docente->user->persona->lastname ?? '' }}
                                ({{ $docente->codigo_docente }})
                            </option>
                        @endforeach
                    </select>

                    

                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Actualizar Docente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const grados = @json($grados);
    const docentes = @json($docentes ?? []);

    // Funciones del Modal
    function openEditModal(codigoAsignatura, nombreAsignatura) {
        document.getElementById('modal_codigo_asignatura').value = codigoAsignatura;
        document.getElementById('modal_asignatura_info').textContent = `${codigoAsignatura} - ${nombreAsignatura}`;
        document.getElementById('modal_codigo_docente').value = '';
        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Cerrar modal al hacer click fuera de él
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Funcionalidad de Filtros
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

    // Inicializar dropdown de grados si hay un nivel preseleccionado
    document.addEventListener('DOMContentLoaded', function() {
        const nivelSelect = document.getElementById('nivelEducativo');
        if (nivelSelect.value) {
            nivelSelect.dispatchEvent(new Event('change'));
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });
</script>

@endsection