@extends('layout.admin.plantilla')

@section('contenido')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-t-lg">
            <h1 class="text-2xl font-bold">Gestión de Asistencias</h1>
            <p class="text-sm mt-1 opacity-90">Control de asistencia de estudiantes por periodo</p>
        </div>

        {{-- Filtros --}}
        <div class="p-6 border-b">
            <form method="GET" action="{{ route('asistencias.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Filtro por Grado --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grado</label>
                        <select name="grado_id" id="grado_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                onchange="loadSecciones()">
                            <option value="">Todos los grados</option>
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Todas las secciones</option>
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
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    {{-- Ordenamiento --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                        <select name="order_by" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                onchange="this.form.submit()">
                            <option value="nombre" {{ $orderBy == 'nombre' ? 'selected' : '' }}>Nombre (A-Z)</option>
                            <option value="apellido" {{ $orderBy == 'apellido' ? 'selected' : '' }}>Apellido (A-Z)</option>
                        </select>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="mt-4 flex justify-between">
                    <div class="space-x-2">
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition duration-200">
                            <i class="ri-search-line mr-1"></i> Filtrar
                        </button>
                        <a href="{{ route('asistencias.index') }}" 
                           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-md inline-block transition duration-200">
                            <i class="ri-refresh-line mr-1"></i> Limpiar
                        </a>
                    </div>
                    
                    @if($gradoId && $seccionId)
                    <a href="{{ route('asistencias.create', ['grado_id' => $gradoId, 'seccion_id' => $seccionId]) }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
                        <i class="ri-add-line mr-1"></i> Registrar Asistencia
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabla de Asistencias --}}
        <div class="overflow-x-auto">
            @if($matriculas->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                N°
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estudiante
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Grado/Sección
                            </th>
                            @foreach($periodos as $periodo)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $periodo->nombre }}
                                    <div class="text-xs font-normal">
                                        P | A | J | T
                                    </div>
                                </th>
                            @endforeach
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($matriculas as $index => $matricula)
                            <tr class="hover:bg-gray-50">
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
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $matricula->seccion->grado->nombre_completo }} - 
                                    Sección {{ $matricula->seccion->seccion }}
                                </td>
                                @foreach($periodos as $periodo)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $asistenciasPeriodo = $asistencias[$matricula->codigo_estudiante][$periodo->id_periodo] ?? collect();
                                            $conteo = [
                                                'Presente' => 0,
                                                'Ausente' => 0,
                                                'Justificado' => 0,
                                                'Tarde' => 0
                                            ];
                                            
                                            foreach($asistenciasPeriodo as $asistencia) {
                                                if(isset($conteo[$asistencia->estado->value])) {
                                                    $conteo[$asistencia->estado->value]++;
                                                }
                                            }
                                        @endphp
                                        
                                        <div class="flex justify-center space-x-2 text-sm">
                                            <span class="text-green-600 font-semibold">{{ $conteo['Presente'] }}</span>
                                            <span class="text-gray-400">|</span>
                                            <span class="text-red-600 font-semibold">{{ $conteo['Ausente'] }}</span>
                                            <span class="text-gray-400">|</span>
                                            <span class="text-blue-600 font-semibold">{{ $conteo['Justificado'] }}</span>
                                            <span class="text-gray-400">|</span>
                                            <span class="text-yellow-600 font-semibold">{{ $conteo['Tarde'] }}</span>
                                        </div>
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('asistencias.show', $matricula->codigo_estudiante) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        <i class="ri-eye-line"></i> Ver
                                    </a>
                                    <a href="{{ route('asistencias.edit', $matricula->codigo_estudiante) }}" 
                                       class="text-yellow-600 hover:text-yellow-900">
                                        <i class="ri-edit-line"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Paginación --}}
                <div class="px-6 py-4 border-t">
                    {{ $matriculas->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="ri-user-search-line"></i>
                    </div>
                    <p class="text-gray-500 text-lg">No se encontraron estudiantes con los filtros aplicados</p>
                    @if($gradoId || $seccionId || $search)
                        <a href="{{ route('asistencias.index') }}" 
                           class="mt-4 inline-block text-green-600 hover:text-green-800">
                            <i class="ri-refresh-line"></i> Limpiar filtros
                        </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- Leyenda --}}
        <div class="p-4 bg-gray-50 rounded-b-lg">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Leyenda:</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-green-500 rounded-full mr-2"></span>
                    <span>P = Presente</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-red-500 rounded-full mr-2"></span>
                    <span>A = Ausente</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-blue-500 rounded-full mr-2"></span>
                    <span>J = Justificado</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></span>
                    <span>T = Tardanza</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadSecciones() {
        const gradoId = document.getElementById('grado_id').value;
        const seccionSelect = document.getElementById('seccion_id');
        
        // Limpiar secciones
        seccionSelect.innerHTML = '<option value="">Todas las secciones</option>';
        
        if (gradoId) {
            // Aquí podrías hacer una petición AJAX para cargar las secciones
            // Por ahora, el formulario se enviará para recargar la página
            document.getElementById('filterForm').submit();
        }
    }
</script>
@endpush
@endsection