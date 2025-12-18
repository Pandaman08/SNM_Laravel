@extends('layout.admin.plantilla')

@section('titulo', 'Editar asignatura')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-pencil-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Asignatura</h1>
                    <p class="text-sm text-gray-500 mt-1">Modifique los datos de la asignatura seleccionada</p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('asignaturas.update', $asignatura->codigo_asignatura) }}" method="POST" class="space-y-6" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Campo: Grado --}}
                    <div class="space-y-2">
                        <label for="id_grado" class="block text-sm font-semibold text-gray-700">
                            Grado académico
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="id_grado"
                                name="id_grado"
                                class="w-full rounded-xl border @error('id_grado') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent
                                       transition-all duration-200 hover:border-teal-300"
                                required
                            >
                                <option value="" disabled {{ old('id_grado', $asignatura->id_grado) ? '' : 'selected' }}>
                                    Seleccione un grado
                                </option>
                                @foreach($grados as $g)
                                    <option value="{{ $g->id_grado }}"
                                        {{ old('id_grado', $asignatura->id_grado) == $g->id_grado ? 'selected' : '' }}>
                                        {{ $g->grado }}° — {{ $g->nivelEducativo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                        @error('id_grado')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Campo: Nombre de la asignatura --}}
                    <div class="space-y-2">
                        <label for="nombre" class="block text-sm font-semibold text-gray-700">
                            Nombre de la asignatura
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-book-line text-gray-400 text-lg"></i>
                            </div>
                            <input
                                id="nombre"
                                name="nombre"
                                type="text"
                                value="{{ old('nombre', $asignatura->nombre) }}"
                                placeholder="Ej: Matemáticas, Comunicación, Ciencias..."
                                class="w-full rounded-xl border @error('nombre') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent
                                       transition-all duration-200 hover:border-teal-300"
                                required
                            >
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="ri-information-line"></i>
                            <span>Ingrese el nombre completo de la asignatura o materia</span>
                        </p>
                        @error('nombre')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Información del registro --}}
                    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4">
                        <div class="flex gap-3">
                            <i class="ri-information-line text-teal-600 text-xl flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-teal-800">
                                <p class="font-medium mb-1">Editando registro</p>
                                <p class="text-teal-700">Código de asignatura: <span class="font-semibold">{{ $asignatura->codigo_asignatura }}</span></p>
                                <p class="text-teal-700">Nombre actual: <span class="font-semibold">{{ $asignatura->nombre }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a
                            href="{{ route('asignaturas.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 
                                   bg-white border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50
                                   text-gray-700 font-semibold rounded-xl
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-close-line text-lg"></i>
                            <span>Cancelar</span>
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3
                                   bg-gradient-to-r from-teal-500 to-teal-600 
                                   hover:from-teal-600 hover:to-teal-700
                                   text-white font-semibold rounded-xl shadow-lg shadow-teal-500/30
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-refresh-line text-lg"></i>
                            <span>Actualizar asignatura</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script para mejorar la experiencia del usuario --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    
    if (nombreInput) {
        // Capitalizar primera letra de cada palabra
        nombreInput.addEventListener('blur', function() {
            this.value = this.value
                .toLowerCase()
                .split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ')
                .trim();
        });
    }
});
</script>
@endsection