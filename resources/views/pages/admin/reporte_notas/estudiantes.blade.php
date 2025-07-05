@extends('layout.admin.plantilla')

@section('titulo', 'Visualizar Notas')

@section('contenido')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-gray-50 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-800">
                    Reporte de Calificaciones - <span class="text-blue-600">{{ $asignatura->nombre }}</span>
                </h2>
            </div>
            <div class="flex items-center mt-2 text-gray-600">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">{{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th rowspan="2" class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100 border-r border-gray-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Competencias
                            </div>
                        </th>
                        @foreach(['PRIMER TRIMESTRE', 'SEGUNDO TRIMESTRE', 'TERCER TRIMESTRE'] as $index => $trimestre)
                        <th colspan="3" class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100 @if($index < 2) border-r border-gray-300 @endif">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $trimestre }}
                            </div>
                        </th>
                        @endforeach
                    </tr>
                    <tr class="bg-gray-50">
                        @foreach(range(3, 5) as $periodo)
                        <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                Nivel
                            </div>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Observación
                            </div>
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider @if($periodo < 5) border-r border-gray-300 @endif">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Estado
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($competencias as $competencia)
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 border-r border-gray-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $competencia->descripcion }}
                            </div>
                        </td>
                        
                        @foreach(range(3, 5) as $periodo)
                        @php
                            $reporte = $competencia->detallesAsignatura
                                ->flatMap->reportesNotas
                                ->where('id_periodo', $periodo)
                                ->first();
                            $nota = $reporte->tipoCalificacion->codigo ?? null;
                            $color = match($nota) {
                                'AD' => 'text-green-600 bg-green-50',
                                'A' => 'text-blue-600 bg-blue-50',
                                'B' => 'text-yellow-600 bg-yellow-50',
                                'C' => 'text-orange-600 bg-orange-50',
                                default => 'text-gray-600 bg-gray-50'
                            };
                        @endphp
                        
                        <td class="px-4 py-4 text-center text-sm font-medium border-r border-gray-200">
                            @if($nota)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                {{ $nota }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-gray-600 border-r border-gray-200">
                            {{ $reporte->observacion ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-center text-sm @if($periodo < 5) border-r border-gray-300 @endif">
                            @if($nota)
                            <span class="inline-flex items-center">
                                @if($nota == 'AD')
                                <svg class="w-5 h-5 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                Excelente
                                @elseif($nota == 'A')
                                <svg class="w-5 h-5 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                Bueno
                                @elseif($nota == 'B')
                                <svg class="w-5 h-5 text-yellow-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                                Regular
                                @else
                                <svg class="w-5 h-5 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ $reporte->tipoCalificacion->nombre ?? '-' }}
                                @endif
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection