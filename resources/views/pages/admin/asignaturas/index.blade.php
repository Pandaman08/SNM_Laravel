@extends('layout.admin.plantilla')
@section('titulo','Lista de asignaturas')

@section('contenido')
<div class="w-full animate-fade-in">
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
            <i class="ri-book-mark-line text-2xl text-[#38b2ac]"></i> Asignaturas registradas
        </h1>
        <a href="{{ route('asignaturas.create') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-[#38b2ac] to-[#2c7a7b]
                  hover:from-[#2c7a7b] hover:to-[#285e61] text-white px-4 py-2 rounded-lg
                  text-sm font-semibold shadow-md transition transform hover:-translate-y-0.5">
            <i class="ri-add-line text-lg"></i> Nueva asignatura
        </a>
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-gradient-to-r from-[#81e6d9] to-[#38b2ac] text-white uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Código</th>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Grado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($asignaturas as $a)
                    <tr class="even:bg-gray-50 hover:bg-gray-100 transition">
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $a->codigo_asignatura }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $a->nombre }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $a->grado->nombre_completo }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('asignaturas.edit', $a->codigo_asignatura) }}"
                               class="inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600
                                      text-white rounded-md text-sm font-medium transition shadow-sm">
                                <i class="ri-pencil-line mr-1"></i> Editar
                            </a>
                            <form action="{{ route('asignaturas.destroy', $a->codigo_asignatura) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('¿Eliminar asignatura?')">
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
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500 italic">
                            No hay asignaturas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection