@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Docentes')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#2e5382]">Periodos</h1>
            <div class="w-1/4 mx-auto h-0.5 bg-[#64d423]"></div>
        </div>

        <div class="flex justify-between mb-6">
            <div class="flex space-x-4">
               <form method="GET" class="flex items-center">
                    <input name="buscarpor" class="border rounded-l py-2 px-4" type="search"
                        placeholder="Buscar por año o descripción" value="{{ $buscarpor }}">
                    <button class="bg-[#98C560] text-white px-4 py-2 rounded-r hover:bg-[#7aa94f]" type="submit">
                        Buscar
                    </button>
                </form>

            </div>
            <a href="{{route('periodos.create')}}" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700" onclick="openCreateModal()">
                Registrar Periodo
            </a>
        </div>


        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-200 text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periodos as $periodo)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $periodo->nombre }}</td>
                          
        
                            <td class="px-4 py-3 flex items-center justify-center space-x-4">


                                <a href="{{route('periodos.edit',$periodo->id_periodo)}}"
                                  
                                    class="text-yellow-500 hover:text-yellow-700 flex items-center justify-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3l3 3-8 8H3v-3l8-8z" />
                                    </svg>
                                </a>


                                <button
                                    onclick="openDeleteModal({{ $periodo->id_periodo }}, '{{ $periodo->name }}')"
                                    class="text-red-500 hover:text-red-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 6h18M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2M10 11v6M14 11v6M5 6h14l1 16a1 1 0 01-1 1H5a1 1 0 01-1-1L5 6z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end text-sm mt-4">
            {{ $periodos->links('pagination::tailwind') }}
        </div>
    </div>

    
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
        <div class="flex items-center justify-center w-full h-full">
            <div class="bg-white p-7 rounded shadow-lg max-w-md w-full relative">
                <button class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2"
                    onclick="closeDeleteModal()">&times;</button>
                <h2 class="text-xl font-bold mb-4">Eliminar Periodo</h2>
                <p>¿Estás seguro de que deseas eliminar al usuario "<span id="usuarioNombre"></span>"?</p>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">Aceptar</button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-700">Cancelar</button>
                </form>
            </div>
        </div>
    </div>

 @if (session('success-update'))
        <script>
            Swal.fire({
                title: "Actualizado!",
                text: "{{ session('success-update') }}",
                icon: "success",
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @elseif (session('success-destroy'))
        <script>
            Swal.fire({
                title: "Eliminado!",
                text: "{{ session('success-destroy') }}",
                icon: "success",
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
        @elseif (session('success'))
        <script>
            Swal.fire({
                title: "Creado!",
                text: "{{ session('success') }}",
                icon: "success",
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @endif
<script>
 function openDeleteModal(id, firstname) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('usuarioNombre').innerText = firstname;
            const form = document.getElementById('deleteForm');


        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

</script>

@endsection