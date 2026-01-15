@extends('layout.admin.plantilla')

@section('titulo', 'Registrar Periodo')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">

        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-time-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Registrar Periodo</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Complete el formulario para agregar un nuevo periodo académico
                    </p>
                </div>
            </div>
        </div>

        {{-- Año escolar activo --}}
        @if(isset($anioActual))
            <div class="mb-6 p-4 bg-blue-50 text-blue-700 rounded-md">
                <span class="font-semibold">Año Escolar Activo:</span> {{ $anioActual->anio }}
            </div>
        @endif

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('periodos.store') }}" method="POST" class="space-y-6" novalidate>
                    @csrf
                    <input type="hidden" name="id_anio_escolar" value="{{ $anioActual->id_anio_escolar ?? '' }}">

                    {{-- Nombre del Periodo --}}
                    <div class="space-y-2">
                        <label for="nombre" class="block text-sm font-semibold text-gray-700">
                            Nombre del periodo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-bookmark-line text-gray-400 text-lg"></i>
                            </div>
                            <input
                                id="nombre"
                                name="nombre"
                                type="text"
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Primer Bimestre, Trimestre I"
                                class="w-full rounded-xl border
                                       @error('nombre') border-red-300 bg-red-50 @else border-gray-200 @enderror
                                       pl-11 pr-4 py-3.5 text-gray-900 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                                       transition-all duration-200"
                                required
                            >
                        </div>
                        @error('nombre')
                            <p class="text-sm text-red-600 flex items-center gap-2">
                                <i class="ri-error-warning-line"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Fechas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Fecha inicio --}}
                        <div class="space-y-2">
                            <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700">
                                Fecha de inicio <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="fecha_inicio"
                                name="fecha_inicio"
                                value="{{ old('fecha_inicio') }}"
                                class="w-full rounded-xl border
                                       @error('fecha_inicio') border-red-300 bg-red-50 @else border-gray-200 @enderror
                                       px-4 py-3.5 text-gray-900 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-purple-500"
                                required
                            >
                            @error('fecha_inicio')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fecha fin --}}
                        <div class="space-y-2">
                            <label for="fecha_fin" class="block text-sm font-semibold text-gray-700">
                                Fecha de fin <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="fecha_fin"
                                name="fecha_fin"
                                value="{{ old('fecha_fin') }}"
                                class="w-full rounded-xl border
                                       @error('fecha_fin') border-red-300 bg-red-50 @else border-gray-200 @enderror
                                       px-4 py-3.5 text-gray-900 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-purple-500"
                                required
                            >
                            @error('fecha_fin')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nota --}}
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-sm text-purple-800">
                        <strong>Nota:</strong>
                        El periodo debe estar dentro del rango del año escolar.
                        La fecha de fin debe ser posterior a la fecha de inicio.
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-6 border-t">
                        <a href="{{ route('periodos.index') }}"
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-semibold shadow-lg">
                            Guardar periodo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Validación JS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const inicio = document.getElementById('fecha_inicio');
    const fin = document.getElementById('fecha_fin');

    function validar() {
        if (inicio.value && fin.value) {
            if (fin.value <= inicio.value) {
                fin.setCustomValidity('La fecha de fin debe ser posterior');
            } else {
                fin.setCustomValidity('');
            }
        }
    }

    inicio?.addEventListener('change', validar);
    fin?.addEventListener('change', validar);
});
</script>
@endsection
