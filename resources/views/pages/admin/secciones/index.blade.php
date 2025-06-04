@extends('layout.admin.plantilla')

@section('titulo', 'Lista de Secciones')

@section('contenido')
<div class="w-full">
    {{-- Mensaje flash con SweetAlert2 --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: @json(session('success')),
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Secciones registradas</h1>
        <a href="{{ route('secciones.create') }}" 
            class="bg-[#98C560] hover:bg-[#7aa94f] text-white px-4 py-2 rounded-md text-sm flex items-center gap-1 shadow">
            <i class="ri-add-line text-lg"></i> Nueva sección
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left">Grado</th>
                    <th class="px-6 py-3 text-left">Sección</th>
                    <th class="px-6 py-3 text-left w-32">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($secciones as $seccion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            {{ $seccion->grado->grado ?? '—' }}
                        </td>
                        <td class="px-6 py-3 font-semibold text-gray-700">
                            {{ $seccion->seccion }}
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('secciones.edit', $seccion->id_seccion) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition" 
                                   title="Editar sección">
                                    <i class="ri-pencil-line text-lg"></i>
                                </a>
                                <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Eliminar esta sección?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 transition" 
                                            title="Eliminar sección">
                                        <i class="ri-delete-bin-2-line text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay secciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
