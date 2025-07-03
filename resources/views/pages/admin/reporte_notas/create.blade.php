@extends('layout.admin.plantilla')

@section('titulo','Registrar Sección')

@section('contenido')
<div class="max-w-3xl mx-auto mt-10 bg-white shadow-lg rounded-xl p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Asignar Nota {{$id_asignatura}}</h2>

    <form action="{{ route('reporte_notas.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Competencia / Detalle Asignatura:</label>
            <select name="id_detalle_asignatura" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                @foreach($detalles_asignatura as $detalle)
                    <option value="{{ $detalle->id_detalle_asignatura }}">
                        {{ $detalle->competencia->descripcion ?? 'Competencia sin nombre' }}
                    </option>
                @endforeach
            </select>
            @error('id_detalle_asignatura')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Tipo de Calificación:</label>
            <select name="id_tipo_calificacion" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                @foreach($tipos_cal as $tipo)
                    <option value="{{ $tipo->id_tipo_calificacion }}">{{ $tipo->codigo }}</option>
                @endforeach
            </select>
            @error('id_tipo_calificacion')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Periodo:</label>
            <select name="id_periodo" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id_periodo }}">{{ $periodo->nombre }}</option>
                @endforeach
            </select>
            @error('id_periodo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Observación:</label>
            <input type="text" name="observacion" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Ingrese observación..." value="{{ old('observacion') }}">
            @error('observacion')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <input type="hidden" name="fecha_registro" value="{{ now() }}">
        <input type="hidden" name="id_asignatura" value="{{ $id_asignatura }}">


        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Guardar Nota
            </button>
        </div>
    </form>
</div>
@endsection