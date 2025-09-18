@extends('layout.admin.plantilla')

@section('title', 'Gestión de Estudiantes')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#2e5382] mb-2">Gestión de Estudiantes</h1>
            <div class="w-24 mx-auto h-1 bg-[#64d423] rounded-full"></div>
            <p class="text-gray-600 mt-3">Administra todos los estudiantes del sistema</p>
        </div>

        <!-- Controls Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search Bar -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" placeholder="Buscar por nombre o DNI..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-all duration-200"
                            oninput="buscarEstudiantes(this.value)">
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                        <span class="font-medium">Total: {{ $estudiantes->total() }}</span>
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
                                    <span>Información Personal</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Ubicación</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Cultural</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Foto
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($estudiantes as $estudiante)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Estudiante Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if ($estudiante->persona->photo)
                                                <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200"
                                                    src="{{ Storage::url($estudiante->persona->photo) }}"
                                                    alt="{{ $estudiante->persona->name }}">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-full bg-[#2e5382] flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($estudiante->persona->name, 0, 1)) }}{{ strtoupper(substr($estudiante->persona->lastname, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $estudiante->persona->name }} {{ $estudiante->persona->lastname }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                DNI: {{ $estudiante->persona->dni ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Información Personal -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-900">
                                            {{ $estudiante->persona->fecha_nacimiento ? \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        @if ($estudiante->persona->address)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ $estudiante->persona->address }}
                                            </div>
                                        @endif
                                        <div class="flex items-center space-x-2">
                                            @if ($estudiante->persona->sexo)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $estudiante->persona->sexo === 'M' ? 'Masculino' : 'Femenino' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Ubicación -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">País:</span> {{ $estudiante->pais ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Departamento:</span> {{ $estudiante->departamento ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Provincia:</span> {{ $estudiante->provincia ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Distrito:</span> {{ $estudiante->distrito ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Cultural -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Lengua materna:</span> {{ $estudiante->lengua_materna ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Religión:</span> {{ $estudiante->religion ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Foto -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($estudiante->persona->photo)
                                        <button
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-150"
                                            onclick="openModal('{{ Storage::url($estudiante->persona->photo) }}', 'image')"
                                            title="Ver foto">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-400">Sin foto</span>
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
                Mostrando {{ $estudiantes->firstItem() ?? 0 }} a {{ $estudiantes->lastItem() ?? 0 }} de {{ $estudiantes->total() }}
                resultados
            </div>
            <div class="flex justify-end">
                {{ $estudiantes->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal para visualizar imágenes -->
    <div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl max-h-[90vh] overflow-hidden mx-4">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Foto de Perfil</h3>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="p-4"></div>
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