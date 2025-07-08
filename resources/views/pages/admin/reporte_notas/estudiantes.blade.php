@extends('layout.admin.plantilla')

@section('titulo', 'Visualizar Notas')

@section('contenido')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-gray-50 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-800">
                    Reporte de Calificaciones - <span class="text-blue-600">{{ $asignatura->nombre }}</span>
                </h2>
            </div>
            <div class="flex items-center mt-2 text-gray-600">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">{{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}</span>
                <span class="mx-2">|</span>
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>DNI: {{ $matricula->estudiante->persona->dni }}</span>
            </div>
            
            @if($periodoActual)
            <div class="mt-2 text-sm text-gray-600">
                <span class="font-semibold">Periodo actual:</span> {{ $periodoActual->nombre }} 
                  ({{ \Carbon\Carbon::parse($periodoActual->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($periodoActual->fecha_fin)->format('d/m/Y') }})
            </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th rowspan="2" class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100 border-r border-gray-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Competencias
                            </div>
                        </th>
                        @foreach($periodos as $periodo)
                        <th colspan="2" class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100 @if(!$loop->last) border-r border-gray-300 @endif">
                            <div class="flex flex-col items-center justify-center">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 @if($periodoActual && $periodoActual->id_periodo == $periodo->id_periodo) text-green-600 @else text-blue-500 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $periodo->nombre }}
                                </div>
                                <div class="text-xs mt-1 @if($periodoActual && $periodoActual->id_periodo == $periodo->id_periodo) text-green-600 font-semibold @else text-gray-500 @endif">
                                      ({{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }})
                                    @if($periodoActual && $periodoActual->id_periodo == $periodo->id_periodo) (Activo) @endif
                                </div>
                            </div>
                        </th>
                        @endforeach
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                PROMEDIO
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-gray-50">
                        @foreach($periodos as $periodo)
                        <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                NL
                            </div>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider @if(!$loop->last) border-r border-gray-300 @endif">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Observación
                            </div>
                        </th>
                        @endforeach
                        <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                NL Final
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($competencias as $competencia)
                    @foreach($competencia->detallesAsignatura as $detalle)
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 border-r border-gray-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $competencia->descripcion }}
                            </div>
                        </td>
                        
                        @foreach($periodos as $periodo)
                        @php
                            $reporte = $detalle->reportesNotas->where('id_periodo', $periodo->id_periodo)->first();
                            $nota = $reporte ? $reporte->tipoCalificacion->codigo : null;
                            $observacion = $reporte ? $reporte->observacion : null;
                            $idReporte = $reporte ? $reporte->id_calificacion : null;
                            $esPeriodoActual = $periodoActual && $periodoActual->id_periodo == $periodo->id_periodo;
                            
                            $color = match($nota) {
                                'AD' => 'text-green-600 bg-green-50',
                                'A' => 'text-blue-600 bg-blue-50',
                                'B' => 'text-yellow-600 bg-yellow-50',
                                'C' => 'text-orange-600 bg-orange-50',
                                default => 'text-gray-600 bg-gray-50'
                            };
                        @endphp
                        
                        <td class="px-4 py-4 text-center text-sm font-medium border-r border-gray-200">
                            @if($nota)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                {{ $nota }}
                            </span>
                            @elseif($esPeriodoActual)
                            <button 
                                class="text-blue-600 hover:text-blue-800"
                                onclick="openRegisterModal(
                                    '{{ $detalle->id_detalle_asignatura }}',
                                    '{{ $periodo->id_periodo }}',
                                    '{{ $asignatura->codigo_asignatura }}'
                                )"
                                title="Registrar nota para este periodo"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-gray-600 @if(!$loop->last) border-r border-gray-300 @endif">
                            @if($nota)
                                {{ $observacion ?? '-' }}
                                @if($esPeriodoActual)
                                <button 
                                    class="ml-2 text-blue-600 hover:text-blue-800"
                                    onclick="openEditModal(
                                        '{{ $idReporte }}',
                                        '{{ $nota }}',
                                        '{{ $observacion }}',
                                        '{{ $periodo->id_periodo }}'
                                    )"
                                    title="Editar nota"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                @endif
                            @else
                            -
                            @endif
                        </td>
                        @endforeach
                        
                        <!-- Columna de Promedio -->
                        <td class="px-4 py-4 text-center text-sm font-medium">
                            @if($detalle->promedio)
                            @php
                                $colorPromedio = match($detalle->promedio) {
                                    'AD' => 'text-green-600 bg-green-50',
                                    'A' => 'text-blue-600 bg-blue-50',
                                    'B' => 'text-yellow-600 bg-yellow-50',
                                    'C' => 'text-orange-600 bg-orange-50',
                                    default => 'text-gray-600 bg-gray-50'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $colorPromedio }}">
                                {{ $detalle->promedio }}
                            </span>
                            @else
                            <span class="text-gray-400">
                                @if($detalle->reportesNotas->isNotEmpty())
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                @else
                                -
                                @endif
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Registrar Nota -->
<div id="registerModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Registrar Nota</h3>
            <form id="registerForm" method="POST" action="{{ route('reporte_notas.store') }}" class="mt-2">
                @csrf
                <input type="hidden" name="id_detalle_asignatura" id="modalDetalleId">
                <input type="hidden" name="id_periodo" id="modalPeriodoId">
                <input type="hidden" name="id_asignatura" id="modalAsignaturaId">
                <input type="hidden" name="fecha_registro" value="{{ now() }}">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_tipo_calificacion">
                        Calificación
                    </label>
                    <select name="id_tipo_calificacion" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @foreach($tiposCalificacion as $tipo)
                            <option value="{{ $tipo->id_tipo_calificacion }}">{{ $tipo->codigo }} - {{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="observacion">
                        Observación
                    </label>
                    <textarea name="observacion" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                </div>
                
                <div class="items-center px-4 py-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Guardar
                    </button>
                    <button onclick="closeModal('registerModal')" type="button" class="ml-2 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Nota -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Editar Nota</h3>
            <form id="editForm" method="POST" class="mt-2">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_periodo" id="editPeriodoId">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_tipo_calificacion">
                        Calificación
                    </label>
                    <select name="id_tipo_calificacion" id="editTipoCalificacion" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @foreach($tiposCalificacion as $tipo)
                            <option value="{{ $tipo->id_tipo_calificacion }}">{{ $tipo->codigo }} - {{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_observacion">
                        Observación
                    </label>
                    <textarea name="observacion" id="editObservacion" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                </div>
                
                <div class="items-center px-4 py-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Actualizar
                    </button>
                    <button onclick="closeModal('editModal')" type="button" class="ml-2 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRegisterModal(detalleId, periodoId, asignaturaId) {
        document.getElementById('modalDetalleId').value = detalleId;
        document.getElementById('modalPeriodoId').value = periodoId;
        document.getElementById('modalAsignaturaId').value = asignaturaId;
        document.getElementById('registerModal').classList.remove('hidden');
    }
    
    function openEditModal(reporteId, nota, observacion, periodoId) {
        document.getElementById('editForm').action = `/reporte_notas/${reporteId}`;
        
        // Seleccionar la opción correcta en el select
        const select = document.getElementById('editTipoCalificacion');
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].text.startsWith(nota)) {
                select.selectedIndex = i;
                break;
            }
        }
        
        document.getElementById('editObservacion').value = observacion || '';
        document.getElementById('editPeriodoId').value = periodoId;
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    
    // Cerrar modal al hacer clic fuera
    window.onclick = function(event) {
        if (event.target.id === 'registerModal') {
            closeModal('registerModal');
        }
        if (event.target.id === 'editModal') {
            closeModal('editModal');
        }
    }

     @if(session('success'))
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#98C560',
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    @elseif(session('error'))
        Swal.fire({
            title: '¡Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#d33',
            timer: 4000,
            timerProgressBar: true
        });
    @endif
</script>
@endsection