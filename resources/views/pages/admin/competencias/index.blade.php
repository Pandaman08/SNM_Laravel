@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Competencias')

@section('contenido')
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-gradient-to-r from-[#38b2ac] to-[#2c7a7b] rounded-2xl shadow-xl p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-white p-3 rounded-full shadow-lg mr-4">
                    <i class="ri-trophy-line text-3xl text-[#2c7a7b]"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">Gestión de Competencias</h1>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <form method="GET" class="flex-1">
                    <div class="relative">
                        <input name="buscarpor" class="w-full pl-4 pr-10 py-3 rounded-xl border-0 shadow-lg focus:ring-2 focus:ring-white focus:outline-none bg-white/90" 
                               type="search" placeholder="Buscar competencias..." value="{{ $buscarpor }}">
                        <button type="submit" class="absolute right-3 top-3 text-[#2c7a7b]">
                            <i class="ri-search-line text-xl"></i>
                        </button>
                    </div>
                </form>
                <a href="{{route('competencias.create')}}" 
                   class="bg-white text-[#2c7a7b] px-6 py-3 rounded-xl font-semibold shadow-lg hover:bg-gray-50 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="ri-add-line"></i> Nueva Competencia
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#38b2ac]">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Asignatura
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Descripción
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($competencias as $competencia)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $competencia->asignatura->nombre ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">Código: {{ $competencia->codigo_asignatura }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-800">{{ $competencia->descripcion }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="{{route('competencias.edit', $competencia->id_competencias)}}"
                                   class="text-blue-500 hover:text-blue-700 transition transform hover:scale-110">
                                    <i class="ri-edit-box-line text-xl"></i>
                                </a>
                                <button onclick="openDeleteModal({{ $competencia->id_competencias }}, '{{ $competencia->descripcion }}')"
                                        class="text-red-500 hover:text-red-700 transition transform hover:scale-110">
                                    <i class="ri-delete-bin-line text-xl"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron competencias registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $competencias->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Modal de Eliminación -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Confirmar Eliminación</h2>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <p class="text-gray-600 mb-6">¿Estás seguro de eliminar la competencia "<span id="competenciaDescripcion" class="font-semibold"></span>"?</p>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl font-medium transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (session('success'))
<script>
    Swal.fire({
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#38b2ac',
        confirmButtonText: 'Aceptar',
        customClass: {
            confirmButton: 'bg-[#38b2ac] hover:bg-[#2c7a7b] focus:ring-[#2c7a7b]'
        }
    });
</script>
@endif

<script>
    function openDeleteModal(id, descripcion) {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('competenciaDescripcion').textContent = descripcion;
        document.getElementById('deleteForm').action = `/competencias/${id}`;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection