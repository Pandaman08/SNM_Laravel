@extends('layout.admin.plantilla')
@section('titulo', 'Lista de grados')

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
        <h1 class="text-3xl font-bold text-gray-800">Grados registrados</h1>
        <a href="{{ route('grados.create') }}" 
           class="bg-[#98C560] hover:bg-[#7aa94f] text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 shadow transition">
            <i class="ri-add-line text-lg"></i> Nuevo grado
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-xl border border-gray-100">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-[#f3f4f6] text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Grado</th>
                    <th class="px-6 py-4 w-32">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($grados as $grado)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $grado->id_grado }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $grado->grado }}  </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('grados.edit', $grado->id_grado) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition" title="Editar">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('grados.destroy', $grado->id_grado) }}" method="POST" onsubmit="return confirm('¿Eliminar grado?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Eliminar">
                                        <i class="ri-delete-bin-2-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">No hay grados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
