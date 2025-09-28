<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Tutor - Bruning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.4)' },
                            '100%': { boxShadow: '0 0 30px rgba(59, 130, 246, 0.6)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-image: url('/images/fachada-bruning2.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3), 0 0 20px rgba(59, 130, 246, 0.2);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: float-particles 20s infinite linear;
        }
        
        @keyframes float-particles {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="min-h-screen relative overflow-hidden">
    <!-- Partículas flotantes -->
    <div class="particles">
        <div class="particle w-2 h-2" style="left: 10%; animation-delay: 0s; animation-duration: 15s;"></div>
        <div class="particle w-3 h-3" style="left: 20%; animation-delay: 2s; animation-duration: 18s;"></div>
        <div class="particle w-1 h-1" style="left: 30%; animation-delay: 4s; animation-duration: 20s;"></div>
        <div class="particle w-2 h-2" style="left: 40%; animation-delay: 6s; animation-duration: 16s;"></div>
        <div class="particle w-1 h-1" style="left: 50%; animation-delay: 8s; animation-duration: 22s;"></div>
        <div class="particle w-3 h-3" style="left: 60%; animation-delay: 10s; animation-duration: 17s;"></div>
        <div class="particle w-2 h-2" style="left: 70%; animation-delay: 12s; animation-duration: 19s;"></div>
        <div class="particle w-1 h-1" style="left: 80%; animation-delay: 14s; animation-duration: 21s;"></div>
        <div class="particle w-2 h-2" style="left: 90%; animation-delay: 16s; animation-duration: 18s;"></div>
    </div>

    <!-- Overlay para mejor contraste con la imagen -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-blue-800/50 to-blue-900/70 z-10"></div>

    <div class="relative z-20 flex items-center justify-center min-h-screen p-4">
        <div class="glass-effect bg-amber-50 p-8 rounded-3xl shadow-2xl w-full max-w-2xl relative animate-fade-in">
            <!-- Logo con efecto flotante -->
            <div class="absolute -top-16 left-1/2 transform -translate-x-1/2 animate-float">
                <div class="relative">
                    <img src="/images/logo-bruning1.png" alt="Logo Bruning"
                        class="rounded-full h-32 w-32 border-4 border-gray-300 shadow-2xl animate-glow">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 opacity-20 animate-pulse-slow"></div>
                </div>
            </div>

            <!-- Título con animación -->
            <div class="text-center mt-16 mb-8">
                <h2 class="text-3xl font-bold text-gray-500 mb-2 animate-slide-up">Registro de Tutor</h2>
                <p class="text-gray-400/80 text-sm animate-slide-up" style="animation-delay: 0.2s">Completa tus datos para registrarte como tutor</p>
            </div>

            <!-- Mensajes de estado con animaciones -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-500/20 backdrop-blur border border-green-400/30 text-green-400 rounded-2xl animate-slide-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-500/20 backdrop-blur border border-red-400/30 text-red-400 rounded-2xl animate-slide-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/20 backdrop-blur border border-red-400/30 text-red-400 rounded-2xl animate-slide-up">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Formulario de registro -->
            <form action="{{ route('tutor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Campo Nombre -->
                    <div class="relative animate-slide-up" style="animation-delay: 0.3s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" name="nombre" id="nombre" placeholder="Nombre"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300"
                            required>
                    </div>

                    <!-- Campo Apellidos -->
                    <div class="relative animate-slide-up" style="animation-delay: 0.4s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300"
                            required>
                    </div>

                    <!-- Campo DNI -->
                    <div class="relative animate-slide-up" style="animation-delay: 0.5s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                        </div>
                        <input type="text" name="dni" id="dni" placeholder="DNI" maxlength="8"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300"
                            required>
                    </div>

                    <!-- Campo Sexo -->
                    <div class="relative animate-slide-up" style="animation-delay: 0.6s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <select name="sexo" id="sexo"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300 appearance-none"
                            required>
                            <option value="" class="text-gray-800">Seleccione Sexo</option>
                            <option value="masculino" class="text-gray-800">Masculino</option>
                            <option value="femenino" class="text-gray-800">Femenino</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Campo Estado Civil -->
                    <div class="relative animate-slide-up" style="animation-delay: 0.7s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-4a2 2 0 11-4 0 2 2 0 014 0zM6 20h4"></path>
                            </svg>
                        </div>
                        <select name="estado_civil" id="estado_civil"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300 appearance-none"
                            required>
                            <option value="" class="text-gray-800">Seleccione Estado Civil</option>
                            <option value="soltero" class="text-gray-800">Soltero(a)</option>
                            <option value="casado" class="text-gray-800">Casado(a)</option>
                            <option value="divorciado" class="text-gray-800">Divorciado(a)</option>
                            <option value="viudo" class="text-gray-800">Viudo(a)</option>
                            <option value="conviviente" class="text-gray-800">Conviviente</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Campo Celular -->
                    <div class="relative animate-slide-up" style="animation-delay: 0.8s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input type="text" name="celular" id="celular" placeholder="Número de Celular" maxlength="9"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300"
                            required>
                    </div>

                    <!-- Campo Correo -->
                    <div class="relative animate-slide-up" style="animation-delay: 0.9s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input type="email" name="correo" id="correo" placeholder="Correo Electrónico"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300"
                            required>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="relative animate-slide-up" style="animation-delay: 1.0s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" placeholder="Contraseña"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300"
                            required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-400 transition-colors">
                                <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Campo Fecha de Nacimiento -->
                    <div class="relative animate-slide-up" style="animation-delay: 1.1s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300"
                            required>
                    </div>

                    <!-- Campo Dirección -->
                    <div class="relative animate-slide-up" style="animation-delay: 1.2s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="direccion" id="direccion" placeholder="Dirección"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300"
                            required>
                    </div>

                    <!-- Campo Foto -->
                    <div class="md:col-span-2 relative animate-slide-up" style="animation-delay: 1.3s">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="file" name="foto" id="foto" accept="image/*"
                            class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-600/20 rounded-2xl text-gray-400 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-8 gap-4 animate-slide-up" style="animation-delay: 1.4s">
                    <a href="{{route('login')}}" class="text-blue-300 hover:text-blue-500 text-sm transition-colors">
                        ¿Ya tienes cuenta? Iniciar sesión
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto btn-gradient text-white font-semibold px-8 py-3 rounded-2xl shadow-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300/50">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Registrar
                        </span>
                    </button>
                </div>
            </form>

            <!-- Indicador de carga -->
            <div id="loading" class="hidden absolute inset-0 bg-black/50 backdrop-blur-sm rounded-3xl flex items-center justify-center">
                <div class="flex flex-col items-center text-gray-400">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-600"></div>
                    <p class="mt-4 text-sm">Registrando tutor...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle para mostrar/ocultar contraseña
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        // Mostrar indicador de carga al enviar formulario
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('loading').classList.remove('hidden');
        });

        // Efecto de partículas adicionales en hover
        const glassEffect = document.querySelector('.glass-effect');
        if (glassEffect) {
            glassEffect.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.3)';
            });

            glassEffect.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2)';
            });
        }

        // Animación de entrada de los inputs
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach((input, index) => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>

</html>