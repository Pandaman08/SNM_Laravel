@extends('layout.admin.plantilla')

@section('contenido')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                Código QR del Estudiante
            </h1>
            <p class="text-gray-600 mt-2">Gestión y visualización del código QR para registro de asistencia</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        Información del QR
                    </h2>
                    <span class="bg-blue-800 text-xs font-medium px-3 py-1 rounded-full text-blue-100">
                        {{ $estudiante->codigo_estudiante }}
                    </span>
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- QR Section -->
                    <div class="space-y-6">
                        <div class="text-center">
                            @if($qrExists)
                                <div class="inline-block p-4 bg-white rounded-lg shadow-md border border-gray-200">
                                               <img src="{{ Storage::url($qrPath)  }}" 
                                         alt="Código QR de {{ $estudiante->persona->nombres }}" 
                                         class="w-64 h-64 mx-auto object-contain rounded-lg border-2 border-gray-100 shadow-sm"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjU2IiBoZWlnaHQ9IjI1NiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxyZWN0IHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgZmlsbD0iI2YzZjNmMyIvPjxwYXRoIGQ9Ik02IDZINhY2SDE4VjE4SDZWNlpNOCA4VjE2SDE2VjhIOFpNMTAgMTBIMTRWMTRIMTBWMTBaIiBmaWxsPSIjOTk5Ii8+PC9zdmc+'">
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                                    <svg class="w-12 h-12 text-yellow-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-yellow-700 font-medium">La imagen del código QR no está disponible</p>
                                </div>
                            @endif
                            
                            <div class="mt-6 space-y-2">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    {{ $estudiante->persona->nombres }} {{ $estudiante->persona->apellidos }}
                                </h3>
                                <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                                    <span class="bg-gray-100 px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                        </svg>
                                        {{ $estudiante->codigo_estudiante }}
                                    </span>
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $estudiante->qr_code }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Section -->
                    <div class="space-y-6">
                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 gap-3">
                            <a href="{{ route('asistencia.download-qr', $estudiante->codigo_estudiante) }}" 
                               class="flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Descargar QR
                            </a>

                            <form action="{{ route('asistencia.repair-qr', $estudiante->codigo_estudiante) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Regenerar QR
                                </button>
                            </form>

                            <a href="{{ route('asistencia.generate-form', $estudiante->codigo_estudiante) }}" 
                               class="flex items-center justify-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Generar Nuevo Código
                            </a>

                            <a href="{{ route('home.tutor', $estudiante->codigo_estudiante) }}" 
                               class="flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Volver al Estudiante
                            </a>
                        </div>

                        <!-- Instructions -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Instrucciones de Uso
                            </h4>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <span class="bg-blue-100 text-blue-800 rounded-full p-1 mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <span class="text-gray-700">Descargue el código QR</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="bg-blue-100 text-blue-800 rounded-full p-1 mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <span class="text-gray-700">Imprima el código QR</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="bg-blue-100 text-blue-800 rounded-full p-1 mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <span class="text-gray-700">El estudiante debe presentar el QR para escanear</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="bg-blue-100 text-blue-800 rounded-full p-1 mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <span class="text-gray-700">Use la cámara en la página de escaneo</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-blue-800 text-sm">
                    El código QR fue generado el: <strong>{{ $estudiante->qr_generated_at }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-hover-effect {
        transition: all 0.3s ease;
        transform: translateY(0);
    }
    
    .btn-hover-effect:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .qr-container {
        transition: transform 0.3s ease;
    }
    
    .qr-container:hover {
        transform: scale(1.02);
    }
</style>

<script>
    // Efectos de hover mejorados
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('a, button');
        buttons.forEach(btn => {
            btn.classList.add('btn-hover-effect');
        });
        
        const qrImage = document.querySelector('img');
        if (qrImage) {
            qrImage.parentElement.classList.add('qr-container');
        }
    });
</script>
@endsection