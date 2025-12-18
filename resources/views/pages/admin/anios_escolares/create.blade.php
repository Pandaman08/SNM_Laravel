@extends('layout.admin.plantilla')

@section('titulo', 'Registrar Año Escolar')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-lime-500 to-lime-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-calendar-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Registrar Año Escolar</h1>
                    <p class="text-sm text-gray-500 mt-1">Complete el formulario para agregar un nuevo año escolar</p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('anios-escolares.store') }}" method="POST" class="space-y-6" novalidate>
                    @csrf

                    {{-- Campo: Año Escolar --}}
                    <div class="space-y-2">
                        <label for="anio" class="block text-sm font-semibold text-gray-700">
                            Año escolar
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-calendar-check-line text-gray-400 text-lg"></i>
                            </div>
                            <input
                                id="anio"
                                name="anio"
                                type="text"
                                value="{{ old('anio') }}"
                                placeholder="Ej: 2024-2025"
                                class="w-full rounded-xl border @error('anio') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-transparent
                                       transition-all duration-200 hover:border-lime-300"
                                required
                            >
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="ri-information-line"></i>
                            <span>Formato recomendado: YYYY-YYYY (Ej: 2024-2025)</span>
                        </p>
                        @error('anio')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Campo: Descripción --}}
                    <div class="space-y-2">
                        <label for="descripcion" class="block text-sm font-semibold text-gray-700">
                            Descripción
                            <span class="text-gray-400 text-xs font-normal ml-1">(Opcional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute top-3.5 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-file-text-line text-gray-400 text-lg"></i>
                            </div>
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                rows="3"
                                placeholder="Agregue una descripción o notas sobre este año escolar..."
                                class="w-full rounded-xl border @error('descripcion') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       pl-11 pr-4 py-3.5 text-gray-900 shadow-sm resize-none
                                       focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-transparent
                                       transition-all duration-200 hover:border-lime-300"
                            >{{ old('descripcion') }}</textarea>
                        </div>
                        @error('descripcion')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Campos de Fecha en Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Fecha Inicio --}}
                        <div class="space-y-2">
                            <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700">
                                Fecha de inicio
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="ri-calendar-event-line text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    type="date"
                                    id="fecha_inicio"
                                    name="fecha_inicio"
                                    value="{{ old('fecha_inicio') }}"
                                    class="w-full rounded-xl border @error('fecha_inicio') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                           pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                                           focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-transparent
                                           transition-all duration-200 hover:border-lime-300"
                                    required
                                >
                            </div>
                            @error('fecha_inicio')
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                    <i class="ri-error-warning-line"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- Fecha Fin --}}
                        <div class="space-y-2">
                            <label for="fecha_fin" class="block text-sm font-semibold text-gray-700">
                                Fecha de fin
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="ri-calendar-close-line text-gray-400 text-lg"></i>
                                </div>
                                <input
                                    type="date"
                                    id="fecha_fin"
                                    name="fecha_fin"
                                    value="{{ old('fecha_fin') }}"
                                    class="w-full rounded-xl border @error('fecha_fin') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                           pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                                           focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-transparent
                                           transition-all duration-200 hover:border-lime-300"
                                    required
                                >
                            </div>
                            @error('fecha_fin')
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                    <i class="ri-error-warning-line"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo: Estado --}}
                    <div class="space-y-2">
                        <label for="estado" class="block text-sm font-semibold text-gray-700">
                            Estado
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                id="estado"
                                name="estado"
                                class="w-full rounded-xl border @error('estado') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-transparent
                                       transition-all duration-200 hover:border-lime-300"
                                required
                            >
                                <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>
                                    ✅ Activo
                                </option>
                                <option value="Finalizado" {{ old('estado') == 'Finalizado' ? 'selected' : '' }}>
                                    ⏸️ Finalizado
                                </option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                        @error('estado')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Nota informativa --}}
                    <div class="bg-lime-50 border border-lime-200 rounded-xl p-4">
                        <div class="flex gap-3">
                            <i class="ri-information-line text-lime-600 text-xl flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-lime-800">
                                <p class="font-medium mb-1">Información importante</p>
                                <p class="text-lime-700">La fecha de fin debe ser posterior a la fecha de inicio. Un año escolar típicamente abarca de marzo a diciembre.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a
                            href="{{ route('anios-escolares.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 
                                   bg-white border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50
                                   text-gray-700 font-semibold rounded-xl
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-arrow-left-line text-lg"></i>
                            <span>Cancelar</span>
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3
                                   bg-gradient-to-r from-lime-500 to-lime-600 
                                   hover:from-lime-600 hover:to-lime-700
                                   text-white font-semibold rounded-xl shadow-lg shadow-lime-500/30
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-save-line text-lg"></i>
                            <span>Guardar año escolar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script para validación de fechas --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    
    // Validar que fecha fin sea mayor que fecha inicio
    function validarFechas() {
        if (fechaInicio.value && fechaFin.value) {
            const inicio = new Date(fechaInicio.value);
            const fin = new Date(fechaFin.value);
            
            if (fin <= inicio) {
                fechaFin.setCustomValidity('La fecha de fin debe ser posterior a la fecha de inicio');
                fechaFin.classList.add('border-red-300', 'bg-red-50');
                fechaFin.classList.remove('border-lime-300', 'bg-lime-50');
            } else {
                fechaFin.setCustomValidity('');
                fechaFin.classList.remove('border-red-300', 'bg-red-50');
                fechaFin.classList.add('border-lime-300', 'bg-lime-50');
            }
        }
    }
    
    if (fechaInicio && fechaFin) {
        fechaInicio.addEventListener('change', validarFechas);
        fechaFin.addEventListener('change', validarFechas);
    }
});
</script>
@endsection