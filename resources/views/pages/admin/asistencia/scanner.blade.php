@extends('layout.admin.plantilla')

@section('contenido')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center justify-center">
                <svg class="w-8 h-8 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
                Escanear Código QR
            </h1>
            <p class="text-gray-600 mt-2">Utilice la cámara para escanear códigos QR de estudiantes</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-5">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Escáner de Códigos QR
                </h2>
            </div>

            <!-- Card Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Scanner Section -->
                    <div class="space-y-6">
                        <div class="relative">
                            <div id="reader" class="w-full h-96 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <div class="text-center text-gray-500 p-8" id="scannerPlaceholder">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    <p class="text-lg font-medium">Cámara no activada</p>
                                    <p class="text-sm">Haga clic en "Iniciar Cámara" para comenzar</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-center space-x-4">
                                <button id="startScanner" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Iniciar Cámara
                                </button>
                                <button id="stopScanner" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium hidden items-center transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                    </svg>
                                    Detener Cámara
                                </button>
                            </div>

                            <!-- Debug Info (opcional - remover en producción) -->
                            <div id="debugInfo" class="mt-4 p-4 bg-gray-100 rounded-lg text-sm text-gray-700 hidden">
                                <h4 class="font-medium mb-2">Información de Debug:</h4>
                                <div id="debugContent"></div>
                            </div>
                        </div>

                        <!-- Camera Permissions Alert -->
                        <div id="permissionAlert" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-yellow-800">
                                        <strong>Permisos de cámara requeridos</strong>. Por favor, permita el acceso a la cámara cuando su navegador lo solicite.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        <div id="errorAlert" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p id="errorMessage" class="text-sm text-red-800"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="space-y-6">
                        <form id="attendanceForm" action="{{ route('asistencia.process-scan') }}" method="POST" class="space-y-4">
                            @csrf
                            {{-- <div>
                                <label for="id_periodo" class="block text-sm font-medium text-gray-700 mb-2">Periodo Académico *</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                                        id="id_periodo" name="id_periodo" required>
                                    <option value="">Seleccione un periodo</option>
                                    @foreach($periodos as $periodo)
                                        <option value="{{ $periodo->id_periodo }}">{{ $periodo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                           <input type="hidden" id="id_periodo" name="id_periodo" value="{{ $periodoActual->id_periodo }}">
                            
                            <div>
                                <label for="qr_code" class="block text-sm font-medium text-gray-700 mb-2">Código QR Escaneado</label>
                                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50" 
                                       id="qr_code" name="qr_code" readonly placeholder="El código aparecerá aquí al escanear">
                            </div>
                            
                            <div id="studentInfo" class="p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                                <h6 class="font-medium text-blue-800 mb-2">Estudiante Detectado:</h6>
                                <p id="studentName" class="text-blue-900 font-semibold"></p>
                            </div>
                            
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center" 
                                    disabled id="submitBtn">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Registrar Asistencia
                            </button>
                        </form>
                        
                        <!-- Scan Results -->
                        <div class="mt-6">
                            <h6 class="font-medium text-gray-700 mb-3">Resultado del Escaneo:</h6>
                            <div id="scanResult" class="space-y-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Instrucciones de Uso
            </h3>
            <ul class="space-y-2 text-blue-700">
                <li class="flex items-start">
                    <span class="bg-blue-200 text-blue-800 rounded-full p-1 mr-3 mt-0.5 flex-shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <span>Haga clic en "Iniciar Cámara" y permita el acceso a la cámara cuando se le solicite</span>
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-200 text-blue-800 rounded-full p-1 mr-3 mt-0.5 flex-shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <span>Enfoca el código QR del estudiante con la cámara</span>
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-200 text-blue-800 rounded-full p-1 mr-3 mt-0.5 flex-shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <span>Seleccione el período académico y haga clic en "Registrar Asistencia"</span>
                </li>
            </ul>
        </div>
    </div>
</div>
<style>
#reader {
    min-height: 384px;
    position: relative;
    border-radius: 8px;
}

#reader video {
    border-radius: 8px;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

#reader__dashboard_section {
    padding: 16px;
    background: white;
    border-radius: 8px;
    margin: 8px;
}

#reader__dashboard_section_csr {
    text-align: center;
}

#reader__camera_permission_button {
    background-color: #059669;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
}

