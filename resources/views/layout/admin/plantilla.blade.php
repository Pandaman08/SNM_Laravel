<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/dist/css/style.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/admin/dist/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <title>@yield('titulo')</title>
</head>

<body class="text-gray-800 font-inter">
    <!-- start: Sidebar -->
    <aside
        class="fixed left-0 top-0 w-[300px] h-full bg-[#0f1657] p-4 z-50 sidebar-menu transition-transform overflow-y-auto shadow-2xl">
        <a href="" class="flex items-center py-8 border-b border-b-[#98C560]">
            <img src="/images/logo-bruning2.png" alt="logo_labcam" class="max-w-full">
        </a>
        <ul class="mt-8">
            @auth


                <li class="mb-1 group cursor-pointer">
                    <a href="{{ route('users.edit_user') }}"
                        class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md {{ request()->routeIs('user.edit_user') ? 'bg-[#98C560]' : 'bg-transparent' }}">
                        <svg class="mr-3 h-6 w-6 " fill="#ffffff" viewBox="0 0 128 128" id="Layer_1" version="1.1"
                            xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <path
                                        d="M30,49c0,18.7,15.3,34,34,34s34-15.3,34-34S82.7,15,64,15S30,30.3,30,49z M90,49c0,14.3-11.7,26-26,26S38,63.3,38,49 s11.7-26,26-26S90,34.7,90,49z">
                                    </path>
                                    <path
                                        d="M24.4,119.4C35,108.8,49,103,64,103s29,5.8,39.6,16.4l5.7-5.7C97.2,101.7,81.1,95,64,95s-33.2,6.7-45.3,18.7L24.4,119.4z">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <span class="text-sm">Mi Cuenta</span>
                    </a>
                </li>


                @if (auth()->user()->isAdmin())
                    <h4 class="text-[#98C560] text-sm font-bold uppercase mb-3">Administración general</h4>

                    <li class="mb-1 group cursor-pointer">
                        <a href=""
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md {{ request()->routeIs('admin-principal') ? 'bg-[#98C560]' : 'bg-transparent' }}">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Principal</span>
                        </a>
                    </li>

                    <li class="mb-1 group cursor-pointer {{ request()->routeIs(['person', 'users']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <svg class="h-8 w-8 mr-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                stroke="#ffffff">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path opacity="0.1"
                                        d="M13 9.5C13 11.433 11.433 13 9.5 13C7.567 13 6 11.433 6 9.5C6 7.567 7.567 6 9.5 6C11.433 6 13 7.567 13 9.5Z"
                                        fill="#ffffff"></path>
                                    <path
                                        d="M15.6309 7.15517C15.9015 7.05482 16.1943 7 16.4999 7C17.8806 7 18.9999 8.11929 18.9999 9.5C18.9999 10.8807 17.8806 12 16.4999 12C16.1943 12 15.9015 11.9452 15.6309 11.8448"
                                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"></path>
                                    <path d="M3 19C3.69137 16.6928 5.46998 16 9.5 16C13.53 16 15.3086 16.6928 16 19"
                                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"></path>
                                    <path d="M17 15C19.403 15.095 20.5292 15.6383 21 17" stroke="#ffffff" stroke-width="2"
                                        stroke-linecap="round"></path>
                                    <path
                                        d="M13 9.5C13 11.433 11.433 13 9.5 13C7.567 13 6 11.433 6 9.5C6 7.567 7.567 6 9.5 6C11.433 6 13 7.567 13 9.5Z"
                                        stroke="#ffffff" stroke-width="2"></path>
                                </g>
                            </svg>
                            <span class="text-sm">Usuarios</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('person') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Usuarios
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="{{ route('docentes.buscar') }}"
                                    class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('users') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Panel Docentes
                                </a>
                            </li>

                            <li class="mb-4">
                                <a href="{{ route('tutores.panel-aprobar') }}"
                                    class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('person') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Aprobar tutores
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="{{ route('users.buscar') }}"
                                    class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('users') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Registrar Usuario
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="mb-1 group cursor-pointer {{ request()->routeIs(['person', 'users']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Gestionar Cursos</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('person') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Cursos
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('users') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Registrar Curso
                                </a>
                            </li>
                        </ul>
                    </li>


                @php
    $gradoActivo = request()->routeIs('grados.index') || request()->routeIs('grados.create') || request()->routeIs('grados.edit');
