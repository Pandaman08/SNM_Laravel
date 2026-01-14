@extends('layout.admin.plantilla')

@section('titulo', 'Editar Periodo')

@section('contenido')
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Editar Periodo: {{ $periodo->nombre }}</h1>

        <form action="{{ route('periodos.update', $periodo->id_periodo) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            @if($periodo->anioEscolar)
            <div class="mb-4 p-4 bg-gray-50 text-gray-700 rounded-md border border-gray-200">
                <span class="font-semibold">Año Escolar:</span> {{ $periodo->anioEscolar->anio }}
            </div>
            @endif

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Periodo</label>
                <input id="nombre" name="nombre" value="{{ old('nombre', $periodo->nombre) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560] transition duration-200"
                    required>
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $periodo->fecha_inicio) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560] transition duration-200"
                        required>
                    @error('fecha_inicio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', $periodo->fecha_fin) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560] transition duration-200"
                        required>
                    @error('fecha_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('periodos.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-[#98C560] hover:bg-[#7aa94f] rounded-md text-white text-sm font-medium shadow-sm transition duration-200">
                    Actualizar Periodo
                </button>
            </div>
        </form>
    </div>
@endsection