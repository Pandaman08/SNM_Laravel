<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cierre de Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="flex items-center justify-center min-h-screen bg-cover bg-center bg-[url('images/laboratorio-mecanica.png')]">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 relative">
        <div class="absolute -top-16 left-1/2 transform -translate-x-1/2">
            <img src="images/labcam_dash_01.png" alt="Ilustración"
                class="rounded-full h-32 w-32 border-4 border-white shadow-lg">
        </div>
        <h2 class="text-2xl font-bold text-black-600 text-center mb-6 mt-14">¡Confirmación de cierre de sesión exitosa!
        </h2>

        <p class="mt-4 text-gray-1000  text-1xl text-center">
            ¡Gracias por administrar!
        </p>

        <div class="flex justify-center mt-6">
            <a href="{{ route('login.index') }}"
                class="bg-green-500 text-white px-4 py-2 rounded-lg text-0xl font-semibold hover:bg-green-600 transition duration-300 flex items-center justify-center">
                Iniciar sesión nuevamente
            </a>
        </div>
    </div>

</body>

</html>