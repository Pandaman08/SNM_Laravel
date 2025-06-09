@extends('layout.admin.plantilla')

@section('titulo','Panel de Docente - Colegio Brunning')

@section('contenido')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Panel de Docente</h1>
                <p class="text-gray-600">Colegio Brunning - Gestión Académica</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="font-medium">Bienvenido, <span class="text-blue-600">{{ Auth::user()->persona->name }}</span></p>
                    <p class="text-sm text-gray-500">{{ now()->format('d M Y, H:i') }}</p>
                </div>
                <img class="w-12 h-12 rounded-full border-2 border-white shadow-md" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->persona->name) }}&background=4f46e5&color=fff" alt="Usuario">
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Estadísticas rápidas -->
            <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="ri-book-2-fill text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Cursos Asignados</p>
                    <p class="text-2xl font-bold">4</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="ri-team-fill text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Estudiantes</p>
                    <p class="text-2xl font-bold">125</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="ri-file-edit-fill text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Notas Pendientes</p>
                    <p class="text-2xl font-bold">32</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
                <div class="p-3 rounded-full bg-amber-100 text-amber-600 mr-4">
                    <i class="ri-calendar-todo-fill text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Clases Hoy</p>
                    <p class="text-2xl font-bold">3</p>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Módulos Principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 text-white">
                        <h2 class="text-xl font-bold">Gestión Académica</h2>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-blue-50 transition-colors">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mb-2">
                                <i class="ri-file-edit-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Registrar Notas</span>
                        </a>
                        <a href="" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-green-50 transition-colors">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mb-2">
                                <i class="ri-book-2-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Mis Cursos</span>
                        </a>
                        <a href="" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-purple-50 transition-colors">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mb-2">
                                <i class="ri-team-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Mis Estudiantes</span>
                        </a>
                        <a href="" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-amber-50 transition-colors">
                            <div class="p-3 rounded-full bg-amber-100 text-amber-600 mb-2">
                                <i class="ri-calendar-check-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Registrar Asistencias</span>
                        </a>
                        <a href="" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-red-50 transition-colors">
                            <div class="p-3 rounded-full bg-red-100 text-red-600 mb-2">
                                <i class="ri-file-upload-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Subir Material</span>
                        </a>
                        <a href="" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-indigo-50 transition-colors">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mb-2">
                                <i class="ri-settings-4-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Configuración</span>
                        </a>
                    </div>
                </div>

                <!-- Notas Pendientes -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-600 to-orange-800 p-4 text-white">
                        <h2 class="text-xl font-bold">Evaluaciones Pendientes</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-start border-b border-gray-100 pb-4">
                            <div class="p-2 rounded-full bg-red-100 text-red-600 mr-3">
                                <i class="ri-alarm-warning-fill"></i>
                            </div>
                            <div>
                                <p class="font-medium">Matemáticas 4to "A"</p>
                                <p class="text-sm text-gray-500">Examen parcial - Vence hoy</p>
                            </div>
                        </div>
                        <div class="flex items-start border-b border-gray-100 pb-4">
                            <div class="p-2 rounded-full bg-amber-100 text-amber-600 mr-3">
                                <i class="ri-time-fill"></i>
                            </div>
                            <div>
                                <p class="font-medium">Ciencias 3ro "B"</p>
                                <p class="text-sm text-gray-500">Trabajo práctico - Vence en 2 días</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                                <i class="ri-time-fill"></i>
                            </div>
                            <div>
                                <p class="font-medium">Historia 5to "C"</p>
                                <p class="text-sm text-gray-500">Proyecto grupal - Vence en 5 días</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho -->
            <div class="space-y-6">
                <!-- Horario de Clases -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 p-4 text-white">
                        <h2 class="text-xl font-bold">Horario de Hoy</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-start border-b border-gray-100 pb-4">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                <i class="ri-time-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">8:00 AM - 9:30 AM</p>
                                <p class="text-sm text-gray-500">Matemáticas - 4to "A" (Aula 203)</p>
                            </div>
                        </div>
                        <div class="flex items-start border-b border-gray-100 pb-4">
                            <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                <i class="ri-time-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">10:00 AM - 11:30 AM</p>
                                <p class="text-sm text-gray-500">Ciencias - 3ro "B" (Laboratorio 2)</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                <i class="ri-time-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">2:00 PM - 3:30 PM</p>
                                <p class="text-sm text-gray-500">Historia - 5to "C" (Aula 105)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accesos Directos -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-600 to-teal-800 p-4 text-white">
                        <h2 class="text-xl font-bold">Accesos Rápidos</h2>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                <i class="ri-calendar-2-fill"></i>
                            </div>
                            <span>Horario Completo</span>
                            <i class="ri-arrow-right-s-line ml-auto text-gray-400"></i>
                        </a>
                        <a href="" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                <i class="ri-megaphone-fill"></i>
                            </div>
                            <span>Enviar Comunicados</span>
                            <i class="ri-arrow-right-s-line ml-auto text-gray-400"></i>
                        </a>
                        <a href="" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                <i class="ri-file-chart-fill"></i>
                            </div>
                            <span>Generar Reportes</span>
                            <i class="ri-arrow-right-s-line ml-auto text-gray-400"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Sistema de Gestión Académica - Colegio Brunning © {{ date('Y') }}</p>
            <p class="mt-1">Panel de Docente - Versión 2.1.0</p>
        </div>
    </div>
@endsection