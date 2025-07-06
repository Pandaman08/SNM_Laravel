@extends('layout.admin.plantilla')
@section('titulo', 'Grados Asignados')
@section('contenido')
<div class="w-full animate-fade-in">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-100 text-indigo-600 rounded-xl p-3">
                📘
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Grados Asignados</h1>
                <p class="text-sm text-indigo-600 mt-1">
                    Total: {{ $asignaciones->count() }} grado{{ $asignaciones->count() > 1 ? 's' : '' }}
                </p>
            </div>
        </div>
        {{-- <div>
            <input type="text" placeholder="Buscar grado..." class="border rounded-lg px-3 py-2 text-sm shadow-sm" />
        </div> --}}
    </div>

    <!-- Tabla de Grados -->
    <div class="overflow-hidden bg-white border border-gray-200 rounded-2xl shadow-md">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-indigo-600 text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4 text-left">📘 Código</th>
                    <th class="px-6 py-4 text-left">📚 Grado</th>
                    <th class="px-6 py-4 text-center">⚙️ Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse ($asignaciones as $asignacion)
                    @php
                        $grado = $asignacion->asignatura->grado ?? null;
                    @endphp
                    <tr class="hover:bg-indigo-50 transition">
                        <td class="px-6 py-4 font-medium text-indigo-600">
                            🆔 {{ $grado?->id_grado ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                {{ $grado?->nombre_completo ?? 'Sin grado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('docente.ver_estudiantes', ['grado_id' => $grado?->id_grado]) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition shadow">
                                👁 Ver Estudiantes
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-gray-400 italic">
                            No tienes grados asignados actualmente.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
