@extends('layout.admin.plantilla')

@section('titulo', 'Detalle de Pago')

@section('contenido')
<div class="max-w-3xl mx-auto my-8 px-4">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Comprobante de Pago</h1>
                <p class="text-gray-600">ID: {{ $pago->id_pago }}</p>
            </div>
            <div class="flex space-x-2">
                <span class="px-3 py-1 text-xs rounded-full 
                    {{ $pago->estado == 'Finalizado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $pago->estado }}
                </span>
            </div>
        </div>
    </div>

    <!-- Información principal -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Datos de la matrícula -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Datos de Matrícula</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Estudiante</p>
                    <p class="font-medium">{{ $pago->matricula->estudiante->persona->name }} {{ $pago->matricula->estudiante->persona->lastname }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Código de Matrícula</p>
                    <p class="font-medium">{{ $pago->matricula->codigo_matricula }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nivel/Grado/Sección</p>
                    <p class="font-medium">
                        {{ $pago->matricula->seccion->grado->nivelEducativo->nombre ?? 'N/A' }} /
                        {{ $pago->matricula->seccion->grado->grado ?? 'N/A' }} /
                        {{ $pago->matricula->seccion->seccion ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Datos del pago -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Datos del Pago</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Concepto</p>
                    <p class="font-medium">{{ $pago->concepto ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Monto</p>
                    <p class="font-medium">S/ {{ number_format($pago->monto, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Fecha de Pago</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Fecha de Registro</p>
                    <p class="font-medium">{{ $pago->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comprobante -->
    @if($pago->comprobante_img)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Comprobante</h2>
        <div class="flex justify-center">
            <div class="max-w-md">
                <img src="{{ asset('storage/' . $pago->comprobante_img) }}" 
                     alt="Comprobante de pago" 
                     class="rounded-lg border border-gray-200 shadow-sm">
                <div class="mt-4 flex justify-center">
                    <a href="{{ asset('storage/' . $pago->comprobante_img) }}" 
                       target="_blank"
                       class="text-blue-500 hover:text-blue-700 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Descargar comprobante
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Acciones -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between">
            <a href="{{ route('pagos.index') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a pagos
            </a>
            
           
        </div>
    </div>
</div>
@endsection