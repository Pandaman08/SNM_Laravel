@extends('layout.admin.plantilla')

@section('titulo','Lista de Asignaturas')

@section('contenido')
<div class="w-full animate-fade-in">
    {{-- Mensajes de éxito o error --}}
    @if (session('success') || session('error'))
        <script>
            Swal.fire({
                icon: '{{ session('error') ? 'error' : 'success' }}',
                title: @json(session('success') ?? session('error')),
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    {{-- Título y filtros --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
                <i class="ri-book-mark-line text-2xl text-[#38b2ac]"></i>
                Asignaturas registradas
            </h1>

            {{-- Filtro Nivel --}}
            <select id="filtro-nivel"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-[#38b2ac] focus:border-[#38b2ac] transition">
                <option value="">Todos los niveles</option>
                <option value="1">Primaria</option>
                <option value="2">Secundaria</option>
            </select>

            {{-- Filtro Grado --}}
            <select id="filtro-grado"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-[#38b2ac] focus:border-[#38b2ac] transition"
                    disabled>
                <option value="">Seleccione nivel primero</option>
            </select>
        </div>

        {{-- Botón Nueva Asignatura --}}
        <a href="{{ route('asignaturas.create') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-[#38b2ac] to-[#2c7a7b]
                  hover:from-[#2c7a7b] hover:to-[#285e61] text-white px-5 py-2 rounded-full
                  shadow-md transform hover:-translate-y-0.5 transition">
            <i class="ri-add-line text-lg"></i> Agregar asignatura
        </a>
    </div>

    {{-- Tabla de Asignaturas --}}
    <div class="overflow-x-auto bg-white border border-gray-200 rounded-2xl shadow-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-gradient-to-r from-[#81e6d9] to-[#38b2ac] text-white uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Código</th>
                    <th class="px-6 py-3 text-left">Nivel</th>
                    <th class="px-6 py-3 text-left">Grado</th>
                    <th class="px-6 py-3 text-left">Asignatura</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($asignaturas as $a)
                    <tr class="even:bg-gray-50 hover:bg-[#ecfeff] transition"
                        data-nivel="{{ $a->grado->nivel_educativo_id }}"
                        data-grado="{{ $a->grado->id_grado }}">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $a->codigo_asignatura }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $a->grado->nivel_educativo_id == 1 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $a->grado->nivelEducativo->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $a->grado->nombre_completo ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-semibold">
                            {{ $a->nombre }}
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('asignaturas.edit', $a->codigo_asignatura) }}"
                               class="inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600
                                      text-white rounded-md text-sm font-medium transition shadow-sm">
                                <i class="ri-pencil-line mr-1"></i> Editar
                            </a>
                            <form action="{{ route('asignaturas.destroy', $a->codigo_asignatura) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('¿Estas seguro de desactivar esta asignatura?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600
                                               text-white rounded-md text-sm font-medium transition shadow-sm">
                                    <i class="ri-delete-bin-2-line mr-1"></i> Desactivar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500 italic">
                            <i class="ri-emotion-sad-line text-2xl text-[#f43f5e]"></i><br>
                            No hay asignaturas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script de filtros dinámicos --}}
<script>
    // Agrupar grados por nivel
    const gradosPorNivel = @json($asignaturas->groupBy('grado.nivel_educativo_id')->map(function($asignaturas) {
        return $asignaturas->pluck('grado')->unique('id_grado')->map(function($grado) {
            return [
                'id' => $grado->id_grado,
                'nombre' => $grado->nombre_completo
            ];
        })->values();
    }));

    const filtroNivel = document.getElementById('filtro-nivel');
    const filtroGrado = document.getElementById('filtro-grado');

    filtroNivel.addEventListener('change', function() {
        const nivel = this.value;

        // Resetear filtro de grado
        filtroGrado.innerHTML = '<option value="">Todos los grados</option>';
        filtroGrado.disabled = !nivel;

        // Cargar grados según el nivel seleccionado
        if (nivel && gradosPorNivel[nivel]) {
            gradosPorNivel[nivel].forEach(grado => {
                const option = document.createElement('option');
                option.value = grado.id;
                option.textContent = grado.nombre;
                filtroGrado.appendChild(option);
            });
        }

        aplicarFiltros();
    });

    filtroGrado.addEventListener('change', aplicarFiltros);

    function aplicarFiltros() {
        const nivel = filtroNivel.value;
        const grado = filtroGrado.value;

        document.querySelectorAll('tbody tr[data-nivel]').forEach(row => {
            const rowNivel = row.dataset.nivel;
            const rowGrado = row.dataset.grado;

            const cumpleNivel = !nivel || rowNivel === nivel;
            const cumpleGrado = !grado || rowGrado === grado;

            row.style.display = cumpleNivel && cumpleGrado ? '' : 'none';
        });
    }
</script>
@endsection
