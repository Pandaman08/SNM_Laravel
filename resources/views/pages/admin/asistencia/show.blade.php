@extends('layout.admin.plantilla')

@section('contenido')
<div class="container mx-auto px-4 py-6">
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-t-lg">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Asistencias</h1>
                <p class="text-sm mt-1 opacity-90">Registros de asistencia del estudiante</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('asistencias.edit', $matricula->codigo_estudiante) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-md transition duration-200">
                    <i class="ri-edit-line mr-1"></i> Editar
                </a>
                <a href="{{ route('asistencias.index') }}" 
                    class="bg-green-500 bg-opacity-80 hover:bg-opacity-30 px-4 py-2 rounded-md transition duration-200">
                    <i class="ri-arrow-left-line mr-1"></i> Volver
                </a>
            </div>
        </div>
    </div>

    {{-- Información del Estudiante --}}
    <div class="p-6 bg-gray-50 border-b">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Estudiante</p>
                <p class="font-semibold">
                    {{ $matricula->estudiante->persona->lastname }}, 
                    {{ $matricula->estudiante->persona->name }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Código</p>
                <p class="font-semibold">{{ $matricula->codigo_estudiante }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Grado y Sección</p>
                <p class="font-semibold">
                    {{ $matricula->seccion->grado->nombre_completo }} - 
                    Sección {{ $matricula->seccion->seccion }}
                </p>
            </div>
        </div>
    </div>

    <div class="space-y-8 mt-6">
        @foreach($statsPorPeriodo as $stat)
        @php
            $periodo = $stat['periodo'];
            $estadoPeriodo = $stat['estadoPeriodo'];
            $dias = $stat['diasClases'];
            $diasTotales = $stat['diasTotalesPeriodo'];
            $totales = $stat['totales'];
            $porcentajes = $stat['porcentajes'];
            
            // Colores según el estado del periodo
            $headerColor = match($estadoPeriodo) {
                'futuro' => 'from-gray-400 to-gray-500',
                'actual' => 'from-purple-600 to-indigo-600',
                'pasado' => 'from-blue-600 to-blue-700',
                default => 'from-purple-600 to-indigo-600'
            };
            
            $estadoLabel = match($estadoPeriodo) {
                'futuro' => 'Próximo',
                'actual' => 'En curso',
                'pasado' => 'Finalizado',
                default => ''
            };
        @endphp

        <div class="bg-white rounded-lg shadow-md">
            {{-- Header periodo --}}
            <div class="bg-gradient-to-r {{ $headerColor }} text-white p-6 rounded-t-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-semibold">{{ $periodo->nombre }}</h2>
                        <p class="mt-1 text-sm">
                            {{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}
                            – 
                            {{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }}
                            @if($estadoPeriodo == 'actual')
                                ({{ $dias }} de {{ $diasTotales }} días lectivos)
                            @elseif($estadoPeriodo == 'pasado')
                                ({{ $diasTotales }} días lectivos)
                            @else
                                ({{ $diasTotales }} días lectivos programados)
                            @endif
                        </p>
                    </div>
                    <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                        {{ $estadoLabel }}
                    </span>
                </div>
            </div>

            {{-- Contenido según el estado del periodo --}}
            <div class="p-6">
                @if($estadoPeriodo == 'futuro')
                    {{-- Periodo futuro --}}
                    <div class="text-center py-8">
                        <i class="ri-time-line text-4xl text-gray-400"></i>
                        <p class="text-gray-500 mt-2">Este periodo aún no ha comenzado</p>
                        <p class="text-sm text-gray-400 mt-1">
                            Iniciará el {{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}
                        </p>
                    </div>
                @elseif($dias > 0)
                    {{-- Periodo con días lectivos --}}
                    <table class="w-full text-center mb-6">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2">Presente</th>
                                <th class="py-2">Ausente</th>
                                <th class="py-2">Justificado</th>
                                <th class="py-2">Tardanza</th>
                                <th class="py-2">Total Registros</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2 text-green-600 font-semibold">{{ $totales['Presente'] }}</td>
                                <td class="py-2 text-red-600 font-semibold">{{ $totales['Ausente'] }}</td>
                                <td class="py-2 text-blue-600 font-semibold">{{ $totales['Justificado'] }}</td>
                                <td class="py-2 text-yellow-600 font-semibold">{{ $totales['Tarde'] }}</td>
                                <td class="py-2 font-medium">{{ $stat['asistenciasCount'] }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Barras de porcentaje --}}
                    <div class="space-y-4">
                        @foreach($totales as $estado => $count)
                            @php
                                $pct = $porcentajes[$estado];
                                $color = match($estado) {
                                    'Presente' => 'bg-green-500',
                                    'Ausente' => 'bg-red-500',
                                    'Justificado' => 'bg-blue-500',
                                    'Tarde' => 'bg-yellow-500',
                                    default => 'bg-gray-500'
                                };
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>{{ $estado }} ({{ $count }})</span>
                                    <span>{{ $pct }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded h-3">
                                    <div class="h-3 {{ $color }} rounded transition-all duration-500" 
                                         style="width: {{ $pct }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($estadoPeriodo == 'actual' && $stat['asistenciasCount'] < $dias)
                        <div class="mt-4 p-3 bg-yellow-50 rounded text-sm text-yellow-800">
                            <i class="ri-alert-line mr-1"></i>
                            Hay {{ $dias - $stat['asistenciasCount'] }} días sin registro de asistencia
                        </div>
                    @endif
                @else
                    {{-- Periodo sin días lectivos registrados --}}
                    <div class="text-center py-8">
                        <i class="ri-calendar-check-line text-4xl text-gray-400"></i>
                        <p class="text-gray-500 mt-2">No hay registros de asistencia en este periodo</p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 text-right rounded-b-lg border-t">
                @if($estadoPeriodo != 'futuro' && $dias > 0)
                    <span class="text-sm text-gray-600">
                        Asistencia: {{ $totales['Presente'] + $totales['Tarde'] }} / {{ $dias }} días
                        ({{ $dias > 0 ? round((($totales['Presente'] + $totales['Tarde']) / $dias) * 100) : 0 }}%)
                    </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection