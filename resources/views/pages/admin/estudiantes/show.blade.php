@extends('layout.admin.plantilla')

@section('title', 'Informacion Estudiante')

@section('contenido')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-8 space-y-6">
  <h2 class="text-2xl font-semibold text-gray-800">Información del Estudiante</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Nombre -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">Nombre</label>
      <input type="text" value="{{ $estudiante->persona->name }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- Apellido -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">Apellido</label>
      <input type="text" value="{{ $estudiante->persona->lastname }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- DNI -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">DNI</label>
      <input type="text" value="{{ $estudiante->persona->dni }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- Dirección -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">Dirección</label>
      <input type="text" value="{{ $estudiante->persona->address }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- País -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">País</label>
      <input type="text" value="{{ $estudiante->pais }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- Provincia -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">Provincia</label>
      <input type="text" value="{{ $estudiante->provincia }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- Distrito -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">Distrito</label>
      <input type="text" value="{{ $estudiante->distrito }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- Departamento -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">Departamento</label>
      <input type="text" value="{{ $estudiante->departamento }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- Lengua materna -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">Lengua Materna</label>
      <input type="text" value="{{ $estudiante->lengua_materna }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>

    <!-- Religión -->
    <div>
      <label class="block text-gray-600 text-sm mb-1">Religión</label>
      <input type="text" value="{{ $estudiante->religion }}" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-700 rounded-lg px-4 py-2 focus:outline-none" />
    </div>
  </div>
</div>
@endsection