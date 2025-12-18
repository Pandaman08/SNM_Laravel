@extends('layout.admin.plantilla')

@section('titulo', 'Lista de asignaturas')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    {{-- Mensajes de sesión con SweetAlert --}}
    @if(session('success') || session('error'))
        <script>
            Swal.fire({
                icon: '{{ session('error') ? 'error' : 'success' }}',
                title: @json(session('success') ?? session('error')),
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    <div class="max-w-7xl mx-auto">
        {{-- Encabezado y botón de acción --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-book-mark-line text-3xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Asignaturas</h1>
                    <p class="text-sm text-gray-500 mt-1">Gestiona las asignaturas registradas en el sistema</p>
                </div>
            </div>
            
            <a href="{{ route('asignaturas.create') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3
                      bg-gradient-to-r from-teal-500 to-teal-600 
                      hover:from-teal-600 hover:to-teal-700
                      text-white font-semibold rounded-xl shadow-lg shadow-teal-500/30
                      transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="ri-add-line text-xl"></i>
                <span>Nueva asignatura</span>
            </a>
        </div>

        {{-- Tarjeta de contenido --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Estadística rápida --}}
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-8 py-4 border-b border-teal-100">
                <div class="flex items-center gap-2 text-teal-800">
                    <i class="ri-bar-chart-box-line text-lg"></i>
                    <span class="text-sm font-semibold">Total de asignaturas registradas: 
                        <span class="text-teal-600">{{ $asignaturas->count() }}</span>
                    </span>
                </div>
            </div>

            {{-- Tabla responsive --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="ri-hashtag text-sm"></i>
                                    <span>Código</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="ri-book-line text-sm"></i>
                                    <span>Nombre</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="ri-graduation-cap-line text-sm"></i>
                                    <span>Grado</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-2">
                                    <i class="ri-settings-3-line text-sm"></i>
                                    <span>Acciones</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($asignaturas as $a)
                            <tr class="hover:bg-teal-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-teal-100 text-teal-700 font-bold text-sm">
                                            {{ $a->codigo_asignatura }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-teal-100 to-cyan-100 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-book-open-line text-teal-600 text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $a->nombre }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Asignatura
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                            <i class="ri-medal-line mr-1"></i>
                                            {{ $a->grado->nombre_completo }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('asignaturas.edit', $a->codigo_asignatura) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 
                                                  bg-blue-500 hover:bg-blue-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105 active:scale-95
                                                  shadow-sm hover:shadow-md"
                                           title="Editar asignatura">
                                            <i class="ri-edit-line"></i>
                                            <span>Editar</span>
                                        </a>
                                        <form action="{{ route('asignaturas.destroy', $a->codigo_asignatura) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="event.preventDefault(); confirmarEliminacion(this, '{{ $a->nombre }}');">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 
                                                           bg-red-500 hover:bg-red-600 text-white 
                                                           rounded-lg text-sm font-medium
                                                           transition-all duration-200 transform hover:scale-105 active:scale-95
                                                           shadow-sm hover:shadow-md"
                                                    title="Eliminar asignatura">
                                                <i class="ri-delete-bin-2-line"></i>
                                                <span>Eliminar</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ri-inbox-line text-4xl"></i>
                                        </div>
                                        <p class="text-lg font-medium text-gray-500 mb-1">No hay asignaturas registradas</p>
                                        <p class="text-sm text-gray-400 mb-4">Comienza agregando tu primera asignatura</p>
                                        <a href="{{ route('asignaturas.create') }}"
                                           class="inline-flex items-center gap-2 px-5 py-2.5
                                                  bg-teal-500 hover:bg-teal-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105">
                                            <i class="ri-add-line"></i>
                                            <span>Crear primera asignatura</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer de la tabla --}}
            @if($asignaturas->count() > 0)
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            <span>Mostrando <span class="font-semibold">{{ $asignaturas->count() }}</span> registro(s)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400">Última actualización:</span>
                            <span class="font-medium">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script para confirmación de eliminación mejorada --}}
<script>
function confirmarEliminacion(form, nombreAsignatura) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `Estás a punto de eliminar la asignatura:<br><strong class="text-teal-600">${nombreAsignatura}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="ri-delete-bin-line mr-1"></i> Sí, eliminar',
        cancelButtonText: '<i class="ri-close-line mr-1"></i> Cancelar',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            confirmButton: 'px-5 py-2.5 rounded-lg font-semibold shadow-lg',
            cancelButton: 'px-5 py-2.5 rounded-lg font-semibold shadow-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endsection