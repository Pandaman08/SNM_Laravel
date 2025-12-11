@extends('layout.admin.plantilla')

@section('titulo', 'Visualizar Notas')

@section('contenido')
    <div class="container mx-auto px-4 py-6">
        
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
            <div class="p-6 bg-gradient-to-r from-blue-50 to-gray-50 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Reporte de Calificaciones
                    </h2>
                </div>

                <div class="flex items-center mt-2 text-gray-600">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">{{ $matricula->estudiante->persona->name }}
                        {{ $matricula->estudiante->persona->lastname }}</span>
                </div>
                <div class="flex items-center mt-1 text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                        </path>
                    </svg>
                    <span class="font-semibold">Grado/Sección:</span>
                    <span class="ml-2">{{ $matricula->seccion->grado->grado }} "{{ $matricula->seccion->seccion }}"</span>
                </div>
                <div class="mt-3">
                    <a href="{{ route('reporte.notas.pdf', $matricula->codigo_matricula) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Generar PDF
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th rowspan="2"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100 border-r border-gray-200">
                                Área Curricular
                            </th>
                            <th rowspan="2"
                                class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100 border-r border-gray-200">
                                Competencias
                            </th>
                            @foreach (['PRIMER BIMESTRE', 'SEGUNDO BIMESTRE', 'TERCER BIMESTRE', 'CUARTO BIMESTRE'] as $bimestre)
                                <th colspan="2"
                                    class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100 @if (!$loop->last) border-r border-gray-200 @endif">
                                    {{ $bimestre }}
                                </th>
                            @endforeach
                            <th rowspan="2"
                                class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider bg-gray-100">
                                NL FINAL
                            </th>
                        </tr>
                        <tr class="bg-gray-50">
                            @foreach (range(1, 4) as $bimestre)
                                <th
                                    class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider border-r border-gray-200">
                                    NL
                                </th>
                                <th
                                    class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider @if ($bimestre < 4) border-r border-gray-200 @endif">
                                    Conclusión
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($asignaturas as $asignatura)
                            @php
                                $rowCount = count($asignatura->competencias);
                                $firstCompetencia = true;
                            @endphp

                            @foreach ($asignatura->competencias as $competencia)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    @if ($firstCompetencia)
                                        <td rowspan="{{ $rowCount }}"
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200 align-top">
                                            {{ $asignatura->nombre }}
                                        </td>
                                        @php $firstCompetencia = false; @endphp
                                    @endif

                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 border-r border-gray-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ $competencia->descripcion }}
                                        </div>
                                    </td>

                                    @php
                                        $detalle = $competencia->detallesAsignatura
                                            ->where('codigo_matricula', $matricula->codigo_matricula)
                                            ->first();
                                    @endphp

                                    @foreach (range(1, 4) as $periodo)
                                        @php
                                            $reporte = $detalle
                                                ? $detalle->reportesNotas->where('id_periodo', $periodo)->first()
                                                : null;
                                            $nota = $reporte->calificacion ?? null;
                                            $color = match ($nota) {
                                                'AD' => 'text-green-600 bg-green-50',
                                                'A' => 'text-blue-600 bg-blue-50',
                                                'B' => 'text-yellow-600 bg-yellow-50',
                                                'C' => 'text-orange-600 bg-orange-50',
                                                default => 'text-gray-600 bg-gray-50',
                                            };
                                        @endphp

                                        <td class="px-4 py-4 text-center text-sm font-medium border-r border-gray-200">
                                            @if ($nota)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                                    {{ $nota }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-4 py-4 text-center text-sm text-gray-600 @if ($periodo < 6) border-r border-gray-200 @endif">
                                            {{ $reporte->observacion ?? '-' }}
                                        </td>
                                    @endforeach

                                    <!-- Columna de Promedio Final -->
                                    <td class="px-4 py-4 text-center text-sm font-medium">
                                        @if ($detalle && $detalle->promedio)
                                            @php
                                                $colorPromedio = match ($detalle->promedio) {
                                                    'AD' => 'text-green-600 bg-green-50',
                                                    'A' => 'text-blue-600 bg-blue-50',
                                                    'B' => 'text-yellow-600 bg-yellow-50',
                                                    'C' => 'text-orange-600 bg-orange-50',
                                                    default => 'text-gray-600 bg-gray-50',
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $colorPromedio }}">
                                                {{ $detalle->promedio }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
       
    </div>
@endsection