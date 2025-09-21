@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Pagos')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#2e5382] mb-2">Gestión de Pagos</h1>
            <div class="w-24 mx-auto h-1 bg-[#64d423] rounded-full"></div>
            <p class="text-gray-600 mt-3">Administra todos los pagos del sistema</p>
        </div>

        <!-- Controls Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search Bar -->
                <div class="flex-1 max-w-md">
                    <form method="GET" class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input name="buscarpor" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-all duration-200" 
                            type="search" placeholder="Buscar por concepto o estudiante" value="{{ $buscarpor }}">
                        <button class="absolute inset-y-0 right-0 px-4 text-white bg-[#98C560] rounded-r-lg hover:bg-[#7aa94f] transition-all duration-200 flex items-center" type="submit">
                            <span class="hidden md:inline">Buscar</span>
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

            
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pagados</p>
                        <p class="text-2xl font-bold text-gray-900">
                            S/ {{ number_format($pagos->where('estado', 'Finalizado')->sum('monto'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pendientes</p>
                        <p class="text-2xl font-bold text-gray-900">
                            S/ {{ number_format($pagos->where('estado', '!=', 'Finalizado')->sum('monto'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-purple-100 p-3 mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Estudiantes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pagos->unique('codigo_matricula')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Estudiante</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Concepto</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Monto</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Fecha</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span>Estado</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Comprobante</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pagos as $pago)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Estudiante Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if ($pago->matricula->estudiante->persona->photo)
                                                <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200"
                                                    src="{{ Storage::url($pago->matricula->estudiante->persona->photo) }}"
                                                    alt="{{ $pago->matricula->estudiante->persona->name }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-[#2e5382] flex items-center justify-center text-white font-semibold text-xs">
                                                    {{ strtoupper(substr($pago->matricula->estudiante->persona->name, 0, 1)) }}{{ strtoupper(substr($pago->matricula->estudiante->persona->lastname, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $pago->matricula->estudiante->persona->name }} {{ $pago->matricula->estudiante->persona->lastname }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Matrícula: {{ $pago->codigo_matricula }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Concepto -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $pago->concepto }}</div>
                                </td>

                                <!-- Monto -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-green-600">
                                        S/ {{ number_format($pago->monto, 2) }}
                                    </div>
                                </td>

                                <!-- Fecha -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ date('d/m/Y', strtotime($pago->fecha_pago)) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ date('H:i', strtotime($pago->fecha_pago)) }}
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $pago->estado == 'Finalizado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $pago->estado }}
                                    </span>
                                </td>

                                <!-- Comprobante -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pago->comprobante_img)
                                        <button onclick="openModal('{{ Storage::url('public/'.$pago->comprobante_img) }}', 'image')"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors duration-150"
                                            title="Ver comprobante">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-400">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-6">
            <div class="text-sm text-gray-600">
                Mostrando {{ $pagos->firstItem() ?? 0 }} a {{ $pagos->lastItem() ?? 0 }} de {{ $pagos->total() }}
                resultados
            </div>
            <div class="flex justify-end">
                {{ $pagos->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal para visualizar imágenes -->
    <div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl max-h-[90vh] overflow-hidden mx-4">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Comprobante de Pago</h3>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="p-4 flex justify-center items-center">
                <!-- Content will be inserted by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-xl shadow-lg p-6 max-w-md w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Eliminar Pago</h2>
                    <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeDeleteModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 mb-4">¿Estás seguro de que deseas eliminar el pago "<span id="pagoConcepto" class="font-medium"></span>"?</p>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-200">
                            Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "Éxito!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    customClass: {
                        confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                    }
                });
            });
        </script>
    @endif

    <script>
        function openModal(url, type) {
            const modal = document.getElementById('archivoModal');
            const content = document.getElementById('modalContent');
            
            if (type === 'image') {
                content.innerHTML = `<img src="${url}" class="max-w-full max-h-[70vh] object-contain" alt="Comprobante de pago">`;
            }
            
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('archivoModal').classList.add('hidden');
        }

        function openDeleteModal(id, concepto) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('pagoConcepto').innerText = concepto;
            document.getElementById('deleteForm').action = `/pagos/${id}`;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const archivoModal = document.getElementById('archivoModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === archivoModal) {
                closeModal();
            }
            
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });
    </script>
@endsection