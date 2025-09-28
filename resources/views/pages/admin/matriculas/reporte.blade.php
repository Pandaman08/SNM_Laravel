@extends('layout.admin.plantilla')
@section('titulo', 'Reporte de Matriculados')

@section('contenido')
<div class="container py-5">

    <!-- Encabezado con filtro -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-5">
        <div class="bg-gradient-to-r from-gray-700 to-gray-900 p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                <i class="bi bi-bar-chart-line-fill text-blue-400"></i>
                <span>Reporte de Matrículas</span>
            </h2>

            <!-- Filtro -->
            <form method="GET" action="{{ route('matriculas.reporte') }}" class="flex items-center space-x-2">
                <label for="nivel" class="font-medium text-sm text-gray-200">Filtrar por:</label>
                <select name="nivel" id="nivel" onchange="this.form.submit()"
                    class="bg-white text-gray-800 border border-gray-300 rounded-lg px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Todos --</option>
                    <option value="Inicial" {{ ($nivel ?? '') == 'Inicial' ? 'selected' : '' }}>Inicial</option>
                    <option value="Primaria" {{ ($nivel ?? '') == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                    <option value="Secundaria" {{ ($nivel ?? '') == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Gráfico por Sección -->
        <div class="bg-white rounded-xl shadow p-4">
            <h5 class="text-lg font-semibold text-center text-gray-700 mb-3">
                <i class="bi bi-people-fill text-blue-500 me-1"></i> Matriculados por Grado y Sección
            </h5>
            <div id="chartSeccion" style="height: 400px;"></div>
        </div>

        <!-- Gráfico por Estado -->
        <div class="bg-white rounded-xl shadow p-4">
            <h5 class="text-lg font-semibold text-center text-gray-700 mb-3">
                <i class="bi bi-clipboard-check text-green-500 me-1"></i> Estado de Matrículas
            </h5>
            <div id="chartEstado" style="height: 380px;"></div>
        </div>
    </div>


<!-- Tabla de alumnos -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mt-6 mx-auto" style="max-width: 1100px;">
    <!-- Encabezado -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4">
        <h4 class="text-lg font-bold text-white text-center tracking-wide">
            📋 Lista de Alumnos Matriculados
        </h4>
    </div>

    <!-- Tabla -->
    <div class="p-0">
        <table class="table-auto w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="py-3">Código Matrícula</th>
                    <th scope="col" class="py-3">Alumno</th>
                    <th scope="col" class="py-3">Sección</th>
                    <th scope="col" class="py-3">Estado</th>
                    <th scope="col" class="py-3">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnos as $m)
                <tr class="hover:bg-gray-100 transition">
                    <td class="fw-semibold text-gray-800">{{ $m->codigo_matricula }}</td>
                    <td class="text-start">
                        <i class="bi bi-person-circle text-blue-600 me-2"></i>
                        <span class="text-gray-900">
                            {{ $m->estudiante?->persona?->name ?? 'Sin nombre' }}
                            {{ $m->estudiante?->persona?->lastname ?? '' }}
                        </span>
                    </td>         
                    <td class="text-blue-500 font-semibold"> {{ $m->seccion?->nombre_completo ?? 'Sin sección' }}

                    </td>
                    <td class="
                     @if($m->estado === 'Aprobado') text-green-600 font-semibold
                     @elseif($m->estado === 'Pendiente') text-yellow-600 font-semibold
                     @elseif($m->estado === 'Rechazado') text-red-600 font-semibold
                      @else text-gray-600
                       @endif">{{ $m->estado ?? '-' }}
                    </td>
                                     
    
                    <td class="text-gray-600">{{ $m->fecha?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">
                        <i class="bi bi-exclamation-circle me-2"></i> No hay alumnos matriculados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>

    // Gráfico por sección (Grafico de barras)
    var optionsSeccion = {
        chart: {
            type: 'bar',
            height: 400
        },
        series: [{
            name: 'Cantidad de Alumnos',
            data: {!! json_encode($matriculasPorSeccion->isNotEmpty() ? $matriculasPorSeccion->pluck('total') : []) !!}
        }],


        xaxis: {
            categories: {!! json_encode(
                $matriculasPorSeccion->isNotEmpty()
                    ? $matriculasPorSeccion->map(fn($m) => $m->seccion?->nombre_completo ?? 'Sin sección')
                    
                    : []
             ) !!},
    labels: {
        style: {
            fontSize: '10px',
            fontWeight: 500
        },
        rotate: -25,   
        trim: false    // evita que se recorten
    }
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '50%',
                distributed: true
            }
        },
        colors: ['#1E90FF', '#FF7F50', '#32CD32', '#FFD700', '#8A2BE2', '#FF69B4'],
        legend: { show: false }
    };

    
    new ApexCharts(document.querySelector("#chartSeccion"), optionsSeccion).render();

    // Gráfico por estado 
    var optionsEstado = {
        chart: {
            type: 'donut',
            height: 380
        },
        series: {!! json_encode($matriculasPorEstado->isNotEmpty() ? $matriculasPorEstado->pluck('total') : []) !!},
        labels: {!! json_encode($matriculasPorEstado->isNotEmpty() ? $matriculasPorEstado->pluck('estado') : []) !!},
        colors: ['#e15f0eff', '#26cd00ff', '#FFCE56', '#C9CBCE'],
        legend: {
            position: 'bottom'
        }
    };
    new ApexCharts(document.querySelector("#chartEstado"), optionsEstado).render();
</script>
@endsection
