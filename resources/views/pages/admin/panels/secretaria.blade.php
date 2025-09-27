@extends('layout.admin.plantilla')

@section('titulo','Panel de Tesorero - Colegio Brunning')

@section('contenido')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Panel de Tesorería</h1>
                <p class="text-gray-600">Colegio Brunning - Gestión Financiera</p>
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
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="ri-file-list-3-fill text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Matrículas Validadas</p>
                    <p class="text-2xl font-bold">{{ $matriculas->filter(function ($m) {return $m->estado_validacion;})->count() }}</p>
                </div>
            </div>

             <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pagados</p>
                        <p class="text-2xl font-bold text-gray-900">
                            S/ {{ number_format($pagos->where('estado', 'Finalizado')->sum('monto'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pendientes</p>
                        <p class="text-2xl font-bold text-gray-900">
                            S/ {{ number_format($pagos->where('estado', '!=', 'Finalizado')->sum('monto'), 2) }}
                        </p>
                    </div>
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
                        <h2 class="text-xl font-bold">Operaciones Financieras</h2>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="{{route('matriculas.index')}}" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-blue-50 transition-colors">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mb-2">
                                <i class="ri-checkbox-circle-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Validar Matrículas</span>
                        </a>
                        <a href="#" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-green-50 transition-colors">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mb-2">
                                <i class="ri-money-dollar-circle-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Asignar Docentes</span>
                        </a>
                        <a href="{{route('estudiantes.buscar')}}" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-purple-50 transition-colors">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mb-2">
                                <i class="ri-team-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Ver Estudiantes</span>
                        </a>
                        <a href="{{route('docentes.buscar')}}" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-amber-50 transition-colors">
                            <div class="p-3 rounded-full bg-amber-100 text-amber-600 mb-2">
                                <i class="ri-user-star-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Ver Docentes</span>
                        </a>
                        <a href="" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-red-50 transition-colors">
                            <div class="p-3 rounded-full bg-red-100 text-red-600 mb-2">
                                <i class="ri-file-chart-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Generar Reportes</span>
                        </a>
                        <a href="{{route('users.edit_user')}}" class="flex flex-col items-center justify-center p-4 rounded-lg border border-gray-200 hover:bg-indigo-50 transition-colors">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mb-2">
                                <i class="ri-settings-4-fill text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-center">Configuración</span>
                        </a>
                    </div>
                </div>

                <!-- Transacciones Recientes -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-900 p-4 text-white">
                        <h2 class="text-xl font-bold">Transacciones Recientes</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach ($pagosReciente as $pago )
                        @php
                        $color = ' ';
                        if ($pago->monto >= 300) {
                            $color = 'green';
                        } elseif ($pago->monto >= 100) {
                            $color = 'blue';
                        } else {        
                            $color = 'amber';
                        }
                        @endphp
                        
                        <div class="flex items-start border-b border-gray-100 pb-4">
                            <div class="p-2 rounded-full bg-green-100 text-{{$color}}-600 mr-3">
                                <i class="ri-money-dollar-circle-line"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <p class="font-medium">{{$pago->matricula->estudiante->persona->name}} {{$pago->matricula->estudiante->persona->lastname}}</p>
                                    <p class="font-bold text-{{$color}}-600">S/ {{$pago->monto}}</p>
                                </div>
                                <p class="text-sm text-gray-500">{{$pago->estado}} - {{$pago->fecha_pago}}</p>
                            </div>
                        </div>
                          @endforeach
                    </div>
                </div>
            </div>

            <!-- Panel Derecho -->
            <div class="space-y-6">
                <!-- Pendientes de Validación -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 p-4 text-white">
                        <h2 class="text-xl font-bold">Matrículas Pendientes</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach ($pagosPendientes as $pago )
                        <div class="flex items-start border-b border-gray-100 pb-4">
                            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                                <i class="ri-time-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{$pago->matricula->estudiante->persona->name}} {{$pago->matricula->estudiante->persona->lastname}}</p>
                                <p class="text-sm text-gray-500">Falta comprobante de pago</p>
                            </div>
                        </div>
                         @endforeach                    
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
                                <i class="ri-bank-card-fill"></i>
                            </div>
                            <span>Cuentas Bancarias</span>
                            <i class="ri-arrow-right-s-line ml-auto text-gray-400"></i>
                        </a>
                        <a href="" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                <i class="ri-price-tag-3-fill"></i>
                            </div>
                            <span>Conceptos de Pago</span>
                            <i class="ri-arrow-right-s-line ml-auto text-gray-400"></i>
                        </a>
                        <a href="" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                <i class="ri-history-fill"></i>
                            </div>
                            <span>Historial Completo</span>
                            <i class="ri-arrow-right-s-line ml-auto text-gray-400"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Sistema de Gestión Académica - Colegio Brunning © {{ date('Y') }}</p>
            <p class="mt-1">Panel de Tesorería - Versión 2.1.0</p>
        </div>
    </div>
@endsection