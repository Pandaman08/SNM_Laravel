@extends('layout.admin.plantilla')

@section('titulo', 'Lista de Secciones')

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
                    <i class="ri-layout-grid-fill text-3xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Secciones</h1>
                    <p class="text-sm text-gray-500 mt-1">Gestiona las secciones registradas en el sistema</p>
                </div>
            </div>
            
            <a href="{{ route('secciones.create') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3
                      bg-gradient-to-r from-emerald-500 to-emerald-600 
                      hover:from-emerald-600 hover:to-emerald-700
                      text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/30
                      transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="ri-add-line text-xl"></i>
                <span>Nueva sección</span>
            </a>
        </div>

        {{-- Tarjeta de contenido --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Estadística rápida --}}
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-8 py-4 border-b border-emerald-100">
                <div class="flex items-center gap-2 text-emerald-800">
                    <i class="ri-bar-chart-box-line text-lg"></i>
                    <span class="text-sm font-semibold">Total de secciones registradas: 
                        <span class="text-emerald-600">{{ $secciones->count() }}</span>
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
                                    <i class="ri-graduation-cap-line text-sm"></i>
                                    <span>Grado</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="ri-text text-sm"></i>
                                    <span>Sección</span>
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
                        @forelse($secciones as $seccion)
                            <tr class="hover:bg-emerald-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-100 to-yellow-100 flex items-center justify-center">
                                            <i class="ri-book-2-line text-amber-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $seccion->grado->nombre_completo ?? '—' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ID Grado: {{ $seccion->id_grado }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white font-bold text-2xl shadow-lg">
                                            {{ $seccion->seccion }}
                                        </span>
                                        <div>
                                            <div class="text-xs text-gray-500">
                                                Sección
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                ID: {{ $seccion->id_seccion }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('secciones.edit', $seccion->id_seccion) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 
                                                  bg-blue-500 hover:bg-blue-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105 active:scale-95
                                                  shadow-sm hover:shadow-md"
                                           title="Editar sección">
                                            <i class="ri-edit-line"></i>
                                            <span>Editar</span>
                                        </a>
                                        <form action="{{ route('secciones.destroy', $seccion->id_seccion) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="event.preventDefault(); confirmarEliminacion(this, '{{ $seccion->grado->nombre_completo ?? "Grado" }} - Sección {{ $seccion->seccion }}');">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 
                                                           bg-red-500 hover:bg-red-600 text-white 
                                                           rounded-lg text-sm font-medium
                                                           transition-all duration-200 transform hover:scale-105 active:scale-95
                                                           shadow-sm hover:shadow-md"
                                                    title="Eliminar sección">
                                                <i class="ri-delete-bin-2-line"></i>
                                                <span>Eliminar</span>
                                            </button>
                                        </form>
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
                                        <p class="text-lg font-medium text-gray-500 mb-1">No hay secciones registradas</p>
                                        <p class="text-sm text-gray-400 mb-4">Comienza agregando tu primera sección</p>
                                        <a href="{{ route('secciones.create') }}"
                                           class="inline-flex items-center gap-2 px-5 py-2.5
                                                  bg-emerald-500 hover:bg-emerald-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105">
                                            <i class="ri-add-line"></i>
                                            <span>Crear primera sección</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer de la tabla --}}
            @if($secciones->count() > 0)
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="ri-file-list-3-line"></i>
                            <span>Mostrando <span class="font-semibold">{{ $secciones->count() }}</span> registro(s)</span>
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
function confirmarEliminacion(form, nombreSeccion) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `Estás a punto de eliminar:<br><strong class="text-emerald-600">${nombreSeccion}</strong>`,
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