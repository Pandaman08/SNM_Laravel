@extends('layout.admin.plantilla')

@section('contenido')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Justificaciones Pendientes</h1>
                    <p class="text-sm text-gray-600 mt-1">Revise y apruebe o rechace las solicitudes de justificación de ausencias</p>
                </div>
                <a href="{{ route('home.auxiliar') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Volver al Panel
                </a>
            </div>
            
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($justificacionesPendientes->count() > 0)
                <div class="space-y-3">
                    @foreach($justificacionesPendientes as $index => $asistencia)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <!-- HEADER COLAPSABLE -->
                            <div class="header-justificacion cursor-pointer bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 transition-colors p-4"
                                 onclick="toggleJustificacion('justificacion-{{ $asistencia->id_asistencia }}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 flex-1">
                                        <!-- Icono de expansión -->
                                        <svg id="icon-justificacion-{{ $asistencia->id_asistencia }}" 
                                             class="w-5 h-5 text-blue-600 transition-transform {{ $index === 0 ? '' : '-rotate-90' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        
                                        <!-- Avatar -->
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($asistencia->estudiante->persona->name, 0, 1) . substr($asistencia->estudiante->persona->lastname, 0, 1)) }}
                                        </div>
                                        
                                        <!-- Nombre y código -->
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900">
                                                {{ $asistencia->estudiante->persona->name }} {{ $asistencia->estudiante->persona->lastname }}
                                            </h3>
                                            <p class="text-xs text-gray-600">
                                                Código: {{ $asistencia->estudiante->codigo_estudiante }} • 
                                                {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        
                                        <!-- Badge de estado -->
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                            Pendiente
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- CONTENIDO COLAPSABLE -->
                            <div id="justificacion-{{ $asistencia->id_asistencia }}" 
                                 class="contenido-justificacion {{ $index === 0 ? '' : 'hidden' }}">
                                <div class="p-6 bg-white">
                                    <!-- Grid de información -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-1">Fecha de Ausencia</p>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                                            </p>
                                            <p class="text-xs text-gray-600">
                                                {{ \Carbon\Carbon::parse($asistencia->fecha)->translatedFormat('l') }}
                                            </p>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-1">Grado y Sección</p>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ $asistencia->estudiante->matriculas->first()->seccion->grado->nombre_completo ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-600">
                                                Sección {{ $asistencia->estudiante->matriculas->first()->seccion->seccion ?? 'N/A' }}
                                            </p>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-1">Período</p>
                                            <p class="font-medium text-gray-900 text-sm">{{ $asistencia->periodo->nombre }}</p>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-1">Solicitada</p>
                                            <p class="font-medium text-gray-900 text-sm">
                                                {{ $asistencia->fecha_solicitud_justificacion ? \Carbon\Carbon::parse($asistencia->fecha_solicitud_justificacion)->diffForHumans() : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Motivo de justificación -->
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                        <p class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            Motivo de la Justificación:
                                        </p>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $asistencia->justificacion }}</p>
                                    </div>
                                    
                                    <!-- Documento adjunto -->
                                    @if($asistencia->archivo_justificacion)
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                            <p class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                                </svg>
                                                Documento Adjunto:
                                            </p>
                                            <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-blue-300">
                                                <div class="flex items-center gap-3">
                                                    @php
                                                        $extension = pathinfo($asistencia->archivo_justificacion, PATHINFO_EXTENSION);
                                                        $iconColor = 'text-gray-600';
                                                        $bgColor = 'bg-gray-100';
                                                        
                                                        if (in_array($extension, ['pdf'])) {
                                                            $iconColor = 'text-red-600';
                                                            $bgColor = 'bg-red-100';
                                                        } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                                                            $iconColor = 'text-green-600';
                                                            $bgColor = 'bg-green-100';
                                                        } elseif (in_array($extension, ['doc', 'docx'])) {
                                                            $iconColor = 'text-blue-600';
                                                            $bgColor = 'bg-blue-100';
                                                        }
                                                    @endphp
                                                    
                                                    <div class="w-10 h-10 {{ $bgColor }} rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $asistencia->archivo_justificacion_original ?? 'Documento de justificación' }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 uppercase">
                                                            Archivo {{ $extension }}
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <button 
                                                    onclick="event.stopPropagation(); verDocumento('{{ route('asistencias.descargar-justificacion', $asistencia->id_asistencia) }}', '{{ $extension }}', '{{ $asistencia->archivo_justificacion_original ?? 'Documento' }}')"
                                                    class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Ver
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                            <p class="text-sm text-red-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                ⚠️ No se adjuntó documento de respaldo
                                            </p>
                                        </div>
                                    @endif
                                    
                                    <!-- Botones de acción -->
                                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                                        <form action="{{ route('auxiliar.aprobar-justificacion', $asistencia->id_asistencia) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" onclick="event.stopPropagation()" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Aprobar
                                            </button>
                                        </form>
                                        
                                        <button onclick="event.stopPropagation(); mostrarModalRechazo({{ $asistencia->id_asistencia }}, '{{ $asistencia->estudiante->persona->name }} {{ $asistencia->estudiante->persona->lastname }}')" 
                                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Rechazar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $justificacionesPendientes->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay justificaciones pendientes</h3>
                    <p class="text-gray-500">Todas las solicitudes han sido revisadas</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Rechazo -->
