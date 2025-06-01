@extends('layout.admin.plantilla')

@section('titulo', 'Ficha de Datos Personales')

@section('contenido')
    <div class="flex flex-col md:flex-row gap-6 p-6 bg-gray-100 min-h-screen">
      
        <div class="w-full md:w-1/3 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Administrador</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm text-gray-700 font-medium">FOTO</h3>
                    @if(Auth::user()->persona->photo)
                      <img src="{{ Storage::url('' . Auth::user()->persona->photo) }}" alt="Foto de perfil" class="w-50 h-30 object-cover">
                    @else
                      <div class="w-20 h-20 bg-gray-300 text-gray-700 flex items-center justify-center rounded-full text-xl font-bold uppercase">
                          {{ substr(Auth::user()->persona->name, 0, 1) }}
                      </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-sm text-gray-700 font-medium">NOMBRES Y APELLIDOS</h3>
                    <p class="text-gray-500">{{ Auth::user()->persona->name }} {{ Auth::user()->persona->lastname }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-700 font-medium">CORREO</h3>
                    <p class="text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-700 font-medium">DNI</h3>
                    <p class="text-gray-500">{{ Auth::user()->persona->dni }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-700 font-medium">CONTACTO</h3>
                    <p class="text-gray-500">{{ Auth::user()->persona->phone }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-700 font-medium">DIRECCIÓN</h3>
                    <p class="text-gray-500">{{ Auth::user()->persona->address }}</p>
                </div>
            </div>
            <p class="text-sm text-gray-400 mt-4">Datos personales del Administrador</p>
        </div>

      
        <div class="w-full md:w-2/3 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Ficha de Datos Personales</h2>
        
            <div class="border-b border-gray-300 mb-6">
                <nav class="flex space-x-4" aria-label="Tabs">
                   <button class="tab px-3 py-2 text-sm font-medium text-indigo-600 border-b-2 border-indigo-600" data-tab="datos">
                         Datos del Usuario
                    </button>
                    <button class="tab px-3 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-indigo-600 hover:border-indigo-600" data-tab="foto">
                         Foto
                   </button>
                   <button class="tab px-3 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-indigo-600 hover:border-indigo-600" data-tab="configuracion">
                        Configuración
                   </button>
                </nav>
            </div>

         
            <div id="datos" class="tab-content">
                <form action="{{ route('users.update_user', Auth::user()->user_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="firstname" class="block text-sm font-medium text-gray-700">Nombres</label>
                            <input type="text" id="firstname" name="firstname" value="{{ old('name', Auth::user()->persona->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Apellidos</label>
                            <input type="text" id="lastname" name="lastname" value="{{ old('lastname', Auth::user()->persona->lastname) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', Auth::user()->persona->phone) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" id="address" name="address" value="{{ old('address', Auth::user()->persona->address) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="career" class="block text-sm font-medium text-gray-700">Dni</label>
                            <input type="text" id="career" name="dni" value="{{ old('dni', Auth::user()->persona->dni) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>

            <div id="foto" class="tab-content hidden">
                <h3 class="text-lg font-medium text-gray-800">Foto</h3>
                <form action="{{ route('users.update_photo', Auth::user()->user_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mt-4 flex items-center space-x-4">
                        <input type="file" id="photo" name="photo"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100" accept="image/jpeg,image/png">
                    </div>
                    <div class="flex justify-end mt-6 space-x-4">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">
                            Guardar Foto
                        </button>
                        <a href="{{ url()->previous() }}" 
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">
                                Cancelar
                        </a>
                    </div>
                </form>

            </div>

            <div id="configuracion" class="tab-content hidden">
                <h3 class="text-lg font-medium text-gray-800">Configuración</h3>
                <form action="{{ route('users.update_password', Auth::user()->user_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mt-2">Nueva Contraseña</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                oninput="validatePassword()"
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
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Repita Contraseña</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                disabled 
                                oninput="validatePasswordConfirmation()"
                            >
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 px-3 text-gray-600"
                                onclick="togglePasswordVisibility('password_confirmation')">
                                👁️
                            </button>
                        </div>
                        <div id="password-confirmation-strength" class="text-sm mt-2"></div>
                        <div id="password-match" class="text-sm mt-2"></div>
                    </div>
                    <div class="flex justify-end mt-6 space-x-4">
                        <button id="save-button" type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 disabled:bg-gray-400" disabled>
                            Guardar Contraseña
                        </button>
                        <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

            @if (session('success-photo'))
        <script>
            Swal.fire({
                title: "Actualizado!",
                text: "{{ session('success-photo') }}",
                icon: "success",
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
          @elseif (session('success-password'))
          <script>
              Swal.fire({
                  title: "Actualizado!",
                  text: "{{ session('success-password') }}",
                  icon: "success",
                  customClass: {
                      confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                  }
              });
          </script>
    @elseif(session('success-user'))
        <script>
            Swal.fire({
                title: "Actualizado!",
                text: "{{ session('success-user') }}",
                icon: "success",
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @endif

<script>
        
    document.querySelectorAll('.tab').forEach(button => {
        button.addEventListener('click', () => {
            const tab = button.dataset.tab;

           
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

           
            document.querySelector(`#${tab}`).classList.remove('hidden');

           
            document.querySelectorAll('.tab').forEach(tabBtn => {
                tabBtn.classList.remove('text-indigo-600', 'border-indigo-600');
                tabBtn.classList.add('text-gray-500', 'border-transparent');
            });

           
            button.classList.add('text-indigo-600', 'border-indigo-600');
            button.classList.remove('text-gray-500', 'border-transparent');
        });
    });

        function togglePasswordVisibility(fieldId) {
        const inputField = document.getElementById(fieldId);
        inputField.type = inputField.type === "password" ? "text" : "password";
    }

   
    function validatePassword() {
        const password = document.getElementById("password").value;
        const strengthElement = document.getElementById("password-strength");
        let isValid = validateStrength(password, strengthElement);

        
        const confirmationField = document.getElementById("password_confirmation");
        confirmationField.disabled = !isValid;

        
        if (isValid) validatePasswordMatch();
    }

function validatePasswordConfirmation() {
    const confirmationPassword = document.getElementById("password_confirmation").value;
    const strengthElement = document.getElementById("password-confirmation-strength");
    
  
    let isValid = validateStrength(confirmationPassword, strengthElement, false);

    if (isValid) {
        strengthElement.innerHTML = ""; 
    }

    validatePasswordMatch();
}
   
    function validatePasswordMatch() {
        const password = document.getElementById("password").value;
        const passwordConfirmation = document.getElementById("password_confirmation").value;
        const matchElement = document.getElementById("password-match");
        const saveButton = document.getElementById("save-button");

        if (passwordConfirmation === "") {
            matchElement.innerHTML = "";
            saveButton.disabled = true;
            return;
        }

       
        if (password === passwordConfirmation) {
            matchElement.innerHTML = "<span class='text-green-600'>Las contraseñas coinciden.</span>";
            saveButton.disabled = false;
        } else {
            matchElement.innerHTML = "<span class='text-red-600'>Las contraseñas no coinciden.</span>";
            saveButton.disabled = true;
        }
    }

    function validateStrength(password, strengthElement, showValidMessage) {
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
            if (isValid && showValidMessage) {
                strengthElement.innerHTML = "<span class='text-green-600'>La contraseña es válida.</span>";
            } else if (!isValid) {
                strengthElement.innerHTML = `<span class='text-red-600'>${strengthMessage.slice(0, -1)}.</span>`;
            } else {
                 strengthElement.innerHTML = ""; 
            }
        }

        return isValid;
    }
</script>

<style>
    .hidden {
        display: none;
    }
</style>
@endsection
