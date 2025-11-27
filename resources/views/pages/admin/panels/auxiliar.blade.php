@extends('layout.admin.plantilla')

@section('contenido')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">Panel de Asistencias</h1>
                                <p class="text-indigo-100 mt-1">Bienvenido, {{ $user->persona->name }} {{ $user->persona->lastname }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl">
                                <p class="text-white text-sm font-medium">
                                    <i class="ri-calendar-line mr-1"></i>
                                    {{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agregar después del header -->
        <div class="mb-6">
            <form action="{{ route('auxiliar.marcar-ausencias') }}" method="POST">
                @csrf
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Marcar Ausencias Automáticas
                </button>
            </form>
        </div>

        <!-- Horarios del día -->
        @if(isset($horarioPromedio))
        <div class="mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-center gap-12">
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span class="text-sm font-medium opacity-90">Horario de Entrada</span>
                        </div>
                        <p class="text-3xl font-bold">
                            {{ \Carbon\Carbon::parse($horarioPromedio->hora_entrada_promedio)->format('h:i A') }}
                        </p>
                    </div>
                    
                    <div class="h-16 w-px bg-white/30"></div>
                    
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="text-sm font-medium opacity-90">Horario de Salida</span>
                        </div>
                        <p class="text-3xl font-bold">
                            {{ \Carbon\Carbon::parse($horarioPromedio->hora_salida_promedio)->format('h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total de estudiantes -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Estudiantes</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalEstudiantes }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-xl">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Estudiantes presentes -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Presentes</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $estudiantesPresentes }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $totalEstudiantes > 0 ? round(($estudiantesPresentes/$totalEstudiantes)*100) : 0 }}%</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Estudiantes con tardanza -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Tardanzas</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $estudiantesTarde }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $totalEstudiantes > 0 ? round(($estudiantesTarde/$totalEstudiantes)*100) : 0 }}%</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-xl">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Estudiantes ausentes -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Ausentes</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $estudiantesAusentes }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $totalEstudiantes > 0 ? round(($estudiantesAusentes/$totalEstudiantes)*100) : 0 }}%</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-xl">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sin registrar -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Sin Registrar</p>
                        <p class="text-3xl font-bold text-gray-600 mt-2">{{ $estudiantesSinRegistro }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $totalEstudiantes > 0 ? round(($estudiantesSinRegistro/$totalEstudiantes)*100) : 0 }}%</p>
                    </div>
                    <div class="p-3 bg-gray-100 rounded-xl">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div> 

        <!-- Botones principales para escanear -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Escanear Entrada -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-8 text-white hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('asistencia.scanner') }}?tipo=entrada'">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Registrar Entrada</h3>
                        <p class="text-green-100">Escanear QR de estudiantes que ingresan</p>
                        @if(isset($horarioPromedio))
                        <p class="text-sm text-green-200 mt-2">
                            <i class="ri-time-line mr-1"></i>
                            Horario: {{ \Carbon\Carbon::parse($horarioPromedio->hora_entrada_promedio)->format('h:i A') }}
                        </p>
                        @endif
                    </div>
                    <div class="p-4 bg-white/20 rounded-2xl">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Escanear Salida -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-8 text-white hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('asistencia.scanner') }}?tipo=salida'">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Registrar Salida</h3>
                        <p class="text-blue-100">Escanear QR de estudiantes que salen</p>
                        @if(isset($horarioPromedio))
                        <p class="text-sm text-blue-200 mt-2">
                            <i class="ri-time-line mr-1"></i>
                            Horario: {{ \Carbon\Carbon::parse($horarioPromedio->hora_salida_promedio)->format('h:i A') }}
                        </p>
                        @endif
                    </div>
                    <div class="p-4 bg-white/20 rounded-2xl">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de Justificaciones Pendientes -->
        <div class="mb-8">
            <a href="{{ route('auxiliar.justificaciones') }}" class="block bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold mb-2">Justificaciones Pendientes</h3>
                        <p class="text-purple-100">Revisar solicitudes de justificación de ausencias</p>
                    </div>
                    <div class="p-4 bg-white/20 rounded-2xl">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Últimas asistencias registradas -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Últimos Registros</h3>
            </div>
            <div class="overflow-x-auto">
                @if($ultimasAsistencias->count() > 0)
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hora Entrada</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hora Salida</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Observación</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($ultimasAsistencias as $asistencia)
                                @php
                                    $horaEntrada = null;
                                    $horaSalida = null;
                                    
                                    if ($asistencia->hora_entrada) {
                                        try {
                                            $horaEntrada = \Carbon\Carbon::createFromFormat('H:i:s', $asistencia->hora_entrada);
                                        } catch (\Exception $e) {
                                            \Log::error('Error parseando hora_entrada: ' . $asistencia->hora_entrada);
                                        }
                                    }
                                    
                                    if ($asistencia->hora_salida) {
                                        try {
                                            $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $asistencia->hora_salida);
                                        } catch (\Exception $e) {
                                            \Log::error('Error parseando hora_salida: ' . $asistencia->hora_salida);
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ $asistencia->estudiante->persona->name }} {{ $asistencia->estudiante->persona->lastname }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            {{ $asistencia->estudiante->codigo_estudiante }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($horaEntrada)
                                            <div class="font-medium text-slate-900">{{ $horaEntrada->format('h:i A') }}</div>
                                            <div class="text-xs text-slate-500">{{ $horaEntrada->format('H:i:s') }}</div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($horaSalida)
                                            <div class="font-medium text-slate-900">{{ $horaSalida->format('h:i A') }}</div>
                                            <div class="text-xs text-slate-500">{{ $horaSalida->format('H:i:s') }}</div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $badgeColor = match($asistencia->estado->value) {
                                                'Presente' => 'bg-green-100 text-green-800',
                                                'Tarde' => 'bg-yellow-100 text-yellow-800',
                                                'Ausente' => 'bg-red-100 text-red-800',
                                                'Justificado' => 'bg-blue-100 text-blue-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $badgeColor }}">
                                            {{ $asistencia->estado->value }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ $asistencia->observacion ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-slate-500">No hay registros de asistencia hoy</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection