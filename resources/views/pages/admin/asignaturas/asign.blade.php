@extends('layout.admin.plantilla')

@section('titulo', 'Asignar Docente')

@section('contenido')
    <h1 class="text-xl font-bold mb-4">Asignar docente a: {{ $asignatura->nombre }}</h1>

    <form action="{{ route('asignaturas.storeAsignacion') }}" method="POST">
        @csrf
        <input type="hidden" name="codigo_asignatura" value="{{ $asignatura->codigo_asignatura }}">

        <label for="codigo_docente">Seleccionar Docente:</label>
        <select name="codigo_docente" id="codigo_docente" class="block w-full p-2 mb-4 border rounded" required>
            <option value="">-- Seleccione un docente --</option>
            @foreach($docentes as $docente)
                <option value="{{ $docente->codigo_docente }}">
                    {{ $docente->user->persona->name }} {{ $docente->user->persona->lastname }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Guardar Asignación
        </button>
        
        <a href="{{ route('asignaturas.asignar.docentes') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">
            Cancelar
        </a>
    </form>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection