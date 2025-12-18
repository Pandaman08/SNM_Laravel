@extends('layout.admin.plantilla')

@section('contenido')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <button onclick="window.location.href='{{ route('home.auxiliar') }}'" class="p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-3xl font-bold text-white">Escanear Código QR</h1>
                                <p class="text-indigo-100 mt-1" id="subtitulo">Registre la asistencia de los estudiantes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Panel de Escaneo -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Cámara de Escaneo</h3>
                </div>
                <div class="p-6">
                    <!-- Selector de tipo de registro -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tipo de Registro</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" id="btn-entrada" onclick="cambiarTipoRegistro('entrada')" 
                                    class="tipo-registro-btn active flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-medium transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Entrada
                            </button>
                            <button type="button" id="btn-salida" onclick="cambiarTipoRegistro('salida')" 
                                    class="tipo-registro-btn flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-medium transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Salida
                            </button>
                        </div>
                        <input type="hidden" id="tipo_registro" value="entrada">
                    </div>

                    <!-- Selector de periodo -->
                    <div class="mb-6">
                        <label for="id_periodo" class="block text-sm font-medium text-slate-700 mb-2">Período Académico</label>
                        <select id="id_periodo" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            @if($periodoActual)
                                <option value="{{ $periodoActual->id_periodo }}" selected>
                                    {{ $periodoActual->nombre }} (Actual)
                                </option>
                            @endif
                            @foreach($periodos as $periodo)
                                @if(!$periodoActual || $periodo->id_periodo != $periodoActual->id_periodo)
                                    <option value="{{ $periodo->id_periodo }}">
                                        {{ $periodo->nombre }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Vista de cámara -->
                    <div class="relative bg-slate-900 rounded-xl overflow-hidden aspect-video">
                        <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
                        <div class="absolute inset-0 border-2 border-dashed border-white/30 m-8 rounded-lg pointer-events-none"></div>
                        <div id="loading-camera" class="absolute inset-0 flex items-center justify-center bg-slate-900">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-500 border-t-transparent mx-auto mb-4"></div>
                                <p class="text-white">Iniciando cámara...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Controles de cámara -->
                    <div class="mt-4 flex gap-3">
                        <button id="btn-cambiar-camara" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Cambiar Cámara
                        </button>
                    </div>
                </div>
            </div>

            <!-- Panel de Resultados -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Resultado del Escaneo</h3>
                </div>
                <div class="p-6">
                    <!-- Estado inicial -->
                    <div id="estado-inicial" class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">Esperando código QR...</p>
                        <p class="text-sm text-slate-400 mt-2">Coloque el código QR frente a la cámara</p>
                    </div>

                    <!-- Panel de resultado (oculto inicialmente) -->
                    <div id="panel-resultado" class="hidden">
                        <!-- Aquí se mostrará el resultado dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<canvas id="canvas" class="hidden"></canvas>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    let context = canvas.getContext('2d');
    let scanning = true;
    let currentDeviceId = null;
    let devices = [];
    let procesando = false;
    let tipoRegistroActual = 'entrada';

    // Verificar parámetro URL para tipo de registro inicial
    const urlParams = new URLSearchParams(window.location.search);
    const tipoInicial = urlParams.get('tipo');
    if (tipoInicial === 'salida') {
        cambiarTipoRegistro('salida');
    }

    async function obtenerCamaras() {
        const dispositivos = await navigator.mediaDevices.enumerateDevices();
        devices = dispositivos.filter(device => device.kind === 'videoinput');
        return devices;
    }

    async function iniciarCamara(deviceId = null) {
        try {
            const constraints = {
                video: deviceId ? { deviceId: { exact: deviceId } } : { facingMode: 'environment' }
            };

            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = stream;
            
            document.getElementById('loading-camera').style.display = 'none';
            
            if (!deviceId) {
                const track = stream.getVideoTracks()[0];
                const settings = track.getSettings();
                currentDeviceId = settings.deviceId;
            } else {
                currentDeviceId = deviceId;
            }

            requestAnimationFrame(escanear);
        } catch (error) {
            console.error('Error al acceder a la cámara:', error);
            document.getElementById('loading-camera').innerHTML = `
                <div class="text-center text-white p-6">
                    <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-medium mb-2">No se pudo acceder a la cámara</p>
                    <p class="text-sm text-slate-300">Verifique los permisos del navegador</p>
                </div>
            `;
        }
    }

    function cambiarTipoRegistro(tipo) {
        tipoRegistroActual = tipo;
        document.getElementById('tipo_registro').value = tipo;
        
        // Actualizar botones
        document.querySelectorAll('.tipo-registro-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        const btnActivo = document.getElementById(`btn-${tipo}`);
        btnActivo.classList.add('active');
        
        // Actualizar subtítulo
        const subtitulo = tipo === 'entrada' ? 'Registre la entrada de los estudiantes' : 'Registre la salida de los estudiantes';
        document.getElementById('subtitulo').textContent = subtitulo;
    }

    document.getElementById('btn-cambiar-camara').addEventListener('click', async () => {
        const camaras = await obtenerCamaras();
        if (camaras.length > 1) {
            const currentIndex = camaras.findIndex(d => d.deviceId === currentDeviceId);
            const nextIndex = (currentIndex + 1) % camaras.length;
            
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
            }
            
            await iniciarCamara(camaras[nextIndex].deviceId);
        }
    });

    function escanear() {
        if (!scanning) return;

        if (video.readyState === video.HAVE_ENOUGH_DATA && !procesando) {
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                procesarQR(code.data);
            }
        }

        requestAnimationFrame(escanear);
    }

    async function procesarQR(qrCode) {
        if (procesando) return;
        procesando = true;

        // Extraer el código del estudiante de la URL
        let codigoEstudiante = qrCode;
        if (qrCode.includes('/qr-scan/')) {
            const partes = qrCode.split('/qr-scan/');
            codigoEstudiante = partes[1];
        }

        const periodoId = document.getElementById('id_periodo').value;
        const tipoRegistro = document.getElementById('tipo_registro').value;

        if (!periodoId) {
            mostrarError('Por favor seleccione un período académico');
            procesando = false;
            return;
        }

        try {
            const response = await fetch('{{ route("asistencia.process-scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    qr_code: codigoEstudiante,
                    id_periodo: periodoId,
                    tipo_registro: tipoRegistro
                })
            });

            const data = await response.json();

            if (data.success) {
                mostrarExito(data);
            } else {
                mostrarError(data.message);
            }

        } catch (error) {
            console.error('Error:', error);
            mostrarError('Error al procesar el código QR');
        }

        setTimeout(() => {
            procesando = false;
        }, 3000);
    }

    function mostrarExito(data) {
        const estadoColor = {
            'Presente': 'bg-green-100 border-green-200 text-green-800',
            'Tarde': 'bg-yellow-100 border-yellow-200 text-yellow-800',
            'Ausente': 'bg-red-100 border-red-200 text-red-800',
            'Justificado': 'bg-blue-100 border-blue-200 text-blue-800'
        };

        const iconColor = {
            'Presente': 'text-green-600',
            'Tarde': 'text-yellow-600',
            'Ausente': 'text-red-600',
            'Justificado': 'text-blue-600'
        };

        const estudiante = data.estudiante;
        const color = estadoColor[estudiante.estado] || 'bg-gray-100 border-gray-200 text-gray-800';
        const icon = iconColor[estudiante.estado] || 'text-gray-600';

        document.getElementById('estado-inicial').classList.add('hidden');
        document.getElementById('panel-resultado').classList.remove('hidden');
        document.getElementById('panel-resultado').innerHTML = `
            <div class="space-y-4">
                <!-- Icono de éxito -->
                <div class="flex items-center justify-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <!-- Mensaje de éxito -->
                <div class="text-center">
                    <p class="text-lg font-semibold text-slate-900">${data.message}</p>
                </div>

                <!-- Información del estudiante -->
                <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Estudiante:</span>
                        <span class="font-medium text-slate-900">${estudiante.nombres} ${estudiante.apellidos}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Código:</span>
                        <span class="font-medium text-slate-900">${estudiante.codigo}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Grado/Sección:</span>
                        <span class="font-medium text-slate-900">${estudiante.grado} - ${estudiante.seccion}</span>
                    </div>
                    ${estudiante.hora_entrada ? `
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Hora de Entrada:</span>
                            <span class="font-medium text-slate-900">${estudiante.hora_entrada}</span>
                        </div>
                    ` : ''}
                    ${estudiante.hora_salida ? `
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Hora de Salida:</span>
                            <span class="font-medium text-slate-900">${estudiante.hora_salida}</span>
                        </div>
                    ` : ''}
                </div>

                <!-- Estado -->
                <div class="text-center">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-medium border-2 ${color}">
                        ${estudiante.estado}
                    </span>
                </div>

                ${estudiante.observacion ? `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-800">${estudiante.observacion}</p>
                    </div>
                ` : ''}
            </div>
        `;

        // Reproducir sonido de éxito (opcional)
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBDGH0fPTgjMGHm7A7+OZSA0PUqzn77BfGAk+ltryxHkqBSd+y/DglEILE2K36+ynVBULSpzd8cJxKwYwddDz1YU1Bx9xu+3mnEoPEVWu5/CyYxsKPZPY88d8LQYnf8rw4JdECxNiuO3uqlYVDEyb3fLFcywHMHPQ89WFNQcfb7vt6J1LDxJWruXwsmMbCj2T2PPHfC0GJ3/K8OCXRAsSYrjt7qpWFQxMm93yxXMsBzBz0PPVhTUHHm+77eidSw8SVq7l8LJjGwo9k9jzx3wtBid/yvDgl0QLE2K47e6qVhUMTJvd8sVzLAcwc9Dz1YU1Bx5vu+3onUsPElau5fCyYxsKPZPY88d8LQYnf8rw4JdECxNiuO3uqlYVDEyb3fLFcywHMHPQ89WFNQcfb7vt6J1LDxJWruXwsmMbCj2T2PPHfC0GJ3/K8OCXRAsTYrjt7qpWFQxMm93yxXMsBzBz0PPVhTUHHm+77eidSw8SVq7l8LJjGwo9k9jzx3wtBid/yvDgl0QLE2K47e6qVhUMTJvd8sVzLAcwc9Dz1YU1Bx9vu+3onUsPElau5fCyYxsKPZPY88d8LQYnf8rw4JdECxNiuO3uqlYVDEyb3fLFcywHMHPQ89WFNQcfb7vt6J1LDxJWruXwsmMbCj2T2PPHfC0GJ3/K8OCXRAsTYrjt7qpWFQxMm93yxXMsBzBz0PPVhTUHHm+77eidSw8SVq7l8LJjGwo9k9jzx3wtBid/yvDgl0QLE2K47e6qVhUMTJvd8sVzLAcwc9Dz1YU1Bx9vu+3onUsPElau5fCyYxsKPZPY88d8LQYnf8rw4JdECxNiuO3uqlYVDEyb3fLFcywHMHPQ89WFNQcfb7vt6J1LDxJWruXwsmMbCj2T2PPHfC0GJ3/K8OCXRAsTYrjt7qpWFQ==');
        audio.play().catch(() => {});

        setTimeout(() => {
            document.getElementById('estado-inicial').classList.remove('hidden');
            document.getElementById('panel-resultado').classList.add('hidden');
        }, 5000);
    }

    function mostrarError(mensaje) {
        document.getElementById('estado-inicial').classList.add('hidden');
        document.getElementById('panel-resultado').classList.remove('hidden');
        document.getElementById('panel-resultado').innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-red-600 mb-2">Error</p>
                <p class="text-slate-600">${mensaje}</p>
            </div>
        `;

        setTimeout(() => {
            document.getElementById('estado-inicial').classList.remove('hidden');
            document.getElementById('panel-resultado').classList.add('hidden');
        }, 3000);
    }

    // Iniciar cámara al cargar la página
    iniciarCamara();
    // Agregar al final del archivo scanner.blade.php en la sección de script

    function mostrarExito(data) {
        const estadoColor = {
            'Presente': 'bg-green-100 border-green-200 text-green-800',
            'Tarde': 'bg-yellow-100 border-yellow-200 text-yellow-800',
            'Ausente': 'bg-red-100 border-red-200 text-red-800',
            'Justificado': 'bg-blue-100 border-blue-200 text-blue-800'
        };

        const iconColor = {
            'Presente': 'text-green-600',
            'Tarde': 'text-yellow-600',
            'Ausente': 'text-red-600',
            'Justificado': 'text-blue-600'
        };

        const estudiante = data.estudiante;
        const color = estadoColor[estudiante.estado] || 'bg-gray-100 border-gray-200 text-gray-800';
        const icon = iconColor[estudiante.estado] || 'text-gray-600';

        // Formatear hora de entrada
        let horaEntradaFormateada = '-';
        if (estudiante.hora_entrada) {
            const parts = estudiante.hora_entrada.split(':');
            const date = new Date();
            date.setHours(parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2]));
            horaEntradaFormateada = date.toLocaleTimeString('es-ES', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            });
        }

        // Formatear hora de salida
        let horaSalidaFormateada = '-';
        if (estudiante.hora_salida) {
            const parts = estudiante.hora_salida.split(':');
            const date = new Date();
            date.setHours(parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2]));
            horaSalidaFormateada = date.toLocaleTimeString('es-ES', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            });
        }

        // Formatear horarios de la sección
        let horarioEntradaSeccion = '-';
        let horarioSalidaSeccion = '-';
        if (estudiante.horario_seccion) {
            if (estudiante.horario_seccion.entrada) {
                const parts = estudiante.horario_seccion.entrada.split(':');
                const date = new Date();
                date.setHours(parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2]));
                horarioEntradaSeccion = date.toLocaleTimeString('es-ES', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true 
                });
            }
            if (estudiante.horario_seccion.salida) {
                const parts = estudiante.horario_seccion.salida.split(':');
                const date = new Date();
                date.setHours(parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2]));
                horarioSalidaSeccion = date.toLocaleTimeString('es-ES', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true 
                });
            }
        }

        document.getElementById('estado-inicial').classList.add('hidden');
        document.getElementById('panel-resultado').classList.remove('hidden');
        document.getElementById('panel-resultado').innerHTML = `
            <div class="space-y-4">
                <!-- Icono de éxito -->
                <div class="flex items-center justify-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <!-- Mensaje de éxito -->
                <div class="text-center">
                    <p class="text-lg font-semibold text-slate-900">${data.message}</p>
                </div>

                <!-- Información del estudiante -->
                <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Estudiante:</span>
                        <span class="font-medium text-slate-900">${estudiante.nombres} ${estudiante.apellidos}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Código:</span>
                        <span class="font-medium text-slate-900">${estudiante.codigo}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Grado/Sección:</span>
                        <span class="font-medium text-slate-900">${estudiante.grado} - ${estudiante.seccion}</span>
                    </div>
                </div>

                <!-- Horarios -->
                <div class="border-t border-slate-200 pt-4">
                    <h4 class="font-semibold text-slate-900 mb-3">Registro de Horarios</h4>
                    <div class="space-y-2">
                        ${estudiante.hora_entrada ? `
                            <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="text-sm text-slate-600">Entrada:</span>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-green-700">${horaEntradaFormateada}</div>
                                    <div class="text-xs text-slate-500">Programada: ${horarioEntradaSeccion}</div>
                                </div>
                            </div>
                        ` : ''}
                        ${estudiante.hora_salida ? `
                            <div class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="text-sm text-slate-600">Salida:</span>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-blue-700">${horaSalidaFormateada}</div>
                                    <div class="text-xs text-slate-500">Programada: ${horarioSalidaSeccion}</div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Estado -->
                <div class="text-center">
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-medium border-2 ${color}">
                        ${estudiante.estado}
                    </span>
                </div>

                ${estudiante.observacion ? `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-blue-800">${estudiante.observacion}</p>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;

        // Reproducir sonido de éxito
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBDGH0fPTgjMGHm7A7+OZSA0PUqzn77BfGAk+ltryxHkqBSd+y/DglEILE2K36+ynVBULSpzd8cJxKwYwddDz1YU1Bx9xu+3mnEoPEVWu5/CyYxsKPZPY88d8LQYnf8rw4JdECxNiuO3uqlYVDEyb3fLFcywHMHPQ89WFNQcfb7vt6J1LDxJWruXwsmMbCj2T2PPHfC0GJ3/K8OCXRAsSYrjt7qpWFQxMm93yxXMsBzBz0PPVhTUHHm+77eidSw8SVq7l8LJjGwo9k9jzx3wtBid/yvDgl0QLE2K47e6qVhUMTJvd8sVzLAcwc9Dz1YU1Bx5vu+3onUsPElau5fCyYxsKPZPY88d8LQYnf8rw4JdECxNiuO3uqlYVDEyb3fLFcywHMHPQ89WFNQcfb7vt6J1LDxJWruXwsmMbCj2T2PPHfC0GJ3/K8OCXRAsTYrjt7qpWFQxMm93yxXMsBzBz0PPVhTUHHm+77eidSw8SVq7l8LJjGwo9k9jzx3wtBid/yvDgl0QLE2K47e6qVhUMTJvd8sVzLAcwc9Dz1YU1Bx9vu+3onUsPElau5fCyYxsKPZPY88d8LQYnf8rw4JdECxNiuO3uqlYVDEyb3fLFcywHMHPQ89WFNQcfb7vt6J1LDxJWruXwsmMbCj2T2PPHfC0GJ3/K8OCXRAsTYrjt7qpWFQxMm93yxXMsBzBz0PPVhTUHHm+77eidSw8SVq7l8LJjGwo9k9jzx3wtBid/yvDgl0QLE2K47e6qVhUMTJvd8sVzLAcwc9Dz1YU1Bx9vu+3onUsPElau5fCyYxsKPZPY88d8LQYnf8rw4JdECxNiuO3uqlYVDEyb3fLFcywHMHPQ89WFNQcfb7vt6J1LDxJWruXwsmMbCj2T2PPHfC0GJ3/K8OCXRAsTYrjt7qpWFQ==');
        audio.play().catch(() => {});

        setTimeout(() => {
            document.getElementById('estado-inicial').classList.remove('hidden');
            document.getElementById('panel-resultado').classList.add('hidden');
        }, 5000);
    }
</script>

<style>
    .tipo-registro-btn {
        border: 2px solid #e2e8f0;
        background: white;
        color: #64748b;
    }
    
    .tipo-registro-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    
    .tipo-registro-btn.active {
        border-color: #6366f1;
        background: #6366f1;
        color: white;
    }
    
    #btn-entrada.active {
        background: linear-gradient(135deg, #10b981, #059669);
        border-color: #10b981;
    }
    
    #btn-salida.active {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-color: #3b82f6;
    }
</style>
@endsection