@extends('layout.admin.plantilla')

@section('titulo', 'Registrar grado')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Registrar grado</h1>

    <form action="{{ route('grados.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="grado" class="block text-sm font-medium text-gray-700">Nombre del grado</label>
            <input
                type="text"
                name="grado"
                id="grado"
                value="{{ old('grado') }}"
                placeholder="Ej. 1.º Primaria"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#98C560] focus:ring-[#98C560]"
                required
            >
            @error('grado')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('grados.index') }}" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-sm font-medium">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 rounded-md bg-[#98C560] hover:bg-[#7aa94f] text-white text-sm font-medium">
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
