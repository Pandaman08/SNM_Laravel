@extends('layout.admin.plantilla')

@section('titulo', 'Registrar grado')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200 animate-fade-in">
    <h1 class="text-4xl font-extrabold text-gray-800 mb-6 flex items-center justify-center gap-3 border-b pb-4">
        <i class="ri-graduation-cap-fill text-3xl text-[#d97706]"></i>
        Registrar Grado
    </h1>

    <form action="{{ route('grados.store') }}" method="POST" class="space-y-6" novalidate>
        @csrf

        {{-- Campo: Nivel educativo (primero para filtrar) --}}
        <div>
            <label for="nivel_educativo_id" class="block text-gray-700 font-semibold mb-2">
                Nivel educativo <span class="text-red-500">*</span>
            </label>
            <select
                name="nivel_educativo_id"
                id="nivel_educativo_id"
                class="w-full rounded-lg border @error('nivel_educativo_id') border-red-500 @else border-gray-300 @enderror p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition"
                required
            >
                <option value="" disabled {{ old('nivel_educativo_id') ? '' : 'selected' }}>Seleccione un nivel</option>
                @foreach($nivelesEducativos as $nivel)
                    <option value="{{ $nivel->id_nivel_educativo }}"
                        {{ old('nivel_educativo_id') == $nivel->id_nivel_educativo ? 'selected' : '' }}>
                        {{ $nivel->nombre }}
                    </option>
                @endforeach
            </select>
            @error('nivel_educativo_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Campo: Grado --}}
        <div>
            <label for="grado" class="block text-gray-700 font-semibold mb-2">
                Grado <span class="text-red-500">*</span>
            </label>
            <select
                name="grado"
                id="grado"
                class="w-full rounded-lg border @error('grado') border-red-500 @else border-gray-300 @enderror p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-[#fbbf24] transition"
                required
                {{ old('nivel_educativo_id') ? '' : 'disabled' }}
            >
                <option value="" disabled selected>Primero seleccione un nivel</option>
                @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('grado') == $i ? 'selected' : '' }}>
                        {{ $i }}° 
                    </option>
                @endfor
            </select>
            @error('grado')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p id="grado-warning" class="text-amber-600 text-sm mt-1 hidden">
                <i class="ri-alert-line"></i> Este grado ya está registrado para este nivel
            </p>
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-4 pt-6 border-t">
            <a href="{{ route('grados.index') }}"
               class="inline-flex items-center px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700
                      font-medium rounded-lg shadow-sm transition transform hover:-translate-y-0.5">
                Cancelar
            </a>
            <button type="submit" id="submit-btn"
                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-[#fbbf24] to-[#d97706]
                           hover:from-[#d97706] hover:to-[#f59e0b] text-white font-semibold rounded-lg shadow-md
                           transition transform hover:-translate-y-0.5">
                Guardar
            </button>
        </div>
    </form>
</div>

<script>
    const gradosExistentes = @json($gradosExistentes ?? []);
    const nivelSelect = document.getElementById('nivel_educativo_id');
    const gradoSelect = document.getElementById('grado');
    const warning = document.getElementById('grado-warning');
    const submitBtn = document.getElementById('submit-btn');

    nivelSelect.addEventListener('change', function() {
        gradoSelect.disabled = !this.value;
        if (!this.value) {
            gradoSelect.value = '';
        }
        verificarGrado();
    });

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

    if (nivelSelect.value) {
        gradoSelect.disabled = false;
        verificarGrado();
    }
</script>
@endsection