@extends('layout.admin.plantilla')

@section('titulo','Editar Sección')

@section('contenido')
<div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center justify-center gap-2 border-b pb-4">
        <i class="ri-pencil-line text-2xl text-[#10b981]"></i>
        Editar Sección
    </h1>

    <form action="{{ route('secciones.update', $seccion->id_seccion) }}" method="POST" class="space-y-6" novalidate>
        @csrf
        @method('PUT')

        {{-- ID (no modificable) --}}
        <div>
            <label class="block text-gray-700 font-semibold mb-2">
                ID
            </label>
            <div class="w-full rounded-lg border border-gray-200 bg-gray-50 p-3 text-gray-600 font-medium">
                {{ $seccion->id_seccion }}
            </div>
        </div>

        {{-- Grado --}}
        <div>
            <label for="id_grado" class="block text-gray-700 font-semibold mb-2">
                Grado <span class="text-red-500">*</span>
            </label>
            <select id="id_grado" name="id_grado"
                class="w-full rounded-lg border @error('id_grado') border-red-500 @else border-gray-300 @enderror p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#10b981] focus:border-[#10b981] transition"
                required>
                <option value="" disabled>— Seleccione un grado —</option>
                @foreach ($grados as $grado)
                    <option value="{{ $grado->id_grado }}"
                        {{ old('id_grado', $seccion->id_grado) == $grado->id_grado ? 'selected' : '' }}>
                        {{ $grado->nivelEducativo->nombre }} - {{ $grado->nombre_completo }}
                    </option>
                @endforeach
            </select>
            @error('id_grado')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Sección --}}
        <div>
            <label for="seccion" class="block text-gray-700 font-semibold mb-2">
                Sección <span class="text-red-500">*</span>
            </label>
            <input id="seccion" name="seccion" type="text"
                value="{{ old('seccion', $seccion->seccion) }}"
                maxlength="1"
                pattern="[A-Za-z]"
                title="Ingrese una sola letra para la sección"
                class="w-full uppercase rounded-lg border @error('seccion') border-red-500 @else border-gray-300 @enderror p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#10b981] focus:border-[#10b981] transition"
                placeholder="Ej: A"
                required>
            @error('seccion')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p id="seccion-warning" class="text-amber-600 text-sm mt-1 hidden">
                <i class="ri-alert-line"></i> Esta sección ya existe para este grado
            </p>
        </div>

        {{-- Vacantes --}}
        <div>
            <label for="vacantes_seccion" class="block text-gray-700 font-semibold mb-2">
                Vacantes <span class="text-red-500">*</span>
            </label>
            <input type="number" name="vacantes_seccion" id="vacantes_seccion" min="1" max="50"
                value="{{ old('vacantes_seccion', $seccion->vacantes_seccion) }}"
                class="w-full rounded-lg border @error('vacantes_seccion') border-red-500 @else border-gray-300 @enderror p-3 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-[#10b981] focus:border-[#10b981] transition" required>
            @error('vacantes_seccion')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Estado vacantes --}}
        <div class="flex items-center">
            <input type="checkbox" name="estado_vacantes" id="estado_vacantes" value="1"
                {{ old('estado_vacantes', $seccion->estado_vacantes) ? 'checked' : '' }}
                class="w-4 h-4 text-[#10b981] border-gray-300 rounded focus:ring-[#10b981]">
            <label for="estado_vacantes" class="ml-2 text-gray-700 font-medium">
                Vacantes disponibles
            </label>
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-4 pt-6 border-t">
            <a href="{{ route('secciones.index') }}"
               class="inline-flex items-center px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700
                      font-medium rounded-lg shadow-sm transition transform hover:-translate-y-0.5">
                Cancelar
            </a>
            <button type="submit" id="submit-btn"
                class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-[#10b981] to-[#22c55e]
                       hover:from-[#22c55e] hover:to-[#10b981] text-white font-semibold rounded-lg
                       shadow-md transition transform hover:-translate-y-0.5">
                <i class="ri-save-line mr-2"></i> Actualizar
            </button>
        </div>
    </form>
</div>

<script>
    const seccionesExistentes = @json($seccionesExistentes ?? []);
    const gradoSelect = document.getElementById('id_grado');
    const seccionInput = document.getElementById('seccion');
    const warning = document.getElementById('seccion-warning');
    const submitBtn = document.getElementById('submit-btn');

    gradoSelect.addEventListener('change', verificarSeccion);
    seccionInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
        verificarSeccion();
    });

    function verificarSeccion() {
        const gradoId = gradoSelect.value;
        const seccion = seccionInput.value.toUpperCase();

        if (gradoId && seccion) {
            const existe = seccionesExistentes[gradoId]?.includes(seccion);
            
            if (existe) {
                warning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                seccionInput.classList.add('border-amber-500');
            } else {
                warning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                seccionInput.classList.remove('border-amber-500');
            }
        }
    }
</script>
@endsection