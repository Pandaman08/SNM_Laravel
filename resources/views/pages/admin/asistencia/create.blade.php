@extends('layout.admin.plantilla')
@section('contenido')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-t-lg">
            <h1 class="text-2xl font-bold">Registrar Asistencia</h1>
            <p class="text-sm mt-1 opacity-90">Marque la asistencia de los estudiantes para el día {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</p>
        </div>
        @if(session('error'))
            <div class="mt-6 mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>¡Atención!</strong> {{ session('error') }}
            </div>
        @endif
        {{-- Filtros --}}
        <div class="p-6 border-b bg-gray-50">
            <form method="GET" action="{{ route('asistencias.create') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- Selector de Fecha --}}
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Fecha</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</p>
                        {{-- Campo oculto para que llegue al controller --}}
                        <input type="hidden" name="fecha" value="{{ $fecha }}">
                    </div>
                    {{-- Filtro por Grado --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grado</label>
                        <select name="grado_id" id="grado_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="loadSecciones()">
                            <option value="">Seleccione un grado</option>
                            @foreach($grados as $grado)
                                <option value="{{ $grado->id_grado }}" 
                                        {{ $gradoId == $grado->id_grado ? 'selected' : '' }}>
                                    {{ $grado->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro por Sección --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sección</label>
                        <select name="seccion_id" id="seccion_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione una sección</option>
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->id_seccion }}" 
                                        {{ $seccionId == $seccion->id_seccion ? 'selected' : '' }}>
                                    Sección {{ $seccion->seccion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buscador --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar estudiante</label>
                        <input type="text" name="search" id="search" 
                               value="{{ $search }}"
                               placeholder="Nombre o apellido..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- Botón Filtrar --}}
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                            <i class="ri-search-line mr-1"></i> Filtrar
                        </button>
                    </div>
                </div>

                {{-- Información del Periodo --}}
                @if($periodo)
                    <div class="mt-4 p-3 bg-blue-50 rounded-md">
                        <p class="text-sm text-blue-800">
                            <i class="ri-information-line mr-1"></i>
                            <strong>Periodo:</strong> {{ $periodo->nombre }} 
                            ({{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }})
                        </p>
                    </div>
                @elseif(isset($mensajePeriodo))
                    <div class="mt-4 p-3 bg-yellow-50 rounded-md">
                        <p class="text-sm text-yellow-800">
                            <i class="ri-alert-line mr-1"></i>
                            {{ $mensajePeriodo }}
                        </p>
                        @if(isset($periodos) && $periodos->count() > 0)
                            <p class="text-xs text-yellow-700 mt-2">Periodos disponibles:</p>
                            <ul class="text-xs text-yellow-700 ml-4">
                                @foreach($periodos as $p)
                                    <li>• {{ $p->nombre }}: {{ \Carbon\Carbon::parse($p->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($p->fecha_fin)->format('d/m/Y') }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </form>
        </div>

        {{-- Formulario de Asistencias --}}
        @if($matriculas && $matriculas->count() > 0 && $gradoId && $seccionId && $periodo)
            <form method="POST" action="{{ route('asistencias.store') }}" id="asistenciaForm">
                @csrf
                <input type="hidden" name="fecha" value="{{ $fecha }}">
                <input type="hidden" name="id_periodo" value="{{ $periodo->id_periodo }}">

                {{-- Tabla de Estudiantes --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    N°
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estudiante
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Presente
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ausente
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Justificado
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tardanza
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Observación
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($matriculas as $index => $matricula)
                                @php
                                    $yaRegistrado = in_array($matricula->codigo_estudiante, $asistenciasExistentes);
                                @endphp
                                <tr class="hover:bg-gray-50 {{ $yaRegistrado ? 'bg-green-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $matriculas->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $matricula->estudiante->persona->lastname }},
                                            {{ $matricula->estudiante->persona->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Código: {{ $matricula->codigo_estudiante }}
                                            @if($yaRegistrado)
                                                <span class="text-green-600 font-medium ml-2">
                                                    <i class="ri-check-line"></i> Ya registrado
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    @if(!$yaRegistrado)
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" 
                                                   name="asistencias[{{ $index }}][estado]" 
                                                   value="Presente"
                                                   class="w-4 h-4 text-green-600 focus:ring-green-500"
                                                   checked>
                                            <input type="hidden" 
                                                   name="asistencias[{{ $index }}][codigo_estudiante]" 
                                                   value="{{ $matricula->codigo_estudiante }}">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" 
                                                   name="asistencias[{{ $index }}][estado]" 
                                                   value="Ausente"
                                                   class="w-4 h-4 text-red-600 focus:ring-red-500">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" 
                                                   name="asistencias[{{ $index }}][estado]" 
                                                   value="Justificado"
                                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" 
                                                   name="asistencias[{{ $index }}][estado]" 
                                                   value="Tarde"
                                                   class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="text" 
                                                   name="asistencias[{{ $index }}][observacion]" 
                                                   placeholder="Opcional..."
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        </td>
                                    @else
                                        <td colspan="5" class="px-6 py-4 text-center text-green-600">
                                            <i class="ri-check-double-line"></i> Asistencia ya registrada para esta fecha
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Paginación --}}
                    <div class="px-6 py-4 border-t">
                        {{ $matriculas->links() }}
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="p-6 bg-gray-50 border-t flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <i class="ri-information-line"></i>
                        Asegúrese de marcar la asistencia de todos los estudiantes antes de guardar.
                    </div>
                    <div class="space-x-3">
                        <button type="button" 
                                onclick="confirmarCancelar()"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md transition duration-200">
                            <i class="ri-close-line mr-1"></i> Cancelar
                        </button>
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md transition duration-200">
                            <i class="ri-save-line mr-1"></i> Guardar Asistencias
                        </button>
                    </div>
                </div>
            </form>
        @else
            {{-- Mensaje cuando no hay estudiantes o no se han seleccionado filtros --}}
            <div class="p-8 text-center">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="ri-user-search-line"></i>
                </div>
                <p class="text-gray-500 text-lg">
                    @if(!$periodo && (!$gradoId || !$seccionId))
                        Seleccione una fecha válida dentro de un periodo activo
                    @elseif(!$gradoId || !$seccionId)
                        Seleccione un grado y sección para registrar asistencias
                    @else
                        No se encontraron estudiantes con los filtros aplicados
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
@section('script')
<script>
    function loadSecciones() {
        const gradoId = document.getElementById('grado_id').value;
        const seccionSelect = document.getElementById('seccion_id');
        const seccionIdActual = "{{ $seccionId }}";
        
        // Limpiar secciones
        seccionSelect.innerHTML = '<option value="">Cargando...</option>';
        seccionSelect.disabled = true;
        
        if (!gradoId) {
            seccionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
            seccionSelect.disabled = false;
            return;
        }
        
        // Hacer petición AJAX
        fetch(`{{ route('asistencias.secciones-por-grado') }}?grado_id=${gradoId}`)
            .then(response => response.json())
            .then(data => {
                seccionSelect.innerHTML = '<option value="">Seleccione una sección</option>';
                
                if (data.secciones && data.secciones.length > 0) {
                    data.secciones.forEach(seccion => {
                        const option = document.createElement('option');
                        option.value = seccion.id_seccion;
                        option.textContent = `Sección ${seccion.seccion}`;
                        
                        // Mantener la selección actual si existe
                        if (seccionIdActual && seccion.id_seccion == seccionIdActual) {
                            option.selected = true;
                        }
                        
                        seccionSelect.appendChild(option);
                    });
                } else {
                    seccionSelect.innerHTML = '<option value="">No tiene secciones asignadas en este grado</option>';
                }
                
                seccionSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                seccionSelect.innerHTML = '<option value="">Error al cargar secciones</option>';
                seccionSelect.disabled = false;
            });
    }

    function confirmarCancelar() {
        if (confirm('¿Está seguro de que desea cancelar? Se perderán todos los cambios no guardados.')) {
            window.location.href = "{{ route('asistencias.index') }}";
        }
    }

    // Prevenir envío accidental del formulario
    document.getElementById('asistenciaForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (confirm('¿Está seguro de que desea guardar las asistencias registradas?')) {
            this.submit();
        }
    });
    
    // Cargar secciones al inicio si hay un grado seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        const gradoId = document.getElementById('grado_id').value;
        if (gradoId) {
            // No llamamos loadSecciones() aquí porque las secciones ya vienen del servidor
            // Esto evita una petición AJAX innecesaria al cargar la página
        }
    });
    
</script>
@endsection