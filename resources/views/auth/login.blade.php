<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
        <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-cover bg-center bg-[url('https://www.arquimaster.com.ar/web/wp-content/uploads/2022/12/salones_colegio_cimort_8.jpg')]">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96 relative">
            <div class="absolute -top-16 left-1/2 transform -translate-x-1/2">
                <img src="/images/logo-bruning1.png" alt="Ilustración"
                    class="rounded-full h-32 w-32 border-4 border-white shadow-lg">
            </div>
            <h2 class="text-2xl font-bold text-center mb-6 mt-14">Iniciar Sesión</h2>

            <!-- Mostrar mensajes de éxito -->
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Mostrar errores de validación -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Contraseña" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-6">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition">Iniciar
                        Sesión</button>
                </div>
                <p class="text-center text-sm">¿No tienes una cuenta? <a href=""
                        class="text-blue-500 hover:underline">Registrarme</a></p>
            </form>
        </div>
    </div>
</body>

</html>