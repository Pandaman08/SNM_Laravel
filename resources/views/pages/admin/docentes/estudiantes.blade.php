@extends('layout.admin.plantilla')
@section('titulo','Lista de asignaturas')

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
            <i class="ri-book-mark-line text-2xl text-[#38b2ac]"></i>Estudiantes Matriculado es  {{$asignatura->nombre}}
        </h1>
       
    </div>

    <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl shadow-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-gradient-to-r from-[#81e6d9] to-[#38b2ac] text-white uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Código Matricula</th>
                    <th class="px-6 py-3 text-left">Nombre Estudiante</th>
                     <th class="px-6 py-3 text-left">DNI Estudiante</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($matriculas as $a)
                    <tr class="even:bg-gray-50 hover:bg-gray-100 transition">
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $a->codigo_matricula }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $a->estudiante->persona->name  . ' '.$a->estudiante->persona->lastname}}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $a->estudiante->persona->dni }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('reporte_notas.create', ['codigo_matricula' => $a->codigo_matricula, 'id_asignatura' => $asignatura->codigo_asignatura]) }}"
                               class="inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600
                                      text-white rounded-md text-sm font-medium transition shadow-sm">
                                <i class="ri-pencil-line mr-1"></i> Registrar Calificacion
                            </a>
                         
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500 italic">
                           La Asignatura {{$asignatura->nombre}} no tiene estudiantes.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection