@extends('layout.admin.plantilla')

@section('titulo', 'Detalle de Matrícula')

@section('contenido')
    <div class="max-w-4xl mx-auto my-8 px-4">
        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-blue-50 to-gray-100 rounded-2xl shadow-lg p-6 mb-6 border border-blue-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Detalles de Matrícula</h1>
                    <p class="text-gray-600 mt-1 text-sm">Código: 
                        <span class="font-semibold text-blue-700">{{ $matricula->codigo_matricula }}</span>
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    @php
                        $color = null;

                        if ($matricula->estado == 'activo') {
                            $color = 'green';
                        } elseif ($matricula->estado == 'finalizado') {
                            $color = 'blue';
                        } elseif ($matricula->estado == 'rechazado') {
                            $color = 'red';
                        } else {
                            $color = 'orange';
                        }
                    @endphp
                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full shadow-sm 
                        bg-{{$color}}-100 text-{{$color}}-800 capitalize">
                        {{ $matricula->estado  }}
                    </span>

                    @if ($matricula->estado == 'activo' && $matricula->pagos->where('estado', 'Finalizado')->count() > 0)
                        <a href="{{ route('matriculas.ficha', $matricula->codigo_matricula) }}"
                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl flex items-center text-sm font-medium shadow-md transition duration-300 ease-in-out"
                            target="_blank">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Ficha de Matrícula
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información principal -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Datos del estudiante -->
            <div class="bg-white border border-blue-100 rounded-xl shadow-md p-6 hover:shadow-lg transition">
                <h2 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2"> 👤Datos del Estudiante</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Nombre completo</p>
                        <p class="font-medium text-gray-800">{{ $matricula->estudiante->persona->name }}
                            {{ $matricula->estudiante->persona->lastname }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">DNI</p>
                        <p class="font-medium text-gray-800">{{ $matricula->estudiante->persona->dni }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fecha de nacimiento</p>
                        <p class="font-medium text-gray-800">{{ $matricula->estudiante->persona->fecha_nacimiento }}</p>
                    </div>
                </div>
            </div>

            <!-- Datos académicos -->
            <div class="bg-white border border-blue-100 rounded-xl shadow-md p-6 hover:shadow-lg transition">
                <h2 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">📘 Datos Académicos</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Año escolar</p>
                        <p class="font-medium text-gray-800">{{ $matricula->anioEscolar->anio ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tipo de matrícula</p>
                        <p class="font-medium text-gray-800">{{ $matricula->tipoMatricula->nombre ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Grado/Sección</p>
                        <p class="font-medium text-gray-800">
                            {{ $matricula->seccion->grado->grado ?? 'N/A' }} /
                            {{ $matricula->seccion->seccion ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fecha de matrícula</p>
                        <p class="font-medium text-gray-800">{{ $matricula->fecha }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de pagos -->
        <div class="bg-white border border-blue-100 rounded-xl shadow-md p-6 hover:shadow-lg transition mb-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">💳 Historial de Pagos</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Concepto</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Monto</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($matricula->pagos as $pago)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4">{{ $pago->concepto }}</td>
                                <td class="px-6 py-4">S/ {{ number_format($pago->monto, 2) }}</td>
                                <td class="px-6 py-4">{{ $pago->fecha_pago }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full font-semibold shadow-sm
                                        {{ $pago->estado == 'Finalizado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $pago->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('pagos.show', $pago->id_pago) }}"
                                        class="text-blue-600 font-medium hover:text-blue-800 transition">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No hay pagos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones -->
        <div class="bg-gradient-to-r from-white-50 to-white-100 rounded-2xl shadow-lg p-6 border border-blue-200">
              <div class="flex justify-between items-center">
        <a href="{{ Auth::user()->isTutor() ? route('matriculas.mis-matriculas') : route('matriculas.index') }}"
            class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white text-blue-700 font-semibold shadow hover:bg-blue-600 hover:text-white transition-all duration-300 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a matrículas
        </a>
        <span class="text-sm text-gray-500 italic">Gestión Académica 2025</span>
    </div>
</div>
    </div>
@endsection