#reader__camera_permission_button:hover {
    background-color: #047857;
}

#reader__scan_region {
    border-radius: 8px;
}

#reader__scan_region img {
    border-radius: 8px;
}

#reader__camera_selection {
    margin: 10px 0;
}

#reader__dashboard_section select {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #d1d5db;
    background: white;
}

.hidden {
    display: none !important;
}

/* Estilos para mejor UX */
#reader__dashboard_section_swaplink {
    color: #059669;
    text-decoration: none;
}

#reader__dashboard_section_swaplink:hover {
    color: #047857;
    text-decoration: underline;
}

/* Animaciones suaves */
.transition-all {
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #reader {
        min-height: 300px;
    }
    
    .grid-cols-1.lg\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('script')
<!-- Incluir Html5QrCode desde CDN más estable -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner = null;
let isScanning = false;
let cameras = [];
let currentCameraId = null;

// Debug mode - cambiar a false en producción
const DEBUG = false;

function debugLog(message, data = null) {
    if (DEBUG) {
        console.log(`[QR Scanner Debug] ${message}`, data || '');
        const debugContent = document.getElementById('debugContent');
        if (debugContent) {
            debugContent.innerHTML += `<p><strong>${new Date().toLocaleTimeString()}:</strong> ${message}</p>`;
            document.getElementById('debugInfo').classList.remove('hidden');
        }
    }
}

document.getElementById('startScanner').addEventListener('click', function() {
    debugLog('Botón Iniciar Cámara presionado');
    startCamera();
});

document.getElementById('stopScanner').addEventListener('click', function() {
    debugLog('Botón Detener Cámara presionado');
    stopCamera();
});

// Verificar soporte del navegador
function checkBrowserSupport() {
    debugLog('Verificando soporte del navegador...');
    
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        showError('Su navegador no soporta acceso a la cámara. Use Chrome, Firefox o Safari.');
        return false;
    }

    if (typeof Html5Qrcode === 'undefined') {
        showError('La librería Html5Qrcode no se cargó correctamente. Verifique su conexión a internet.');
        return false;
    }

    debugLog('Navegador soportado ✓');
    return true;
}

// Obtener cámaras disponibles
async function getCameras() {
    try {
        debugLog('Obteniendo lista de cámaras...');
        cameras = await Html5Qrcode.getCameras();
        debugLog(`Cámaras encontradas: ${cameras.length}`, cameras);
        
        if (cameras && cameras.length > 0) {
            // Preferir cámara trasera
            currentCameraId = cameras.find(camera => 
                camera.label.toLowerCase().includes('back') || 
                camera.label.toLowerCase().includes('rear')
            )?.id || cameras[0].id;
            
            debugLog(`Cámara seleccionada: ${currentCameraId}`);
            return true;
        } else {
            showError('No se encontraron cámaras disponibles.');
            return false;
        }
    } catch (err) {
        debugLog('Error obteniendo cámaras', err);
        showError('Error al acceder a las cámaras: ' + err.message);
        return false;
    }
}

