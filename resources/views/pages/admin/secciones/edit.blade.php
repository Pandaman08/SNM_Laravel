@extends('layout.admin.plantilla')

@section('titulo', 'Editar Sección')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-pencil-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Sección</h1>
                    <p class="text-sm text-gray-500 mt-1">Modifique los datos de la sección seleccionada</p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('secciones.update', $seccion->id_seccion) }}" method="POST" class="space-y-6" novalidate>
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
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                       transition-all duration-200 hover:border-emerald-300"
                                required
                                aria-describedby="id_grado_help"
                            >
                                <option value="" disabled {{ old('id_grado', $seccion->id_grado) ? '' : 'selected' }}>
                                    Seleccione un grado
                                </option>
                                @foreach ($grados as $grado)
                                    <option value="{{ $grado->id_grado }}"
                                        {{ old('id_grado', $seccion->id_grado) == $grado->id_grado ? 'selected' : '' }}>
                                        {{ $grado->nombre_completo }}
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
                                <span id="id_grado_help">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Campo: Sección --}}
                    <div class="space-y-2">
                        <label for="seccion" class="block text-sm font-semibold text-gray-700">
                            Letra de la sección
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-text text-gray-400 text-lg"></i>
                            </div>
                            <input
                                id="seccion"
                                name="seccion"
                                type="text"
                                value="{{ old('seccion', $seccion->seccion) }}"
                                maxlength="1"
                                pattern="[A-Za-z]"
                                title="Ingrese una sola letra para la sección"
                                placeholder="Ej: A"
                                class="w-full uppercase rounded-xl border @error('seccion') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       pl-11 pr-4 py-3.5 text-gray-900 shadow-sm text-center text-2xl font-bold
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                       transition-all duration-200 hover:border-emerald-300"
                                required
                                aria-describedby="seccion_help"
                            >
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="ri-information-line"></i>
                            <span>Ingrese una sola letra (A-Z). Se convertirá automáticamente a mayúscula</span>
                        </p>
                        @error('seccion')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span id="seccion_help">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Información del registro --}}
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                        <div class="flex gap-3">
                            <i class="ri-information-line text-emerald-600 text-xl flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-emerald-800">
                                <p class="font-medium mb-1">Editando registro</p>
                                <p class="text-emerald-700">ID de la sección: <span class="font-semibold">{{ $seccion->id_seccion }}</span></p>
                                <p class="text-emerald-700">Sección actual: <span class="font-semibold">{{ $seccion->seccion }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a
                            href="{{ route('secciones.index') }}"
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
                                   bg-gradient-to-r from-emerald-500 to-emerald-600 
                                   hover:from-emerald-600 hover:to-emerald-700
                                   text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/30
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-refresh-line text-lg"></i>
                            <span>Actualizar sección</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script para validación en tiempo real --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const seccionInput = document.getElementById('seccion');
    
    if (seccionInput) {
        seccionInput.addEventListener('input', function(e) {
            // Convertir a mayúscula automáticamente
            this.value = this.value.toUpperCase();
            
            // Validar que solo sea una letra
            if (this.value.length > 1) {
                this.value = this.value.charAt(0);
            }
            
            // Remover caracteres que no sean letras
            this.value = this.value.replace(/[^A-Z]/g, '');
        });
        
        // Feedback visual al escribir
        seccionInput.addEventListener('keyup', function() {
            if (this.value && /^[A-Z]$/.test(this.value)) {
                this.classList.remove('border-red-300', 'bg-red-50');
                this.classList.add('border-emerald-300', 'bg-emerald-50');
            } else if (this.value) {
                this.classList.remove('border-emerald-300', 'bg-emerald-50');
                this.classList.add('border-red-300', 'bg-red-50');
            } else {
                this.classList.remove('border-emerald-300', 'bg-emerald-50', 'border-red-300', 'bg-red-50');
            }
        });
    }
});
</script>
@endsection