@endphp

<li class="mb-1 group cursor-pointer {{ $gradoActivo ? 'selected' : '' }}">
    <a class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.selected]:bg-[#98C560] sidebar-dropdown-toggle">
        <i class="ri-graduation-cap-line mr-3 text-lg"></i>
        <span class="text-sm">Gestionar Grados</span>
        <i class="ri-arrow-right-s-line ml-auto transform transition-transform group-[.selected]:rotate-90"></i>
    </a>
    <ul class="pl-7 mt-2 {{ $gradoActivo ? 'block' : 'hidden' }} group-[.selected]:block">
        <li class="mb-2">
            <a href="{{ route('grados.index') }}"
                class="text-sm flex items-center py-2 px-4 rounded-md text-white hover:bg-[#98C560] {{ request()->routeIs('grados.index') ? 'bg-[#98C560]' : '' }}">
                <span class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('grados.index') ? 'bg-[#98C560]' : 'bg-gray-400' }}"></span>
                Lista de Grados
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('grados.create') }}"
                class="text-sm flex items-center py-2 px-4 rounded-md text-white hover:bg-[#98C560] {{ request()->routeIs('grados.create') ? 'bg-[#98C560]' : '' }}">
                <span class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('grados.create') ? 'bg-[#98C560]' : 'bg-gray-400' }}"></span>
                Registrar Grado
            </a>
        </li>
    </ul>
</li>

   @php
    $seccionActivo = request()->routeIs('secciones.index') || request()->routeIs('secciones.create') || request()->routeIs('secciones.edit');
@endphp

<li class="mb-1 group cursor-pointer {{ $seccionActivo ? 'selected' : '' }}">
    <a class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.selected]:bg-[#98C560] sidebar-dropdown-toggle">
        <i class="ri-layout-grid-line mr-3 text-lg"></i>
        <span class="text-sm">Gestionar Secciones</span>
        <i class="ri-arrow-right-s-line ml-auto transform transition-transform group-[.selected]:rotate-90"></i>
    </a>
    <ul class="pl-7 mt-2 {{ $seccionActivo ? 'block' : 'hidden' }} group-[.selected]:block">
        <li class="mb-2">
            <a href="{{ route('secciones.index') }}"
                class="text-sm flex items-center py-2 px-4 rounded-md text-white hover:bg-[#98C560] {{ request()->routeIs('secciones.index') ? 'bg-[#98C560]' : '' }}">
                <span class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('secciones.index') ? 'bg-[#98C560]' : 'bg-gray-400' }}"></span>
                Lista de Secciones
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('secciones.create') }}"
                class="text-sm flex items-center py-2 px-4 rounded-md text-white hover:bg-[#98C560] {{ request()->routeIs('secciones.create') ? 'bg-[#98C560]' : '' }}">
                <span class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('secciones.create') ? 'bg-[#98C560]' : 'bg-gray-400' }}"></span>
                Registrar Sección
            </a>
        </li>
    </ul>
</li>
   

@php
    $asignaturaActivo = request()->routeIs('asignaturas.index') || request()->routeIs('asignaturas.create') || request()->routeIs('asignaturas.edit');
@endphp

