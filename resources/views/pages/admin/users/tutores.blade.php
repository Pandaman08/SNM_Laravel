@extends('layout.admin.plantilla')
@section('title', 'Gestión de Personas')
@section('contenido')

<div class="max-w-screen-2xl mx-auto my-8 px-4">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-[#2e5382]">Tutores por aprobar</h1>
        <div class="w-1/4 mx-auto h-0.5 bg-[#64d423]"></div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-200 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Apellido</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Parentesco</th>
                    <th class="px-4 py-3">Teléfono</th>
                    <th class="px-4 py-3">Dirección</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $user->persona->name }}</td>
                        <td class="px-4 py-3">{{ $user->persona->lastname }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->tutor->parentesco }}</td>
                        <td class="px-4 py-3">{{ $user->persona->phone }}</td>
                        <td class="px-4 py-3">{{ $user->persona->address }}</td>
                        <td class="px-4 py-3">
                            @if ($user->persona->photo)
                                <div class="px-8 py-0.1 text-center">
                                    <button class="w-8 h-8 flex items-center justify-start rounded shadow cursor-pointer" 
                                            onclick="openModal('{{ Storage::url($user->persona->photo) }}', 'image')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6" viewBox="0 0 24 24">
                                            <path d="M18 22H4a2 2 0 0 1-2-2V6"/>
                                            <path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18"/>
                                            <circle cx="12" cy="8" r="2"/>
                                            <rect width="16" height="16" x="6" y="2" rx="2"/>
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <span>No hay foto</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center space-x-4">
                                @if (!$user->estado)
                                    <!-- Botón Aprobar -->
                                    <form action="{{ route('person.approve', $user->user_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-500 hover:text-green-700 font-medium"
                                                onclick="return confirm('¿Está seguro de que desea aprobar este tutor?')">
                                            Aprobar
                                        </button>
                                    </form>

                                    <!-- Botón Rechazar -->
                                    <button type="button" 
                                            class="text-red-500 hover:text-red-700 font-medium"
                                            onclick="openRejectModal({{ $user->user_id }})">
                                        Rechazar
                                    </button>
                                @else
                                    <span class="text-green-500 font-medium">Aprobado</span>
                                    
                                    <!-- Botón Eliminar (solo para usuarios ya aprobados) -->
                                    <form action="{{ route('person.destroy_person', $user->user_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700 ml-2"
                                                onclick="return confirm('¿Está seguro de que desea eliminar este tutor?')">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end text-sm mt-4">
        {{ $users->links('pagination::tailwind') }}
    </div>
</div>

<!-- Modal para Ver Imagen -->
<div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-7 rounded shadow-lg max-w-7xl w-full relative">
        <button class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2" onclick="closeModal()">×</button>
        <div id="modalContent"></div>
    </div>
</div>

<!-- Modal de Rechazo -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Rechazar Solicitud</h3>
        
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo del rechazo <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="reason" 
                    id="reason" 
                    rows="4" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Explique detalladamente el motivo del rechazo..."
                    required
                    maxlength="500"></textarea>
                <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50"
                        onclick="closeRejectModal()">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                        onclick="return confirm('¿Está seguro de rechazar esta solicitud? Esta acción no se puede deshacer.')">
                    Rechazar Solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts existentes y nuevos -->
@if (session('success-approve'))
    <script>
        Swal.fire({
            title: "Aprobado Exitosamente!",
            text: "{{ session('success-approve') }}",
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
@endif

<script>
    let currentUserId = null;

    // Función para abrir modal de imagen (ya existente)
    function openModal(imageUrl, type) {
        const modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `<img src="${imageUrl}" class="w-full max-h-[75vh] object-contain">`;
        document.getElementById('archivoModal').classList.remove('hidden');
    }

    // Función para cerrar modal de imagen (ya existente)
    function closeModal() {
        document.getElementById('archivoModal').classList.add('hidden');
    }

    // Nueva función para abrir modal de rechazo
    function openRejectModal(userId) {
        currentUserId = userId;
        document.getElementById('rejectForm').action = `/tutores/${userId}/reject`; 
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('reason').value = '';
        document.getElementById('reason').focus();
    }

    // Nueva función para cerrar modal de rechazo
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        currentUserId = null;
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
        }
    });
</script>

@endsection
