@extends('layout.admin.plantilla')

@section('titulo', 'Editar grado')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Editar grado</h1>

    <form action="{{ route('grados.update', $grado->id_grado) }}" method="POST" class="space-y-5" novalidate>
        @csrf
        @method('PUT')

        <div>
            <label for="grado" class="block text-sm font-semibold text-gray-700 mb-1">
                Nombre del grado <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="grado"
                name="grado"
                value="{{ old('grado', $grado->grado) }}"
                placeholder="Ej. Primero, Segundo, Tercero"
                maxlength="50"
                autocomplete="off"
                class="w-full rounded-md border @error('grado') border-red-500 @else border-gray-300 @enderror p-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560]"
                required
                aria-describedby="grado_help"
            >
            @error('grado')
                <p id="grado_help" class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('grados.index') }}" 
               class="inline-block px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold transition shadow">
                Cancelar
            </a>
            <button type="submit" 
                class="inline-block px-4 py-2 rounded-md bg-[#98C560] hover:bg-[#7aa94f] text-white font-semibold transition shadow">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection
