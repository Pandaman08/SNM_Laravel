<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Tutor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-cover bg-center bg-[url('/images/fachada-bruning2.jpg')] min-h-screen">
    <div class="flex items-center justify-center min-h-screen py-6 px-4">
        <div class="w-full max-w-2xl bg-white p-8 md:p-10 rounded-2xl shadow-lg">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="/images/logo-bruning1.png" alt="Logo" class="h-20 w-20 rounded-full border-4 border-white shadow-lg">
            </div>
            
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Registro de Tutor</h2>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-300">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-300">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tutor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                        <input type="text" name="nombre" id="nombre" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>


                    <div>
                        <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">Apellidos</label>
                        <input type="text" name="apellidos" id="apellidos" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>


                    <div>
                        <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">DNI</label>
                        <input type="text" name="dni" id="dni" maxlength="8" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div>
                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                        <select name="sexo" id="sexo" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="">Seleccione</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                        </select>
                    </div>

                    <div>
                        <label for="estado_civil" class="block text-sm font-medium text-gray-700 mb-2">Estado Civil</label>
                        <select name="estado_civil" id="estado_civil" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="">Seleccione</option>
                            <option value="soltero">Soltero(a)</option>
                            <option value="casado">Casado(a)</option>
                            <option value="divorciado">Divorciado(a)</option>
                            <option value="viudo">Viudo(a)</option>
                            <option value="conviviente">Conviviente</option>
                        </select>
                    </div>

                    <div>
                        <label for="celular" class="block text-sm font-medium text-gray-700 mb-2">Número de Celular</label>
                        <input type="text" name="celular" id="celular" maxlength="9" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div>
                        <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                        <input type="password" name="password" id="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div>
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                        <input type="text" name="direccion" id="direccion" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                        <input type="file" name="foto" id="foto" accept="image/*" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center mt-8 gap-4">
                    <a href="/login" class="text-blue-500 hover:underline text-sm">
                        ¿Ya tienes cuenta? Iniciar sesión
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition-all duration-200 shadow-md">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>