// Función para iniciar la cámara - VERSIÓN CORREGIDA
async function startCamera() {
    try {
        debugLog('Iniciando proceso de arranque de cámara...');

        if (!checkBrowserSupport()) {
            return;
        }

        // Mostrar alerta de permisos y ocultar errores
        document.getElementById('permissionAlert').classList.remove('hidden');
        document.getElementById('errorAlert').classList.add('hidden');
        
        // Cambiar estado de botones
        document.getElementById('startScanner').classList.add('hidden');
        document.getElementById('stopScanner').classList.remove('hidden');
        document.getElementById('stopScanner').classList.add('flex');
        
        // Obtener cámaras disponibles
        if (!(await getCameras())) {
            resetScanner();
            return;
        }

        // Limpiar escáner anterior si existe
        if (html5QrcodeScanner && isScanning) {
            debugLog('Limpiando escáner anterior...');
            try {
                await html5QrcodeScanner.stop();
                html5QrcodeScanner.clear();
            } catch (clearError) {
                debugLog('Error limpiando escáner anterior (continuando)', clearError);
            }
        }
        
        // Ocultar placeholder
        document.getElementById('scannerPlaceholder').classList.add('hidden');
        
        // Crear nuevo escáner
        debugLog('Creando nuevo escáner...');
        html5QrcodeScanner = new Html5Qrcode("reader");
        
        // Configuración del escáner mejorada
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            disableFlip: false,
        };

        debugLog('Iniciando escáner con configuración', config);

        // Iniciar el escáner
        await html5QrcodeScanner.start(
            currentCameraId,
            config,
            onScanSuccess,
            onScanFailure
        );
        
        isScanning = true;
        debugLog('Escáner iniciado exitosamente ✓');
        
        // Ocultar alerta después de 3 segundos si todo va bien
        setTimeout(() => {
            if (isScanning) {
                document.getElementById('permissionAlert').classList.add('hidden');
                showSuccess('Cámara activada correctamente. Enfoque un código QR para escanear.');
            }
        }, 3000);
        
    } catch (error) {
        debugLog('Error crítico al iniciar cámara', error);
        
        let errorMessage = 'Error al acceder a la cámara: ';
        
        if (error.name === 'NotAllowedError' || error.message.includes('Permission')) {
            errorMessage += 'Permisos de cámara denegados. Por favor, permita el acceso e intente nuevamente.';
        } else if (error.name === 'NotFoundError') {
            errorMessage += 'No se encontró ninguna cámara disponible.';
        } else if (error.name === 'NotReadableError') {
            errorMessage += 'La cámara está siendo utilizada por otra aplicación.';
        } else if (error.message.includes('OverconstrainedError')) {
            errorMessage += 'La configuración de la cámara no es compatible con su dispositivo.';
        } else {
            errorMessage += error.message || 'Error desconocido. Intente recargar la página.';
        }
        
        showError(errorMessage);
        resetScanner();
    }
}

// Función para detener la cámara
async function stopCamera() {
    try {
        debugLog('Deteniendo cámara...');
        
        if (html5QrcodeScanner && isScanning) {
            await html5QrcodeScanner.stop();
            html5QrcodeScanner.clear();
            isScanning = false;
            debugLog('Cámara detenida exitosamente ✓');
        }
        
        resetScanner();
        showSuccess('Cámara desactivada.');
        
    } catch (error) {
        debugLog('Error al detener cámara', error);
        console.error("Error al detener la cámara:", error);
        resetScanner();
    }
}

// Resetear el escáner a estado inicial
function resetScanner() {
    debugLog('Reseteando escáner...');
    document.getElementById('startScanner').classList.remove('hidden');
    document.getElementById('stopScanner').classList.add('hidden');
    document.getElementById('scannerPlaceholder').classList.remove('hidden');
    document.getElementById('permissionAlert').classList.add('hidden');
    isScanning = false;
}

