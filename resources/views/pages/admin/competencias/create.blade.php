@extends('layout.admin.plantilla')

@section('titulo','Registrar Competencia')

@section('contenido')
<div class="max-w-xl mx-auto bg-gradient-to-br from-white to-gray-50 p-8 rounded-3xl shadow-2xl border border-gray-100 animate-fade-in">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8 flex items-center justify-center gap-3">
        <span class="p-3 bg-[#38b2ac] rounded-full text-white shadow-lg">
            <i class="ri-trophy-line text-2xl"></i>
        </span>
        Registrar Competencia
    </h1>

    <form action="{{ route('competencias.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- Asignatura --}}
        <div class="space-y-1">
            <label for="codigo_asignatura" class="block text-gray-800 font-medium">Asignatura <span class="text-red-500">*</span></label>
            <div class="relative">
                <select id="codigo_asignatura" name="codigo_asignatura"
                    class="w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-inner
                           focus:outline-none focus:ring-2 focus:ring-[#38b2ac] focus:border-transparent transition"
                    required>
                    <option value="" disabled selected>Seleccione una asignatura...</option>
                    @foreach($asignaturas as $asignatura)
                        <option value="{{ $asignatura->codigo_asignatura }}"
                            {{ old('codigo_asignatura') == $asignatura->codigo_asignatura ? 'selected' : '' }}>
                            {{ $asignatura->nombre }} ({{ $asignatura->grado->grado }}° {{ $asignatura->grado->nivelEducativo->nombre }})
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <i class="ri-arrow-down-s-line text-gray-400 text-xl"></i>
                </div>
            </div>
            @error('codigo_asignatura')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="space-y-1">
            <label for="descripcion" class="block text-gray-800 font-medium">Descripción <span class="text-red-500">*</span></label>
            <div class="relative">
                <textarea id="descripcion" name="descripcion" rows="4"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-inner
                           focus:outline-none focus:ring-2 focus:ring-[#38b2ac] focus:border-transparent transition"
                    placeholder="Ingrese la descripción de la competencia..."
                    required>{{ old('descripcion') }}</textarea>
                <div class="absolute inset-y-0 right-3 top-3 text-gray-400">
                    <i class="ri-file-text-line text-lg"></i>
                </div>
            </div>
            @error('descripcion')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end items-center gap-4 mt-6">
            <a href="{{ route('competencias.index') }}"
               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                Cancelar
            </a>
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-[#38b2ac] to-[#2c7a7b]
                       hover:from-[#2c7a7b] hover:to-[#285e61] text-white font-semibold rounded-xl
                       shadow-lg transition transform hover:-translate-y-0.5">
                <i class="ri-save-line mr-2 text-lg"></i> Guardar
            </button>
        </div>
    </form>
</div>
@endsection