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
                <div class="space-y-4">
                    @foreach($justificacionesPendientes as $asistencia)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($asistencia->estudiante->persona->name, 0, 1) . substr($asistencia->estudiante->persona->lastname, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-lg">
                                                {{ $asistencia->estudiante->persona->name }} {{ $asistencia->estudiante->persona->lastname }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                Código: {{ $asistencia->estudiante->codigo_estudiante }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-1">Fecha de Ausencia</p>
                                            <p class="font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                                                <span class="text-sm text-gray-600">
                                                    ({{ \Carbon\Carbon::parse($asistencia->fecha)->translatedFormat('l') }})
                                                </span>
                                            </p>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-1">Grado y Sección</p>
                                            <p class="font-medium text-gray-900">
                                                {{ $asistencia->estudiante->matriculas->first()->seccion->grado->nombre_completo ?? 'N/A' }} - 
                                                {{ $asistencia->estudiante->matriculas->first()->seccion->seccion ?? 'N/A' }}
                                            </p>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-1">Período</p>
                                            <p class="font-medium text-gray-900">{{ $asistencia->periodo->nombre }}</p>
                                        </div>
                                        
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-600 mb-1">Solicitada</p>
                                            <p class="font-medium text-gray-900">
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
                                    
                                    <!-- ✅ DOCUMENTO ADJUNTO -->
                                    @if($asistencia->archivo_justificacion)
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <p class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
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
                                                
                                                <div class="flex gap-2">
                                                    <!-- Botón ver/descargar -->
                                                    <a href="{{ route('asistencias.descargar-justificacion', $asistencia->id_asistencia) }}" 
                                                       target="_blank"
                                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Ver
                                                    </a>
                                                    
                                                    <a href="{{ route('asistencias.descargar-justificacion', $asistencia->id_asistencia) }}" 
                                                       download
                                                       class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Descargar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                            <p class="text-sm text-red-700 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                ⚠️ No se adjuntó documento de respaldo
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="ml-6 flex flex-col gap-2">
                                    <form action="{{ route('auxiliar.aprobar-justificacion', $asistencia->id_asistencia) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium shadow-sm flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Aprobar
                                        </button>
                                    </form>
                                    
                                    <button onclick="mostrarModalRechazo({{ $asistencia->id_asistencia }}, '{{ $asistencia->estudiante->persona->name }} {{ $asistencia->estudiante->persona->lastname }}')" 
                                            class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium shadow-sm flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Rechazar
                                    </button>
                                    
                                    <!-- Botón para ver detalles completos -->
                                    <button onclick="verDetalles({{ $asistencia->id_asistencia }})" 
                                            class="w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Ver Detalles
                                    </button>
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

<!-- Modal de Detalles -->
<div id="modal-detalles" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Detalles de la Justificación</h3>
                <button type="button" onclick="cerrarModalDetalles()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div id="contenido-detalles">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script>
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

function verDetalles(asistenciaId) {
    // Aquí puedes agregar lógica para mostrar más detalles si es necesario
    console.log('Ver detalles de asistencia:', asistenciaId);
}

function cerrarModalDetalles() {
    document.getElementById('modal-detalles').classList.add('hidden');
}

// Cerrar modales al hacer clic fuera
document.getElementById('modal-rechazo').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalRechazo();
    }
});

document.getElementById('modal-detalles').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalDetalles();
    }
});
</script>
@endsection