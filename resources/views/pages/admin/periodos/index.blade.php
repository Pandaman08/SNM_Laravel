@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Periodos')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    {{-- Mensajes de sesión con SweetAlert --}}
    @if(session('success') || session('success-update') || session('success-destroy') || session('error'))
        <script>
            Swal.fire({
                icon: '{{ session('error') ? 'error' : 'success' }}',
                title: @json(session('success') ?? session('success-update') ?? session('success-destroy') ?? session('error')),
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
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-time-line text-3xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Periodos Académicos</h1>
                    <p class="text-sm text-gray-500 mt-1">Gestiona los periodos académicos del sistema</p>
                </div>
            </div>
            
            <a href="{{ route('periodos.create') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3
                      bg-gradient-to-r from-purple-500 to-purple-600 
                      hover:from-purple-600 hover:to-purple-700
                      text-white font-semibold rounded-xl shadow-lg shadow-purple-500/30
                      transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="ri-add-line text-xl"></i>
                <span>Nuevo periodo</span>
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
                        placeholder="Buscar por nombre, año o fechas..."
                        class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                               transition-all duration-200 hover:border-purple-300"
                    >
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5
                           bg-purple-500 hover:bg-purple-600 text-white font-semibold rounded-xl
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
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-8 py-4 border-b border-purple-100">
                <div class="flex items-center gap-2 text-purple-800">
                    <i class="ri-bar-chart-box-line text-lg"></i>
                    <span class="text-sm font-semibold">Total de periodos: 
                        <span class="text-purple-600">{{ $periodos->total() }}</span>
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
                                    <i class="ri-bookmark-line text-sm"></i>
                                    <span>Nombre del Periodo</span>
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
                            <th class="px-6 py-3 text-left font-medium">Nombre</th>
                            <th class="px-6 py-3 text-left font-medium">Año Escolar</th>
                            <th class="px-6 py-3 text-left font-medium">Fecha Inicio</th>
                            <th class="px-6 py-3 text-left font-medium">Fecha Fin</th>
                            <th class="px-6 py-3 text-center font-medium">Estado</th>
                            <th class="px-6 py-3 text-center font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($periodos as $periodo)
                            @php
                                $now = now();
                                $start = \Carbon\Carbon::parse($periodo->fecha_inicio);
                                $end = \Carbon\Carbon::parse($periodo->fecha_fin);
                                
                                if ($now->between($start, $end)) {
                                    $status = 'Activo';
                                    $statusIcon = 'ri-checkbox-circle-fill';
                                    $statusColor = 'bg-green-100 text-green-700';
                                } elseif ($now->lt($start)) {
                                    $status = 'Pendiente';
                                    $statusIcon = 'ri-time-fill';
                                    $statusColor = 'bg-yellow-100 text-yellow-700';
                                } else {
                                    $status = 'Finalizado';
                                    $statusIcon = 'ri-pause-circle-fill';
                                    $statusColor = 'bg-gray-100 text-gray-700';
                                }
                            @endphp
                            <tr class="hover:bg-purple-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center">
                                            <i class="ri-time-line text-purple-600 text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $periodo->nombre }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ID: {{ $periodo->id_periodo }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <i class="ri-play-circle-line text-green-500"></i>
                                        <span>{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <i class="ri-stop-circle-line text-red-500"></i>
                                        <span>{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                        <i class="{{ $statusIcon }}"></i>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $periodo->anioEscolar ? $periodo->anioEscolar->anio : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $now = now();
                                        $start = \Carbon\Carbon::parse($periodo->fecha_inicio);
                                        $end = \Carbon\Carbon::parse($periodo->fecha_fin);
                                        
                                        if ($now->between($start, $end)) {
                                            $status = 'Activo';
                                            $color = 'bg-green-100 text-green-800';
                                        } elseif ($now->lt($start)) {
                                            $status = 'Pendiente';
                                            $color = 'bg-yellow-100 text-yellow-800';
                                        } else {
                                            $status = 'Finalizado';
                                            $color = 'bg-gray-100 text-gray-800';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('periodos.edit', $periodo->id_periodo) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 
                                                  bg-blue-500 hover:bg-blue-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105 active:scale-95
                                                  shadow-sm hover:shadow-md"
                                           title="Editar periodo">
                                            <i class="ri-edit-line"></i>
                                            <span>Editar</span>
                                        </a>
                                        <button
                                            onclick="confirmarEliminacion({{ $periodo->id_periodo }}, '{{ $periodo->nombre }}')"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 
                                                   bg-red-500 hover:bg-red-600 text-white 
                                                   rounded-lg text-sm font-medium
                                                   transition-all duration-200 transform hover:scale-105 active:scale-95
                                                   shadow-sm hover:shadow-md"
                                            title="Eliminar periodo">
                                            <i class="ri-delete-bin-2-line"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ri-inbox-line text-4xl"></i>
                                        </div>
                                        <p class="text-lg font-medium text-gray-500 mb-1">No hay periodos registrados</p>
                                        <p class="text-sm text-gray-400 mb-4">Comienza agregando tu primer periodo académico</p>
                                        <a href="{{ route('periodos.create') }}"
                                           class="inline-flex items-center gap-2 px-5 py-2.5
                                                  bg-purple-500 hover:bg-purple-600 text-white 
                                                  rounded-lg text-sm font-medium
                                                  transition-all duration-200 transform hover:scale-105">
                                            <i class="ri-add-line"></i>
                                            <span>Crear primer periodo</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($periodos->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $periodos->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script para confirmación de eliminación con SweetAlert --}}
<script>
function confirmarEliminacion(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `Estás a punto de eliminar el periodo:<br><strong class="text-purple-600">${nombre}</strong>`,
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
            form.action = `/periodos/${id}/delete`;
            
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