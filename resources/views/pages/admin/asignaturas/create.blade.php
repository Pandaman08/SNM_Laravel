@extends('layout.admin.plantilla')

@section('titulo','Registrar asignatura')

@section('contenido')
<div class="max-w-xl mx-auto bg-gradient-to-br from-white to-gray-50 p-8 rounded-3xl shadow-2xl border border-gray-100 animate-fade-in">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8 flex items-center justify-center gap-3">
        <span class="p-3 bg-[#38b2ac] rounded-full text-white shadow-lg">
            <i class="ri-book-add-line text-2xl"></i>
        </span>
        Registrar Asignatura
    </h1>

    <form action="{{ route('asignaturas.store') }}" method="POST" class="space-y-8" novalidate>
        @csrf

        {{-- Grado --}}
        <div class="space-y-1">
            <label for="id_grado" class="block text-gray-800 font-medium">Grado <span class="text-red-500">*</span></label>
            <div class="relative">
                <select id="id_grado" name="id_grado"
                    class="w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-inner
                           focus:outline-none focus:ring-2 focus:ring-[#38b2ac] focus:border-transparent transition"
                    required>
                    <option value="" disabled {{ old('id_grado') ? '' : 'selected' }}>Seleccione un grado...</option>
                    @foreach($grados as $g)
                        <option value="{{ $g->id_grado }}"
                            {{ old('id_grado') == $g->id_grado ? 'selected' : '' }}>
                            {{ $g->grado }}° — {{ $g->nivelEducativo->nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <i class="ri-arrow-down-s-line text-gray-400 text-xl"></i>
                </div>
            </div>
            @error('id_grado')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nombre --}}
        <div class="space-y-1">
            <label for="nombre" class="block text-gray-800 font-medium">Nombre de la asignatura <span class="text-red-500">*</span></label>
            <div class="relative">
                <input id="nombre" name="nombre" type="text"
                    value="{{ old('nombre') }}"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-inner
                           focus:outline-none focus:ring-2 focus:ring-[#38b2ac] focus:border-transparent transition"
                    placeholder="Ingrese el título de la asignatura..." />
                <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="ri-book-line text-lg"></i>
                </div>
            </div>
            @error('nombre')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end items-center gap-4 mt-6">
            <a href="{{ route('asignaturas.index') }}"
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