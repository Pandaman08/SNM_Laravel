@extends('layout.admin.plantilla')
@section('contenido')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 px-4 py-3">
            <h2 class="text-white text-xl font-semibold">Generar Código QR</h2>
        </div>
        
        <div class="p-6">
            <div class="mb-6 flex items-center space-x-4">
                @if($estudiante->persona->foto)
                    <img src="{{ asset('storage/' . $estudiante->persona->foto) }}" alt="Foto" class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-medium">{{ $estudiante->persona->nombres }} {{ $estudiante->persona->apellidos }}</h3>
                    <p class="text-gray-600">Código: {{ $estudiante->codigo_estudiante }}</p>
                </div>
            </div>

            <p class="text-gray-700 mb-4">¿Está seguro que desea generar un nuevo código QR para este estudiante?</p>
            <p class="text-sm text-yellow-600 bg-yellow-50 p-3 rounded-md mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                El código QR anterior se invalidará automáticamente.
            </p>
            
            <form method="POST" action="{{ route('asistencia.generate', $estudiante->codigo_estudiante) }}">
                @csrf
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                        Generar Nuevo QR
                    </button>
                    <a href="{{ route('reporte_notas.tutor') }}" 
                       class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection