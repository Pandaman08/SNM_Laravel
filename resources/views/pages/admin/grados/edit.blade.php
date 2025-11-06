@extends('layout.admin.plantilla')

@section('titulo', 'Editar grado')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center gap-2 border-b pb-3">
        <i class="ri-pencil-line text-2xl text-[#d97706]"></i> Editar grado
    </h1>

    <form action="{{ route('grados.update', $grado->id_grado) }}" method="POST" class="space-y-6" novalidate>
        @csrf
        @method('PUT')

        {{-- ID (no modificable) --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-2">
                ID
            </label>
            <div class="w-full rounded-lg border border-gray-200 bg-gray-50 p-3 text-gray-600 font-medium">
                {{ $grado->id_grado }}
            </div>
        </div>

        {{-- Nivel educativo --}}
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
                <option value="" disabled>Seleccione nivel</option>
                @foreach($nivelesEducativos as $nivel)
                    <option value="{{ $nivel->id_nivel_educativo }}"
                        {{ old('nivel_educativo_id', $grado->nivel_educativo_id) == $nivel->id_nivel_educativo ? 'selected' : '' }}>
                        {{ $nivel->nombre }}
                    </option>
                @endforeach
            </select>
            @error('nivel_educativo_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Grado --}}
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
            >
                <option value="" disabled>Seleccione grado</option>
                @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('grado', $grado->grado) == $i ? 'selected' : '' }}>
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
               class="inline-flex items-center px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700
                      font-medium rounded-lg shadow-sm transition transform hover:-translate-y-0.5">
                Cancelar
            </a>
            <button type="submit" id="submit-btn"
                    class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-[#fbbf24] to-[#d97706]
                           hover:from-[#d97706] hover:to-[#f59e0b] text-white font-semibold rounded-lg shadow-md
                           transition transform hover:-translate-y-0.5">
                Actualizar
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