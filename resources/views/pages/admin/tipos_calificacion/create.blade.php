@extends('layout.admin.plantilla')

@section('titulo', 'Registrar Tipo de Calificación')

@section('contenido')
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Registrar Tipo de Calificación</h1>

        <form action="{{ route('tipos-calificacion.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700">Código</label>
                <input id="codigo" name="codigo" value="{{ old('codigo') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required placeholder="Ej: EXA, TAR, PAR">
                @error('codigo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input id="nombre" name="nombre" value="{{ old('nombre') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required placeholder="Ej: Examen, Tarea, Participación">
                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea id="descripcion" name="descripcion"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    placeholder="Descripción detallada del tipo de calificación">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('tipos-calificacion.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-[#98C560] hover:bg-[#7aa94f] rounded-md text-white text-sm">Guardar</button>
            </div>
        </form>
    </div>
@endsection