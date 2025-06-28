@extends('layout.admin.plantilla')

@section('titulo','Visualizar Notas')

@section('contenido')

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Reporte de Notas - {{ $asignatura->nombre }}</h2>
        <a href="{{ route('reporte_notas.export', $asignatura->codigo_asignatura) }}" 
           class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg">
            <i class="ri-download-line mr-1"></i> Exportar Excel
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-md shadow">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="py-3 px-4 text-left">Estudiante</th>
                    <th class="py-3 px-4 text-left">Tipo Calificación</th>
                    <th class="py-3 px-4 text-left">Periodo</th>
                    <th class="py-3 px-4 text-left">Observación</th>
                    <th class="py-3 px-4 text-left">Fecha</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-200">
                @foreach($reportes as $reporte)
                <tr>
                    <td class="py-3 px-4">{{ $reporte->detalleAsignatura->matricula->estudiante->persona->name . ' ' .  $reporte->detalleAsignatura->matricula->estudiante->persona->lastname }}</td>
                    <td class="py-3 px-4">{{ $reporte->tipoCalificacion->codigo }}</td>
                    <td class="py-3 px-4">{{ $reporte->periodo->nombre }}</td>
                    <td class="py-3 px-4">{{ $reporte->observacion }}</td>
                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($reporte->fecha_registro)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
