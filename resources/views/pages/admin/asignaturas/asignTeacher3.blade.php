<!-- está junto con 1 -->
@extends('layout.admin.plantilla')

@section('titulo','Asignar docente')

@section('contenido')

<div class="w-full">
    <div class="bg-green-100 border border-green-800 text-gray-800 px-4 py-3 rounded">
        <h1 class="font-bold text-2xl text-center">Relación Asignatura-Docente</h1>
    </div>

    <!-- Formulario de Filtros -->
    <form method="GET" action=" " class="m-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <!-- Dropdown de Nivel Educativo -->
            <div>
                <label for="nivelEducativo" class="block mb-2 text-sm font-medium text-gray-700">
                    Seleccionar Nivel Educativo:
                </label>
                <select id="nivelEducativo" name="nivelEducativo"
                    class="block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700">
                    <option value="">-- Seleccione un nivel --</option>
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
                <label for="grado" class="block mb-2 text-sm font-medium text-gray-700">
                    Seleccionar Grado:
                </label>
                <select id="grado" name="grado"
                    class="block w-full p-2.5 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700"
                    {{ !request('nivelEducativo') ? 'disabled' : '' }}>
                    <option value="">-- Primero seleccione un nivel educativo --</option>
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

        <!-- Botón de Búsqueda -->
        <div class="mb-4">
            <button type="submit" id="buscarpor" name="buscarpor"
                class="px-6 py-2 bg-green-600 text-white font-semibold rounded-md shadow-sm hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                🔍 Buscar Asignaturas
            </button>
            @if(request('nivelEducativo') || request('grado'))
                <a href="{{ route('asignaturas.show') }}" 
                   class="ml-3 px-6 py-2 bg-gray-500 text-white font-semibold rounded-md shadow-sm hover:bg-gray-600 transition duration-200">
                    Limpiar Filtros
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Código</th>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Docente</th>
                    <th class="px-6 py-3 text-center w-32">Estado de Asignación</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($asignaturas as $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">{{ $a->codigo_asignatura }}</td>
                    <td class="px-6 py-3">{{ $a->nombre }}</td>
                    <td class="px-6 py-3">
                        @if ($a->docentes->isEmpty())
                            <span class="text-gray-500 italic">Sin profesor</span>
                        @else
                            @foreach ($a->docentes as $docente)
                                <span class="block">
                                    {{ $docente->user->persona->name ?? 'Nombre no disponible' }} 
                                    {{ $docente->user->persona->lastname ?? '' }}
                                </span>
                            @endforeach
                        @endif
                    </td>                                    
                    <td class="px-6 py-3 text-center space-x-2">
                        @if ($a->docentes->isEmpty())
                            <a href="{{ route('asignaturas.asignar', ['id' => $a->codigo_asignatura]) }}"
                            class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
                                Asignar
                            </a>
                        @else
                            <span class="text-green-600 font-semibold">Exitoso</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay asignaturas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

<script>
    const grados = @json($grados);

    document.getElementById('nivelEducativo').addEventListener('change', function () {
        const nivelId = parseInt(this.value);
        const gradoSelect = document.getElementById('grado');

        // Limpia las opciones anteriores
        gradoSelect.innerHTML = '';

        if (isNaN(nivelId)) {
            gradoSelect.disabled = true;
            gradoSelect.innerHTML = '<option value="">-- Primero seleccione un nivel educativo --</option>';
            return;
        }

        // Filtrar los grados que tienen el mismo nivel educativo
        const gradosFiltrados = grados.filter(g => g.nivel_educativo_id === nivelId);

        if (gradosFiltrados.length > 0) { 
            gradoSelect.disabled = false;
            gradoSelect.innerHTML = '<option value="">-- Seleccione un grado --</option>';

            gradosFiltrados.forEach(grado => {
                gradoSelect.innerHTML += `<option value="${grado.id_grado}">${grado.grado}°</option>`;
            });
            
            // Mantener selección si existe
            const gradoSeleccionado = '{{ request("grado") }}';
            if (gradoSeleccionado) {
                gradoSelect.value = gradoSeleccionado;
            }
        } else {
            gradoSelect.disabled = true;
            gradoSelect.innerHTML = '<option value="">-- No hay grados disponibles --</option>';
        }
    });

    // Inicializar dropdown de grados si hay un nivel preseleccionado
    document.addEventListener('DOMContentLoaded', function() {
        const nivelSelect = document.getElementById('nivelEducativo');
        if (nivelSelect.value) {
            nivelSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

@endsection