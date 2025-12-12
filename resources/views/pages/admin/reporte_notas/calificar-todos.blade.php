@extends('layout.admin.plantilla')

@section('titulo', 'Calificar todos los periodos - Prueba')

@section('contenido')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Calificar todos los periodos - {{ $asignatura->nombre }}</h1>

    <form action="{{ route('reporte_notas.guardar-todos') }}" method="POST">
        @csrf
        <input type="hidden" name="id_asignatura" value="{{ $asignatura->codigo_asignatura }}">

        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200 shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estudiante</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Competencia</th>
                        @foreach($periodos as $periodo)
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $periodo->nombre }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $row = 1; @endphp
                    @foreach($matriculas as $mat)
                        @foreach($competencias as $competencia)
                            <tr>
                                <td class="px-6 py-3 text-sm text-gray-700">{{ $row++ }}</td>
                                <td class="px-6 py-3 text-sm text-gray-800">{{ $mat->estudiante->persona->name }} {{ $mat->estudiante->persona->lastname }} <br><span class="text-xs text-gray-500">{{ $mat->codigo_matricula }}</span></td>
                                <td class="px-6 py-3 text-sm text-gray-700">{{ $competencia->descripcion }}</td>

                                @php
                                    $detalle = $detalles->get($mat->codigo_matricula)->where('id_competencias', $competencia->id_competencias)->first() ?? null;
                                @endphp

                                @foreach($periodos as $periodo)
                                    @php
                                        $reporte = null;
                                        if ($detalle) {
                                            $reporte = $detalle->reportesNotas->where('id_periodo', $periodo->id_periodo)->first();
                                        }
                                        $valor = $reporte->calificacion ?? '';
                                        $observacion = $reporte->observacion ?? '';
                                    @endphp

                                    <td class="px-3 py-2 text-center align-middle">
                                        <select name="calificaciones[{{ $periodo->id_periodo }}][{{ $detalle?->id_detalle_asignatura ?? 'new_' . $mat->codigo_matricula . '_' . $competencia->id_competencias }}]" class="border rounded px-2 py-1 text-sm w-24">
                                            <option value="">-</option>
                                            <option value="AD" {{ $valor === 'AD' ? 'selected' : '' }}>AD</option>
                                            <option value="A" {{ $valor === 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ $valor === 'B' ? 'selected' : '' }}>B</option>
                                            <option value="C" {{ $valor === 'C' ? 'selected' : '' }}>C</option>
                                        </select>
                                        <div class="mt-1">
                                            <input type="text" name="observaciones[{{ $periodo->id_periodo }}][{{ $detalle?->id_detalle_asignatura ?? 'new_' . $mat->codigo_matricula . '_' . $competencia->id_competencias }}]" value="{{ $observacion }}" placeholder="Obs." class="text-xs w-full border rounded px-2 py-1">
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">Guardar calificaciones (multi-periodo)</button>
            <a href="{{ route('docentes.asignaturas') }}" class="ml-2 bg-gray-200 px-4 py-2 rounded">Volver</a>
        </div>
    </form>
</div>
@endsection
