@extends('layout.admin.plantilla')

@section('titulo','Principal')
@section('contenido')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 p-10 bg-slate-200 rounded-lg">
        <!-- Sección de bienvenida -->
        <div class="flex flex-col justify-center">
             <h1 class="text-4xl font-extrabold text-gray-800 text-center">
                ¡Bienvenido al Panel del sistema educativo!
            </h1>
            <br>
            <p class="text-lg text-gray-600 leading-relaxed text-justify">
                Este panel te permite gestionar y mantener actualizada toda la información de la pagina. En la sección
                <span class="font-medium text-blue-600">Administración General</span> podrás gestionar usuarios, crear nuevos
                administradores y modificar los datos de tu cuenta.
                Podrás controlar el acceso al sistema, asegurándote de que la información esté actualizada y accesible.
            </p>
             </p>
        <p class="text-base text-gray-700 leading-relaxed text-justify">
            Además, en la sección <span class="font-semibold text-purple-600">Pestañas</span>, encontrarás los módulos clave del sistema:
            <span class="text-yellow-600 font-medium">Home</span>,
            <span class="text-red-600 font-medium">Nosotros</span>,
            <span class="text-pink-600 font-medium">Papers</span>,
            <span class="text-indigo-600 font-medium">Investigación</span>,
            <span class="text-teal-600 font-medium">Organización</span> y
            <span class="text-amber-800 font-medium">Novedades</span>.
        </p>
            <p class="text-lg text-gray-600 mt-4 leading-relaxed text-justify">
                En la sección <span class="font-medium text-green-500">Pestañas</span>, podrás acceder a las diferentes áreas
                de, como <span class="font-medium text-yellow-500">Home</span>,
                <span class="font-medium text-red-500">Nosotros</span>,
                <span class="font-medium text-purple-500">Papers</span>,
                <span class="font-medium text-indigo-500">Investigación</span>,
                <span class="font-medium text-teal-500">Organización</span> y
                <span class="font-medium text-amber-950">Novedades</span>.
                Estas áreas contienen información clave sobre el funcionamiento del sistema educativo Colegio Brunning
            </p>
            <br>
            <div class="text-center">
            <a href="#"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-lg px-6 py-3 rounded-md transition-all duration-200">
                <i class="ri-book-open-line text-xl"></i> Ver Tutoriales
            </a>
        </div>
    </div>
            <img class="w-full h-full rounded-lg" src="https://campusschool.bruningcolegio.edu.pe/pluginfile.php/1/core_admin/logocompact/300x300/1742829670/logo%20bruning%202.png" alt="logo_dashboard">
        </div>
    </div>
@endsection




