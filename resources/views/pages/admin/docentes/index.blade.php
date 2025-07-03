@extends('layout.admin.plantilla')

@section('title', 'Gestión de Docentes')

@section('contenido')
<div class="max-w-screen-2xl mx-auto my-8 px-4">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-[#2e5382]">Docentes</h1>
        <div class="w-1/4 mx-auto h-0.5 bg-[#64d423]"></div>
    </div>

    <div class="flex justify-between mb-6">
        <div class="flex space-x-4">
            <input type="text" id="search" placeholder="Buscar por nombre o email" class="px-4 py-2 border rounded"
                oninput="buscarDocentes(this.value)">
        </div>
        <button class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700" onclick="openCreateModal()">
            Registrar Docente
        </button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-200 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-3">Código</th>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Apellido</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Teléfono</th>
                    <th class="px-4 py-3">Especialidad</th>
                    <th class="px-4 py-3">Jornada</th>
                    <th class="px-4 py-3">Departamento</th>
                    <th class="px-4 py-3">Contratación</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $user->docente->codigo_docente }}</td>
                    <td class="px-4 py-3">{{ $user->persona->name }}</td>
                    <td class="px-4 py-3">{{ $user->persona->lastname }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">{{ $user->persona->phone }}</td>
                    <td class="px-4 py-3">{{ $user->docente->especialidad }}</td>
                    <td class="px-4 py-3">{{ $user->docente->jornada_laboral }} hrs</td>
                    <td class="px-4 py-3">{{ $user->docente->departamento_estudios }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($user->docente->fecha_contratacion)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        @if ($user->persona->photo)
                        <button class="w-8 h-8 flex items-center justify-center rounded shadow cursor-pointer"
                            onclick="openModal('{{ Storage::url($user->persona->photo) }}', 'image')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
                                class="w-6 h-6" viewBox="0 0 24 24">
                                <path d="M18 22H4a2 2 0 0 1-2-2V6" />
                                <path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18" />
                                <circle cx="12" cy="8" r="2" />
                                <rect width="16" height="16" x="6" y="2" rx="2" />
                            </svg>
                        </button>
                        @else
                        <span>No hay foto</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex items-center justify-center space-x-4">
                        <button type="button" onclick="openEditModal(this)"
                            data-user='@json($user->load(["persona", "docente"]))'
                            class="text-yellow-500 hover:text-yellow-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        <button onclick="openDeleteModal({{ $user->user_id }}, '{{ $user->persona->name }}')"
                            class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
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

<!-- Modal de Creación -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded shadow-lg max-w-4xl w-full max-h-screen overflow-y-auto relative">
        <button class="absolute top-2 right-2 text-gray-500 hover:text-black text-3xl p-2"
            onclick="closeCreateModal()">
            &times;
        </button>
        <h2 class="text-xl font-bold mb-4">Registrar Nuevo Docente</h2>
        <form action="{{ route('docentes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna 1 -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block">Nombre</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="lastname" class="block">Apellido</label>
                        <input type="text" id="lastname" name="lastname" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="dni" class="block">DNI</label>
                        <input type="text" id="dni" name="dni" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="phone" class="block">Teléfono</label>
                        <input type="text" id="phone" name="phone" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="sexo" class="block">Sexo</label>
                        <select id="sexo" name="sexo" class="w-full px-4 py-2 border rounded">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                </div>

                <!-- Columna 2 -->
                <div class="space-y-4">
                    <div>
                        <label for="address" class="block">Dirección</label>
                        <input type="text" id="address" name="address" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="fecha_nacimiento" class="block">Fecha Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="especialidad" class="block">Especialidad</label>
                        <input type="text" id="especialidad" name="especialidad" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="jornada_laboral" class="block">Jornada Laboral (horas)</label>
                        <input type="number" step="0.1" id="jornada_laboral" name="jornada_laboral" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="departamento_estudios" class="block">Departamento de Estudios</label>
                        <input type="text" id="departamento_estudios" name="departamento_estudios" class="w-full px-4 py-2 border rounded" required>
                    </div>
                </div>
            </div>

            <!-- Fila adicional -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label for="fecha_contratacion" class="block">Fecha de Contratación</label>
                    <input type="date" id="fecha_contratacion" name="fecha_contratacion" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div>
                    <label for="photo" class="block">Foto de Perfil</label>
                    <input type="file" id="photo" name="photo" class="w-full px-2 py-1 border rounded" accept="image/*">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Registrar Docente
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Edición -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
    <div class="flex items-center justify-center w-full h-full">
        <div class="bg-white px-8 py-6 rounded-lg shadow-xl max-w-4xl w-full relative max-h-screen overflow-y-auto">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-semibold text-blue-800">Editar Docente</h2>
                <div class="mx-auto mt-2 w-1/5 h-1 bg-green-400"></div>
            </div>

            <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda -->
                    <div class="space-y-4">
                        <div>
                            <label for="edit_name" class="block text-gray-700">Nombres:</label>
                            <input type="text" id="edit_name" name="edit_name" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_lastname" class="block text-gray-700">Apellidos:</label>
                            <input type="text" id="edit_lastname" name="edit_lastname" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_dni" class="block text-gray-700">DNI:</label>
                            <input type="text" id="edit_dni" name="edit_dni" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_phone" class="block text-gray-700">Teléfono:</label>
                            <input type="text" id="edit_phone" name="edit_phone" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_sexo" class="block text-gray-700">Sexo:</label>
                            <select id="edit_sexo" name="edit_sexo" class="w-full px-4 py-2 border rounded">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="space-y-4">
                        <div>
                            <label for="edit_email" class="block text-gray-700">Email:</label>
                            <input type="email" id="edit_email" name="edit_email" class="w-full px-4 py-2 border rounded bg-gray-100" readonly>
                        </div>
                        <div>
                            <label for="edit_fecha_nacimiento" class="block text-gray-700">Fecha Nacimiento:</label>
                            <input type="date" id="edit_fecha_nacimiento" name="edit_fecha_nacimiento" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_address" class="block text-gray-700">Dirección:</label>
                            <input type="text" id="edit_address" name="edit_address" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_especialidad" class="block text-gray-700">Especialidad:</label>
                            <input type="text" id="edit_especialidad" name="edit_especialidad" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_jornada_laboral" class="block text-gray-700">Jornada Laboral (horas):</label>
                            <input type="number" step="0.1" id="edit_jornada_laboral" name="edit_jornada_laboral" class="w-full px-4 py-2 border rounded" required>
                        </div>
                    </div>
                </div>

                <!-- Segunda fila de campos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="edit_departamento_estudios" class="block text-gray-700">Departamento de Estudios:</label>
                        <input type="text" id="edit_departamento_estudios" name="edit_departamento_estudios" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="edit_fecha_contratacion" class="block text-gray-700">Fecha de Contratación:</label>
                        <input type="date" id="edit_fecha_contratacion" name="edit_fecha_contratacion" class="w-full px-4 py-2 border rounded" required>
                    </div>
                </div>

                <!-- Foto de perfil -->
                <div class="mt-6">
                    <label class="block text-gray-700 mb-1">Foto de Perfil:</label>
                    <div id="edit-image-upload" class="border-2 border-dashed border-gray-300 w-full h-52 flex flex-col items-center justify-center cursor-pointer relative text-center rounded-md" onclick="document.getElementById('edit_photo').click()">
                        <span id="edit-image-placeholder" class="text-gray-500 flex flex-col items-center">
                            <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            Selecciona o arrastra una imagen
                        </span>
                        <img id="previewImg" src="" alt="Vista previa" class="hidden w-52 h-full object-cover rounded shadow mx-auto">
                        <input type="file" id="edit_photo" name="edit_photo" class="hidden" accept="image/*">
                    </div>
                </div>

                <div class="flex justify-center gap-4 mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Actualizar
                    </button>
                    <button type="button" onclick="closeEditModal()" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Eliminación -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
    <div class="flex items-center justify-center w-full h-full">
        <div class="bg-white p-7 rounded shadow-lg max-w-md w-full relative">
            <button class="absolute top-2 right-2 text-gray-500 hover:text-black text-3xl p-2"
                onclick="closeDeleteModal()">&times;</button>
            <h2 class="text-xl font-bold mb-4">Eliminar Docente</h2>
            <p>¿Estás seguro de que deseas eliminar al docente "<span id="docenteNombre"></span>"?</p>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">Aceptar</button>
                <button type="button" onclick="closeDeleteModal()" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-700">Cancelar</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal para visualizar imágenes -->
<div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-7 rounded shadow-lg max-w-7xl w-full relative">
        <button class="absolute top-2 right-2 text-gray-500 hover:text-black text-3xl p-2"
            onclick="closeModal()">×</button>
        <div id="modalContent"></div>
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
@elseif (session('success-update'))
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
@elseif (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            html: "{!! session('error') !!}",
            customClass: {
                confirmButton: 'bg-red-500 text-white hover:bg-red-600 focus:ring-2 focus:ring-red-300 rounded-lg py-2 px-4'
            }
        });
    </script>
@endif

<script>
// Funciones para los modales
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(button) {
    const user = JSON.parse(button.getAttribute('data-user'));
    
    // Datos personales
    document.getElementById('edit_name').value = user.persona.name;
    document.getElementById('edit_lastname').value = user.persona.lastname;
    document.getElementById('edit_dni').value = user.persona.dni;
    document.getElementById('edit_phone').value = user.persona.phone;
    document.getElementById('edit_sexo').value = user.persona.sexo;
    document.getElementById('edit_address').value = user.persona.address;
    
    // Formatear fechas
    const fechaNacimiento = new Date(user.persona.fecha_nacimiento);
    document.getElementById('edit_fecha_nacimiento').value = fechaNacimiento.toISOString().split('T')[0];
    
    // Datos de usuario
    document.getElementById('edit_email').value = user.email;
    
    // Datos de docente
    document.getElementById('edit_especialidad').value = user.docente.especialidad;
    document.getElementById('edit_jornada_laboral').value = user.docente.jornada_laboral;
    document.getElementById('edit_departamento_estudios').value = user.docente.departamento_estudios;
    
    const fechaContratacion = new Date(user.docente.fecha_contratacion);
    document.getElementById('edit_fecha_contratacion').value = fechaContratacion.toISOString().split('T')[0];
    
    // Foto de perfil
    const previewImg = document.getElementById('previewImg');
    if(user.persona.photo) {
        previewImg.src = `/storage/${user.persona.photo}`;
        previewImg.classList.remove('hidden');
        document.getElementById('edit-image-placeholder').classList.add('hidden');
    } else {
        previewImg.src = '';
        previewImg.classList.add('hidden');
        document.getElementById('edit-image-placeholder').classList.remove('hidden');
    }
    
    // Establecer acción del formulario
    document.getElementById('editForm').action = `/docentes/${user.user_id}`;
    
    // Mostrar modal
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openDeleteModal(id, nombre) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('docenteNombre').textContent = nombre;
    document.getElementById('deleteForm').action = `/docentes/${id}/delete`;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function openModal(imageUrl, type) {
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `<img src="${imageUrl}" class="w-full max-h-[75vh] object-contain">`;
    document.getElementById('archivoModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('archivoModal').classList.add('hidden');
}

function buscarDocentes(query) {
    fetch(`/docentes/buscar?search=${query}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const tableBody = doc.querySelector('tbody');
        document.querySelector('tbody').innerHTML = tableBody.innerHTML;
    })
    .catch(error => console.error('Error:', error));
}

// Preview de imagen en edición
document.getElementById('edit_photo').addEventListener('change', function(e) {
    const previewImg = document.getElementById('previewImg');
    const placeholder = document.getElementById('edit-image-placeholder');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endsection