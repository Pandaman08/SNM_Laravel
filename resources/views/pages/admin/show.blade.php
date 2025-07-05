@extends('layout.admin.plantilla')

@section('title', 'Gestión de Usuarios')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#2e5382]">Usuarios</h1>
            <div class="w-1/4 mx-auto h-0.5 bg-[#64d423]"></div>
        </div>

        <div class="flex justify-between mb-6">
            <div class="flex space-x-4">
                <input type="text" id="search" placeholder="Buscar por email" class="px-4 py-2 border rounded"
                    oninput="buscarUsuarios(this.value)">
            </div>
            <button class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700" onclick="openCreateModal()">
                Crear Usuario
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-200 text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Apellido</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Fecha Nacimiento</th>
                        <th class="px-4 py-3">Dirección</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $usuario)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $usuario->persona->name }}</td>
                            <td class="px-4 py-3">{{ $usuario->persona->lastname }}</td>
                            <td class="px-4 py-3">{{ $usuario->rol }}</td>
                            <td class="px-4 py-3">{{ $usuario->email }}</td>
                            <td class="px-4 py-3">{{ $usuario->persona->phone }}</td>
                            <td class="px-4 py-3">{{ $usuario->persona->fecha_nacimiento }}</td>
                            <td class="px-4 py-3">{{ $usuario->persona->address }}</td>
                            <td class="px-4 py-3">
                                @if ($usuario->persona->photo)
                                    <div class="px-8 py-0.1 text-center">
                                        <button
                                            class="w-8 h-8 flex items-center justify-start rounded shadow cursor-pointer"
                                            onclick="openModal('{{ Storage::url('' . $usuario->persona->photo) }}', 'image')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="w-6 h-6" viewBox="0 0 24 24">
                                                <path d="M18 22H4a2 2 0 0 1-2-2V6" />
                                                <path d="m22 13-1.296-1.296a2.41 2.41 0 0 0-3.408 0L11 18" />
                                                <circle cx="12" cy="8" r="2" />
                                                <rect width="16" height="16" x="6" y="2" rx="2" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span>No hay foto</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                <button type="button" onclick="openEditModal(this)"
                                    data-user='@json($usuario->load('persona'))'
                                    class="text-yellow-500 hover:text-yellow-700 flex items-center justify-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3l3 3-8 8H3v-3l8-8z" />
                                    </svg>
                                </button>

                                <button onclick="openDeleteModal({{ $usuario->user_id }}, '{{ $usuario->persona->name }}')"
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
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Modal de Creación -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-4 rounded shadow-lg max-w-5xl w-full max-h-screen overflow-y-auto relative">
            <button class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2"
                onclick="closeCreateModal()">
                &times;
            </button>
            <h2 class="text-xl font-bold mb-2">Crear Usuario</h2>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="name" class="block">Nombre</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="lastname" class="block">Apellido</label>
                            <input type="text" id="lastname" name="lastname" class="w-full px-4 py-2 border rounded"
                                required>
                        </div>
                        <div id="email-preview" class="text-sm text-gray-600 mt-2"></div>
                        <div class="mb-4">
                            <label for="dni" class="block">DNI</label>
                            <input type="text" id="dni" name="dni" class="w-full px-4 py-2 border rounded"
                                required maxlength="8" pattern="[0-9]{8}">
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block">Teléfono</label>
                            <input type="text" id="phone" name="phone" class="w-full px-4 py-2 border rounded"
                                required maxlength="9" pattern="[0-9]{9}">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="address" class="block">Dirección</label>
                            <input type="text" id="address" name="address" class="w-full px-4 py-2 border rounded"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="sexo" class="block text-gray-700">Sexo:</label>
                            <select id="sexo" name="sexo" class="w-full px-4 py-2 border rounded">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div>
                            <label for="estado_civil" class="block text-gray-700">Estado Civil:</label>
                            <select id="estado_civil" name="estado_civil"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="S">Soltero/a</option>
                                <option value="C">Casado/a</option>
                                <option value="D">Divorciado/a</option>
                                <option value="V">Viudo/a</option>
                            </select>
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="block text-gray-700">Fecha Nacimiento:</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                                      focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="block">Foto</label>
                            <input type="file" id="photo" name="photo" class="w-full px-2 py-1 border rounded"
                                accept="image/jpeg,image/png">
                        </div>
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-700">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
        <div class="flex items-center justify-center w-full h-full">
            <div class="bg-white px-8 py-6 rounded-lg shadow-xl max-w-4xl w-full relative max-h-screen overflow-y-auto">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-blue-800">Editar Usuario</h2>
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
                                <input type="text" id="edit_name" name="edit_name"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="edit_lastname" class="block text-gray-700">Apellidos:</label>
                                <input type="text" id="edit_lastname" name="edit_lastname"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="edit_dni" class="block text-gray-700">DNI:</label>
                                <input type="text" id="edit_dni" name="edit_dni"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required maxlength="8" pattern="[0-9]{8}">
                            </div>

                            <div>
                                <label for="edit_phone" class="block text-gray-700">Teléfono:</label>
                                <input type="text" id="edit_phone" name="edit_phone"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required maxlength="9" pattern="[0-9]{9}">
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="space-y-4">
                            <div>
                                <label for="edit_email" class="block text-gray-700">Email:</label>
                                <input type="email" id="edit_email" name="edit_email"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="edit_fecha_nacimiento" class="block text-gray-700">Fecha Nacimiento:</label>
                                <input type="date" id="edit_fecha_nacimiento" name="edit_fecha_nacimiento"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="edit_sexo" class="block text-gray-700">Sexo:</label>
                                <select id="edit_sexo" name="edit_sexo"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>

                            <div>
                                <label for="edit_estado_civil" class="block text-gray-700">Estado Civil:</label>
                                <select id="edit_estado_civil" name="edit_estado_civil"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="S">Soltero/a</option>
                                    <option value="C">Casado/a</option>
                                    <option value="D">Divorciado/a</option>
                                    <option value="V">Viudo/a</option>
                                </select>
                            </div>

                            <div>
                                <label for="edit_address" class="block text-gray-700">Dirección:</label>
                                <input type="text" id="edit_address" name="edit_address"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md
                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-gray-700 mb-1">Foto de Perfil:</label>
                        <div id="edit-image-upload"
                            class="border-2 border-dashed border-gray-300 w-full h-52
                            flex flex-col items-center justify-center cursor-pointer relative text-center rounded-md"
                            onclick="document.getElementById('edit_photo').click()" ondragover="handleDragOver(event)"
                            ondrop="handleDrop(event, 'edit_photo')">

                            <span id="edit-image-placeholder" class="text-gray-500 flex flex-col items-center">
                                <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 20 16" aria-hidden="true">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                Selecciona o arrastra una imagen (png, jpeg, jpg)
                            </span>

                            <img id="previewImg" src="" alt="Vista previa"
                                class="hidden w-52 h-full object-cover rounded shadow mx-auto">

                            <button type="button" id="edit_remove_image"
                                class="hidden absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition cursor-pointer"
                                onclick="removeImageEdit(event)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m3 0h4" />
                                </svg>
                            </button>
                            <input type="file" id="edit_photo" name="photo" class="hidden"
                                accept="image/png, image/jpeg, image/jpg" onchange="previewImageEdit(event)">
                        </div>
                    </div>

                    <div class="flex justify-center gap-4 mt-6">
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Actualizar
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">
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
                <button class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2"
                    onclick="closeDeleteModal()">&times;</button>
                <h2 class="text-xl font-bold mb-4">Eliminar Usuario</h2>
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

    <!-- Modal para visualizar imágenes -->
    <div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-7 rounded shadow-lg max-w-7xl w-full relative">
            <button class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2"
                onclick="closeModal()">×</button>
            <div id="modalContent"></div>
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
    @elseif (session('success'))
        <script>
            Swal.fire({
                title: "Registrado!",
                text: "{{ session('success') }}",
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
                title: '¡Hubo un error!',
                html: "{!! session('error') !!}",
                showConfirmButton: true,
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'bg-red-500 text-white hover:bg-red-600 focus:ring-2 focus:ring-red-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @endif

    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');

            // Mostrar cómo se generará el email
            const nameInput = document.getElementById('name');
            const lastnameInput = document.getElementById('lastname');
            const emailPreview = document.getElementById('email-preview');

            const updateEmailPreview = () => {
                const name = nameInput.value.toLowerCase();
                const lastname = lastnameInput.value.toLowerCase();
                if (name && lastname) {
                    const randomNumbers = Math.floor(1000 + Math.random() * 9000);
                    emailPreview.textContent =
                        `Email generado: ${name}${lastname.charAt(0)}_${randomNumbers}@bruning.com`;
                } else {
                    emailPreview.textContent = '';
                }
            };

            nameInput.addEventListener('input', updateEmailPreview);
            lastnameInput.addEventListener('input', updateEmailPreview);
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(button) {
            const user = JSON.parse(button.getAttribute('data-user'));

            // Llenar campos del formulario
            document.getElementById('edit_name').value = user.persona.name;
            document.getElementById('edit_lastname').value = user.persona.lastname;
            document.getElementById('edit_dni').value = user.persona.dni;
            document.getElementById('edit_phone').value = user.persona.phone;
            document.getElementById('edit_sexo').value = user.persona.sexo;
            document.getElementById('edit_estado_civil').value = user.persona.estado_civil;
            document.getElementById('edit_address').value = user.persona.address;
            const fechaNacimiento = new Date(user.persona.fecha_nacimiento);
            const formattedDate = fechaNacimiento.toISOString().split('T')[0];
            document.getElementById('edit_fecha_nacimiento').value = formattedDate;
            document.getElementById('edit_email').value = user.email;

            // Manejar la foto de perfil
            const previewImg = document.getElementById('previewImg');
            const editImagePlaceholder = document.getElementById('edit-image-placeholder');
            const removeBtn = document.getElementById('edit_remove_image');

            if (user.persona.photo) {
                previewImg.src = `/storage/${user.persona.photo}`;
                previewImg.classList.remove('hidden');
                editImagePlaceholder.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            } else {
                previewImg.src = "";
                previewImg.classList.add('hidden');
                editImagePlaceholder.classList.remove('hidden');
                removeBtn.classList.add('hidden');
            }

            // Establecer la acción del formulario
            document.getElementById('editForm').action = `/users/${user.user_id}`;

            // Mostrar el modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editForm').reset();
            removeImageEdit();
        }

        function previewImageEdit(event) {
            const input = event.target;
            const previewImage = document.getElementById("previewImg");
            const imagePlaceholder = document.getElementById("edit-image-placeholder");
            const removeBtn = document.getElementById("edit_remove_image");

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                if (!allowedTypes.includes(file.type)) {
                    alert("Tipo de archivo no permitido. Solo se permiten png, jpeg, jpg.");
                    input.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    imagePlaceholder.classList.add('hidden');
                    removeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImageEdit(event) {
            if (event) event.stopPropagation();
            const imageInput = document.getElementById("edit_photo");
            imageInput.value = "";
            const previewImage = document.getElementById("previewImg");
            previewImage.src = "";
            previewImage.classList.add('hidden');
            document.getElementById("edit-image-placeholder").classList.remove('hidden');
            document.getElementById("edit_remove_image").classList.add('hidden');
        }

        function handleDragOver(event) {
            event.preventDefault();
        }

        function handleDrop(event, inputId) {
            event.preventDefault();
            const inputElement = document.getElementById(inputId);
            if (event.dataTransfer.files && event.dataTransfer.files[0]) {
                inputElement.files = event.dataTransfer.files;
                if (inputId === 'edit_photo') {
                    previewImageEdit({
                        target: inputElement
                    });
                }
            }
        }

        function openDeleteModal(id, firstname) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('usuarioNombre').innerText = firstname;
            document.getElementById('deleteForm').action = `/users/${id}/delete`;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function buscarUsuarios(query) {
            fetch(`/users/buscar?search=${query}`, {
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
    </script>
@endsection
