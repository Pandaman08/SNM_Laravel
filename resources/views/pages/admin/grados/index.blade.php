@extends('layout.admin.plantilla')

@section('titulo','Lista de grados')

@section('contenido')
<div class="w-full animate-fade-in">
    @if(session('success') || session('error'))
        <script>
            Swal.fire({
                icon: '{{ session('error') ? 'error' : 'success' }}',
                title: @json(session('success') ?? session('error')),
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
                <i class="ri-graduation-cap-fill text-2xl text-[#d97706]"></i> Grados registrados
            </h1>
            <select id="filtro-nivel" 
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-[#fbbf24]">
                <option value="">Todos</option>
                <option value="1">Primaria</option>
                <option value="2">Secundaria</option>
            </select>
        </div>
        <a href="{{ route('grados.create') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-[#d97706] to-[#fbbf24]
                  hover:from-[#f59e0b] hover:to-[#d97706] text-white px-5 py-2 rounded-full
                  shadow-md transform hover:-translate-y-0.5 transition">
            <i class="ri-add-line text-lg"></i> Agregar grado
        </a>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-2xl shadow-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-gradient-to-r from-[#fde68a] to-[#fcd34d] text-gray-900 uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Nivel</th>
                    <th class="px-6 py-3 text-left">Grado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($grados as $grado)
                    <tr class="even:bg-gray-50 hover:bg-[#fffbeb] transition" data-nivel="{{ $grado->nivel_educativo_id }}">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $grado->nivel_educativo_id == 1 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $grado->nivelEducativo->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700 font-medium">{{ $grado->nombre_completo }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('grados.edit', $grado->id_grado) }}"
                               class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm transition shadow-sm">
                                <i class="ri-edit-line mr-1"></i> Editar
                            </a>
                            <form action="{{ route('grados.destroy', $grado->id_grado) }}" method="POST" class="inline"
                                  onsubmit="return confirm('¿Estas Seguro de desactivar este grado?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm transition shadow-sm">
                                    <i class="ri-delete-bin-2-line mr-1"></i> Desactivar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-gray-500 italic">No hay grados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('filtro-nivel').addEventListener('change', function() {
        const nivel = this.value;
        document.querySelectorAll('tbody tr[data-nivel]').forEach(row => {
            row.style.display = !nivel || row.dataset.nivel === nivel ? '' : 'none';
        });
    });
</script>
@endsection