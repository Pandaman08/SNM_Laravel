@extends('layout.admin.plantilla')

@section('titulo', 'Registrar Periodo')

@section('contenido')
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Editar Periodo</h1>

        <form action="{{ route('periodos.update',$periodo->id_periodo) }}" method="POST" class="space-y-5">
               @csrf 
               @method('PUT')


            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input id="nombre" name="nombre" value="{{ old('nombre', $periodo->nombre) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="numero_periodo" class="block text-sm font-medium text-gray-700">Numero Periodo</label>
                <input id="numero_periodo" name="numero_periodo" value="{{ old('numero_periodo', $periodo->numero_periodo) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('numero_periodo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $periodo->fecha_inicio) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('fecha_inicio')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="fecha_final" class="block text-sm font-medium text-gray-700">Fecha Final</label>
                <input type="date" id="fecha_inicio" name="fecha_final" value="{{ old('fecha_final', $periodo->fecha_final) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('fecha_final')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="estado" class="block text-gray-700">Estado:</label>
                <select id="estado" name="estado"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md
                                      focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('estado', $periodo->estado) }}">
                    <option value="No Iniciado">No Iniciado</option>
                    <option value="Proceso">Proceso</option>
                      <option value="Finalizado">Proceso</option>
                </select>
                  @error('estado')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('periodos.index') }}"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-[#98C560] hover:bg-[#7aa94f] rounded-md text-white text-sm">Guardar</button>
            </div>
        </form>
    </div>
@endsection