<div id="modal-rechazo" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <form id="form-rechazo" method="POST">
            @csrf
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Rechazar Justificación</h3>
                    <button type="button" onclick="cerrarModalRechazo()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                
                <p id="estudiante-nombre" class="text-sm text-gray-600 mb-4"></p>
                
                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del Rechazo *</label>
                <textarea 
                    name="motivo_rechazo" 
                    rows="4" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Explique por qué se rechaza la justificación..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Este motivo será visible para el tutor</p>
                
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="cerrarModalRechazo()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                        Confirmar Rechazo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para visualizar documentos -->
<div id="modal-documento" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-[95vh] flex flex-col">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 id="documento-titulo" class="text-lg font-semibold text-gray-900">Documento</h3>
            <button type="button" onclick="cerrarModalDocumento()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
        <div id="documento-contenido" class="flex-1 overflow-auto p-4 bg-gray-100">
            <!-- Aquí se cargará el contenido -->
        </div>
    </div>
</div>

<style>
/* Estilos para el acordeón de justificaciones */
.header-justificacion {
    transition: all 0.2s ease;
}

.contenido-justificacion {
    transition: all 0.3s ease;
    overflow: hidden;
}

.contenido-justificacion.hidden {
    max-height: 0;
    opacity: 0;
}

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
</style>

<script>
function toggleJustificacion(idJustificacion) {
    const contenido = document.getElementById(idJustificacion);
    const icono = document.getElementById(`icon-${idJustificacion}`);
    
    if (contenido.classList.contains('hidden')) {
        contenido.classList.remove('hidden');
        icono.classList.remove('-rotate-90');
    } else {
        contenido.classList.add('hidden');
        icono.classList.add('-rotate-90');
    }
}

function mostrarModalRechazo(asistenciaId, nombreEstudiante) {
    const modal = document.getElementById('modal-rechazo');
    const form = document.getElementById('form-rechazo');
    const nombreElement = document.getElementById('estudiante-nombre');
    
    form.action = `/auxiliar/rechazar-justificacion/${asistenciaId}`;
    nombreElement.textContent = `Estudiante: ${nombreEstudiante}`;
    modal.classList.remove('hidden');
}

function cerrarModalRechazo() {
    const modal = document.getElementById('modal-rechazo');
    modal.classList.add('hidden');
    document.querySelector('[name="motivo_rechazo"]').value = '';
}

function verDocumento(url, extension, nombreArchivo) {
    const modal = document.getElementById('modal-documento');
    const titulo = document.getElementById('documento-titulo');
    const contenido = document.getElementById('documento-contenido');
    
    titulo.textContent = nombreArchivo;
    
    contenido.innerHTML = '<div class="flex items-center justify-center h-64"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>';
    
    modal.classList.remove('hidden');
    
    const esImagen = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension.toLowerCase());
    const esPDF = extension.toLowerCase() === 'pdf';
    
    if (esImagen) {
        contenido.innerHTML = `
            <div class="flex items-center justify-center">
                <img src="${url}" alt="${nombreArchivo}" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-lg">
            </div>
        `;
    } else if (esPDF) {
        contenido.innerHTML = `
            <iframe src="${url}" class="w-full h-[70vh] rounded-lg shadow-lg" frameborder="0"></iframe>
        `;
    } else {
        contenido.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-gray-600 mb-4">Este tipo de archivo no se puede visualizar directamente.</p>
                <a href="${url}" download class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Descargar Archivo
                </a>
            </div>
        `;
    }
}

function cerrarModalDocumento() {
    const modal = document.getElementById('modal-documento');
    modal.classList.add('hidden');
    document.getElementById('documento-contenido').innerHTML = '';
}

// Cerrar modales al hacer clic fuera
document.getElementById('modal-rechazo').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalRechazo();
    }
});

document.getElementById('modal-documento').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalDocumento();
    }
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalRechazo();
        cerrarModalDocumento();
    }
});
</script>
@endsection