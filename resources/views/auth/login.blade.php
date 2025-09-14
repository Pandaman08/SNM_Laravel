<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Bruning</title>
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
        <div class="glass-effect bg-amber-50 p-8 rounded-3xl shadow-2xl w-full max-w-md relative animate-fade-in">
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
                <h2 class="text-3xl font-bold text-gray-500 mb-2 animate-slide-up">Bienvenido de vuelta</h2>
                <p class="text-gray-500/80 text-sm animate-slide-up" style="animation-delay: 0.2s">Ingresa tus credenciales para continuar</p>
            </div>

            <!-- Mensajes de estado con animaciones -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-500/20 backdrop-blur border border-green-400/30 text-green-100 rounded-2xl animate-slide-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/20 backdrop-blur border border-red-400/30 text-gray-500 rounded-2xl animate-slide-up">
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

            <!-- Formulario mejorado -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Campo Email -->
                <div class="relative animate-slide-up" style="animation-delay: 0.3s">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}"
                        required
                        class="w-full pl-10 pr-4 py-4  bg-white/10 border border-gray-500/20 rounded-2xl text-gray-500 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300">
                </div>

                <!-- Campo Contraseña -->
                <div class="relative animate-slide-up" style="animation-delay: 0.4s">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input type="password" name="password" placeholder="Contraseña" required
                        class="w-full pl-10 pr-4 py-4 bg-white/10 border border-gray-500/20 rounded-2xl text-gray-500 placeholder-gray-400 focus:outline-none focus:border-blue-400 input-glow transition-all duration-300">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Recordar sesión -->
                <div class="flex items-center justify-between animate-slide-up" style="animation-delay: 0.5s">
                    <label class="flex items-center text-gray-400/80 text-sm">
                        <input type="checkbox" name="remember" class="mr-2 rounded border-white/20 bg-white/10 text-blue-500 focus:ring-blue-400">
                        Recordar sesión
                    </label>
                    <a href="#" class="text-blue-300 hover:text-blue-500 text-sm transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <!-- Botón de envío -->
                <div class="animate-slide-up" style="animation-delay: 0.6s">
                    <button type="submit"
                        class="w-full btn-gradient text-white font-semibold py-4 rounded-2xl shadow-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300/50">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Iniciar Sesión
                        </span>
                    </button>
                </div>

                <!-- Separador -->
                <div class="relative flex items-center justify-center py-4 animate-slide-up" style="animation-delay: 0.7s">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-black/20"></div>
                    </div>
                    <div class="relative bg-transparent px-4">
                        <span class="text-gray-400/60 text-sm">o</span>
                    </div>
                </div>

                <!-- Enlace de registro -->
                <div class="text-center animate-slide-up" style="animation-delay: 0.8s">
                    <p class="text-gray-400/80 text-sm mb-4">¿No tienes una cuenta?</p>
                    <a href="{{ route('tutor.register') }}" 
                        class="inline-flex items-center px-6 py-3 bg-white/10 border border-white/20 rounded-2xl text-green-400 hover:bg-white/20 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear cuenta nueva
                    </a>
                </div>
            </form>

            <!-- Indicador de carga -->
            <div id="loading" class="hidden absolute inset-0 bg-black/50 backdrop-blur-sm rounded-3xl flex items-center justify-center">
                <div class="flex flex-col items-center text-gray-400">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                    <p class="mt-4 text-sm">Iniciando sesión...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle para mostrar/ocultar contraseña
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="password"]');
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
        document.querySelector('.glass-effect').addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.3)';
        });

        document.querySelector('.glass-effect').addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2)';
        });

        // Animación de entrada de los inputs
        const inputs = document.querySelectorAll('input');
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