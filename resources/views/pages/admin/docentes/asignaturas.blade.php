@extends('layout.admin.plantilla')
@section('titulo', 'Lista de asignaturas')

@section('contenido')
    <div class="w-full animate-fade-in">
        @if (session('success') || session('error'))
            <script>
                Swal.fire({
                    icon: '{{ session('error') ? 'error' : 'success' }}',
                    title: @json(session('success') ?? session('error')),
                    showConfirmButton: false,
                    timer: 2000
                });
            </script>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
                <i class="ri-book-mark-line text-2xl text-[#38b2ac]"></i> Asignaturas Asignadas
            </h1>

        </div>

        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-lg">
            <table class="min-w-full text-sm">
                <thead class="bg-gradient-to-r from-[#81e6d9] to-[#38b2ac] text-white uppercase text-xs tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Código</th>
                        <th class="px-6 py-3 text-left">Nombre</th>
                        <th class="px-6 py-3 text-left">Grado</th>
                        <th class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detalles as $a)
                        <tr class="even:bg-gray-50 hover:bg-gray-100 transition">
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $a->asignatura->codigo_asignatura }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $a->asignatura->nombre }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $a->asignatura->grado->nombre_completo }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('docentes.estudiantes', $a->asignatura->codigo_asignatura) }}"
                                    class="inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600
                                      text-white rounded-md text-sm font-medium transition shadow-sm">
                                    <i class="ri-pencil-line mr-1"></i> Ver Estudiantes
                                </a>
                               

                            </td>
                               <td class="px-6 py-4 text-center space-x-2">
                             <a href="{{ route('reporte_notas.docente', $a->asignatura->codigo_asignatura) }}"
                                    class="text-blue-500 hover:text-blue-700 p-1 rounded-full hover:bg-blue-50"
                                    title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                                 </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500 italic">
                                No hay asignaturas Asignadas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
