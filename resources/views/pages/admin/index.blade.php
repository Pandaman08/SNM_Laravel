@extends('layout.admin.plantilla')

@section('titulo','Principal')
@section('contenido')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 p-10 bg-slate-200 rounded-lg">
        <!-- Sección de bienvenida -->
        <div class="flex flex-col justify-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center">
                ¡Bienvenido al Panel del sistema!
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed text-justify">
                Este panel te permite gestionar y mantener actualizada toda la información de la pagina. En la sección
                <span class="font-medium text-blue-500">Administración General</span> podrás gestionar usuarios, crear nuevos
                administradores y modificar los datos de tu cuenta.
                Podrás controlar el acceso al sistema, asegurándote de que la información esté actualizada y accesible.
            </p>
            <p class="text-lg text-gray-600 mt-4 leading-relaxed text-justify">
                En la sección <span class="font-medium text-green-500">Pestañas</span>, podrás acceder a las diferentes áreas
                de, como <span class="font-medium text-yellow-500">Home</span>,
                <span class="font-medium text-red-500">Nosotros</span>,
                <span class="font-medium text-purple-500">Papers</span>,
                <span class="font-medium text-indigo-500">Investigación</span>,
                <span class="font-medium text-teal-500">Organización</span> y
                <span class="font-medium text-amber-950">Novedades</span>.
                Estas áreas contienen información clave sobre el funcionamiento y los proyectos de LABCAM.
            </p>
            <a href="" class=" bg-blue-500 hover:bg-blue-600 transition-all ease-out p-4 text-center rounded-md my-3 text-lg text-white cursor-pointer">Ver Tutoriales</a>
        </div>
        <div class="w-full h-full">
            <img class="w-full h-full rounded-lg" src="/images/logo-bruning3.png" alt="logo_dashboard">
        </div>
    </div>
@endsection