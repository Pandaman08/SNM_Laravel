<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/dist/css/style.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/admin/dist/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <title>@yield('titulo')</title>
    <link rel="icon" href="/images/logo-bruning1.png" type="image/png">
</head>

<body class="text-gray-800 font-inter">
    <!-- start: Sidebar -->
      <div class="sidebar-overlay md:hidden"></div>
     <aside class="fixed left-0 top-0 w-[300px] h-full glass-effect p-6 z-50 sidebar-menu transition-transform duration-300 overflow-y-auto shadow-2xl">
        <!-- Logo Section -->
        <div class="flex items-center py-6 border-b-2 border-gradient-to-r from-[#98C560] to-transparent mb-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-2 mr-3 border border-[#98C560]/30">
                <img src="/images/logo-bruning2.png" alt="logo_bruning" class="max-w-[240px] h-auto">
            </div>
        </div>

       <!-- Navigation Menu -->
<nav class="space-y-2">
    @auth
        <!-- Profile Section -->
        <div class="profile-card rounded-xl p-4 mb-6 backdrop-blur-sm">
            <a href="{{ route('users.edit_user') }}" class="flex items-center space-x-3 text-white hover:text-[#98C560] transition-colors duration-300 {{ request()->routeIs('user.edit_user') ? 'text-[#98C560]' : '' }}">
                <div class="relative">
                    @if (Auth::check() && Auth::user()->persona->photo)
                        <img src="{{ Storage::url('' . Auth::user()->persona->photo) }}" alt="Foto de perfil" class="w-10 h-10 rounded-full object-cover border-2 border-[#98C560]/30">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-[#98C560] to-[#7CB342] rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                            {{ substr(Auth::user()->persona->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                </div>
                <div>
                    <span class="text-sm font-medium block">Mi Cuenta</span>
                    <span class="text-xs text-gray-300">Gestionar perfil</span>
                </div>
            </a>
        </div>

        @if (auth()->user()->isAdmin())
            <div class="space-y-1">
                <h4 class="text-[#98C560] text-xs font-bold uppercase mb-4 tracking-wider px-3 flex items-center">
                    <div class="w-6 h-[1px] bg-gradient-to-r from-[#98C560] to-transparent mr-2"></div>
                    Administración General
                </h4>

                <!-- Dashboard -->
                <div class="sidebar-item relative">
                    <a href="{{ route('home.admin') }}" class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 group {{ request()->routeIs('home.admin') ? 'bg-[#98C560]/30' : '' }}">
                        @if(request()->routeIs('home.admin'))
                            <div class="active-indicator"></div>
                        @endif
                        <div class="w-8 h-8 bg-gradient-to-br from-[#98C560] to-[#7CB342] rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-dashboard-3-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Dashboard Principal</span>
                    </a>
                </div>

                <!-- Users Management -->
                <div class="sidebar-item relative group {{ request()->routeIs(['person', 'users', 'docentes.buscar', 'secretarias.buscar', 'estudiantes.buscar', 'tutores.panel-aprobar']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['person', 'users', 'docentes.buscar', 'secretarias.buscar', 'estudiantes.buscar', 'tutores.panel-aprobar']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M13 9.5C13 11.433 11.433 13 9.5 13C7.567 13 6 11.433 6 9.5C6 7.567 7.567 6 9.5 6C11.433 6 13 7.567 13 9.5Z"/>
                                <path d="M3 19C3.69137 16.6928 5.46998 16 9.5 16C13.53 16 15.3086 16.6928 16 19"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestión de Usuarios</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['person', 'users', 'docentes.buscar', 'secretarias.buscar', 'estudiantes.buscar', 'tutores.panel-aprobar']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('docentes.buscar') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('docentes.buscar') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-[#98C560] rounded-full mr-3 opacity-60"></div>
                                Panel Docentes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('secretarias.buscar') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('secretarias.buscar') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-orange-400 rounded-full mr-3 opacity-60"></div>
                                Panel Secretarios
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('estudiantes.buscar') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('estudiantes.buscar') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mr-3 opacity-60"></div>
                                Panel Estudiantes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tutores.panel-aprobar') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('tutores.panel-aprobar') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-purple-400 rounded-full mr-3 opacity-60"></div>
                                Aprobar Tutores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('users.buscar') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('users.buscar') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Usuario
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Enrollment Management -->
                <div class="sidebar-item relative group {{ request()->routeIs(['matriculas.index', 'matriculas.create']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['matriculas.index', 'matriculas.create']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-graduation-cap-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Matrículas</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['matriculas.index', 'matriculas.create']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('matriculas.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('matriculas.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-purple-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Matrículas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('matriculas.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('matriculas.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Matrícula
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('matriculas.reporte') }}" 
                            class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('matriculas.reporte') ? 'text-[#98C560] bg-white/5' : '' }}">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                             Reporte Matrícula
                            </a>
                        </li>

                    </ul>
                </div>

                <!-- Grades Management -->
                @php
                    $gradoActivo =
                        request()->routeIs('grados.index') ||
                        request()->routeIs('grados.create') ||
                        request()->routeIs('grados.edit');
                @endphp
                <div class="sidebar-item relative group {{ $gradoActivo ? 'selected' : '' }}">
                    @if($gradoActivo)
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-graduation-cap-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Grados</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ $gradoActivo ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('grados.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('grados.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-indigo-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Grados
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('grados.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('grados.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Grado
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Sections Management -->
                @php
                    $seccionActivo =
                        request()->routeIs('secciones.index') ||
                        request()->routeIs('secciones.create') ||
                        request()->routeIs('secciones.edit');
                @endphp
                <div class="sidebar-item relative group {{ $seccionActivo ? 'selected' : '' }}">
                    @if($seccionActivo)
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-layout-grid-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Secciones</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ $seccionActivo ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('secciones.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('secciones.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-teal-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Secciones
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('secciones.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('secciones.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Sección
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Subjects Management -->
                @php
                    $asignaturaActivo =
                        request()->routeIs('asignaturas.index') ||
                        request()->routeIs('asignaturas.create') ||
                        request()->routeIs('asignaturas.edit') ||
                        request()->routeIs('asignaturas.asignar.docentes');
                @endphp
                <div class="sidebar-item relative group {{ $asignaturaActivo ? 'selected' : '' }}">
                    @if($asignaturaActivo)
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-book-2-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Asignaturas</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ $asignaturaActivo ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('asignaturas.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('asignaturas.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-amber-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Asignaturas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('asignaturas.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('asignaturas.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Asignatura
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('asignaturas.asignar.docentes') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('asignaturas.asignar.docentes') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mr-3 opacity-60"></div>
                                Asignar docente
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- School Years Management -->
                <div class="sidebar-item relative group {{ request()->routeIs(['anios-escolares.index', 'anios-escolares.create']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['anios-escolares.index', 'anios-escolares.create']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-calendar-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Años Escolares</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['anios-escolares.index', 'anios-escolares.create']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('anios-escolares.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('anios-escolares.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-red-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Años Escolares
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('anios-escolares.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('anios-escolares.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Año Escolar
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Grading Types Management -->
                <div class="sidebar-item relative group {{ request()->routeIs(['tipos-calificacion.index', 'tipos-calificacion.create']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['tipos-calificacion.index', 'tipos-calificacion.create']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-award-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Tipos Calificaciones</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['tipos-calificacion.index', 'tipos-calificacion.create']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('tipos-calificacion.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('tipos-calificacion.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-pink-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Tipos Calificación
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tipos-calificacion.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('tipos-calificacion.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Tipo Calificación
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Periods Management -->
                <div class="sidebar-item relative group {{ request()->routeIs(['periodos.index', 'periodos.create']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['periodos.index', 'periodos.create']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-timer-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Periodos</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['periodos.index', 'periodos.create']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('periodos.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('periodos.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-cyan-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Periodos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('periodos.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('periodos.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Periodo
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Competencies Management -->
                <div class="sidebar-item relative group {{ request()->routeIs(['competencias.index', 'competencias.create']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['competencias.index', 'competencias.create']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-lime-500 to-lime-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-lightbulb-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Competencias</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['competencias.index', 'competencias.create']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('competencias.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('competencias.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-lime-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Competencias
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('competencias.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('competencias.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Competencia
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

        @elseif(auth()->user()->isSecretaria())
            <div class="space-y-1">
                <h4 class="text-[#98C560] text-xs font-bold uppercase mb-4 tracking-wider px-3 flex items-center">
                    <div class="w-6 h-[1px] bg-gradient-to-r from-[#98C560] to-transparent mr-2"></div>
                    Secretaría
                </h4>
                
                <div class="sidebar-item relative">
                    <a href="{{ route('home.secretaria') }}" class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 group {{ request()->routeIs('home.secretaria') ? 'bg-[#98C560]/30' : '' }}">
                        @if(request()->routeIs('home.secretaria'))
                            <div class="active-indicator"></div>
                        @endif
                        <div class="w-8 h-8 bg-gradient-to-br from-[#98C560] to-[#7CB342] rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-dashboard-3-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Dashboard Principal</span>
                    </a>
                </div>

                <!-- Enrollment Management for Secretary -->
                <div class="sidebar-item relative group {{ request()->routeIs(['matriculas.index']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['matriculas.index']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-graduation-cap-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Matrículas</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['matriculas.index']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('matriculas.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('matriculas.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-purple-400 rounded-full mr-3 opacity-60"></div>
                                Lista de Matrículas
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Users Management for Secretary -->
                <div class="sidebar-item relative group {{ request()->routeIs(['docentes.buscar', 'estudiantes.buscar', 'tutores.panel-aprobar']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['docentes.buscar', 'estudiantes.buscar', 'tutores.panel-aprobar']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M13 9.5C13 11.433 11.433 13 9.5 13C7.567 13 6 11.433 6 9.5C6 7.567 7.567 6 9.5 6C11.433 6 13 7.567 13 9.5Z"/>
                                <path d="M3 19C3.69137 16.6928 5.46998 16 9.5 16C13.53 16 15.3086 16.6928 16 19"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestión de Usuarios</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['docentes.buscar', 'estudiantes.buscar', 'tutores.panel-aprobar']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('docentes.buscar') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('docentes.buscar') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-[#98C560] rounded-full mr-3 opacity-60"></div>
                                Panel Docentes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('estudiantes.buscar') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('estudiantes.buscar') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mr-3 opacity-60"></div>
                                Panel Estudiantes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tutores.panel-aprobar') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('tutores.panel-aprobar') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-purple-400 rounded-full mr-3 opacity-60"></div>
                                Aprobar Tutores
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Teacher Assignment for Secretary -->
                <div class="sidebar-item relative">
                    <a href="{{ route('asignaturas.asignar.docentes') }}" class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 group {{ request()->routeIs('asignaturas.asignar.docentes') ? 'bg-[#98C560]/30' : '' }}">
                        @if(request()->routeIs('asignaturas.asignar.docentes'))
                            <div class="active-indicator"></div>
                        @endif
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-team-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Asignar Docente</span>
                    </a>
                </div>
            </div>

        @elseif(auth()->user()->isDocente())
            <div class="space-y-1">
                <h4 class="text-[#98C560] text-xs font-bold uppercase mb-4 tracking-wider px-3 flex items-center">
                    <div class="w-6 h-[1px] bg-gradient-to-r from-[#98C560] to-transparent mr-2"></div>
                    Docente
                </h4>
                
                <div class="sidebar-item relative">
                    <a href="{{ route('home.docente') }}" class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 group {{ request()->routeIs('home.docente') ? 'bg-[#98C560]/30' : '' }}">
                        @if(request()->routeIs('home.docente'))
                            <div class="active-indicator"></div>
                        @endif
                        <div class="w-8 h-8 bg-gradient-to-br from-[#98C560] to-[#7CB342] rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-dashboard-3-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Dashboard Principal</span>
                    </a>
                </div>

                <!-- Subjects Management for Teacher -->
                <div class="sidebar-item relative group {{ request()->routeIs(['docentes.asignaturas', 'docente.mis_estudiantes']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['docentes.asignaturas', 'docente.mis_estudiantes']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-book-2-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Asignaturas</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['docentes.asignaturas', 'docente.mis_estudiantes']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('docentes.asignaturas') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('docentes.asignaturas') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-amber-400 rounded-full mr-3 opacity-60"></div>
                                Asignaturas Asignadas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('docente.mis_estudiantes') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('docente.mis_estudiantes') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mr-3 opacity-60"></div>
                                Mis Grados
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Attendance Management for Teacher -->
                <div class="sidebar-item relative group {{ request()->routeIs(['asistencias.index']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['asistencias.index']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-calendar-check-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Gestionar Asistencia</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['asistencias.index']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('asistencias.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('asistencias.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Estudiantes
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        @elseif(auth()->user()->isTutor())
            <div class="space-y-1">
                <h4 class="text-[#98C560] text-xs font-bold uppercase mb-4 tracking-wider px-3 flex items-center">
                    <div class="w-6 h-[1px] bg-gradient-to-r from-[#98C560] to-transparent mr-2"></div>
                    Tutor
                </h4>
                
                <div class="sidebar-item relative">
                    <a href="{{ route('home.tutor') }}" class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 group {{ request()->routeIs('home.tutor') ? 'bg-[#98C560]/30' : '' }}">
                        @if(request()->routeIs('home.tutor'))
                            <div class="active-indicator"></div>
                        @endif
                        <div class="w-8 h-8 bg-gradient-to-br from-[#98C560] to-[#7CB342] rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-dashboard-3-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Dashboard Principal</span>
                    </a>
                </div>

                <!-- Grades Management for Tutor -->
                <div class="sidebar-item relative group {{ request()->routeIs(['reporte_notas.tutor']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['reporte_notas.tutor']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-award-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Calificaciones</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['reporte_notas.tutor']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('reporte_notas.tutor') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('reporte_notas.tutor') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-amber-400 rounded-full mr-3 opacity-60"></div>
                                Visualizar Calificaciones
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Enrollment Management for Tutor -->
                <div class="sidebar-item relative group {{ request()->routeIs(['matriculas.create', 'matriculas.mis-matriculas']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['matriculas.create', 'matriculas.mis-matriculas']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-graduation-cap-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Matrículas</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['matriculas.create', 'matriculas.mis-matriculas']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('matriculas.create') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('matriculas.create') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-purple-400 rounded-full mr-3 opacity-60"></div>
                                Registrar Matrícula
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('matriculas.mis-matriculas') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('matriculas.mis-matriculas') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mr-3 opacity-60"></div>
                                Mis Matrículas
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Payments Management for Tutor -->
                <div class="sidebar-item relative group {{ request()->routeIs(['pagos.index']) ? 'selected' : '' }}">
                    @if(request()->routeIs(['pagos.index']))
                        <div class="active-indicator"></div>
                    @endif
                    <a class="flex items-center py-3 px-4 text-white hover:bg-[#98C560]/20 rounded-lg hover-scale transition-all duration-300 sidebar-dropdown-toggle cursor-pointer group-[.selected]:bg-[#98C560]/30">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="ri-money-dollar-circle-line text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium flex-1">Pagos</span>
                        <i class="ri-arrow-right-s-line submenu-indicator text-lg text-[#98C560]"></i>
                    </a>
                    
                    <ul class="pl-12 mt-2 space-y-2 {{ request()->routeIs(['pagos.index']) ? 'block' : 'hidden' }} group-[.selected]:block">
                        <li>
                            <a href="{{ route('pagos.index') }}" class="flex items-center py-2 px-3 text-gray-300 hover:text-[#98C560] hover:bg-white/5 rounded-md transition-all duration-200 text-sm {{ request()->routeIs('pagos.index') ? 'text-[#98C560] bg-white/5' : '' }}">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 opacity-60"></div>
                                Mis pagos
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    @endauth
</nav>
    </aside>

    <!-- end: Sidebar -->

    <!-- start: Main -->

    <main class="w-full md:w-[calc(100%-300px)] md:ml-[300px] bg-gray-50 min-h-screen transition-all main">
        <section class="py-5 px-6 bg-white flex items-center shadow-md shadow-black/5 sticky top-0 left-0 z-30">
            <button type="button" class="text-2xl text-gray-600 sidebar-toggle">
                <i class="ri-menu-line"></i>
            </button>
            <ul class="ml-auto flex items-center">
                <li class="dropdown ml-3">
                    <button type="button"
                        class="dropdown-toggle flex items-center gap-x-2 hover:text-[#98C560] group">
                        <div
                            class="relative inline-block bg-white p-[2.5px] rounded-full border-[1px] border-black group-hover:border-[#98C560]">
                            @if (Auth::check() && Auth::user()->persona->photo)
                                <img src="{{ Storage::url('' . Auth::user()->persona->photo) }}"
                                    alt="Foto de perfil" class="w-12 h-12 rounded-full block object-cover">
                            @elseif(Auth::check())
                                <div
                                    class="w-12 h-12 bg-gray-300 text-gray-700 flex items-center justify-center rounded-full text-xl font-bold uppercase">
                                    {{ substr(Auth::user()->persona->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        @if (Auth::check())
                            <div>
                                <h4 class="text-[14.5px] font-medium">{{ Auth::user()->persona->name }}
                                    {{ Auth::user()->persona->lastname }}</h4>
                                <h4 class="text-[12.5px] font-normal uppercase">{{ Auth::user()->rol }}</h4>
                            </div>
                        @endif
                        </div>

                    </button>
                    <ul
                        class="dropdown-menu shadow-md shadow-black/5 z-30 hidden py-2 rounded-md bg-white border border-gray-100 w-[140px] text-black text-[15px]">
                        <li>
                            <a href="{{ route('users.edit_user') }}"
                                class="flex items-center py-1.5 px-4 hover:text-[#98C560]">Mi Perfil</a>
                        </li>
                        <li>
                            <a href="#" onclick="confirmLogout()"
                                class="flex items-center py-1.5 px-4 hover:text-[#98C560]">Cerrar
                                Sesión</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
        <section class="d-flex px-6 py-9">
            @yield('contenido')
        </section>

    </main>

    <!-- end: Main -->

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="/admin/dist/js/script.js"></script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Se cerrará tu sesión",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: 'swal2-confirm-green',
                    cancelButton: 'swal2-cancel-red'
                },
                confirmButtonText: "Sí, salir",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('logout') }}";
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar-menu');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');
        const main = document.querySelector('.main');

        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }
        });

        // Close sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });

        sidebar.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' && window.innerWidth <= 768) {
                    setTimeout(() => {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }, 200);
                }
            });

        // Dropdown toggle functionality
        document.querySelectorAll('.sidebar-dropdown-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.closest('.group');
                const submenu = parent.querySelector('ul');
                
                // Close other open submenus
                document.querySelectorAll('.group.selected').forEach(function(openGroup) {
                    if (openGroup !== parent) {
                        openGroup.classList.remove('selected');
                        const openSubmenu = openGroup.querySelector('ul');
                        if (openSubmenu && !openSubmenu.classList.contains('hidden')) {
                            openSubmenu.classList.add('hidden');
                        }
                    }
                });
                
                parent.classList.toggle('selected');
                
                if (submenu.classList.contains('hidden')) {
                    submenu.classList.remove('hidden');
                } else {
                    submenu.classList.add('hidden');
                }
            });
        });

            window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

          });
    </script>

    @yield('script')
</body>

</html>
<style>
    .swal2-confirm-green {
        background-color: #28a745 !important;
        /* Green */
        color: white !important;
        border: none;
        padding: 0.625em 1.25em;
        font-weight: bold;
        border-radius: 0.25em;
    }

    .swal2-cancel-red {
        background-color: #dc3545 !important;
        /* Red */
        color: white !important;
        border: none;
        padding: 0.625em 1.25em;
        font-weight: bold;
        border-radius: 0.25em;
    }

     .glass-effect {
            background: rgba(15, 22, 87, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(152, 197, 96, 0.2);
        }
        
        .nav-gradient {
            background: linear-gradient(135deg, #0f1657 0%, #1a1f7a 100%);
        }
        
        .hover-scale {
            transition: all 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: translateX(4px) scale(1.02);
        }
        
        .sidebar-item {
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(152, 197, 96, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .sidebar-item:hover::before {
            left: 100%;
        }
        
        .active-indicator {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #98C560, #7CB342);
            border-radius: 0 2px 2px 0;
        }
        
        .submenu-indicator {
            transition: transform 0.3s ease;
        }
        
        .group.selected .submenu-indicator {
            transform: rotate(90deg);
        }
        
        .profile-card {
            background: linear-gradient(135deg, rgba(152, 197, 96, 0.1), rgba(152, 197, 96, 0.05));
            border: 1px solid rgba(152, 197, 96, 0.2);
        }
        
        
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(152, 197, 96, 0.5);
            border-radius: 3px;
        }
        
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(152, 197, 96, 0.8);
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .sidebar-menu {
                transform: translateX(-100%);
            }
            
            .sidebar-menu.active {
                transform: translateX(0);
            }
            
            .main {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
</style>
