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

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-red-700 font-medium mb-2">Errores en el formulario:</p>
                        <ul class="list-disc list-inside text-red-600 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
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

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Filtros</h2>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="mostrar-fines-semana" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                        <span class="ml-2 text-sm text-gray-700">Mostrar fines de semana</span>
                    </label>
                    <select id="vista-calendario" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="año">Vista Anual</option>
                        <option value="periodo">Por Períodos</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                    <select id="filtro-periodo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">Todos los períodos</option>
                        @foreach($statsPorPeriodo as $stat)
                            <option value="{{ $stat['periodo']['id_periodo'] }}">{{ $stat['periodo']['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="filtro-estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">Todos los estados</option>
                        <option value="Presente">Presente</option>
                        <option value="Ausente">Ausente</option>
                        <option value="Justificado">Justificado</option>
                        <option value="Tarde">Tarde</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                    <select id="filtro-año" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <!-- Se llenará dinámicamente -->
                    </select>
                </div>

                <div class="flex items-end">
                    <button id="limpiar-filtros" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                        Limpiar filtros
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
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded mr-2"></div>
                    <span class="text-gray-700">Fin de semana</span>
                </div>
            </div>
        </div>

        <!-- Calendario Principal -->
        <div id="contenedor-calendario" class="space-y-8">
            <!-- Será generado dinámicamente por JavaScript -->
        </div>

        <!-- Modal de Justificación - FORM CORREGIDO -->
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Motivo de la Justificación *
                                </label>
                                <textarea 
                                    name="motivo_justificacion" 
                                    id="motivo-justificacion"
                                    rows="4" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Explique detalladamente el motivo de la ausencia..."></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Documento de Respaldo <span class="text-red-600">*</span>
                                </label>
                                
                                <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer">
                                    <input 
                                        type="file" 
                                        name="archivo_justificacion" 
                                        id="archivo-justificacion"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                        required
                                        class="hidden">
                                    
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
                                
                                <p class="text-xs text-red-600 mt-2">
                                    ⚠️ Es obligatorio adjuntar un documento que respalde la justificación
                                </p>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-xs text-yellow-700">
                                    La justificación será revisada por el auxiliar de asistencias. Debe adjuntar un documento válido.
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="cerrarModalJustificacion()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </button>
                            <button type="submit" id="btn-enviar-justificacion" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                Enviar Solicitud
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('archivo-justificacion');
            const fileUploadContent = document.getElementById('file-upload-content');
            const filePreview = document.getElementById('file-preview');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            
            dropZone.addEventListener('click', () => fileInput.click());
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('border-blue-500', 'bg-blue-50');
                });
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('border-blue-500', 'bg-blue-50');
                });
            });
            
            dropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFiles(files[0]);
                }
            });
            
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleFiles(e.target.files[0]);
                }
            });
            
            function handleFiles(file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. Máximo 5MB.');
                    fileInput.value = '';
                    return;
                }
                
                const validTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg', 
                                'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!validTypes.includes(file.type)) {
                    alert('Tipo de archivo no permitido.');
                    fileInput.value = '';
                    return;
                }
                
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileUploadContent.classList.add('hidden');
                filePreview.classList.remove('hidden');
            }
            
            function removeFile() {
                fileInput.value = '';
                fileUploadContent.classList.remove('hidden');
                filePreview.classList.add('hidden');
            }
            
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            // ✅ AGREGAR VALIDACIÓN ANTES DE ENVIAR
            document.getElementById('form-justificacion').addEventListener('submit', function(e) {
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
                
                // Deshabilitar botón para evitar doble envío
                const btnEnviar = document.getElementById('btn-enviar-justificacion');
                btnEnviar.disabled = true;
                btnEnviar.textContent = 'Enviando...';
                
                console.log('✅ Formulario enviado correctamente');
            });
        </script>
        @endif
        
        <!-- Panel de Información del Día Seleccionado -->
        <div id="panel-informacion" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-96 overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 id="titulo-informacion" class="text-lg font-semibold text-gray-900"></h3>
                        <button onclick="cerrarPanelInformacion()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    <div id="contenido-informacion">
                        <!-- Contenido dinámico -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos desde PHP
    const asistenciasData = @json($asistenciasPlanas);
    const periodosData = @json($statsPorPeriodo);
    const esTutor = {{ auth()->user()->tutor ? 'true' : 'false' }};
    const asistenciasPorFecha = asistenciasData;

    // Elementos DOM
    const contenedorCalendario = document.getElementById('contenedor-calendario');
    const filtros = {
        periodo: document.getElementById('filtro-periodo'),
        estado: document.getElementById('filtro-estado'),
        año: document.getElementById('filtro-año'),
        vistaTipo: document.getElementById('vista-calendario')
    };
    const mostrarFinesSemana = document.getElementById('mostrar-fines-semana');
    const limpiarFiltros = document.getElementById('limpiar-filtros');
    
    console.log('📊 Datos cargados:', Object.keys(asistenciasData).length, 'asistencias');

    // Utilidades
    function obtenerColorEstado(estado) {
        const colores = {
            'Presente': 'bg-green-500',
            'Ausente': 'bg-red-500',
            'Justificado': 'bg-yellow-500',
            'Tarde': 'bg-orange-500'
        };
        return colores[estado] || 'bg-gray-200';
    }

    function getWeekNumber(date) {
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }

    // Inicializar años
    function inicializarAños() {
        const años = new Set();
        periodosData.forEach(stat => {
            const fechaInicio = new Date(stat.periodo.fecha_inicio);
            const fechaFin = new Date(stat.periodo.fecha_fin);
            años.add(fechaInicio.getFullYear());
            años.add(fechaFin.getFullYear());
        });
        
        filtros.año.innerHTML = '<option value="">Todos los años</option>';
        Array.from(años).sort().forEach(año => {
            const option = document.createElement('option');
            option.value = año;
            option.textContent = año;
            if (año === new Date().getFullYear()) option.selected = true;
            filtros.año.appendChild(option);
        });
    }

    // Crear día del calendario
    function crearDiaCalendario(fecha, asistencia = null, esFindeSemana = false) {
        const div = document.createElement('div');
        const fechaStr = fecha.toISOString().split('T')[0];
        
        let clases = 'w-8 h-8 rounded text-xs flex items-center justify-center font-medium cursor-pointer transition-all duration-200 hover:scale-110 dia-calendario';
        if (esFindeSemana) {
            clases += ' bg-blue-50 border border-blue-200 text-blue-600';
            // ✅ NO ocultar fines de semana por defecto, solo si el checkbox está desmarcado
        } else if (asistencia) {
            clases += ` ${obtenerColorEstado(asistencia.estado)} text-white shadow-sm`;
        } else {
            clases += ' bg-gray-100 border border-gray-300 text-gray-500';
        }
        
        div.className = clases;
        div.textContent = fecha.getDate();
        div.dataset.fecha = fechaStr;
        div.dataset.finSemana = esFindeSemana;
        
        if (asistencia) {
            div.dataset.estado = asistencia.estado;
            div.dataset.asistenciaId = asistencia.id_asistencia || '';
            div.addEventListener('click', () => mostrarInformacionDia(fecha, asistencia));
        }
        
        return div;
    }

    // Crear calendario anual
    function crearCalendarioAnual(año) {
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        const diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        
        contenedorCalendario.innerHTML = '';
        
        for (let mes = 0; mes < 12; mes++) {
            const divMes = document.createElement('div');
            divMes.className = 'bg-white rounded-xl shadow-sm border border-gray-200 p-6';
            
            const tituloMes = document.createElement('h3');
            tituloMes.className = 'text-lg font-semibold text-gray-900 mb-4 text-center';
            tituloMes.textContent = `${meses[mes]} ${año}`;
            divMes.appendChild(tituloMes);
            
            const encabezados = document.createElement('div');
            encabezados.className = 'grid grid-cols-7 gap-1 mb-2';
            diasSemana.forEach(dia => {
                const divDia = document.createElement('div');
                divDia.className = 'text-xs font-medium text-gray-500 text-center p-1';
                divDia.textContent = dia;
                encabezados.appendChild(divDia);
            });
            divMes.appendChild(encabezados);
            
            const gridDias = document.createElement('div');
            gridDias.className = 'grid grid-cols-7 gap-1';
            
            const primerDia = new Date(año, mes, 1);
            const ultimoDia = new Date(año, mes + 1, 0);
            
            // ✅ FIX: Ajustar para que Lunes sea columna 0, Domingo columna 6
            let diaSemanaInicio = primerDia.getDay();
            diaSemanaInicio = diaSemanaInicio === 0 ? 6 : diaSemanaInicio - 1;
            
            // Rellenar espacios vacíos antes del primer día
            for (let i = 0; i < diaSemanaInicio; i++) {
                const divVacio = document.createElement('div');
                divVacio.className = 'w-8 h-8';
                gridDias.appendChild(divVacio);
            }
            
            // Agregar todos los días del mes (INCLUYE SÁBADOS Y DOMINGOS)
            for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
                const fecha = new Date(año, mes, dia);
                const fechaStr = fecha.toISOString().split('T')[0];
                const asistencia = asistenciasPorFecha[fechaStr];
                const esFindeSemana = fecha.getDay() === 0 || fecha.getDay() === 6;
                
                const divDia = crearDiaCalendario(fecha, asistencia, esFindeSemana);
                gridDias.appendChild(divDia);
            }
            
            divMes.appendChild(gridDias);
            contenedorCalendario.appendChild(divMes);
        }
    }

    // Crear vista por períodos
    function crearVistaPeriodos() {
        contenedorCalendario.innerHTML = '';
        
        periodosData.forEach(stat => {
            const periodo = stat.periodo;
            const fechaInicio = new Date(periodo.fecha_inicio);
            const fechaFin = new Date(periodo.fecha_fin);
            
            const divPeriodo = document.createElement('div');
            divPeriodo.className = 'bg-white rounded-xl shadow-sm border border-gray-200 p-6';
            divPeriodo.dataset.periodo = periodo.id_periodo;
            
            const tituloPeriodo = document.createElement('h3');
            tituloPeriodo.className = 'text-lg font-semibold text-gray-900 mb-2';
            tituloPeriodo.textContent = periodo.nombre;
            divPeriodo.appendChild(tituloPeriodo);
            
            const fechasPeriodo = document.createElement('p');
            fechasPeriodo.className = 'text-sm text-gray-600 mb-4';
            fechasPeriodo.textContent = `${fechaInicio.toLocaleDateString('es-ES')} - ${fechaFin.toLocaleDateString('es-ES')}`;
            divPeriodo.appendChild(fechasPeriodo);
            
            // Estadísticas
            const estadisticas = document.createElement('div');
            estadisticas.className = 'grid grid-cols-4 gap-4 mb-6';
            
            ['Presente', 'Ausente', 'Justificado', 'Tarde'].forEach(estado => {
                const divEstat = document.createElement('div');
                const coloresFondo = {'Presente': 'bg-green-50', 'Ausente': 'bg-red-50', 'Justificado': 'bg-yellow-50', 'Tarde': 'bg-orange-50'};
                const coloresTexto = {'Presente': 'text-green-600', 'Ausente': 'text-red-600', 'Justificado': 'text-yellow-600', 'Tarde': 'text-orange-600'};
                
                divEstat.className = `text-center p-3 rounded-lg ${coloresFondo[estado]}`;
                divEstat.innerHTML = `
                    <div class="text-2xl font-bold ${coloresTexto[estado]}">${stat.totales[estado] || 0}</div>
                    <div class="text-sm text-gray-700">${estado}</div>
                    <div class="text-xs ${coloresTexto[estado]} mt-1">${stat.porcentajes[estado] || 0}%</div>
                `;
                estadisticas.appendChild(divEstat);
            });
            divPeriodo.appendChild(estadisticas);
            
            // Calendario del período
            const diasPorMes = {};
            const fechaActual = new Date(fechaInicio);
            
            while (fechaActual <= fechaFin) {
                const mesAño = `${fechaActual.getFullYear()}-${fechaActual.getMonth()}`;
                if (!diasPorMes[mesAño]) diasPorMes[mesAño] = [];
                diasPorMes[mesAño].push(new Date(fechaActual));
                fechaActual.setDate(fechaActual.getDate() + 1);
            }
            
            const calendarioPeriodo = document.createElement('div');
            calendarioPeriodo.className = 'space-y-4';
            
            Object.entries(diasPorMes).forEach(([mesAño, dias]) => {
                const [año, mes] = mesAño.split('-').map(Number);
                const nombreMes = new Date(año, mes).toLocaleDateString('es-ES', {month: 'long', year: 'numeric'});
                
                const divMes = document.createElement('div');
                divMes.className = 'border border-gray-200 rounded-lg p-4';
                
                const tituloMes = document.createElement('h4');
                tituloMes.className = 'font-medium text-gray-900 mb-3 capitalize';
                tituloMes.textContent = nombreMes;
                divMes.appendChild(tituloMes);
                
                const gridDias = document.createElement('div');
                gridDias.className = 'flex flex-wrap gap-1';
                
                dias.forEach(fecha => {
                    const fechaStr = fecha.toISOString().split('T')[0];
                    const asistencia = asistenciasPorFecha[fechaStr];
                    const esFindeSemana = fecha.getDay() === 0 || fecha.getDay() === 6;
                    gridDias.appendChild(crearDiaCalendario(fecha, asistencia, esFindeSemana));
                });
                
                divMes.appendChild(gridDias);
                calendarioPeriodo.appendChild(divMes);
            });
            
            divPeriodo.appendChild(calendarioPeriodo);
            contenedorCalendario.appendChild(divPeriodo);
        });
    }

    // Mostrar información del día
    function mostrarInformacionDia(fecha, asistencia) {
        const panel = document.getElementById('panel-informacion');
        const titulo = document.getElementById('titulo-informacion');
        const contenido = document.getElementById('contenido-informacion');
        
        titulo.textContent = fecha.toLocaleDateString('es-ES', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'});
        
        const estadoNormalizado = (asistencia.estado || '').toLowerCase();
        const esAusente = estadoNormalizado === 'ausente';
        const tieneJustificacion = asistencia.justificacion?.trim();
        const tieneId = asistencia.id_asistencia;
        
        let html = `
            <div class="space-y-4">
                <div class="flex items-center justify-center p-4 rounded-lg bg-gray-50 border">
                    <div class="w-6 h-6 ${obtenerColorEstado(asistencia.estado)} rounded mr-3"></div>
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
                        <h4 class="font-medium mb-1 flex items-center justify-between">
                            <span>Justificación</span>
                            ${asistencia.estado_justificacion === 'pendiente' ? '<span class="text-xs bg-yellow-500 text-white px-2 py-1 rounded">Pendiente</span>' : ''}
                            ${asistencia.estado_justificacion === 'aprobada' ? '<span class="text-xs bg-green-500 text-white px-2 py-1 rounded">Aprobada</span>' : ''}
                            ${asistencia.estado_justificacion === 'rechazada' ? '<span class="text-xs bg-red-500 text-white px-2 py-1 rounded">Rechazada</span>' : ''}
                        </h4>
                        <p class="text-sm mb-2">${asistencia.justificacion}</p>
                        ${asistencia.archivo_justificacion ? `
                            <a href="/asistencias/descargar-justificacion/${asistencia.id_asistencia}" target="_blank" class="text-blue-600 text-sm underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                </svg>
                                ${asistencia.archivo_justificacion_original || 'Documento adjunto'}
                            </a>
                        ` : ''}
                        ${asistencia.fecha_solicitud_justificacion ? `
                            <p class="text-xs text-gray-500 mt-2">Solicitado: ${new Date(asistencia.fecha_solicitud_justificacion).toLocaleString('es-ES')}</p>
                        ` : ''}
                    </div>
                ` : ''}
                
                ${asistencia.motivo_rechazo && asistencia.estado_justificacion === 'rechazada' ? `
                    <div class="bg-red-50 border border-red-200 p-3 rounded-lg">
                        <h4 class="font-medium text-red-900 mb-1">Motivo de Rechazo</h4>
                        <p class="text-sm text-red-700">${asistencia.motivo_rechazo}</p>
                    </div>
                ` : ''}
                
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Información del día</h4>
                    <div class="text-sm text-gray-600">
                        <p>Día: ${fecha.toLocaleDateString('es-ES', {weekday: 'long'})}</p>
                        <p>Fecha: ${fecha.toLocaleDateString('es-ES')}</p>
                        <p>Semana: ${getWeekNumber(fecha)}</p>
                    </div>
                </div>
        `;
        
        // ✅ BOTONES DE JUSTIFICACIÓN COMPLETOS
        if (esTutor && esAusente && !tieneJustificacion && tieneId) {
            html += `
                <button onclick="abrirModal('${asistencia.id_asistencia}', '${fecha.toISOString().split('T')[0]}')" 
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Solicitar Justificación
                </button>
            `;
        } else if (esTutor && tieneJustificacion && asistencia.estado_justificacion === 'pendiente') {
            html += `
                <a href="/asistencias/cancelar-justificacion/${asistencia.id_asistencia}" 
                    class="block w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-center transition-colors">
                    Cancelar Solicitud Pendiente
                </a>
            `;
        } else if (esTutor && tieneJustificacion && asistencia.estado_justificacion === 'rechazada') {
            html += `
                <button onclick="abrirModal('${asistencia.id_asistencia}', '${fecha.toISOString().split('T')[0]}')" 
                    class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Enviar Nueva Justificación
                </button>
            `;
        } else if (!esTutor) {
            html += `
                <div class="bg-gray-100 p-3 rounded-lg text-center">
                    <p class="text-sm text-gray-600">
                        <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Solo los tutores pueden solicitar justificaciones
                    </p>
                </div>
            `;
        } else if (tieneJustificacion && asistencia.estado_justificacion === 'aprobada') {
            html += `
                <div class="bg-green-50 border border-green-200 p-3 rounded-lg text-center">
                    <p class="text-sm text-green-700">
                        <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Ausencia justificada y aprobada
                    </p>
                </div>
            `;
        } else if (!esAusente) {
            html += `
                <div class="bg-blue-50 border border-blue-200 p-3 rounded-lg text-center">
                    <p class="text-sm text-blue-700">
                        <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Solo las ausencias pueden ser justificadas
                    </p>
                </div>
            `;
        }
        
        html += `</div>`;
        contenido.innerHTML = html;
        panel.classList.remove('hidden');
    }

    // Actualizar vista
    function actualizarVista() {
        const tipoVista = filtros.vistaTipo.value;
        const añoSeleccionado = filtros.año.value || new Date().getFullYear();
        
        if (tipoVista === 'año') {
            crearCalendarioAnual(parseInt(añoSeleccionado));
        } else {
            crearVistaPeriodos();
        }
    }

    // Funciones globales
    window.abrirModal = (id, fecha) => {
        document.getElementById('asistencia-id').value = id;
        const f = new Date(fecha);
        document.getElementById('fecha-justificacion').textContent = `Fecha: ${f.toLocaleDateString('es-ES', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}`;
        document.getElementById('modal-justificacion')?.classList.remove('hidden');
        document.getElementById('panel-informacion').classList.add('hidden');
    };

    window.cerrarModalJustificacion = () => {
        document.getElementById('modal-justificacion')?.classList.add('hidden');
        document.getElementById('motivo-justificacion').value = '';
        removeFile();
    };

    window.cerrarPanelInformacion = () => {
        document.getElementById('panel-informacion').classList.add('hidden');
    };

    window.removeFile = () => {
        document.getElementById('archivo-justificacion').value = '';
        document.getElementById('file-upload-content').classList.remove('hidden');
        document.getElementById('file-preview').classList.add('hidden');
    };

    window.exportarCalendario = () => {
        let csv = 'Fecha,Estado,Observación,Justificación\n';
        Object.entries(asistenciasPorFecha).forEach(([fecha, a]) => {
            csv += `"${fecha}","${a.estado}","${a.observacion||''}","${a.justificacion||''}"\n`;
        });
        const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `asistencias_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
    };

    // Event listeners
    filtros.vistaTipo.addEventListener('change', actualizarVista);
    filtros.año.addEventListener('change', actualizarVista);
    
    // ✅ Agregar listener para mostrar/ocultar fines de semana
    mostrarFinesSemana.addEventListener('change', function() {
        document.querySelectorAll('[data-fin-semana="true"]').forEach(dia => {
            dia.style.display = this.checked ? 'flex' : 'none';
        });
    });
    
    limpiarFiltros.addEventListener('click', () => {
        Object.values(filtros).forEach(f => {
            if (f.id !== 'vista-calendario') f.value = f.id === 'filtro-año' ? new Date().getFullYear() : '';
        });
        mostrarFinesSemana.checked = true; // ✅ Por defecto mostrar fines de semana
        actualizarVista();
    });

    // Manejo de archivos
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('archivo-justificacion');
    
    dropZone?.addEventListener('click', () => fileInput.click());
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => {
        dropZone?.addEventListener(e, evt => evt.preventDefault());
    });
    
    dropZone?.addEventListener('drop', e => {
        if (e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            handleFiles(e.dataTransfer.files[0]);
        }
    });
    
    fileInput?.addEventListener('change', e => {
        if (e.target.files[0]) handleFiles(e.target.files[0]);
    });
    
    function handleFiles(file) {
        if (file.size > 5242880) return alert('Archivo muy grande (máx 5MB)');
        const validTypes = ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!validTypes.includes(file.type)) return alert('Tipo no permitido');
        
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = (file.size / 1024).toFixed(2) + ' KB';
        document.getElementById('file-upload-content').classList.add('hidden');
        document.getElementById('file-preview').classList.remove('hidden');
    }

    // Cerrar modales
    document.getElementById('panel-informacion').addEventListener('click', e => {
        if (e.target.id === 'panel-informacion') cerrarPanelInformacion();
    });
    
    document.getElementById('modal-justificacion')?.addEventListener('click', e => {
        if (e.target.id === 'modal-justificacion') cerrarModalJustificacion();
    });

    // Inicializar
    inicializarAños();
    actualizarVista();
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .bg-gray-50 {
        background: white !important;
    }
    
    .shadow-sm, .shadow-2xl {
        box-shadow: none !important;
    }
    
    .border {
        border: 1px solid #d1d5db !important;
    }
    
    .rounded-xl {
        border-radius: 8px !important;
    }
    
    #panel-informacion, #modal-justificacion {
        display: none !important;
    }
    
    .fixed {
        position: relative !important;
    }
    
    .grid {
        display: grid !important;
        break-inside: avoid;
    }
    
    .space-y-8 > * + * {
        margin-top: 2rem !important;
    }
    
    .space-y-4 > * + * {
        margin-top: 1rem !important;
    }
}

@media (max-width: 640px) {
    .grid-cols-7 {
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 2px;
    }
    
    .w-8.h-8 {
        width: 1.5rem;
        height: 1.5rem;
        font-size: 0.6rem;
    }
    
    .grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .grid.grid-cols-1.md\\:grid-cols-4 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

.dia-calendario:hover {
    transform: scale(1.1);
    z-index: 10;
    position: relative;
}

.transition-all {
    transition: all 0.2s ease-in-out;
}

/* Animaciones para los días */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.dia-calendario {
    animation: fadeIn 0.3s ease-out;
}

/* Efectos de hover mejorados */
.dia-calendario:not(.bg-gray-100):hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: scale(1.15) translateY(-2px);
}

/* Indicadores de estado mejorados */
.bg-green-500 {
    background: linear-gradient(135deg, #10b981, #059669);
}

.bg-red-500 {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.bg-yellow-500 {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.bg-orange-500 {
    background: linear-gradient(135deg, #f97316, #ea580c);
}

/* Animación para mensajes */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection