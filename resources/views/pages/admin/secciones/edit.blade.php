@extends('layout.admin.plantilla')

@section('titulo', 'Editar Sección')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Editar Sección</h1>

    <form action="{{ route('secciones.update', $seccion->id_seccion) }}" method="POST" class="space-y-6" novalidate>
        @csrf
        @method('PUT')

        <div>
            <label for="id_grado" class="block text-gray-700 font-semibold mb-2">Grado <span class="text-red-500">*</span></label>
            <select id="id_grado" name="id_grado" 
                class="w-full rounded-md border border-gray-300 p-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560]" 
                required aria-describedby="id_grado_help"
            >
                <option value="" disabled {{ old('id_grado', $seccion->id_grado) ? '' : 'selected' }} title="Seleccione un grado">Seleccione un grado</option>
                @foreach ($grados as $grado)
                    <option value="{{ $grado->id_grado }}" {{ old('id_grado', $seccion->id_grado) == $grado->id_grado ? 'selected' : '' }}>
                        {{ $grado->grado }}
                    </option>
                @endforeach
            </select>
            @error('id_grado')
                <p id="id_grado_help" class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="seccion" class="block text-gray-700 font-semibold mb-2">Sección <span class="text-red-500">*</span></label>
            <input 
                id="seccion" 
                type="text" 
                name="seccion" 
                value="{{ old('seccion', $seccion->seccion) }}" 
                maxlength="1" 
                class="w-full uppercase rounded-md border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560]" 
                required 
                aria-describedby="seccion_help"
                pattern="[A-Za-z]"
                title="Ingrese una sola letra para la sección"
            >
            @error('seccion')
                <p id="seccion_help" class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('secciones.index') }}" 
                class="inline-block px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold transition"
            >
                Cancelar
            </a>
            <button type="submit" 
                class="inline-block px-4 py-2 rounded-md bg-[#98C560] hover:bg-[#7aa94f] text-white font-semibold transition"
            >
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection
