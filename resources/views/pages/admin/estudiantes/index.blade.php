@extends('layout.admin.plantilla')

@section('title', 'Gestión de Estudiantes')

@section('contenido')
<div class="max-w-screen-2xl mx-auto my-8 px-4">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-[#2e5382]">Estudiantes</h1>
        <div class="w-1/4 mx-auto h-0.5 bg-[#64d423]"></div>
    </div>

    <div class="flex justify-between mb-6">
        <div class="flex space-x-4">
            <input type="text" id="search" placeholder="Buscar por nombre o DNI" class="px-4 py-2 border rounded"
                oninput="buscarEstudiantes(this.value)">
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-200 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Apellido</th>
                    <th class="px-4 py-3">DNI</th>
                    <th class="px-4 py-3">Dirección</th>
                    <th class="px-4 py-3">País</th>
                    <th class="px-4 py-3">Provincia</th>
                    <th class="px-4 py-3">Distrito</th>
                    <th class="px-4 py-3">Departamento</th>
                    <th class="px-4 py-3">Lengua Materna</th>
                    <th class="px-4 py-3">Religión</th>
                    <th class="px-4 py-3">Foto</th>
                 <!--   <th class="px-4 py-3">Acciones</th>  -->
                </tr>
            </thead>
            <tbody>
                @foreach ($estudiantes as $estudiante)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $estudiante->persona->name }}</td>
                    <td class="px-4 py-3">{{ $estudiante->persona->lastname }}</td>
                    <td class="px-4 py-3">{{ $estudiante->persona->dni }}</td>
                    <td class="px-4 py-3">{{ $estudiante->persona->address }}</td>
                    <td class="px-4 py-3">{{ $estudiante->pais }}</td>
                    <td class="px-4 py-3">{{ $estudiante->provincia }}</td>
                    <td class="px-4 py-3">{{ $estudiante->distrito }}</td>
                    <td class="px-4 py-3">{{ $estudiante->departamento }}</td>
                    <td class="px-4 py-3">{{ $estudiante->lengua_materna }}</td>
                    <td class="px-4 py-3">{{ $estudiante->religion }}</td>
                    <td class="px-4 py-3">
                        @if ($estudiante->persona->photo)
                            <button class="w-8 h-8 flex items-center justify-center rounded shadow cursor-pointer"
                                onclick="openModal('{{ Storage::url($estudiante->persona->photo) }}', 'image')">
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
                 <!-- <td class="px-4 py-3 flex items-center justify-center space-x-4">
                        <button type="button" onclick="openEditModal(this)"
                            data-estudiante='@json($estudiante->load("persona"))'
                            class="text-yellow-500 hover:text-yellow-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    </td> -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end text-sm mt-4">
        {{ $estudiantes->links('pagination::tailwind') }}
    </div>
</div>

<!-- Modal de Edición -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
    <div class="flex items-center justify-center w-full h-full">
        <div class="bg-white px-8 py-6 rounded-lg shadow-xl max-w-4xl w-full relative max-h-screen overflow-y-auto">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-semibold text-blue-800">Editar Estudiante</h2>
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
                            <input type="text" id="edit_name" name="name" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_lastname" class="block text-gray-700">Apellidos:</label>
                            <input type="text" id="edit_lastname" name="lastname" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_dni" class="block text-gray-700">DNI:</label>
                            <input type="text" id="edit_dni" name="dni" class="w-full px-4 py-2 border rounded" required maxlength="8">
                        </div>
                     
                        <div>
                            <label for="edit_sexo" class="block text-gray-700">Sexo:</label>
                            <select id="edit_sexo" name="sexo" class="w-full px-4 py-2 border rounded">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_address" class="block text-gray-700">Dirección:</label>
                            <input type="text" id="edit_address" name="address" class="w-full px-4 py-2 border rounded" required>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="space-y-4">
                        <div>
                            <label for="edit_fecha_nacimiento" class="block text-gray-700">Fecha Nacimiento:</label>
                            <input type="date" id="edit_fecha_nacimiento" name="fecha_nacimiento" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_pais" class="block text-gray-700">País:</label>
                            <input type="text" id="edit_pais" name="pais" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_provincia" class="block text-gray-700">Provincia:</label>
                            <input type="text" id="edit_provincia" name="provincia" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_distrito" class="block text-gray-700">Distrito:</label>
                            <input type="text" id="edit_distrito" name="distrito" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_departamento" class="block text-gray-700">Departamento:</label>
                            <input type="text" id="edit_departamento" name="departamento" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_lengua_materna" class="block text-gray-700">Lengua Materna:</label>
                            <input type="text" id="edit_lengua_materna" name="lengua_materna" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_religion" class="block text-gray-700">Religión:</label>
                            <input type="text" id="edit_religion" name="religion" class="w-full px-4 py-2 border rounded" required>
                        </div>
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
                        <input type="file" id="edit_photo" name="photo" class="hidden" accept="image/*">
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
            title: "Actualizado!",
            text: "{{ session('success') }}",
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
function openEditModal(button) {
    const estudiante = JSON.parse(button.getAttribute('data-estudiante'));
    
    // Datos personales
    document.getElementById('edit_name').value = estudiante.persona.name;
    document.getElementById('edit_lastname').value = estudiante.persona.lastname;
    document.getElementById('edit_dni').value = estudiante.persona.dni;
    document.getElementById('edit_sexo').value = estudiante.persona.sexo;
    document.getElementById('edit_address').value = estudiante.persona.address;
   const fechaNacimiento = new Date(estudiante.persona.fecha_nacimiento);
    document.getElementById('edit_fecha_nacimiento').value = fechaNacimiento.toISOString().split('T')[0];
    
    // Datos de estudiante
    document.getElementById('edit_pais').value = estudiante.pais;
    document.getElementById('edit_provincia').value = estudiante.provincia;
    document.getElementById('edit_distrito').value = estudiante.distrito;
    document.getElementById('edit_departamento').value = estudiante.departamento;
    document.getElementById('edit_lengua_materna').value = estudiante.lengua_materna;
    document.getElementById('edit_religion').value = estudiante.religion;
    
    // Foto de perfil
    const previewImg = document.getElementById('previewImg');
    if(estudiante.persona.photo) {
        previewImg.src = `/storage/${estudiante.persona.photo}`;
        previewImg.classList.remove('hidden');
        document.getElementById('edit-image-placeholder').classList.add('hidden');
    } else {
        previewImg.src = '';
        previewImg.classList.add('hidden');
        document.getElementById('edit-image-placeholder').classList.remove('hidden');
    }
    
    // Establecer acción del formulario
    document.getElementById('editForm').action = `/estudiantes/${estudiante.id}`;
    
    // Mostrar modal
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function buscarEstudiantes(query) {
    fetch(`/estudiantes/buscar?search=${query}`, {
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

function openModal(imageUrl, type) {
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `<img src="${imageUrl}" class="w-full max-h-[75vh] object-contain">`;
    document.getElementById('archivoModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('archivoModal').classList.add('hidden');
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