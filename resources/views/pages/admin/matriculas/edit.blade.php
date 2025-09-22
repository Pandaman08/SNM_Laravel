@extends('layout.admin.plantilla')

@section('titulo', 'Editar Matrícula')

@section('contenido')
<div class="max-w-4xl mx-auto my-8 px-4">
    <!-- Encabezado -->
    <div class="bg-gradient-to-r from-yellow-50 to-gray-100 rounded-2xl shadow-lg p-6 mb-6 border border-yellow-200">
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Editar Matrícula</h1>
        <p class="text-gray-600 mt-1 text-sm">
            Código: <span class="font-semibold text-yellow-700">{{ $matricula->codigo_matricula }}</span>
        </p>
    </div>

    <!-- Formulario de edición -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6">
        <form action="{{ route('matriculas.update', $matricula->codigo_matricula) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Estudiante (solo lectura) -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Estudiante</label>
                <input type="text" value="{{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}"
                       disabled
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Año Escolar -->
            <div class="mb-4">
                <label for="id_anio_escolar" class="block text-gray-700 font-medium mb-2">Año Escolar</label>
                <select name="id_anio_escolar" id="id_anio_escolar"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500">
                    @foreach ($aniosEscolares as $anio)
                        <option value="{{ $anio->id_anio_escolar }}"
                            {{ $matricula->id_anio_escolar == $anio->id_anio_escolar ? 'selected' : '' }}>
                            {{ $anio->anio }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo de matrícula -->
            <div class="mb-4">
                <label for="id_tipo_matricula" class="block text-gray-700 font-medium mb-2">Tipo de Matrícula</label>
                <select name="id_tipo_matricula" id="id_tipo_matricula"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500">
                    @foreach ($tiposMatricula as $tipo)
                        <option value="{{ $tipo->id_tipo_matricula }}"
                            {{ $matricula->id_tipo_matricula == $tipo->id_tipo_matricula ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Grado y Sección -->
            <div class="mb-4">
                <label for="seccion_id" class="block text-gray-700 font-medium mb-2">Grado / Sección</label>
                <select name="seccion_id" id="seccion_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500">
                    @foreach ($secciones as $s) 
                        <option value="{{ $s->id_seccion }}"
                            {{ $matricula->seccion_id == $s->id_seccion ? 'selected' : '' }}>
                            {{ $s->grado->grado }} - {{ $s->seccion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Estado -->
            <div class="mb-4">
                <label for="estado" class="block text-gray-700 font-medium mb-2">Estado</label>
                <select name="estado" id="estado"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500">
                    <option value="pendiente" {{ $matricula->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="activo" {{ $matricula->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="rechazado" {{ $matricula->estado == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    <option value="finalizado" {{ $matricula->estado == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('matriculas.show', $matricula->codigo_matricula) }}"
                   class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 rounded-xl font-medium text-gray-700 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-xl shadow transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#f59e0b',
        timer: 2500
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session("error") }}',
        confirmButtonColor: '#f43f5e',
    });
</script>
@endif

@endsection




