@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Matrículas')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#2e5382]">Matrículas Registradas</h1>
            <div class="w-1/4 mx-auto h-0.5 bg-[#64d423]"></div>
        </div>

        <div class="flex justify-between mb-6">
            <div class="flex space-x-4">
                <form method="GET" class="flex items-center">
                    <input name="buscarpor" class="border rounded-l py-2 px-4" type="search"
                        placeholder="Buscar por estudiante o código" value="{{ $buscarpor }}">
                    <button class="bg-[#98C560] text-white px-4 py-2 rounded-r hover:bg-[#7aa94f]" type="submit">
                        Buscar
                    </button>
                </form>
            </div>
            <a href="{{route('matriculas.create')}}" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                Nueva Matrícula
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-200 text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3">Estudiante</th>
                        <th class="px-4 py-3">Año Escolar</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matriculas as $matricula)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $matricula->codigo_matricula }}</td>
                            <td class="px-4 py-3">{{ $matricula->estudiante->user->persona->name ?? 'No' }}</td>
                            <td class="px-4 py-3">{{ $matricula->anioEscolar->anio ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $matricula->tipoMatricula->nombre ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $matricula->fecha }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $matricula->estado_validacion ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $matricula->estado_validacion ? 'Validada' : 'Pendiente' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex items-center justify-center space-x-4">


                                <button onclick="openDeleteModal({{ $matricula->codigo_matricula }}, '{{ $matricula->estudiante->user->persona->name }}')"
                                    class="text-red-500 hover:text-red-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end text-sm mt-4">
            {{ $matriculas->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Modal de Eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
        <div class="flex items-center justify-center w-full h-full">
            <div class="bg-white p-7 rounded shadow-lg max-w-md w-full relative">
                <button class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2"
                    onclick="closeDeleteModal()">&times;</button>
                <h2 class="text-xl font-bold mb-4">Eliminar Matrícula</h2>
                <p>¿Estás seguro de que deseas eliminar la matrícula de "<span id="estudianteNombre"></span>"?</p>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" onclick="closeDeleteModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">Cancelar</button>
                        <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">Eliminar</button>
                    </div>
                </form>
            </div>
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

    <script>
        function openDeleteModal(id, nombre) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('estudianteNombre').innerText = nombre;
            document.getElementById('deleteForm').action = `/matriculas/${id}`;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endsection