@extends('layout.admin.plantilla')
@section('titulo', 'Reporte de Matriculados')

@section('contenido')
<div class="container py-4">

    <h2 class="mb-4 text-center fw-bold text-teal-600">Reporte de Matriculados</h2>

    <div class="row g-4 mb-4">
        <!-- Gráfico por Sección -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3 fw-semibold">Matriculados por Grado y Sección</h5>
                    <div id="chartSeccion" style="height: 400px;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfico por Estado -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3 fw-semibold">Estado de Matrículas</h5>
                    <div id="chartEstado" style="height: 380px;"></div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <!-- Tabla de alumnos -->
    <h4 class="mt-4 mb-3 text-center fw-bold text-primary">Lista de Alumnos Matriculados</h4>

    <div class="table-responsive shadow rounded-4 border border-light">
        <table class="table table-striped table-hover align-middle text-center mb-0">
            <thead class="bg-gradient text-white" style="background: linear-gradient(90deg, #0eaedaff);">
                <tr>
                    <th scope="col">Código Matrícula</th>
                    <th scope="col">Alumno</th>
                    <th scope="col">Sección</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnos as $m)
                <tr>
                    <td class="fw-semibold text-dark">{{ $m->codigo_matricula }}</td>
                    <td class="text-start">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        {{ $m->estudiante?->persona?->name ?? 'Sin nombre' }}
                        {{ $m->estudiante?->persona?->lastname ?? '' }}
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-info text-dark px-3 py-2">
                            {{ $m->seccion?->nombre_completo ?? 'Sin sección' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2 
                            @if($m->estado === 'Aprobado') bg-success
                            @elseif($m->estado === 'Pendiente') bg-warning text-dark
                            @elseif($m->estado === 'Rechazado') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ $m->estado ?? '-' }}
                        </span>
                    </td>
                    <td class="text-muted">{{ $m->fecha?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
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
