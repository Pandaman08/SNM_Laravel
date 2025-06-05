@extends('layout.admin.plantilla')

@section('titulo','Lista de asignaturas')

@section('contenido')
<div class="w-full">
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
        <h1 class="text-3xl font-bold text-gray-800">Asignaturas registradas</h1>
        <a href="{{ route('asignaturas.create') }}" 
           class="inline-flex items-center gap-1 bg-[#98C560] hover:bg-[#7aa94f] text-white px-4 py-2 rounded-md text-sm font-semibold shadow-sm transition"
        >
            <i class="ri-add-line text-lg"></i> Nueva asignatura
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-100">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Código</th>
                    <th class="px-6 py-3 text-left font-semibold">Nombre</th>
                    <th class="px-6 py-3 text-left font-semibold">Grado</th>
                    <th class="px-6 py-3 text-center w-32 font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($asignaturas as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $a->codigo_asignatura }}</td>
                        <td class="px-6 py-3">{{ $a->nombre }}</td>
                        <td class="px-6 py-3">{{ $a->grado->grado }} - {{ $a->grado->nivelEducativo->nombre}}</td>
                        <td class="px-6 py-3 text-center space-x-3">
                            <a href="{{ route('asignaturas.edit', $a->codigo_asignatura) }}" 
                               class="text-blue-600 hover:text-blue-800" 
                               title="Editar"
                            >
                                <i class="ri-pencil-line"></i>
                            </a>
                            <form action="{{ route('asignaturas.destroy', $a->codigo_asignatura) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar asignatura?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-800" title="Eliminar" type="submit">
                                    <i class="ri-delete-bin-2-line"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">
                            No hay asignaturas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
