@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Competencias')

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
                <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-trophy-line text-3xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Competencias</h1>
                    <p class="text-sm text-gray-500 mt-1">Gestiona las competencias académicas del sistema</p>
                </div>
            </div>
            
            <a href="{{ route('competencias.create') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3
                      bg-gradient-to-r from-cyan-500 to-cyan-600 
                      hover:from-cyan-600 hover:to-cyan-700
                      text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/30
                      transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="ri-add-line text-xl"></i>
                <span>Nueva competencia</span>
            </a>
        </div>

        {{-- Barra de búsqueda --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="ri-search-line text-gray-400 text-lg"></i>
                    </div>
                    <input
                        name="buscarpor"
                        type="search"
                        value="{{ $buscarpor }}"
                        placeholder="Buscar competencias..."
                        class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent
                               transition-all duration-200 hover:border-cyan-300"
                    >
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5
                           bg-cyan-500 hover:bg-cyan-600 text-white font-semibold rounded-xl
                           transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]
                           shadow-md"
                >
                    <i class="ri-search-2-line"></i>
                    <span>Buscar</span>
                </button>
            </form>
        </div>

        {{-- Tarjeta de contenido --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Estadística rápida --}}
            <div class="bg-gradient-to-r from-cyan-50 to-blue-50 px-8 py-4 border-b border-cyan-100">
                <div class="flex items-center gap-2 text-cyan-800">
                    <i class="ri-bar-chart-box-line text-lg"></i>
                    <span class="text-sm font-semibold">Total de competencias: 
                        <span class="text-cyan-600">{{ $competencias->total() }}</span>
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
                                    <i class="ri-book-line text-sm"></i>
                                    <span>Asignatura</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="ri-file-text-line text-sm"></i>
                                    <span>Descripción</span>
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
                        @forelse($competencias as $competencia)
                            <tr class="hover:bg-cyan-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-100 to-blue-100 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-book-open-line text-cyan-600 text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $competencia->asignatura->nombre ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Código: {{ $competencia->codigo_asignatura }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 line-clamp-2">
                                        {{ $competencia->descripcion }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('competencias.edit', $competencia->id_competencias) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 
                                                  bg-blue-500 hover:bg-blue-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105 active:scale-95
                                                  shadow-sm hover:shadow-md"
                                           title="Editar competencia">
                                            <i class="ri-edit-line"></i>
                                            <span>Editar</span>
                                        </a>
                                        <button
                                            onclick="confirmarEliminacion({{ $competencia->id_competencias }}, '{{ addslashes($competencia->descripcion) }}')"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 
                                                   bg-red-500 hover:bg-red-600 text-white 
                                                   rounded-lg text-sm font-medium
                                                   transition-all duration-200 transform hover:scale-105 active:scale-95
                                                   shadow-sm hover:shadow-md"
                                            title="Eliminar competencia">
                                            <i class="ri-delete-bin-2-line"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ri-inbox-line text-4xl"></i>
                                        </div>
                                        <p class="text-lg font-medium text-gray-500 mb-1">No hay competencias registradas</p>
                                        <p class="text-sm text-gray-400 mb-4">Comienza agregando tu primera competencia</p>
                                        <a href="{{ route('competencias.create') }}"
                                           class="inline-flex items-center gap-2 px-5 py-2.5
                                                  bg-cyan-500 hover:bg-cyan-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105">
                                            <i class="ri-add-line"></i>
                                            <span>Crear primera competencia</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($competencias->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $competencias->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script para confirmación de eliminación con SweetAlert --}}
<script>
function confirmarEliminacion(id, descripcion) {
    // Truncar descripción si es muy larga
    const descripcionCorta = descripcion.length > 100 ? descripcion.substring(0, 100) + '...' : descripcion;
    
    Swal.fire({
        title: '¿Estás seguro?',
        html: `Estás a punto de eliminar la competencia:<br><strong class="text-cyan-600">"${descripcionCorta}"</strong>`,
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
            // Crear formulario dinámico para enviar DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/competencias/${id}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection