@extends('layout.admin.plantilla')

@section('titulo', 'Mis Matrículas')

@section('contenido')
<div class="max-w-screen-2xl mx-auto my-8 px-4">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-[#2e5382]">Matrículas de Mis Estudiantes</h1>
        <div class="w-1/4 mx-auto h-0.5 bg-[#64d423]"></div>
    </div>

    <div class="flex justify-between mb-6">
        <div class="flex space-x-4">
            <form method="GET" class="flex items-center">
                <input name="buscarpor" class="border rounded-l py-2 px-4 focus:ring-[#98C560] focus:border-[#98C560]" 
                       type="search" placeholder="Buscar por estudiante o código" value="{{ $buscarpor }}">
                <button class="bg-[#98C560] text-white px-4 py-2 rounded-r hover:bg-[#7aa94f] flex items-center" type="submit">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Buscar
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-200 text-gray-700 uppercase">
                <tr>
                    <th class="px-6 py-3">Código</th>
                    <th class="px-6 py-3">Estudiante</th>
                    <th class="px-6 py-3">Nivel Educativo</th>
                    <th class="px-6 py-3">Grado/Sección</th>
                    <th class="px-6 py-3">Tipo</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($matriculas as $matricula)
                    @php
                        $pago = $matricula->pagos()->latest()->first();
                        $nivelGrado = $matricula->seccion->grado->nivelEducativo->nombre ?? 'N/A';
                        $gradoSeccion = ($matricula->seccion->grado->grado ?? 'N/A') . ' ' . ($matricula->seccion->seccion ?? '');
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $matricula->codigo_matricula }}</td>
                        <td class="px-6 py-4">
                            {{ $matricula->estudiante->persona->name . ' ' . $matricula->estudiante->persona->lastname }}
                        </td>
                        <td class="px-6 py-4">{{ $nivelGrado }}</td>
                        <td class="px-6 py-4">{{ $gradoSeccion }}</td>
                        <td class="px-6 py-4">{{ $matricula->tipoMatricula->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $matricula->fecha }}</td>
                        <td class="px-6 py-4">
                            @if(!$pago)
                                <a href="{{ route('pagos.create', $matricula->codigo_matricula) }}" class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 flex items-center justify-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pendiente de pago
                                </a>
                            @elseif($pago && $matricula->estado !=='pendiente')
                                <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 flex items-center justify-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pendiente de validación
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 flex items-center justify-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Validado y finalizado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('matriculas.show', $matricula->codigo_matricula) }}"
                                   class="text-blue-500 hover:text-blue-700 p-1 rounded-full hover:bg-blue-50"
                                   title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                
                                @if($pago)
                                <a href="{{ route('pagos.show', $pago->id_pago) }}"
                                   class="text-purple-500 hover:text-purple-700 p-1 rounded-full hover:bg-purple-50"
                                   title="Ver pago">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No se encontraron matrículas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end text-sm mt-4">
        {{ $matriculas->links('pagination::tailwind') }}
    </div>
</div>

@if (session('success'))
    <script>
        Swal.fire({
            title: "Éxito!",
            text: "{{ session('success') }}",
            icon: "success",
            customClass: {
                confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
            }
        });
    </script>
@endif
@endsection