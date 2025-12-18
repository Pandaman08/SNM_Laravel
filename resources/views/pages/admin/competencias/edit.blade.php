@extends('layout.admin.plantilla')

@section('titulo', 'Editar Competencia')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-pencil-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Competencia</h1>
                    <p class="text-sm text-gray-500 mt-1">Modifique los datos de la competencia seleccionada</p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('competencias.update', $competencia->id_competencias) }}" method="POST" class="space-y-6" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Campo: Asignatura --}}
                    <div class="space-y-2">
                        <label for="codigo_asignatura" class="block text-sm font-semibold text-gray-700">
                            Asignatura
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="codigo_asignatura"
                                name="codigo_asignatura"
                                class="w-full rounded-xl border @error('codigo_asignatura') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent
                                       transition-all duration-200 hover:border-cyan-300"
                                required
                            >
                                @foreach($asignaturas as $asignatura)
                                    <option value="{{ $asignatura->codigo_asignatura }}"
                                        {{ old('codigo_asignatura', $competencia->codigo_asignatura) == $asignatura->codigo_asignatura ? 'selected' : '' }}>
                                        {{ $asignatura->nombre }} ({{ $asignatura->grado->grado }}° {{ $asignatura->grado->nivelEducativo->nombre }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                        @error('codigo_asignatura')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Campo: Descripción --}}
                    <div class="space-y-2">
                        <label for="descripcion" class="block text-sm font-semibold text-gray-700">
                            Descripción de la competencia
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute top-3.5 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-file-text-line text-gray-400 text-lg"></i>
                            </div>
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                rows="5"
                                placeholder="Describa la competencia que se evaluará..."
                                class="w-full rounded-xl border @error('descripcion') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       pl-11 pr-4 py-3.5 text-gray-900 shadow-sm resize-none
                                       focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent
                                       transition-all duration-200 hover:border-cyan-300"
                                required
                            >{{ old('descripcion', $competencia->descripcion) }}</textarea>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="ri-information-line"></i>
                            <span>Describa claramente qué habilidades o conocimientos se evaluarán</span>
                        </p>
                        @error('descripcion')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Información del registro --}}
                    <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-4">
                        <div class="flex gap-3">
                            <i class="ri-information-line text-cyan-600 text-xl flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-cyan-800">
                                <p class="font-medium mb-1">Editando registro</p>
                                <p class="text-cyan-700">ID: <span class="font-semibold">{{ $competencia->id_competencias }}</span></p>
                                <p class="text-cyan-700">Asignatura actual: <span class="font-semibold">{{ $competencia->asignatura->nombre ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a
                            href="{{ route('competencias.index') }}"
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
                                   bg-gradient-to-r from-cyan-500 to-cyan-600 
                                   hover:from-cyan-600 hover:to-cyan-700
                                   text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/30
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-refresh-line text-lg"></i>
                            <span>Actualizar competencia</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection