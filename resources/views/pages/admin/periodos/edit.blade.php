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
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Periodo</h1>
                    <p class="text-sm text-gray-500">Modifique los datos del periodo seleccionado</p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('periodos.update', $periodo->id_periodo) }}" method="POST" class="space-y-6" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Año Escolar --}}
                    @if($periodo->anioEscolar)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-700">
                            <span class="font-semibold">Año Escolar:</span>
                            {{ $periodo->anioEscolar->anio }}
                        </div>
                    @endif

                    {{-- Nombre --}}
                    <div class="space-y-2">
                        <label for="nombre" class="block text-sm font-semibold text-gray-700">
                            Nombre del periodo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-bookmark-line text-gray-400"></i>
                            </div>
                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre', $periodo->nombre) }}"
                                class="w-full rounded-xl border pl-11 pr-4 py-3.5
                                @error('nombre') border-red-300 bg-red-50 @else border-gray-200 @enderror
                                focus:ring-2 focus:ring-purple-500"
                                required
                            >
                        </div>
                        @error('nombre')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fechas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Inicio --}}
                        <div class="space-y-2">
                            <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700">
                                Fecha de inicio <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="fecha_inicio"
                                name="fecha_inicio"
                                value="{{ old('fecha_inicio', $periodo->fecha_inicio) }}"
                                class="w-full rounded-xl border px-4 py-3
                                @error('fecha_inicio') border-red-300 bg-red-50 @else border-gray-200 @enderror
                                focus:ring-2 focus:ring-purple-500"
                                required
                            >
                            @error('fecha_inicio')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fin --}}
                        <div class="space-y-2">
                            <label for="fecha_fin" class="block text-sm font-semibold text-gray-700">
                                Fecha de fin <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="fecha_fin"
                                name="fecha_fin"
                                value="{{ old('fecha_fin', $periodo->fecha_fin) }}"
                                class="w-full rounded-xl border px-4 py-3
                                @error('fecha_fin') border-red-300 bg-red-50 @else border-gray-200 @enderror
                                focus:ring-2 focus:ring-purple-500"
                                required
                            >
                            @error('fecha_fin')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-sm">
                        <p><strong>ID:</strong> {{ $periodo->id_periodo }}</p>
                        <p><strong>Periodo actual:</strong> {{ $periodo->nombre }}</p>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-6 border-t">
                        <a href="{{ route('periodos.index') }}"
                           class="px-6 py-3 border rounded-xl text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700">
                            Actualizar periodo
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Validación fechas --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const inicio = document.getElementById('fecha_inicio');
    const fin = document.getElementById('fecha_fin');

    function validar() {
        if (inicio.value && fin.value && fin.value <= inicio.value) {
            fin.setCustomValidity('La fecha de fin debe ser posterior');
        } else {
            fin.setCustomValidity('');
        }
    }

    inicio.addEventListener('change', validar);
    fin.addEventListener('change', validar);
});
</script>
@endsection
