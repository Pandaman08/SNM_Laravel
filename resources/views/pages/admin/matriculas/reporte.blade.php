@extends('layout.admin.plantilla')
@section('content')
<div class="container">
    <h1 class="mb-4">Reporte de Matrículas</h1>

    {{-- Tabla --}}
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Código Matrícula</th>
                <th>Estudiante</th>
                <th>DNI</th>
                <th>Grado</th>
                <th>Sección</th>
                <th>Nivel</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matriculas as $index => $matricula)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $matricula->codigo_matricula }}</td>
                    <td>
                        {{ optional($matricula->estudiante->persona)->name ?? '---' }}
                        {{ optional($matricula->estudiante->persona)->lastname ?? '' }}
                    </td>
                    <td>{{ optional($matricula->estudiante->persona)->dni ?? '---' }}</td>
                    <td>{{ optional($matricula->seccion->grado)->grado ?? '---' }}</td>
                    <td>{{ $matricula->seccion->seccion ?? '---' }}</td>
                    <td>{{ optional($matricula->seccion->grado->nivelEducativo)->nombre ?? '---' }}</td>
                    <td>
                        {{ $matricula->fecha ? \Carbon\Carbon::parse($matricula->fecha)->format('d/m/Y') : '---' }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $matricula->estado == 'activo' ? 'success' : ($matricula->estado == 'pendiente' ? 'warning' : 'danger') }}">
                            {{ ucfirst($matricula->estado) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No hay matrículas registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Gráfico con Chart.js --}}
    <h2 class="mt-5">Distribución de Matrículas por Estado</h2>
    <canvas id="matriculasChart"></canvas>
</div>

{{-- Cargar Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('matriculasChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($estadisticas->keys()),
            datasets: [{
                label: 'Cantidad',
                data: @json($estadisticas->values()),
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',   
                    'rgba(255, 193, 7, 0.7)',  
                    'rgba(220, 53, 69, 0.7)'    
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
@endsection


