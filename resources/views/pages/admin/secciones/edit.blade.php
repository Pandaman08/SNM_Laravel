@extends('layout.admin.plantilla')

@section('titulo','Editar Sección')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center justify-center gap-2 border-b pb-4">
        <i class="ri-pencil-line text-2xl text-[#10b981]"></i>
        Editar Sección
    </h1>

    <form action="{{ route('secciones.update', $seccion->id_seccion) }}" method="POST" class="space-y-6" novalidate>
        @csrf
        @method('PUT')

        {{-- Grado --}}
        <div>
            <label for="id_grado" class="block text-gray-700 font-semibold mb-2">
                Grado <span class="text-red-500">*</span>
            </label>
            <select id="id_grado" name="id_grado"
                class="w-full rounded-lg border border-gray-300 p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#10b981] focus:border-[#10b981]
                       transition"
                required aria-describedby="id_grado_help">
                <option value="" disabled {{ old('id_grado', $seccion->id_grado) ? '' : 'selected' }}>
                    — Seleccione un grado —
                </option>
                @foreach ($grados as $grado)
                    <option value="{{ $grado->id_grado }}"
                        {{ old('id_grado', $seccion->id_grado) == $grado->id_grado ? 'selected' : '' }}>
                        {{ $grado->nombre_completo }}
                    </option>
                @endforeach
            </select>
            @error('id_grado')
                <p id="id_grado_help" class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Sección --}}
        <div>
            <label for="seccion" class="block text-gray-700 font-semibold mb-2">
                Sección <span class="text-red-500">*</span>
            </label>
            <input id="seccion" name="seccion" type="text"
                value="{{ old('seccion', $seccion->seccion) }}"
                maxlength="1"
                pattern="[A-Za-z]"
                title="Ingrese una sola letra para la sección"
                class="w-full uppercase rounded-lg border border-gray-300 p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#10b981] focus:border-[#10b981]
                       transition"
                required aria-describedby="seccion_help">
            @error('seccion')
                <p id="seccion_help" class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-4 pt-6 border-t">
            <a href="{{ route('secciones.index') }}"
               class="inline-flex items-center px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700
                      font-medium rounded-lg shadow-sm transition transform hover:-translate-y-0.5">
                Cancelar
            </a>
            <button type="submit"
                class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-[#10b981] to-[#22c55e]
                       hover:from-[#22c55e] hover:to-[#10b981] text-white font-semibold rounded-lg
                       shadow-md transition transform hover:-translate-y-0.5">
                <i class="ri-save-line mr-2"></i> Actualizar
            </button>
        </div>
    </form>
</div>
@endsection