<li class="mb-1 group cursor-pointer {{ $asignaturaActivo ? 'selected' : '' }}">
    <a class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.selected]:bg-[#98C560] sidebar-dropdown-toggle">
        <i class="ri-book-2-line mr-3 text-lg"></i>
        <span class="text-sm">Gestionar Asignaturas</span>
        <i class="ri-arrow-right-s-line ml-auto transform transition-transform group-[.selected]:rotate-90"></i>
    </a>
    <ul class="pl-7 mt-2 {{ $asignaturaActivo ? 'block' : 'hidden' }} group-[.selected]:block">
        <li class="mb-2">
            <a href="{{ route('asignaturas.index') }}"
                class="text-sm flex items-center py-2 px-4 rounded-md text-white hover:bg-[#98C560] {{ request()->routeIs('asignaturas.index') ? 'bg-[#98C560]' : '' }}">
                <span class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('asignaturas.index') ? 'bg-[#98C560]' : 'bg-gray-400' }}"></span>
                Lista de Asignaturas
            </a>
        </li>
        <li class="mb-2">
            <a href="{{ route('asignaturas.create') }}"
                class="text-sm flex items-center py-2 px-4 rounded-md text-white hover:bg-[#98C560] {{ request()->routeIs('asignaturas.create') ? 'bg-[#98C560]' : '' }}">
                <span class="w-2 h-2 rounded-full mr-3 {{ request()->routeIs('asignaturas.create') ? 'bg-[#98C560]' : 'bg-gray-400' }}"></span>
                Registrar Asignatura
            </a>
        </li>
    </ul>
