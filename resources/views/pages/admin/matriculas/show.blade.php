@extends('layout.admin.plantilla')

@section('titulo', 'Detalle de Matrícula')

@section('contenido')
<div class="max-w-5xl mx-auto my-10 px-4 space-y-8">

    <!-- HEADER -->
    <div class="relative overflow-hidden rounded-2xl border border-blue-200 shadow-lg bg-gradient-to-br from-blue-50 via-white to-gray-50 p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">
                    Detalle de Matrícula
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Código:
                    <span class="font-semibold text-blue-700">
                        {{ $matricula->codigo_matricula }}
                    </span>
                </p>
            </div>

            @php
                $estadoClases = match($matricula->estado) {
                    'activo' => 'bg-green-100 text-green-800 ring-green-300',
                    'finalizado' => 'bg-blue-100 text-blue-800 ring-blue-300',
                    'rechazado' => 'bg-red-100 text-red-800 ring-red-300',
                    default => 'bg-yellow-100 text-yellow-800 ring-yellow-300',
                };
            @endphp

            <div class="flex items-center gap-3">
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full ring-1 {{ $estadoClases }} capitalize">
                    {{ $matricula->estado }}
                </span>

                @if ($matricula->estado === 'activo' && $matricula->pagos->where('estado','Finalizado')->count() > 0)
                    <a href="{{ route('matriculas.ficha', $matricula->codigo_matricula) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold shadow hover:bg-blue-700 transition">
                        📄 Ficha de Matrícula
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- INFO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- ESTUDIANTE -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md p-6 hover:shadow-lg transition">
            <h2 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 flex items-center gap-2">
                👤 Información del Estudiante
            </h2>

            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="text-gray-500">Nombre completo</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $matricula->estudiante->persona->name }}
                        {{ $matricula->estudiante->persona->lastname }}
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500">DNI</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $matricula->estudiante->persona->dni }}
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500">Fecha de nacimiento</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $matricula->estudiante->persona->fecha_nacimiento }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- ACADÉMICO -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md p-6 hover:shadow-lg transition">
            <h2 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 flex items-center gap-2">
                📘 Información Académica
            </h2>

            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="text-gray-500">Año escolar</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $matricula->anioEscolar->anio ?? 'N/A' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500">Tipo de matrícula</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $matricula->tipoMatricula->nombre ?? 'N/A' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500">Grado / Sección</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $matricula->seccion->grado->grado ?? 'N/A' }} -
                        {{ $matricula->seccion->seccion ?? 'N/A' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-gray-500">Fecha de matrícula</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $matricula->fecha }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- PAGOS -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 flex items-center gap-2">
            💳 Historial de Pagos
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Concepto</th>
                        <th class="px-5 py-3 text-left">Monto</th>
                        <th class="px-5 py-3 text-left">Fecha</th>
                        <th class="px-5 py-3 text-left">Estado</th>
                        <th class="px-5 py-3 text-left">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($matricula->pagos as $pago)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3">{{ $pago->concepto }}</td>
                            <td class="px-5 py-3 font-medium">S/ {{ number_format($pago->monto, 2) }}</td>
                            <td class="px-5 py-3">{{ $pago->fecha_pago }}</td>
                            <td class="px-5 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $pago->estado === 'Finalizado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $pago->estado }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <a href="{{ route('pagos.show', $pago->id_pago) }}"
                                   class="text-blue-600 font-semibold hover:underline">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500 italic">
                                No existen pagos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- FOOTER ACTION -->
    <div class="flex items-center justify-between">
        <a href="{{ Auth::user()->isTutor() ? route('matriculas.mis-matriculas') : route('matriculas.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition">
            ⬅ Volver a matrículas
        </a>

        <span class="text-sm text-gray-400 italic">
            Sistema de Gestión Académica · 2025
        </span>
    </div>

</div>
@endsection