// Función de éxito al escanear
function onScanSuccess(decodedText, decodedResult) {
    debugLog('QR escaneado exitosamente', { text: decodedText, result: decodedResult });
    
    // Detener el escáner después de leer
    stopCamera();
    
    // Mostrar el código escaneado
   
    
    // Verificar el código con el servidor usando la ruta corta
    const shortCode = decodedText.split('/').pop(); // Extraer solo el código
    document.getElementById('qr_code').value = shortCode;

    fetch(`/qr-scan/${shortCode}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        debugLog('Respuesta del servidor recibida', response.status);
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        debugLog('Datos del servidor', data);
        if (data.success) {
            document.getElementById('studentInfo').classList.remove('hidden');
            document.getElementById('studentName').textContent = 
                `${data.estudiante.nombres} ${data.estudiante.apellidos}`;
            document.getElementById('submitBtn').disabled = false;
            
            showSuccess(`Código QR válido. Estudiante: ${data.estudiante.nombres} ${data.estudiante.apellidos}`);
            checkFormValidity();
        } else {
            showError('Código QR inválido o estudiante no encontrado.');
            document.getElementById('qr_code').value = ''; // Limpiar código inválido
        }
    })
    .catch(error => {
        debugLog('Error en fetch', error);
        console.error('Error:', error);
        showError('Error al verificar el código QR. Verifique su conexión e intente nuevamente.');
        document.getElementById('qr_code').value = ''; // Limpiar código con error
    });
}

// Función de fallo al escanear
function onScanFailure(error) {
    // Solo mostramos errores importantes, no cada intento fallido
    if (error.includes('NotFound') || error.includes('NotFoundException') || error.includes('No MultiFormat Readers')) {
        // Estos son errores normales cuando no hay QR en la imagen
        return;
    }
    debugLog('Error de escaneo', error);
}

// Mostrar mensaje de éxito
function showSuccess(message) {
    debugLog(`Mostrando éxito: ${message}`);
    document.getElementById('errorAlert').classList.add('hidden');
    document.getElementById('scanResult').innerHTML = `
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-800">${message}</span>
            </div>
        </div>
    `;
}

// Mostrar mensaje de error
function showError(message) {
    debugLog(`Mostrando error: ${message}`);
    document.getElementById('errorAlert').classList.remove('hidden');
    document.getElementById('errorMessage').textContent = message;
    
    document.getElementById('scanResult').innerHTML = `
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="text-red-800">${message}</span>
            </div>
        </div>
    `;
}

// Habilitar el botón de enviar solo cuando haya período seleccionado y código QR
document.getElementById('id_periodo').addEventListener('change', checkFormValidity);
document.getElementById('qr_code').addEventListener('input', checkFormValidity);

function checkFormValidity() {
    const periodo = document.getElementById('id_periodo').value;
    const qrCode = document.getElementById('qr_code').value;
    const isValid = periodo && qrCode;
    document.getElementById('submitBtn').disabled = !isValid;
    debugLog(`Validez del formulario: ${isValid}`, { periodo, qrCode });
}

// Manejar envío del formulario
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    const periodo = document.getElementById('id_periodo').value;
    const qrCode = document.getElementById('qr_code').value;
    
    if (!periodo || !qrCode) {
        e.preventDefault();
        showError('Por favor complete todos los campos antes de registrar la asistencia.');
        return;
    }
    
    debugLog('Enviando formulario de asistencia', { periodo, qrCode });
});

// Limpiar el escáner cuando se cambie de página
window.addEventListener('beforeunload', function() {
    if (html5QrcodeScanner && isScanning) {
        html5QrcodeScanner.stop().catch(error => {
            console.error("Error al limpiar el escáner:", error);
        });
    }
});

// Verificación inicial cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    debugLog('DOM cargado, inicializando...');
    checkBrowserSupport();
    
    // Verificar si hay mensajes flash de Laravel
    @if(session('success'))
        showSuccess('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        showError('{{ session('error') }}');
    @endif
    
    @if(session('info'))
        document.getElementById('scanResult').innerHTML = `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-blue-800">{{ session('info') }}</span>
                </div>
            </div>
        `;
    @endif
});

// Función para reiniciar el escáner después de un error
function restartScanner() {
    debugLog('Reiniciando escáner...');
    if (isScanning) {
        stopCamera().then(() => {
            setTimeout(startCamera, 1000);
        });
    } else {
        startCamera();
    }
}

// Agregar botón de reinicio en caso de errores
window.addEventListener('load', function() {
    // Agregar listener para errores no capturados
    window.addEventListener('error', function(e) {
        debugLog('Error global capturado', e.error);
        if (e.error && e.error.message && e.error.message.includes('Html5Qrcode')) {
            showError('Error en el escáner QR. Intente reiniciar la cámara.');
        }
    });
});
</script>


@endsection