</li>



                    <li class="mb-1 group cursor-pointer {{ request()->routeIs(['person', 'users']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Gestionar Grados</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('person') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Grados
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('users') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Registrar Grado
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="mb-1 group cursor-pointer {{ request()->routeIs(['person', 'users']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Gestionar Periodos</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="{{ route('periodos.index') }}"
                                    class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('person') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Periodos
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="{{ route('periodos.create') }}"
                                    class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('users') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Registrar Periodo
                                </a>
                            </li>
                        </ul>
                    </li>
                     <li class="mb-1 group cursor-pointer {{ request()->routeIs(['person', 'users']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Gestionar Años Escolares</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="{{ route('anios-escolares.index') }}"
                                    class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('person') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Años Escolares
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="{{ route('anios-escolares.create') }}"
                                    class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('users') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Registrar Año escolar
                                </a>
                            </li>
                        </ul>
                    </li>

                @elseif(auth()->user()->isSecretaria())
                    <h4 class="text-[#98C560] text-sm font-bold uppercase mb-3 mt-8">Secretaria</h4>

                    <li class="mb-1 group cursor-pointer">
                        <a href=""
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md {{ request()->routeIs('admin-homeSlider') ? 'bg-[#98C560]' : 'bg-transparent' }}">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Principal</span>
                        </a>
                    </li>

                    

                    <li
                        class="mb-1 group cursor-pointer {{ request()->routeIs(['papers.create', 'papers.index', 'papers.edit']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Gestionar Matricula</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs(['papers.index', 'papers.edit']) ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Matricula</a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('papers.create') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Validar Matricula</a>
                            </li>
                        </ul>
                    </li>



                    <li
                        class="mb-1 group cursor-pointer {{ request()->routeIs(['areas_proyectos_admin', 'proyect', 'topic-panel', 'topics.edit']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Gestionar Usuarios</span>

                        </a>

                    </li>

                    <li
                        class="mb-1 group cursor-pointer {{ request()->routeIs(['capital_index', 'direccion_index']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Registro de información</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('capital_index') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Estudiantes
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('direccion_index') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Pendientes
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif(auth()->user()->isDocente())
                    <h4 class="text-[#98C560] text-sm font-bold uppercase mb-3 mt-8">Docente</h4>

                    <li class="mb-1 group cursor-pointer">
                        <a href=""
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md {{ request()->routeIs('admin-homeSlider') ? 'bg-[#98C560]' : 'bg-transparent' }}">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Principal</span>
                        </a>
                    </li>


                    <li
                        class="mb-1 group cursor-pointer {{ request()->routeIs(['papers.create', 'papers.index', 'papers.edit']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Gestionar Asignaturas</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs(['papers.index', 'papers.edit']) ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Calificaciones</a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('papers.create') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Estudiantes</a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('papers.create') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Asignaturas</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="mb-1 group cursor-pointer {{ request()->routeIs(['capital_index', 'direccion_index']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Registro de información</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('capital_index') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Estudiantes
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('direccion_index') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Pendientes
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif(auth()->user()->isTutor())
                    <h4 class="text-[#98C560] text-sm font-bold uppercase mb-3 mt-8">Usuario</h4>

                    <li class="mb-1 group cursor-pointer">
                        <a href=""
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md {{ request()->routeIs('admin-homeSlider') ? 'bg-[#98C560]' : 'bg-transparent' }}">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Principal</span>
                        </a>
                    </li>


                    <li
                        class="mb-1 group cursor-pointer {{ request()->routeIs(['papers.create', 'papers.index', 'papers.edit']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Calificaciones</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs(['papers.index', 'papers.edit']) ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Visualizar Calificaciones</a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('papers.create') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Lista de Asignaturas</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="mb-1 group cursor-pointer {{ request()->routeIs(['capital_index', 'direccion_index']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Matriculas</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('capital_index') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Registrar Matricula
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('direccion_index') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Mis Matriculas
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="mb-1 group cursor-pointer {{ request()->routeIs(['capital_index', 'direccion_index']) ? 'active' : '' }}">
                        <a
                            class="flex items-center py-2 px-4 text-white hover:bg-[#98C560] rounded-md group-[.active]:bg-[#98C560] group-[.active]:text-white sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Información Personal</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                        <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('capital_index') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Datos
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="" class="text-sm flex items-center py-2 px-4 rounded-md text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('direccion_index') ? 'bg-[#98C560]' : 'bg-gray-300' }}"></span>
                                    Mis Matriculas
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endauth
        </ul>
        <div class="fixed top-0 left-[300px] w-full h-full z-40 md:hidden sidebar-overlay backdrop-blur-sm"></div>
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
                                <img src="{{ Storage::url('' . Auth::user()->persona->photo) }}" alt="Foto de perfil"
                                    class="w-12 h-12 rounded-full block object-cover">
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
                            <a href="#" class="flex items-center py-1.5 px-4 hover:text-[#98C560]">Mi Perfil</a>
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


    <li class="dropdown ml-3">
    <button type="button"
        class="dropdown-toggle flex items-center gap-x-2 hover:text-[#98C560] group">
        <div
            class="relative inline-block bg-white p-[2.5px] rounded-full border-[1px] border-black group-hover:border-[#98C560]">
            @if (Auth::check() && optional(Auth::user()->persona)->photo)
                <img src="{{ asset('storage/' . Auth::user()->persona->photo) }}" alt="Foto de perfil"
                    class="w-12 h-12 rounded-full block object-cover">
            @elseif(Auth::check() && optional(Auth::user()->persona)->name)
                <div
                    class="w-12 h-12 bg-gray-300 text-gray-700 flex items-center justify-center rounded-full text-xl font-bold uppercase">
                    {{ substr(Auth::user()->persona->name, 0, 1) }}
                </div>
            @else
                <div
                    class="w-12 h-12 bg-gray-300 text-gray-700 flex items-center justify-center rounded-full text-xl font-bold uppercase">
                    ?
                </div>
            @endif
        </div>
        @if (Auth::check() && Auth::user()->persona)
            <div>
                <h4 class="text-[14.5px] font-medium">
                    {{ Auth::user()->persona->name }} {{ Auth::user()->persona->lastname }}
                </h4>
                <h4 class="text-[12.5px] font-normal uppercase">{{ Auth::user()->rol }}</h4>
            </div>
        @endif
    </button>
    <ul
        class="dropdown-menu shadow-md shadow-black/5 z-30 hidden py-2 rounded-md bg-white border border-gray-100 w-[140px] text-black text-[15px]">
        <li>
            <a href="#" class="flex items-center py-1.5 px-4 hover:text-[#98C560]">Mi Perfil</a>
        </li>
        <li>
            <a href="#" onclick="confirmLogout()"
                class="flex items-center py-1.5 px-4 hover:text-[#98C560]">Cerrar Sesión</a>
        </li>
    </ul>
</li>

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
</style>
