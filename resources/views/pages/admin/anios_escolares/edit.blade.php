@extends('layout.admin.plantilla')

@section('titulo', 'Editar Año Escolar')

@section('contenido')
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Editar Año Escolar</h1>

        <form action="{{ route('anios-escolares.update', $anio->id_anio_escolar) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="anio" class="block text-sm font-medium text-gray-700">Año Escolar</label>
                <input id="anio" name="anio" value="{{ old('anio', $anio->anio) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('anio')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea id="descripcion" name="descripcion"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]">{{ old('descripcion', $anio->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $anio->fecha_inicio) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('fecha_inicio')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', $anio->fecha_fin) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('fecha_fin')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="estado" class="block text-gray-700">Estado:</label>
                <select id="estado" name="estado"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Activo" {{ old('estado', $anio->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Finalizado" {{ old('estado', $anio->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                </select>
                @error('estado')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('anios-escolares.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-[#98C560] hover:bg-[#7aa94f] rounded-md text-white text-sm">Actualizar</button>
            </div>
        </form>
    </div>
@endsection