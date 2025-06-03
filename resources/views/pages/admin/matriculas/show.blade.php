@extends('layout.admin.plantilla')

@section('titulo', 'Detalle de Matrícula')

@section('contenido')
<div class="max-w-4xl mx-auto my-8 px-4">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detalles de Matrícula</h1>
                <p class="text-gray-600">Código: {{ $matricula->codigo_matricula }}</p>
            </div>
            <div>
                <span class="px-3 py-1 text-xs rounded-full 
                    {{ $matricula->estado_validacion ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $matricula->estado_validacion ? 'Validada' : 'Pendiente de validación' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Información principal -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Datos del estudiante -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Datos del Estudiante</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Nombre completo</p>
                    <p class="font-medium">{{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">DNI</p>
                    <p class="font-medium">{{ $matricula->estudiante->persona->dni }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Fecha de nacimiento</p>
                    <p class="font-medium">{{ $matricula->estudiante->persona->fecha_nacimiento }}</p>
                </div>
            </div>
        </div>

        <!-- Datos académicos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Datos Académicos</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Año escolar</p>
                    <p class="font-medium">{{ $matricula->anioEscolar->anio ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tipo de matrícula</p>
                    <p class="font-medium">{{ $matricula->tipoMatricula->nombre ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Grado/Sección</p>
                    <p class="font-medium">
                        {{ $matricula->seccion->grado->nombre ?? 'N/A' }} /
                        {{ $matricula->seccion->nombre ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Fecha de matrícula</p>
                    <p class="font-medium">{{ $matricula->fecha }}</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Historial de pagos -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Historial de Pagos</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concepto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($matricula->pagos as $pago)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pago->concepto }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">S/ {{ number_format($pago->monto, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $pago->fecha_pago }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $pago->estado == 'Finalizado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $pago->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('pagos.show', $pago->id_pago) }}" 
                               class="text-blue-500 hover:text-blue-700">Ver</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay pagos registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Acciones -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between">
            <a href="{{ route('matriculas.mis-matriculas') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a matrículas
            </a>
            
           
        </div>
    </div>
</div>
@endsection