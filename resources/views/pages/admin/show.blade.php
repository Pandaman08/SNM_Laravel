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
                <input
                    type="text"
                    id="search"
                    placeholder="Buscar por nombre o email"
                    class="px-4 py-2 border rounded"
                    oninput="buscarUsuarios(this.value)"
                >
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
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Dirección</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $usuario)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $usuario->name }}</td>
                            <td class="px-4 py-3">{{ $usuario->lastname }}</td>
                            <td class="px-4 py-3">{{ $usuario->email }}</td>
                            <td class="px-4 py-3">{{ $usuario->phone }}</td>
                            <td class="px-4 py-3">{{ $usuario->address }}</td>
                            <td class="px-4 py-3">
                                @if ($usuario->photo)
                                <div class="px-8 py-0.1 text-center">
                                        <button 
                                            class="w-8 h-8 flex items-center justify-start rounded shadow cursor-pointer"
                                            onclick="openModal('{{ Storage::url(''. $usuario->photo) }}', 'image')"
                                        >
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                fill="none" 
                                                stroke="currentColor" 
                                                stroke-width="2" 
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                class="w-6 h-6"
                                                viewBox="0 0 24 24"
                                            >
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
                            <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                
                                <a
                                    href=""
                                    class="text-yellow-500 hover:text-yellow-700 flex items-center justify-center mt-2"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3l3 3-8 8H3v-3l8-8z" />
                                    </svg>
                                </a>
                               
                               <button onclick="openDeleteModal({{ $usuario->user_id}}, '{{ $usuario->name }}')" class="text-red-500 hover:text-red-700 flex items-center justify-center">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                       <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2M10 11v6M14 11v6M5 6h14l1 16a1 1 0 01-1 1H5a1 1 0 01-1-1L5 6z" />
                                   </svg>
                               </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end text-sm mt-4">
            {{ $users->links() }}
        </div>
    </div>


    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-4 rounded shadow-lg max-w-5xl w-full max-h-screen overflow-y-auto relative">
            <button
                class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2"
                onclick="closeCreateModal()"
            >
                &times;
            </button>
            <h2 class="text-xl font-bold mb-2">Crear Usuario</h2>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block">Nombre</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="lastname" class="block">Apellido</label>
                    <input type="text" id="lastname" name="lastname" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block">Contraseña</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-2 border rounded"
                            oninput="validatePassword()"
                            required
                        >
                        <button 
                            type="button" 
                            class="absolute inset-y-0 right-0 px-3 text-gray-600"
                            onclick="togglePasswordVisibility('password')">
                            👁️
                        </button>
                    </div>
                    <div id="password-strength" class="text-sm mt-2"></div>
                </div>

              
                <div class="mb-4">
                    <label for="phone" class="block">Teléfono</label>
                    <input type="text" id="phone" name="phone" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="address" class="block">Dirección</label>
                    <input type="text" id="address" name="address" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="photo" class="block">Foto</label>
                    <input type="file" id="photo" name="photo" class="w-full px-2 py-1 border rounded" accept="image/jpeg,image/png">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-700">Guardar</button>
            </form>
        </div>
    </div>

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
                    <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">Aceptar</button>
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-700">Cancelar</button>
                </form>
            </div>
        </div>
    </div>

    
<div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-7 rounded shadow-lg max-w-7xl w-full relative">
            <button class="absolute top-0.5 right-0.5 text-gray-500 hover:text-black text-3xl p-2" onclick="closeModal()">×</button>
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
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openDeleteModal(id, firstname) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('usuarioNombre').innerText = firstname;
    const form = document.getElementById('deleteForm');
    console.log("id: ",id,"name",firstname)
   
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

 
function togglePasswordVisibility(fieldId) {
        const inputField = document.getElementById(fieldId);
        inputField.type = inputField.type === "password" ? "text" : "password";
    }

   
function validatePassword() {
        const password = document.getElementById("password").value;
        const strengthElement = document.getElementById("password-strength");
        validateStrength(password, strengthElement);
    }

function validateStrength(password, strengthElement) {
        let strengthMessage = "La contraseña debe tener:";
        let isValid = true;

        if (!/[A-Z]/.test(password)) {
            strengthMessage += " una mayúscula,";
            isValid = false;
        }
        if (!/[a-z]/.test(password)) {
            strengthMessage += " una minúscula,";
            isValid = false;
        }
        if (!/\d/.test(password)) {
            strengthMessage += " un número,";
            isValid = false;
        }
        if (password.length < 6) {
            strengthMessage += " al menos 6 caracteres,";
            isValid = false;
        }

        if (strengthElement) {
            if (isValid) {
                strengthElement.innerHTML = "<span class='text-green-600'>✔ La contraseña es válida.</span>";
            } else {
                strengthElement.innerHTML = `<span class='text-red-600'>${strengthMessage.slice(0, -1)}.</span>`;
            }
        }

        return isValid;
    }
</script>
@endsection