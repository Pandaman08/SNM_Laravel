@extends('layout.admin.plantilla')

@section('titulo', 'Editar grado')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center gap-2 border-b pb-3">
        <i class="ri-pencil-line text-2xl text-[#d97706]"></i> Editar grado
    </h1>

    <form action="{{ route('grados.update', $grado->id_grado) }}" method="POST" class="space-y-6" novalidate>
        @csrf
        @method('PUT')

        {{-- Grado --}}
        <div>
            <label for="grado" class="block text-gray-700 font-semibold mb-2">
                Grado <span class="text-red-500">*</span>
            </label>
            <select
                name="grado"
                id="grado"
                class="w-full rounded-lg border @error('grado') border-red-500 @else border-gray-300 @enderror p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition"
                required
            >
                <option value="" disabled {{ old('grado', $grado->grado) ? '' : 'selected' }}>Seleccione grado</option>
                @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('grado', $grado->grado) == $i ? 'selected' : '' }}>
                        {{ $i }}°
                    </option>
                @endfor
            </select>
            @error('grado')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nivel educativo --}}
        <div>
            <label for="nivel_educativo_id" class="block text-gray-700 font-semibold mb-2">
                Nivel educativo <span class="text-red-500">*</span>
            </label>
            <select
                name="nivel_educativo_id"
                id="nivel_educativo_id"
                class="w-full rounded-lg border @error('nivel_educativo_id') border-red-500 @else border-gray-300 @enderror p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition"
                required
            >
                <option value="" disabled {{ old('nivel_educativo_id', $grado->nivel_educativo_id) ? '' : 'selected' }}>Seleccione nivel</option>
                @foreach($nivelesEducativos as $nivel)
                    <option value="{{ $nivel->id_nivel_educativo }}"
                        {{ old('nivel_educativo_id', $grado->nivel_educativo_id) == $nivel->id_nivel_educativo ? 'selected' : '' }}>
                        {{ $nivel->nombre }}
                    </option>
                @endforeach
            </select>
            @error('nivel_educativo_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-4 pt-6 border-t">
            <a
                href="{{ route('grados.index') }}"
                class="inline-flex items-center px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700
                       font-medium rounded-lg shadow-sm transition transform hover:-translate-y-0.5"
            >
                Cancelar
            </a>
            <button
                type="submit"
                class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-[#fbbf24] to-[#d97706]
                       hover:from-[#d97706] hover:to-[#f59e0b] text-white font-semibold rounded-lg shadow-md
                       transition transform hover:-translate-y-0.5"
            >
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection