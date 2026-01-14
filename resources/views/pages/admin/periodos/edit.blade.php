@extends('layout.admin.plantilla')

@section('titulo', 'Editar Periodo')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-pencil-line text-2xl text-white"></i>
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Editar Periodo: {{ $periodo->nombre }}</h1>

        <form action="{{ route('periodos.update', $periodo->id_periodo) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            @if($periodo->anioEscolar)
            <div class="mb-4 p-4 bg-gray-50 text-gray-700 rounded-md border border-gray-200">
                <span class="font-semibold">Año Escolar:</span> {{ $periodo->anioEscolar->anio }}
            </div>
            @endif

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Periodo</label>
                <input id="nombre" name="nombre" value="{{ old('nombre', $periodo->nombre) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560] transition duration-200"
                    required>
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $periodo->fecha_inicio) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-[#98C560] focus:border-[#98C560] transition duration-200"
                        required>
                    @error('fecha_inicio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Periodo</h1>
                    <p class="text-sm text-gray-500 mt-1">Modifique los datos del periodo seleccionado</p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('periodos.update', $periodo->id_periodo) }}" method="POST" class="space-y-6" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Campo: Nombre del Periodo --}}
                    <div class="space-y-2">
                        <label for="nombre" class="block text-sm font-semibold text-gray-700">
                            Nombre del periodo
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-bookmark-line text-gray-400 text-lg"></i>
                            </div>
                            <input
                                id="nombre"
                                name="nombre"
                                type="text"
                                value="{{ old('nombre', $periodo->nombre) }}"
                                placeholder="Ej: Primer Bimestre, Trimestre I, etc."
                                class="w-full rounded-xl border @error('nombre') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                       transition-all duration-200 hover:border-purple-300"
                                required
                            >
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="ri-information-line"></i>
                            <span>Ingrese un nombre descriptivo para el periodo académico</span>
                        </p>
                        @error('nombre')
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
                                    value="{{ old('fecha_inicio', $periodo->fecha_inicio) }}"
                                    class="w-full rounded-xl border @error('fecha_inicio') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                           pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                                           focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                           transition-all duration-200 hover:border-purple-300"
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
                                    value="{{ old('fecha_fin', $periodo->fecha_fin) }}"
                                    class="w-full rounded-xl border @error('fecha_fin') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                           pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                                           focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                           transition-all duration-200 hover:border-purple-300"
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

                    {{-- Información del registro --}}
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                        <div class="flex gap-3">
                            <i class="ri-information-line text-purple-600 text-xl flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-purple-800">
                                <p class="font-medium mb-1">Editando registro</p>
                                <p class="text-purple-700">ID: <span class="font-semibold">{{ $periodo->id_periodo }}</span></p>
                                <p class="text-purple-700">Periodo actual: <span class="font-semibold">{{ $periodo->nombre }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a
                            href="{{ route('periodos.index') }}"
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
                                   bg-gradient-to-r from-purple-500 to-purple-600 
                                   hover:from-purple-600 hover:to-purple-700
                                   text-white font-semibold rounded-xl shadow-lg shadow-purple-500/30
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-refresh-line text-lg"></i>
                            <span>Actualizar periodo</span>
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
                fechaFin.classList.remove('border-purple-300', 'bg-purple-50');
            } else {
                fechaFin.setCustomValidity('');
                fechaFin.classList.remove('border-red-300', 'bg-red-50');
                fechaFin.classList.add('border-purple-300', 'bg-purple-50');
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