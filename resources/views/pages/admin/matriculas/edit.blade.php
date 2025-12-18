@extends('layout.admin.plantilla')

@section('titulo', 'Editar Matrícula')

@section('contenido')
<div class="min-h-screen py-8 px-4">
    <div class="max-w-3xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-pencil-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Matrícula</h1>
                    <p class="text-sm text-gray-500 mt-1">Código: <span class="font-semibold text-indigo-600">{{ $matricula->codigo_matricula }}</span></p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('matriculas.update', $matricula->codigo_matricula) }}" method="POST" class="space-y-6" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Estudiante (solo lectura) --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            Estudiante
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ri-user-line text-gray-400 text-lg"></i>
                            </div>
                            <input 
                                type="text" 
                                value="{{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}"
                                disabled
                                class="w-full rounded-xl border border-gray-200 bg-gray-50
                                       pl-11 pr-4 py-3.5 text-gray-900 shadow-sm cursor-not-allowed"
                            >
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="ri-information-line"></i>
                            <span>El estudiante no puede ser modificado</span>
                        </p>
                    </div>

                    {{-- Año Escolar --}}
                    <div class="space-y-2">
                        <label for="id_anio_escolar" class="block text-sm font-semibold text-gray-700">
                            Año Escolar
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="id_anio_escolar"
                                id="id_anio_escolar"
                                class="w-full rounded-xl border border-gray-200 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       transition-all duration-200 hover:border-indigo-300"
                                required
                            >
                                @foreach ($aniosEscolares as $anio)
                                    <option value="{{ $anio->id_anio_escolar }}"
                                        {{ $matricula->id_anio_escolar == $anio->id_anio_escolar ? 'selected' : '' }}>
                                        {{ $anio->anio }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Tipo de Matrícula --}}
                    <div class="space-y-2">
                        <label for="id_tipo_matricula" class="block text-sm font-semibold text-gray-700">
                            Tipo de Matrícula
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="id_tipo_matricula"
                                id="id_tipo_matricula"
                                class="w-full rounded-xl border border-gray-200 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       transition-all duration-200 hover:border-indigo-300"
                                required
                            >
                                @foreach ($tiposMatricula as $tipo)
                                    <option value="{{ $tipo->id_tipo_matricula }}"
                                        {{ $matricula->id_tipo_matricula == $tipo->id_tipo_matricula ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Grado y Sección --}}
                    <div class="space-y-2">
                        <label for="seccion_id" class="block text-sm font-semibold text-gray-700">
                            Grado / Sección
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="seccion_id"
                                id="seccion_id"
                                class="w-full rounded-xl border border-gray-200 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       transition-all duration-200 hover:border-indigo-300"
                                required
                            >
                                @foreach ($secciones as $s)
                                    <option value="{{ $s->id_seccion }}"
                                        {{ $matricula->seccion_id == $s->id_seccion ? 'selected' : '' }}>
                                        {{ $s->grado->grado }}° - Sección {{ $s->seccion }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="space-y-2">
                        <label for="estado" class="block text-sm font-semibold text-gray-700">
                            Estado
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="estado"
                                id="estado"
                                class="w-full rounded-xl border border-gray-200 
                                       px-4 py-3.5 text-gray-900 shadow-sm appearance-none cursor-pointer
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                       transition-all duration-200 hover:border-indigo-300"
                                required
                            >
                                <option value="pendiente" {{ $matricula->estado == 'pendiente' ? 'selected' : '' }}>
                                    ⏳ Pendiente
                                </option>
                                <option value="activo" {{ $matricula->estado == 'activo' ? 'selected' : '' }}>
                                    ✅ Activo
                                </option>
                                <option value="rechazado" {{ $matricula->estado == 'rechazado' ? 'selected' : '' }}>
                                    ❌ Rechazado
                                </option>
                                <option value="finalizado" {{ $matricula->estado == 'finalizado' ? 'selected' : '' }}>
                                    🏁 Finalizado
                                </option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="ri-arrow-down-s-line text-xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Información del registro --}}
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                        <div class="flex gap-3">
                            <i class="ri-information-line text-indigo-600 text-xl flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-indigo-800">
                                <p class="font-medium mb-1">Información de la matrícula</p>
                                <div class="space-y-1 text-indigo-700">
                                    <p>Estudiante: <span class="font-semibold">{{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}</span></p>
                                    <p>DNI: <span class="font-semibold">{{ $matricula->estudiante->persona->dni }}</span></p>
                                    <p>Código Estudiante: <span class="font-semibold">{{ $matricula->estudiante->codigo_estudiante ?? 'Pendiente' }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a
                            href="{{ route('matriculas.show', $matricula->codigo_matricula) }}"
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
                                   bg-gradient-to-r from-indigo-500 to-purple-600 
                                   hover:from-indigo-600 hover:to-purple-700
                                   text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/30
                                   transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="ri-save-line text-lg"></i>
                            <span>Guardar cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 para mensajes --}}
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session("error") }}',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Entendido'
        });
    </script>
@endif
@endsection