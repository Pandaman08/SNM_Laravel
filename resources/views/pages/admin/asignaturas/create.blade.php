@extends('layout.admin.plantilla')

@section('titulo','Registrar asignatura')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow-lg border border-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-2">Registrar asignatura</h1>

    <form action="{{ route('asignaturas.store') }}" method="POST" class="space-y-6" novalidate>
        @csrf

        {{-- Grado --}}
        <div>
            <label for="id_grado" class="block text-gray-700 font-semibold mb-2">
                Grado <span class="text-red-500">*</span>
            </label>
            <select id="id_grado" name="id_grado" 
                class="w-full rounded-md border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560]" 
                required aria-describedby="id_grado_help"
            >
                <option value="" disabled {{ old('id_grado') ? '' : 'selected' }}>Seleccione un grado</option>
                @foreach($grados as $g)
                    <option value="{{ $g->id_grado }}" {{ old('id_grado') == $g->id_grado ? 'selected' : '' }}>
                        {{ $g->grado }}
                    </option>
                @endforeach
            </select>
            @error('id_grado')
                <p id="id_grado_help" class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nombre --}}
        <div>
            <label for="nombre" class="block text-gray-700 font-semibold mb-2">
                Nombre de la asignatura <span class="text-red-500">*</span>
            </label>
            <input id="nombre" name="nombre" type="text"
                value="{{ old('nombre') }}"
                class="w-full rounded-md border border-gray-300 p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560]" 
                required 
                aria-describedby="nombre_help"
            >
            @error('nombre')
                <p id="nombre_help" class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end space-x-3 pt-4 border-t">
            <a href="{{ route('asignaturas.index') }}" 
                class="inline-block px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold shadow-sm transition"
            >
                Cancelar
            </a>
            <button type="submit" 
                class="inline-block px-4 py-2 rounded-md bg-[#98C560] hover:bg-[#7aa94f] text-white font-semibold shadow-md transition"
            >
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
