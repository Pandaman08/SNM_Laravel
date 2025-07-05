@extends('layout.admin.plantilla')

@section('titulo','Lista de Secciones')

@section('contenido')
<div class="w-full animate-fade-in">
    {{-- Flash con SweetAlert2 --}}
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

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
            <i class="ri-layout-grid-fill text-2xl text-[#0d9488]"></i>
            Secciones registradas
        </h1>
        <a href="{{ route('secciones.create') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-[#0d9488] to-[#14b8a6]
                  hover:from-[#14b8a6] hover:to-[#0d9488] text-white px-5 py-2 rounded-full
                  shadow-md transform hover:-translate-y-0.5 transition">
            <i class="ri-add-line text-lg"></i> Nueva sección
        </a>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-2xl shadow-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-gradient-to-r from-[#c7f9f1] to-[#a2f5ec] text-[#065f46] uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Grado</th>
                    <th class="px-6 py-3 text-left">Sección</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($secciones as $seccion)
                    <tr class="even:bg-gray-50 hover:bg-[#ecfeff] transition">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $seccion->grado->nombre_completo ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $seccion->seccion }}
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('secciones.edit', $seccion->id_seccion) }}"
                               class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600
                                      text-white rounded-md text-sm font-medium transition shadow-sm">
                                <i class="ri-pencil-line mr-1"></i> Editar
                            </a>
                            <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}"
                                  method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta sección?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600
                                               text-white rounded-md text-sm font-medium transition shadow-sm">
                                    <i class="ri-delete-bin-2-line mr-1"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-gray-500 italic">
                            <i class="ri-emotion-sad-line text-2xl text-[#f43f5e]"></i><br>
                            No hay secciones registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection