@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Años Escolares')

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
                <div class="w-14 h-14 bg-gradient-to-br from-lime-500 to-lime-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-calendar-line text-3xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Años Escolares</h1>
                    <p class="text-sm text-gray-500 mt-1">Gestiona los años escolares registrados en el sistema</p>
                </div>
            </div>
            
            <a href="{{ route('anios-escolares.create') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3
                      bg-gradient-to-r from-lime-500 to-lime-600 
                      hover:from-lime-600 hover:to-lime-700
                      text-white font-semibold rounded-xl shadow-lg shadow-lime-500/30
                      transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="ri-add-line text-xl"></i>
                <span>Nuevo año escolar</span>
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
                        placeholder="Buscar por año o descripción..."
                        class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-transparent
                               transition-all duration-200 hover:border-lime-300"
                    >
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5
                           bg-lime-500 hover:bg-lime-600 text-white font-semibold rounded-xl
                           transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]
                           shadow-md"
                >
                    <i class="ri-search-2-line"></i>
                    <span>Buscar</span>
                </button>
            </form>
        {{-- Tarjeta de contenido --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            {{-- Estadística rápida --}}
            <div class="bg-gradient-to-r from-lime-50 to-green-50 px-8 py-4 border-b border-lime-100">
                <div class="flex items-center gap-2 text-lime-800">
                    <i class="ri-bar-chart-box-line text-lg"></i>
                    <span class="text-sm font-semibold">Total de años escolares: 
                        <span class="text-lime-600">{{ $anios->total() }}</span>
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
                                    <i class="ri-calendar-check-line text-sm"></i>
                                    <span>Año Escolar</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="ri-file-text-line text-sm"></i>
                                    <span>Descripción</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="ri-calendar-event-line text-sm"></i>
                                    <span>Fecha Inicio</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <i class="ri-calendar-close-line text-sm"></i>
                                    <span>Fecha Fin</span>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-2">
                                    <i class="ri-checkbox-circle-line text-sm"></i>
                                    <span>Estado</span>
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
                        @forelse($anios as $anio)
                            <tr class="hover:bg-lime-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-lime-100 to-green-100 flex items-center justify-center">
                                            <i class="ri-calendar-2-line text-lime-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $anio->anio }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ID: {{ $anio->id_anio_escolar }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 max-w-xs truncate">
                                        {{ $anio->descripcion ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <i class="ri-play-circle-line text-green-500"></i>
                                        <span>{{ \Carbon\Carbon::parse($anio->fecha_inicio)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <i class="ri-stop-circle-line text-red-500"></i>
                                        <span>{{ \Carbon\Carbon::parse($anio->fecha_fin)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($anio->estado == 'Activo')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <i class="ri-checkbox-circle-fill"></i>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <i class="ri-pause-circle-fill"></i>
                                            Finalizado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('anios-escolares.edit', $anio->id_anio_escolar) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 
                                                  bg-blue-500 hover:bg-blue-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105 active:scale-95
                                                  shadow-sm hover:shadow-md"
                                           title="Editar año escolar">
                                            <i class="ri-edit-line"></i>
                                            <span>Editar</span>
                                        </a>
                                        <button
                                            onclick="confirmarEliminacion({{ $anio->id_anio_escolar }}, '{{ $anio->anio }}')"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 
                                                   bg-red-500 hover:bg-red-600 text-white 
                                                   rounded-lg text-sm font-medium
                                                   transition-all duration-200 transform hover:scale-105 active:scale-95
                                                   shadow-sm hover:shadow-md"
                                            title="Eliminar año escolar">
                                            <i class="ri-delete-bin-2-line"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ri-inbox-line text-4xl"></i>
                                        </div>
                                        <p class="text-lg font-medium text-gray-500 mb-1">No hay años escolares registrados</p>
                                        <p class="text-sm text-gray-400 mb-4">Comienza agregando tu primer año escolar</p>
                                        <a href="{{ route('anios-escolares.create') }}"
                                           class="inline-flex items-center gap-2 px-5 py-2.5
                                                  bg-lime-500 hover:bg-lime-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105">
                                            <i class="ri-add-line"></i>
                                            <span>Crear primer año escolar</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($anios->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $anios->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script para confirmación de eliminación con SweetAlert --}}
<script>
function confirmarEliminacion(id, anio) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `Estás a punto de eliminar el año escolar:<br><strong class="text-lime-600">${anio}</strong>`,
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
            form.action = `/anios-escolares/${id}`;
            
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