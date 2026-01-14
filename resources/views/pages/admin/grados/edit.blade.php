@extends('layout.admin.plantilla')

@section('titulo', 'Editar grado')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-pencil-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Grado</h1>
                    <p class="text-sm text-gray-500 mt-1">Modifique los datos del grado seleccionado</p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('grados.update', $grado->id_grado) }}" method="POST" class="space-y-6" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Campo: Grado --}}
                    <div class="space-y-2">
                        <label for="grado" class="block text-sm font-semibold text-gray-700">
                            Grado académico
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="grado"
                                id="grado"
                                class="w-full rounded-xl border @error('grado') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                       transition-all duration-200 hover:border-amber-300"
                                required
                            >
                                <option value="" disabled {{ old('grado', $grado->grado) ? '' : 'selected' }}>Seleccione el grado</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ old('grado', $grado->grado) == $i ? 'selected' : '' }}>
                                        {{ $i }}° Grado
                                    </option>
                                @endfor
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                        @error('grado')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Campo: Nivel educativo --}}
                    <div class="space-y-2">
                        <label for="nivel_educativo_id" class="block text-sm font-semibold text-gray-700">
                            Nivel educativo
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="nivel_educativo_id"
                                id="nivel_educativo_id"
                                class="w-full rounded-xl border @error('nivel_educativo_id') border-red-300 bg-red-50 @else border-gray-200 @enderror 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                       transition-all duration-200 hover:border-amber-300"
                                required
                            >
                                <option value="" disabled {{ old('nivel_educativo_id', $grado->nivel_educativo_id) ? '' : 'selected' }}>Seleccione el nivel</option>
                                @foreach($nivelesEducativos as $nivel)
                                    <option value="{{ $nivel->id_nivel_educativo }}"
                                        {{ old('nivel_educativo_id', $grado->nivel_educativo_id) == $nivel->id_nivel_educativo ? 'selected' : '' }}>
                                        {{ $nivel->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                        @error('nivel_educativo_id')
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
                                <i class="ri-error-warning-line"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Información del registro --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex gap-3">
                            <i class="ri-information-line text-amber-600 text-xl flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-amber-800">
                                <p class="font-medium mb-1">Editando registro</p>
                                <p class="text-amber-700">ID del grado: <span class="font-semibold">{{ $grado->id_grado }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a
                            href="{{ route('grados.index') }}"
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
                                   bg-gradient-to-r from-amber-500 to-amber-600 
                                   hover:from-amber-600 hover:to-amber-700
                                   text-white font-semibold rounded-xl shadow-lg shadow-amber-500/30
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-refresh-line text-lg"></i>
                            <span>Actualizar grado</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const gradosExistentes = @json($gradosExistentes ?? []);
    const nivelSelect = document.getElementById('nivel_educativo_id');
    const gradoSelect = document.getElementById('grado');
    const warning = document.getElementById('grado-warning');
    const submitBtn = document.getElementById('submit-btn');

    nivelSelect.addEventListener('change', verificarGrado);
    gradoSelect.addEventListener('change', verificarGrado);

    function verificarGrado() {
        const nivelId = nivelSelect.value;
        const grado = gradoSelect.value;

        if (nivelId && grado) {
            const existe = gradosExistentes[nivelId]?.includes(parseInt(grado));
            
            if (existe) {
                warning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                gradoSelect.classList.add('border-amber-500');
            } else {
                warning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                gradoSelect.classList.remove('border-amber-500');
            }
        }
    }
</script>
@endsection