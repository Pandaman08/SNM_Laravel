@extends('layout.admin.plantilla')

@section('titulo', 'Editar Tipo de Calificación')

@section('contenido')
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Editar Tipo de Calificación</h1>

        <form action="{{ route('tipos-calificacion.update', $tipo->id_tipo_calificacion) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700">Código</label>
                <input id="codigo" name="codigo" value="{{ old('codigo', $tipo->codigo) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('codigo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input id="nombre" name="nombre" value="{{ old('nombre', $tipo->nombre) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea id="descripcion" name="descripcion"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]">{{ old('descripcion', $tipo->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('tipos-calificacion.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-[#98C560] hover:bg-[#7aa94f] rounded-md text-white text-sm">Actualizar</button>
            </div>
        </form>
    </div>
@endsection