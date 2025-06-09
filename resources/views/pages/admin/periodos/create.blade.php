@extends('layout.admin.plantilla')

@section('titulo', 'Registrar Periodo')

@section('contenido')
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">Registrar Periodo</h1>

        <form action="{{ route('periodos.store') }}" method="POST" class="space-y-5">
            @csrf


            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input id="nombre" name="nombre" value="{{ old('nombre') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-[#98C560] focus:border-[#98C560]"
                    required>
                @error('nombre')
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
