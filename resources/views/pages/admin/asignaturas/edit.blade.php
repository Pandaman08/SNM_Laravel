@extends('layout.admin.plantilla')

@section('titulo','Registrar asignatura')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center justify-center gap-2 border-b pb-4">
        <i class="ri-book-add-line text-2xl text-[#d946ef]"></i>
        Registrar asignatura
    </h1>

    <form action="{{ route('asignaturas.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Grado --}}
        <div>
            <label for="id_grado" class="block text-gray-700 font-semibold mb-2">
                Grado <span class="text-red-500">*</span>
            </label>
            <select id="id_grado" name="id_grado"
                class="w-full rounded-lg border border-gray-300 p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#d946ef] focus:border-[#d946ef]
                       transition"
                required aria-describedby="id_grado_help">
                <option value="" disabled {{ old('id_grado') ? '' : 'selected' }}>
                    — Seleccione un grado —
                </option>
                @foreach($grados as $g)
                    <option value="{{ $g->id_grado }}"
                        {{ old('id_grado') == $g->id_grado ? 'selected' : '' }}>
                        {{ $g->grado }}° — {{ $g->nivelEducativo->nombre }}
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
                class="w-full rounded-lg border border-gray-300 p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#d946ef] focus:border-[#d946ef]
                       transition"
                required aria-describedby="nombre_help">
            @error('nombre')
                <p id="nombre_help" class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-4 pt-6 border-t">
            <a href="{{ route('asignaturas.index') }}"
               class="inline-flex items-center px-5 py-2 bg-gray-100 hover:bg-gray-200
                      text-gray-700 font-medium rounded-lg shadow-sm transition transform hover:-translate-y-0.5">
                Cancelar
            </a>
            <button type="submit"
                class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-[#d946ef] to-[#9d174d]
                       hover:from-[#9d174d] hover:to-[#be185d] text-white font-semibold rounded-lg
                       shadow-md transition transform hover:-translate-y-0.5">
                <i class="ri-save-line mr-2"></i> Guardar
            </button>
        </div>
    </form>
</div>
@endsection