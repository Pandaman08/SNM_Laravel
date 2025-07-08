@extends('layout.admin.plantilla')
@section('contenido')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-t-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Editar Asistencias</h1>
                    <p class="text-sm mt-1 opacity-90">Modificar registros de asistencia del estudiante</p>
                </div>
                <a href="{{ route('asistencias.index') }}" 
                   class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-md transition duration-200">
                    <i class="ri-arrow-left-line mr-1"></i> Volver
                </a>
            </div>
        </div>

        {{-- Información del Estudiante --}}
        <div class="p-6 bg-gray-50 border-b">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Estudiante</p>
                    <p class="font-semibold">
                        {{ $matricula->estudiante->persona->lastname }}, 
                        {{ $matricula->estudiante->persona->name }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Código</p>
                    <p class="font-semibold">{{ $matricula->codigo_estudiante }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Grado y Sección</p>
                    <p class="font-semibold">
                        {{ $matricula->seccion->grado->nombre_completo }} - 
                        Sección {{ $matricula->seccion->seccion }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Formulario de Edición --}}
        <form method="POST" action="{{ route('asistencias.update', $matricula->codigo_estudiante) }}" id="editForm">
            @csrf
            @method('PUT')

            {{-- Asistencias por Periodo --}}
            <div class="p-6">
                @foreach($asistenciasPorPeriodo as $data)
                    <div class="mb-8">
                        {{-- Header del Periodo --}}
                        <div class="bg-gray-100 p-4 rounded-t-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $data['periodo']->nombre }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($data['periodo']->fecha_inicio)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($data['periodo']->fecha_fin)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Total de registros: {{ $data['estadisticas']['total'] }}</p>
                                    <div class="flex gap-3 text-xs mt-1">
                                        <span class="text-green-600">P: {{ $data['estadisticas']['Presente'] }}</span>
                                        <span class="text-red-600">A: {{ $data['estadisticas']['Ausente'] }}</span>
                                        <span class="text-blue-600">J: {{ $data['estadisticas']['Justificado'] }}</span>
                                        <span class="text-yellow-600">T: {{ $data['estadisticas']['Tarde'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla de Asistencias del Periodo --}}
                        @if($data['asistencias']->count() > 0)
                            <div class="border border-gray-200 rounded-b-lg overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Fecha
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Estado Actual
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cambiar Estado
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Observación
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Justificación
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($data['asistencias'] as $index => $asistencia)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                                                    <span class="text-gray-500 text-xs block">
                                                        {{ \Carbon\Carbon::parse($asistencia->fecha)->translatedFormat('l') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @php
                                                        $colors = [
                                                            'Presente' => 'bg-green-100 text-green-800',
                                                            'Ausente' => 'bg-red-100 text-red-800',
                                                            'Justificado' => 'bg-blue-100 text-blue-800',
                                                            'Tarde' => 'bg-yellow-100 text-yellow-800'
                                                        ];
                                                        $color = $colors[$asistencia->estado->value] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $color }}">
                                                        {{ $asistencia->estado->value }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="hidden" 
                                                           name="asistencias[{{ $asistencia->id_asistencia }}][id_asistencia]" 
                                                           value="{{ $asistencia->id_asistencia }}">
                                                    
                                                    <div class="flex justify-center gap-3">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" 
                                                                   name="asistencias[{{ $asistencia->id_asistencia }}][estado]" 
                                                                   value="Presente"
                                                                   {{ $asistencia->estado->value == 'Presente' ? 'checked' : '' }}
                                                                   class="w-4 h-4 text-green-600 focus:ring-green-500">
                                                            <span class="ml-1 text-xs">P</span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" 
                                                                   name="asistencias[{{ $asistencia->id_asistencia }}][estado]" 
                                                                   value="Ausente"
                                                                   {{ $asistencia->estado->value == 'Ausente' ? 'checked' : '' }}
                                                                   class="w-4 h-4 text-red-600 focus:ring-red-500">
                                                            <span class="ml-1 text-xs">A</span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" 
                                                                   name="asistencias[{{ $asistencia->id_asistencia }}][estado]" 
                                                                   value="Justificado"
                                                                   {{ $asistencia->estado->value == 'Justificado' ? 'checked' : '' }}
                                                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                                            <span class="ml-1 text-xs">J</span>
                                                        </label>
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" 
                                                                   name="asistencias[{{ $asistencia->id_asistencia }}][estado]" 
                                                                   value="Tarde"
                                                                   {{ $asistencia->estado->value == 'Tarde' ? 'checked' : '' }}
                                                                   class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                                            <span class="ml-1 text-xs">T</span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="text" 
                                                           name="asistencias[{{ $asistencia->id_asistencia }}][observacion]" 
                                                           value="{{ $asistencia->observacion }}"
                                                           placeholder="Sin observaciones..."
                                                           class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="text" 
                                                           name="asistencias[{{ $asistencia->id_asistencia }}][justificacion]" 
                                                           value="{{ $asistencia->justificacion }}"
                                                           placeholder="Sin justificación..."
                                                           class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="border border-gray-200 rounded-b-lg p-6 text-center text-gray-500">
                                No hay registros de asistencia en este periodo
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Botones de Acción --}}
            <div class="p-6 bg-gray-50 border-t flex justify-between items-center">
                <a href="{{ route('asistencias.show', $matricula->codigo_estudiante) }}" 
                   class="text-gray-600 hover:text-gray-800">
                    <i class="ri-arrow-left-line mr-1"></i> Volver a detalles
                </a>
                <div class="space-x-3">
                    <button type="button" 
                            onclick="resetForm()"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md transition duration-200">
                        <i class="ri-refresh-line mr-1"></i> Restablecer
                    </button>
                    <button type="submit" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md transition duration-200">
                        <i class="ri-save-line mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>   
@endsection
@section('script')
<script>
    // Guardar valores originales al cargar la página
    const originalValues = {};
    document.addEventListener('DOMContentLoaded', function() {
        // Guardar estados originales
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            const name = radio.name;
            originalValues[name] = radio.value;
        });
        
        // Guardar textos originales
        document.querySelectorAll('input[type="text"]').forEach(input => {
            originalValues[input.name] = input.value;
        });
    });

    function resetForm() {
        if (confirm('¿Está seguro de que desea restablecer todos los cambios?')) {
            // Restablecer radio buttons
            Object.keys(originalValues).forEach(key => {
                if (key.includes('[estado]')) {
                    const radio = document.querySelector(`input[name="${key}"][value="${originalValues[key]}"]`);
                    if (radio) radio.checked = true;
                } else {
                    // Restablecer inputs de texto
                    const input = document.querySelector(`input[name="${key}"]`);
                    if (input) input.value = originalValues[key];
                }
            });
        }
    }

    // Confirmar antes de enviar
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (confirm('¿Está seguro de que desea guardar los cambios realizados?')) {
            this.submit();
        }
    });

    // Resaltar campos que han cambiado
    document.querySelectorAll('input[type="radio"], input[type="text"]').forEach(input => {
        input.addEventListener('change', function() {
            const tr = this.closest('tr');
            if (tr) {
                const originalValue = originalValues[this.name];
                if (this.value !== originalValue) {
                    tr.classList.add('bg-yellow-50');
                } else {
                    // Verificar si hay otros cambios en la fila
                    let hasChanges = false;
                    tr.querySelectorAll('input').forEach(inp => {
                        if (originalValues[inp.name] !== undefined && inp.value !== originalValues[inp.name]) {
                            hasChanges = true;
                        }
                    });
                    if (!hasChanges) {
                        tr.classList.remove('bg-yellow-50');
                    }
                }
            }
        });
    });
</script>
@endsection