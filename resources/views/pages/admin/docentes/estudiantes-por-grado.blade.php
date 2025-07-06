@extends('layout.admin.plantilla')

@section('titulo', 'Estudiantes por Grado')

@section('contenido')
<div x-data="{ modal: false, estudiante: {} }" @keydown.escape.window="modal = false" class="w-full animate-fade-in">
    <div class="mb-6 bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded shadow">
        <h2 class="text-2xl font-bold text-center">👩‍🎓 Estudiantes Matriculados - {{ $gradoNombre }}</h2>
    </div>

    <form method="GET" class="mb-6 flex items-center gap-4 flex-wrap">
        <label for="seccion_id" class="text-sm font-medium text-gray-700">Filtrar por sección:</label>
        <select name="seccion_id" id="seccion_id" class="border rounded px-3 py-2 shadow-sm">
            <option value="">-- Todas las secciones --</option>
            @foreach($secciones as $seccion)
                <option value="{{ $seccion->id_seccion }}" {{ request('seccion_id') == $seccion->id_seccion ? 'selected' : '' }}>
      {{ $seccion->seccion }} {{-- OJO: aquí debe ser ->seccion, no ->nombre --}}
    </option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            🔍 Filtrar
        </button>
    </form>

    <!-- Tabla principal -->
    <div class="bg-white shadow-lg rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-indigo-50 text-gray-700 uppercase tracking-wider text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">👤 Nombre</th>
                    <th class="px-6 py-3 text-left">🆔 DNI</th>
                    <th class="px-6 py-3 text-left">🎂 Nacimiento</th>
                    <th class="px-6 py-3 text-left">🏫 Sección</th>
                    <th class="px-6 py-3 text-left">🔎 Más</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse ($matriculas as $matricula)
                    @php
                        $persona = $matricula->estudiante->persona ?? null;
                        $seccion = $matricula->seccion->seccion ?? 'Sin sección';

                        $extra = [
                            'país' => $matricula->estudiante->pais ?? 'Perú',
                            'provincia' => $matricula->estudiante->provincia ?? 'No definida',
                            'distrito' => $matricula->estudiante->distrito ?? 'No definido',
                            'departamento' => $matricula->estudiante->departamento ?? 'No definido',
                            'lengua' => $matricula->estudiante->lengua_materna ?? 'No especificada',
                            'religión' => $matricula->estudiante->religion ?? 'No especificada',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $persona?->name }} {{ $persona?->lastname }}</td>
                        <td class="px-6 py-3">{{ $persona?->dni ?? 'Sin DNI' }}</td>
                        <td class="px-6 py-3">
                            {{ $persona?->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}
                        </td>
                        <td class="px-6 py-3">{{ $seccion }}</td>
                        <td class="px-6 py-3">
                            <button 
                                type="button"
                                class="text-indigo-600 hover:underline"
                                @click="modal = true; estudiante = {{ Js::from(array_merge(['nombre' => $persona?->name . ' ' . $persona?->lastname], $extra)) }}">
                                Ver más
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                            No hay estudiantes matriculados en esta sección.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div x-show="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <h3 class="text-lg font-semibold mb-4 text-indigo-700">📋 Detalles del Estudiante</h3>
            <ul class="text-sm text-gray-700 space-y-1">
                <li><strong>Nombre:</strong> <span x-text="estudiante.nombre"></span></li>
                <li><strong>País:</strong> <span x-text="estudiante.país"></span></li>
                <li><strong>Provincia:</strong> <span x-text="estudiante.provincia"></span></li>
                <li><strong>Distrito:</strong> <span x-text="estudiante.distrito"></span></li>
                <li><strong>Departamento:</strong> <span x-text="estudiante.departamento"></span></li>
                <li><strong>Lengua Materna:</strong> <span x-text="estudiante.lengua"></span></li>
                <li><strong>Religión:</strong> <span x-text="estudiante.religión"></span></li>
            </ul>
            <button @click="modal = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                ✖
            </button>
        </div>
    </div>
</div>
@endsection
