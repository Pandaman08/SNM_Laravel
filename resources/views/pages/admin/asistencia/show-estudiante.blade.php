@extends('layout.admin.plantilla')

@section('contenido')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- MENSAJES DE ÉXITO/ERROR -->
        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Header del estudiante -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($matricula->estudiante->persona->name, 0, 1) . substr($matricula->estudiante->persona->lastname, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}
                        </h1>
                        <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                Código: {{ $matricula->estudiante->codigo_estudiante }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $matricula->seccion->grado->getNombreCompletoAttribute() }} - {{ $matricula->seccion->seccion }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button onclick="exportarCalendario()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Exportar
                    </button>
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                        </svg>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>

        <!-- Resumen Estadístico -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            @php
                $totalesGenerales = ['Presente' => 0, 'Ausente' => 0, 'Justificado' => 0, 'Tarde' => 0];
                $totalDias = 0;
                foreach($statsPorPeriodo as $stat) {
                    foreach($stat['totales'] as $estado => $cantidad) {
                        if (isset($totalesGenerales[$estado])) {
                            $totalesGenerales[$estado] += $cantidad;
                        }
                    }
                }
                $totalDias = array_sum($totalesGenerales);
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Días Presente</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalesGenerales['Presente'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    {{ $totalDias > 0 ? round(($totalesGenerales['Presente'] / $totalDias) * 100, 1) : 0 }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Días Ausente</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalesGenerales['Ausente'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                                    {{ $totalDias > 0 ? round(($totalesGenerales['Ausente'] / $totalDias) * 100, 1) : 0 }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Días Justificado</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalesGenerales['Justificado'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-yellow-600">
                                    {{ $totalDias > 0 ? round(($totalesGenerales['Justificado'] / $totalDias) * 100, 1) : 0 }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Días Tarde</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $totalesGenerales['Tarde'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-orange-600">
                                    {{ $totalDias > 0 ? round(($totalesGenerales['Tarde'] / $totalDias) * 100, 1) : 0 }}%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔥 NUEVO: Selector de Rango de Fechas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Rango de Fechas</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                    <input type="date" id="fecha-desde" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                    <input type="date" id="fecha-hasta" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-end">
                    <button id="aplicar-rango" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                        </svg>
                        Aplicar Rango
                    </button>
                </div>
            </div>
        </div>

        <!-- Leyenda -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                    <span class="text-gray-700">Presente</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-gray-700">Ausente</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                    <span class="text-gray-700">Justificado</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                    <span class="text-gray-700">Tarde</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-200 border border-gray-300 rounded mr-2"></div>
                    <span class="text-gray-700">Sin registro</span>
                </div>
            </div>
        </div>

        <!-- 🔥 NUEVA TABLA ESTILO LÍNEA DE TIEMPO -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <div id="contenedor-tabla-asistencias" class="min-w-full">
                    <!-- Se generará dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Modal de Justificación -->
        @if(auth()->user()->tutor)
        <div id="modal-justificacion" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <form id="form-justificacion" action="{{ route('asistencias.solicitar-justificacion') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="asistencia-id" name="asistencia_id">
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Solicitar Justificación de Ausencia</h3>
                            <button type="button" onclick="cerrarModalJustificacion()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="mb-6">
                            <p id="fecha-justificacion" class="text-sm text-gray-600 mb-4"></p>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo de la Justificación *</label>
                                <textarea name="motivo_justificacion" id="motivo-justificacion" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Explique detalladamente el motivo de la ausencia..."></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Documento de Respaldo <span class="text-red-600">*</span></label>
                                
                                <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer">
                                    <input type="file" name="archivo_justificacion" id="archivo-justificacion"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required class="hidden">
                                    
                                    <div id="file-upload-content">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-600 mb-1">
                                            <span class="text-blue-600 font-medium">Haz clic para seleccionar</span> o arrastra el archivo aquí
                                        </p>
                                        <p class="text-xs text-gray-500">PDF, JPG, PNG o Word (máx. 5MB)</p>
                                    </div>
                                    
                                    <div id="file-preview" class="hidden">
                                        <div class="flex items-center justify-center gap-3 bg-blue-50 p-3 rounded-lg">
                                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 text-left">
                                                <p id="file-name" class="text-sm font-medium text-gray-900"></p>
                                                <p id="file-size" class="text-xs text-gray-500"></p>
                                            </div>
                                            <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="cerrarModalJustificacion()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </button>
                            <button type="submit" id="btn-enviar-justificacion" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Enviar Solicitud
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Estilos para la tabla de asistencias tipo línea de tiempo */
.tabla-asistencias {
    display: table;
    width: 100%;
    border-collapse: collapse;
}

.tabla-asistencias-header {
    display: table-row;
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    color: white;
    font-weight: 600;
}

.tabla-asistencias-header > div {
    display: table-cell;
    padding: 12px 8px;
    text-align: center;
    border-right: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 0.875rem;
    white-space: nowrap;
}

.tabla-asistencias-row {
    display: table-row;
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.2s;
}

.tabla-asistencias-row:hover {
    background-color: #f9fafb;
}

.tabla-asistencias-cell {
    display: table-cell;
    padding: 10px 8px;
    text-align: center;
    border-right: 1px solid #e5e7eb;
    vertical-align: middle;
}

.celda-fecha {
    font-weight: 600;
    color: #1f2937;
    min-width: 120px;
}

.celda-dia {
    color: #6b7280;
    font-size: 0.875rem;
    min-width: 100px;
}

.celda-asistencia {
    min-width: 80px;
}

/* 🔥 BLOQUES DE SEMANA (ACORDEÓN) */
.bloque-semana {
    transition: all 0.3s ease;
}

.header-semana {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid #e2e8f0;
}

.header-semana:hover {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
}

.contenido-semana {
    transition: all 0.3s ease;
    overflow: hidden;
}

.contenido-semana.hidden {
    max-height: 0;
    opacity: 0;
}

/* Indicadores de estado */
.indicador-estado {
    display: inline-block;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.indicador-estado:hover {
    transform: scale(1.2);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.estado-presente { 
    background: linear-gradient(135deg, #10b981, #059669); 
}

.estado-ausente { 
    background: linear-gradient(135deg, #ef4444, #dc2626); 
}

.estado-justificado { 
    background: linear-gradient(135deg, #f59e0b, #d97706); 
}

.estado-tarde { 
    background: linear-gradient(135deg, #f97316, #ea580c); 
}

.estado-sin-registro { 
    background: #e5e7eb; 
    border: 2px solid #d1d5db; 
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bloque-semana {
    animation: fadeIn 0.3s ease-out;
}

@media print {
    .no-print { display: none !important; }
    .contenido-semana { display: block !important; }
}
</style>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // DATOS DESDE PHP
    // ========================================
    const asistenciasData = @json($asistenciasPlanas);
    const periodosData = @json($statsPorPeriodo);
    const esTutor = {{ auth()->user()->tutor ? 'true' : 'false' }};

    // ========================================
    // ELEMENTOS DOM
    // ========================================
    const contenedorTabla = document.getElementById('contenedor-tabla-asistencias');
    const fechaDesdeInput = document.getElementById('fecha-desde');
    const fechaHastaInput = document.getElementById('fecha-hasta');
    const btnAplicarRango = document.getElementById('aplicar-rango');

    console.log('📊 Datos cargados:', Object.keys(asistenciasData).length, 'asistencias');

    // ========================================
    // UTILIDADES
    // ========================================
    function obtenerColorEstado(estado) {
        const colores = {
            'Presente': 'estado-presente',
            'Ausente': 'estado-ausente',
            'Justificado': 'estado-justificado',
            'Tarde': 'estado-tarde'
        };
        return colores[estado] || 'estado-sin-registro';
    }

    function obtenerNombreDia(fecha) {
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return dias[fecha.getDay()];
    }

    function formatearFecha(fecha) {
        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        return `${fecha.getDate()} ${meses[fecha.getMonth()]}`;
    }

    function getWeekNumber(date) {
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }

    function obtenerRangoFechas(fechaDesde, fechaHasta) {
        const fechas = [];
        const fechaActual = new Date(fechaDesde);
        const fechaFin = new Date(fechaHasta);
        
        while (fechaActual <= fechaFin) {
            // 🔥 EXCLUIR SÁBADOS (6) Y DOMINGOS (0)
            const diaSemana = fechaActual.getDay();
            if (diaSemana !== 0 && diaSemana !== 6) {
                fechas.push(new Date(fechaActual));
            }
            fechaActual.setDate(fechaActual.getDate() + 1);
        }
        
        return fechas;
    }

    function agruparPorSemanas(fechas) {
        const semanas = {};
        const primeraSemanaDelPeriodo = obtenerPrimeraSemanaDelPeriodo();
        
        fechas.forEach(fecha => {
            const numSemana = getWeekNumber(fecha);
            const año = fecha.getFullYear();
            const claveSemana = `${año}-S${numSemana}`;
            
            // Calcular número de semana relativo al período (Semana 1, 2, 3...)
            let numeroRelativo = numSemana - primeraSemanaDelPeriodo + 1;
            
            // Ajustar si cruzamos de año
            if (numeroRelativo <= 0) {
                numeroRelativo += 52; // Aproximación para cambio de año
            }
            
            if (!semanas[claveSemana]) {
                semanas[claveSemana] = {
                    numero: numSemana,
                    numeroRelativo: numeroRelativo,
                    año: año,
                    fechas: []
                };
            }
            
            semanas[claveSemana].fechas.push(fecha);
        });
        
        return semanas;
    }

    function obtenerRangoSemana(fechas) {
        if (fechas.length === 0) return '';
        const primera = fechas[0];
        const ultima = fechas[fechas.length - 1];
        return `${primera.toLocaleDateString('es-ES', {day: '2-digit', month: '2-digit', year: 'numeric'})} - ${ultima.toLocaleDateString('es-ES', {day: '2-digit', month: '2-digit', year: 'numeric'})}`;
    }

    // ========================================
    // INICIALIZAR FECHAS POR DEFECTO
    // ========================================
    function inicializarFechasPorDefecto() {
        const hoy = new Date();
        
        // Establecer la fecha actual en ambos inputs
        fechaDesdeInput.value = hoy.toISOString().split('T')[0];
        fechaHastaInput.value = hoy.toISOString().split('T')[0];
    }
    
    // ========================================
    // OBTENER RANGO DE SEMANA ACTUAL
    // ========================================
    function obtenerSemanaActual() {
        const hoy = new Date();
        const diaSemana = hoy.getDay(); // 0 = Domingo, 1 = Lunes, ..., 6 = Sábado
        
        // Calcular el lunes de la semana actual
        const lunes = new Date(hoy);
        const diasDesdeElLunes = diaSemana === 0 ? -6 : 1 - diaSemana; // Si es domingo, retroceder 6 días
        lunes.setDate(hoy.getDate() + diasDesdeElLunes);
        
        // Calcular el viernes de la semana actual
        const viernes = new Date(lunes);
        viernes.setDate(lunes.getDate() + 4);
        
        return {
            inicio: lunes,
            fin: viernes
        };
    }

    // ========================================
    // OBTENER PRIMERA SEMANA DEL PERIODO
    // ========================================
    function obtenerPrimeraSemanaDelPeriodo() {
        // Buscar el período que contiene la fecha seleccionada
        const fechaDesde = new Date(fechaDesdeInput.value);
        
        for (const stat of periodosData) {
            const fechaInicioPeriodo = new Date(stat.periodo.fecha_inicio);
            const fechaFinPeriodo = new Date(stat.periodo.fecha_fin);
            
            if (fechaDesde >= fechaInicioPeriodo && fechaDesde <= fechaFinPeriodo) {
                // Encontramos el período, calcular número de semana del inicio
                return getWeekNumber(fechaInicioPeriodo);
            }
        }
        
        // Si no encuentra período, usar la primera fecha seleccionada
        return getWeekNumber(fechaDesde);
    }

    // ========================================
    // CREAR TABLA DE ASISTENCIAS POR SEMANAS
    // ========================================
    function crearTablaAsistencias(fechaDesde, fechaHasta) {
        const fechas = obtenerRangoFechas(fechaDesde, fechaHasta);
        
        if (fechas.length === 0) {
            contenedorTabla.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-lg font-medium">No hay fechas seleccionadas</p>
                    <p class="text-sm mt-2">Selecciona un rango de fechas válido</p>
                </div>
            `;
            return;
        }

        const semanas = agruparPorSemanas(fechas);
        const clavesOrdenadas = Object.keys(semanas).sort();
        const primeraSemanaDelPeriodo = obtenerPrimeraSemanaDelPeriodo();
        
        let html = '<div class="space-y-4">';
        
        clavesOrdenadas.forEach((claveSemana, indexSemana) => {
            const semana = semanas[claveSemana];
            const rangoFechas = obtenerRangoSemana(semana.fechas);
            
            // 🔥 La primera semana es la que corresponde al inicio del período
            const isFirstWeek = semana.numero === primeraSemanaDelPeriodo;
            
            // 🔥 ACORDEÓN DE SEMANA
            html += `
                <div class="bloque-semana bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header de la semana (clickeable) -->
                    <div class="header-semana cursor-pointer hover:bg-gray-50 transition-colors" 
                         onclick="toggleSemana('semana-${claveSemana}')">
                        <div class="flex items-center justify-between p-4">
                            <div class="flex items-center space-x-3">
                                <svg id="icon-semana-${claveSemana}" class="w-5 h-5 text-blue-600 transition-transform ${isFirstWeek ? '' : '-rotate-90'}" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">${rangoFechas}</h3>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">${semana.fechas.length} días</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenido de la semana (colapsable) -->
                    <div id="semana-${claveSemana}" class="contenido-semana ${isFirstWeek ? '' : 'hidden'}">
                        <div class="tabla-asistencias">
                            <!-- Header de tabla -->
                            <div class="tabla-asistencias-header">
                                <div style="min-width: 120px;">Fecha</div>
                                <div style="min-width: 100px;">Día</div>
                                <div style="min-width: 80px;">Hora</div>
                                <div style="min-width: 80px;">Tipo</div>
                                <div style="min-width: 120px;">Justificación</div>
                            </div>
            `;
            
            // 🔥 FILAS DE LA SEMANA
            semana.fechas.forEach(fecha => {
                const fechaStr = fecha.toISOString().split('T')[0];
                const asistencia = asistenciasData[fechaStr];
                const nombreDia = obtenerNombreDia(fecha);
                
                html += '<div class="tabla-asistencias-row">';
                
                // Fecha
                html += `<div class="tabla-asistencias-cell celda-fecha">${fechaStr}</div>`;
                
                // Día
                html += `<div class="tabla-asistencias-cell celda-dia">${nombreDia}</div>`;
                
                // Hora
                const hora = asistencia?.hora_registro ? asistencia.hora_registro.substring(0, 5) : '-';
                html += `<div class="tabla-asistencias-cell text-gray-600 text-sm">${hora}</div>`;
                
                // Estado
                if (asistencia) {
                    const claseEstado = obtenerColorEstado(asistencia.estado);
                    html += `
                        <div class="tabla-asistencias-cell celda-asistencia">
                            <div class="indicador-estado ${claseEstado}" 
                                 onclick="mostrarDetalleAsistencia('${fechaStr}')"
                                 title="${asistencia.estado}">
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="tabla-asistencias-cell celda-asistencia">
                            <div class="indicador-estado estado-sin-registro" title="Sin registro"></div>
                        </div>
                    `;
                }
                
                // Justificación
                if (asistencia?.justificacion) {
                    const estadoJustif = asistencia.estado_justificacion || 'pendiente';
                    const colorBadge = {
                        'pendiente': 'bg-yellow-100 text-yellow-800',
                        'aprobada': 'bg-green-100 text-green-800',
                        'rechazada': 'bg-red-100 text-red-800'
                    }[estadoJustif];
                    
                    html += `
                        <div class="tabla-asistencias-cell">
                            <span class="px-2 py-1 rounded text-xs font-medium ${colorBadge}">
                                ${estadoJustif.charAt(0).toUpperCase() + estadoJustif.slice(1)}
                            </span>
                        </div>
                    `;
                } else if (asistencia?.estado === 'Ausente' && esTutor) {
                    html += `
                        <div class="tabla-asistencias-cell">
                            <button onclick="abrirModal('${asistencia.id_asistencia}', '${fechaStr}')" 
                                    class="text-xs text-blue-600 hover:text-blue-800 underline">
                                Justificar
                            </button>
                        </div>
                    `;
                } else {
                    html += `<div class="tabla-asistencias-cell text-gray-400 text-sm">-</div>`;
                }
                
                html += '</div>';
            });
            
            html += `
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        contenedorTabla.innerHTML = html;
        
        console.log('✅ Tabla creada con', Object.keys(semanas).length, 'semanas');
    }

    // ========================================
    // TOGGLE SEMANA (Expandir/Colapsar)
    // ========================================
    window.toggleSemana = function(idSemana) {
        const contenido = document.getElementById(idSemana);
        const icono = document.getElementById(`icon-${idSemana}`);
        
        if (contenido.classList.contains('hidden')) {
            contenido.classList.remove('hidden');
            icono.classList.remove('-rotate-90');
        } else {
            contenido.classList.add('hidden');
            icono.classList.add('-rotate-90');
        }
    };

    // ========================================
    // MOSTRAR DETALLE DE ASISTENCIA
    // ========================================
    window.mostrarDetalleAsistencia = function(fechaStr) {
        const asistencia = asistenciasData[fechaStr];
        if (!asistencia) return;
        
        const fecha = new Date(fechaStr);
        const fechaFormateada = fecha.toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        let detalleHtml = `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">${fechaFormateada}</h3>
                    <button onclick="cerrarDetalle()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-center p-4 rounded-lg bg-gray-50 border">
                        <div class="w-6 h-6 ${obtenerColorEstado(asistencia.estado).replace('estado-', 'bg-')} rounded mr-3"></div>
                        <span class="font-semibold text-lg">${asistencia.estado}</span>
                    </div>
                    
                    ${asistencia.observacion ? `
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-1">Observación</h4>
                            <p class="text-sm text-gray-600">${asistencia.observacion}</p>
                        </div>
                    ` : ''}
                    
                    ${asistencia.justificacion ? `
                        <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg">
                            <h4 class="font-medium mb-1">Justificación</h4>
                            <p class="text-sm mb-2">${asistencia.justificacion}</p>
                            ${asistencia.archivo_justificacion ? `
                                <a href="/asistencias/descargar-justificacion/${asistencia.id_asistencia}" 
                                   class="text-blue-600 text-sm underline">
                                    📎 ${asistencia.archivo_justificacion_original || 'Documento adjunto'}
                                </a>
                            ` : ''}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        const modal = document.createElement('div');
        modal.id = 'modal-detalle';
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        modal.innerHTML = detalleHtml;
        modal.onclick = (e) => { if (e.target === modal) cerrarDetalle(); };
        document.body.appendChild(modal);
    };

    window.cerrarDetalle = function() {
        const modal = document.getElementById('modal-detalle');
        if (modal) modal.remove();
    };

    // ========================================
    // EVENT LISTENERS
    // ========================================
    btnAplicarRango.addEventListener('click', function() {
        const fechaDesde = fechaDesdeInput.value;
        const fechaHasta = fechaHastaInput.value;
        
        if (!fechaDesde || !fechaHasta) {
            alert('Por favor selecciona ambas fechas');
            return;
        }
        
        if (new Date(fechaDesde) > new Date(fechaHasta)) {
            alert('La fecha inicial no puede ser mayor a la fecha final');
            return;
        }
        
        crearTablaAsistencias(fechaDesde, fechaHasta);
    });

    // ========================================
    // FUNCIONES GLOBALES
    // ========================================
    window.abrirModal = function(id, fecha) {
        document.getElementById('asistencia-id').value = id;
        const f = new Date(fecha);
        document.getElementById('fecha-justificacion').textContent = 
            `Fecha: ${f.toLocaleDateString('es-ES', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}`;
        document.getElementById('modal-justificacion')?.classList.remove('hidden');
    };

    window.cerrarModalJustificacion = function() {
        document.getElementById('modal-justificacion')?.classList.add('hidden');
        document.getElementById('motivo-justificacion').value = '';
        removeFile();
    };

    window.removeFile = function() {
        document.getElementById('archivo-justificacion').value = '';
        document.getElementById('file-upload-content').classList.remove('hidden');
        document.getElementById('file-preview').classList.add('hidden');
    };

    window.exportarCalendario = function() {
        const fechaDesde = fechaDesdeInput.value;
        const fechaHasta = fechaHastaInput.value;
        const fechas = obtenerRangoFechas(fechaDesde, fechaHasta);
        
        let csv = 'Fecha,Día,Estado,Hora,Observación,Justificación\n';
        fechas.forEach(fecha => {
            const fechaStr = fecha.toISOString().split('T')[0];
            const a = asistenciasData[fechaStr];
            const dia = obtenerNombreDia(fecha);
            csv += `"${fechaStr}","${dia}","${a?.estado || 'Sin registro'}","${a?.hora_registro || ''}","${a?.observacion || ''}","${a?.justificacion || ''}"\n`;
        });
        
        const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `asistencias_${fechaDesde}_${fechaHasta}.csv`;
        link.click();
    };

    // ========================================
    // MANEJO DE ARCHIVOS
    // ========================================
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('archivo-justificacion');
    
    if (dropZone && fileInput) {
        dropZone.addEventListener('click', () => fileInput.click());
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => {
            dropZone.addEventListener(e, evt => {
                evt.preventDefault();
                evt.stopPropagation();
            });
        });
        
        dropZone.addEventListener('drop', (e) => {
            if (e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
                handleFiles(e.dataTransfer.files[0]);
            }
        });
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files[0]) handleFiles(e.target.files[0]);
        });
    }
    
    function handleFiles(file) {
        if (file.size > 5242880) {
            alert('Archivo muy grande (máx 5MB)');
            return;
        }
        
        const validTypes = [
            'application/pdf', 
            'image/jpeg', 
            'image/png', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (!validTypes.includes(file.type)) {
            alert('Tipo no permitido');
            return;
        }
        
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = (file.size / 1024).toFixed(2) + ' KB';
        document.getElementById('file-upload-content').classList.add('hidden');
        document.getElementById('file-preview').classList.remove('hidden');
    }

    document.getElementById('form-justificacion')?.addEventListener('submit', function(e) {
        const motivo = document.getElementById('motivo-justificacion').value.trim();
        const archivo = document.getElementById('archivo-justificacion').files.length;
        
        if (!motivo) {
            e.preventDefault();
            alert('Debe ingresar el motivo de la justificación');
            return false;
        }
        
        if (!archivo) {
            e.preventDefault();
            alert('Debe adjuntar un documento que respalde la justificación');
            return false;
        }
        
        const btnEnviar = document.getElementById('btn-enviar-justificacion');
        btnEnviar.disabled = true;
        btnEnviar.textContent = 'Enviando...';
    });

    document.getElementById('modal-justificacion')?.addEventListener('click', function(e) {
        if (e.target.id === 'modal-justificacion') cerrarModalJustificacion();
    });

    // ========================================
    // INICIALIZACIÓN
    // ========================================
    inicializarFechasPorDefecto();
    
    // Obtener la semana actual (lunes a viernes)
    const semanaActual = obtenerSemanaActual();
    
    // Crear la tabla mostrando la semana actual por defecto
    crearTablaAsistencias(
        semanaActual.inicio.toISOString().split('T')[0], 
        semanaActual.fin.toISOString().split('T')[0]
    );
    
    console.log('✅ Sistema de asistencias inicializado');
    console.log('📅 Mostrando semana:', semanaActual.inicio.toLocaleDateString('es-ES'), '-', semanaActual.fin.toLocaleDateString('es-ES'));
});
</script>