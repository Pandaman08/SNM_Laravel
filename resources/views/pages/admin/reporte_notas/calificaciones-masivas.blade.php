@extends('layout.admin.plantilla')

@section('titulo', 'Calificar - ' . $asignatura->nombre)

@section('contenido')
<div class="w-full animate-fade-in">
    @if (session('success') || session('error'))
        <div class="notification animate-fade-in-down">
            <div class="alert alert-{{ session('error') ? 'error' : 'success' }} shadow-lg mb-6">
                <div>
                    @if(session('error'))
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @endif
                    <span>{{ session('success') ?? session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Calificar Asignatura</h1>
                <p class="text-sm text-blue-600 font-medium flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ $asignatura->nombre }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="badge badge-lg bg-blue-100 text-blue-800 border-blue-200 rounded-3xl p-3 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Período {{ $periodoActual->numero_periodo }}
                <br>
                <span class="text-xs">({{ \Carbon\Carbon::parse($periodoActual->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($periodoActual->fecha_fin)->format('d/m/Y') }})</span>
            </span>
            
            <span class="badge badge-lg bg-green-100 text-green-800 border-green-200 rounded-3xl p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $matriculas->count() }} estudiantes
            </span>
        </div>
    </div>

    <!-- Información de Competencias -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Competencias a Evaluar:
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($competencias as $competencia)
            <div class="bg-white p-3 rounded-lg border border-blue-100">
                <p class="text-sm text-gray-700">
                    <span class="font-semibold text-blue-600">C{{ $loop->index + 1 }}:</span> 
                    {{ $competencia->descripcion }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tabla de Calificaciones -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
        <form id="formCalificaciones" method="POST" action="{{ route('reporte_notas.guardar-masivas') }}">
            @csrf

            <input type="hidden" name="id_asignatura" value="{{ $asignatura->codigo_asignatura }}">
            <input type="hidden" name="id_periodo" value="{{ $periodoActual->id_periodo }}">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Header Dinámico: Competencias por Período -->
                    <thead class="bg-gradient-to-r from-blue-500 to-blue-600">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider sticky left-0 bg-gradient-to-r from-blue-500 to-blue-600 z-10 min-w-max">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Estudiante
                                </div>
                            </th>
                            @foreach($competencias as $index => $competencia)
                            <th scope="col" class="px-4 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider border-l border-blue-400">
                                <div class="font-bold text-sm">C{{ $index + 1 }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>

                    <!-- Body: Estudiantes -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($matriculas as $matricula)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <!-- Nombre del Estudiante -->
                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white hover:bg-gray-50 z-5 min-w-max">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}
                                    </div>
                                </div>
                            </td>

                            <!-- Inputs de Calificaciones por Competencia -->
                            @foreach($competencias as $competencia)
                            <td class="px-4 py-4 text-center border-l border-gray-200">
                                @php
                                    $detallesEstudiante = $detalles->get($matricula->codigo_matricula, collect());
                                    $detalleCompetencia = $detallesEstudiante->firstWhere('id_competencias', $competencia->id_competencias);
                                    $reporteActual = $detalleCompetencia?->reportesNotas->first();
                                @endphp

                                @if($notasRegistradas && $reporteActual)
                                    <!-- Modo edición: si las notas ya están registradas -->
                                    <select name="calificaciones_editar_valores[{{ $reporteActual->id_reporte_notas }}]" 
                                            class="w-full px-2 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent font-semibold">
                                        <option value="">-</option>
                                        <option value="AD" {{ $reporteActual->calificacion === 'AD' ? 'selected' : '' }}>AD</option>
                                        <option value="A" {{ $reporteActual->calificacion === 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $reporteActual->calificacion === 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ $reporteActual->calificacion === 'C' ? 'selected' : '' }}>C</option>
                                    </select>
                                @else
                                    <!-- Modo registro: si aún no hay notas -->
                                    @if($detalleCompetencia)
                                    <select name="calificaciones[{{ $detalleCompetencia->id_detalle_asignatura }}]" 
                                            class="w-full px-2 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent font-semibold">
                                        <option value="">-</option>
                                        <option value="AD">AD</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                    @else
                                    <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $competencias->count() + 1 }}" class="px-6 py-8 text-center">
                                <p class="text-gray-500">No hay estudiantes matriculados en esta asignatura</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Botones de acción -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                <a href="{{ route('docentes.asignaturas') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md shadow-sm text-sm font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancelar
                </a>

                @if(!$notasRegistradas)
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Registrar Calificaciones
                    </button>
                @else
                    <button type="button" 
                            onclick="document.getElementById('formEditar').submit()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Calificaciones
                    </button>
                @endif
            </div>
        </form>

        <!-- Formulario oculto para editar -->
        @if($notasRegistradas)
        <form id="formEditar" method="POST" action="{{ route('reporte_notas.actualizar-masivas') }}" style="display: none;">
            @csrf
            <input type="hidden" name="id_periodo" value="{{ $periodoActual->id_periodo }}">
            @foreach($matriculas as $matricula)
                @php
                    $detallesEstudiante = $detalles->get($matricula->codigo_matricula, collect());
                @endphp
                @foreach($competencias as $competencia)
                    @php
                        $detalleCompetencia = $detallesEstudiante->firstWhere('id_competencias', $competencia->id_competencias);
                        $reporteActual = $detalleCompetencia?->reportesNotas->first();
                    @endphp
                    @if($reporteActual)
                        <input type="hidden" name="calificaciones[{{ $reporteActual->id_reporte_notas }}]" value="{{ $reporteActual->calificacion }}">
                    @endif
                @endforeach
            @endforeach
        </form>
        @endif
    </div>
</div>

<script>
    document.getElementById('formCalificaciones').addEventListener('submit', function(e) {
        // Validar que haya al menos una calificación ingresada
        const inputs = this.querySelectorAll('select[name^="calificaciones"]');
        let hayCalificaciones = false;
        
        inputs.forEach(input => {
            if (input.value && input.value !== '') {
                hayCalificaciones = true;
            }
        });

        if (!hayCalificaciones) {
            e.preventDefault();
            alert('Debe ingresar al menos una calificación');
        }
    });

    // Para modo edición: permitir cambios en los selects
    document.querySelectorAll('select[name^="calificaciones_editar_valores"]').forEach(select => {
        select.addEventListener('change', function() {
            this.classList.add('bg-yellow-50');
            this.classList.add('border-yellow-400');
        });
    });
</script>
@endsection