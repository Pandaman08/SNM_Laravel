@extends('layout.admin.plantilla')

@section('title', 'Matricular')

@section('contenido')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Matrícula de Estudiante</h1>
                        <p class="text-gray-600 mt-1">Seleccione el tipo de matrícula para continuar</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Año Escolar:</span>
                        <span class="font-semibold text-blue-600">2025</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar mensajes de éxito o error -->
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

        <!-- Formulario -->
        <form action="{{ route('matriculas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Sección 1: Tipo de Matrícula (Siempre visible) -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-file-list-line text-blue-500 mr-2"></i>
                        Tipo de Matrícula
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tipo de Matrícula -->
                        <div>
                            <label for="tipo_matricula_id" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Matrícula *</label>
                            <select name="tipo_matricula_id" id="tipo_matricula_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required onchange="cambiarTipoMatricula()">
                                <option value="" disabled selected>Seleccione el tipo de matrícula</option>
                                @foreach($tiposMatricula as $tipo)
                                    <option value="{{ $tipo->id_tipo_matricula }}" data-nombre="{{ strtolower($tipo->nombre) }}">
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Año Escolar -->
                        <div>
                            <label for="anio_escolar_id" class="block text-sm font-medium text-gray-700 mb-2">Año Escolar *</label>
                            <select name="anio_escolar_id" id="anio_escolar_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                <option value="" disabled selected>Seleccione el año escolar</option>
                                @foreach($aniosEscolares as $anio)
                                    <option value="{{ $anio->id_anio_escolar }}">
                                        {{ $anio->anio }} - {{ $anio->descripcion ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Información del tipo seleccionado -->
                    <div id="info_tipo" class="mt-6 p-4 rounded-lg" style="display: none;">
                        <div id="info_ingreso" class="info-tipo bg-green-50 border border-green-200" style="display: none;">
                            <h4 class="font-semibold text-green-800 mb-2">📝 Matrícula de Ingreso</h4>
                            <p class="text-green-700 text-sm">Complete todos los datos del estudiante. Es la primera vez que se matricula en esta institución.</p>
                        </div>
                        <div id="info_regular" class="info-tipo bg-blue-50 border border-blue-200" style="display: none;">
                            <h4 class="font-semibold text-blue-800 mb-2">🔍 Matrícula Regular</h4>
                            <p class="text-blue-700 text-sm">Ingrese el DNI del estudiante para buscar sus datos y matricularlo al siguiente grado.</p>
                        </div>
                        <div id="info_traslado" class="info-tipo bg-orange-50 border border-orange-200" style="display: none;">
                            <h4 class="font-semibold text-orange-800 mb-2">🏫 Matrícula por Traslado</h4>
                            <p class="text-orange-700 text-sm">Busque al estudiante por DNI y actualice los datos necesarios de la institución anterior.</p>
                        </div>
                        <div id="info_reincorporacion" class="info-tipo bg-purple-50 border border-purple-200" style="display: none;">
                            <h4 class="font-semibold text-purple-800 mb-2">↩️ Reincorporación</h4>
                            <p class="text-purple-700 text-sm">Busque al estudiante por DNI y verifique el grado correspondiente según el tiempo transcurrido.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Búsqueda de Estudiante (Para tipos: regular, traslado, reincorporación) -->
            <div id="seccion_busqueda" class="bg-white rounded-lg shadow-sm border border-gray-200" style="display: none;">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-search-line text-green-500 mr-2"></i>
                        Buscar Estudiante
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label for="dni_busqueda" class="block text-sm font-medium text-gray-700 mb-2">DNI del Estudiante *</label>
                            <input type="text" id="dni_busqueda" maxlength="8" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="Ingrese el DNI del estudiante">
                        </div>
                        <button type="button" onclick="buscarEstudiante()" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <i class="ri-search-line mr-2"></i>
                            Buscar
                        </button>
                    </div>

                    <!-- Resultado de búsqueda -->
                    <div id="resultado_busqueda" class="mt-6" style="display: none;">
                        <div class="p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Estudiante Encontrado</h4>
                                    <p id="datos_estudiante" class="text-gray-600 text-sm mt-1"></p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Activo</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje de no encontrado -->
                    <div id="no_encontrado" class="mt-6" style="display: none;">
                        <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex">
                                <i class="ri-error-warning-line text-red-500 mr-2 mt-0.5"></i>
                                <div>
                                    <h4 class="font-semibold text-red-800">Estudiante No Encontrado</h4>
                                    <p class="text-red-700 text-sm mt-1">No se encontró ningún estudiante con ese DNI. Verifique el número o use matrícula de "Ingreso".</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Datos del Estudiante (Para tipo: ingreso o edición en traslado) -->
            <div id="seccion_datos_estudiante" class="bg-white rounded-lg shadow-sm border border-gray-200" style="display: none;">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-user-line text-blue-500 mr-2"></i>
                        <span id="titulo_datos">Datos del Estudiante</span>
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Información sobre código automático -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <i class="ri-information-line text-blue-500 mr-2 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-blue-800">Código de Estudiante</h4>
                                <p class="text-blue-700 text-sm mt-1">El código del estudiante se generará automáticamente al completar la matrícula (formato: EST2025XXXX).</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">Apellidos *</label>
                            <input type="text" name="apellidos" id="apellidos" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- DNI -->
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">DNI *</label>
                            <input type="text" name="dni" id="dni" maxlength="8" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Sexo -->
                        <div>
                            <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">Sexo *</label>
                            <select name="sexo" id="sexo" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- País -->
                        <div>
                            <label for="pais" class="block text-sm font-medium text-gray-700 mb-2">País *</label>
                            <input type="text" name="pais" id="pais" value="Perú" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Provincia -->
                        <div>
                            <label for="provincia" class="block text-sm font-medium text-gray-700 mb-2">Provincia *</label>
                            <input type="text" name="provincia" id="provincia" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Distrito -->
                        <div>
                            <label for="distrito" class="block text-sm font-medium text-gray-700 mb-2">Distrito *</label>
                            <input type="text" name="distrito" id="distrito" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Departamento -->
                        <div>
                            <label for="departamento" class="block text-sm font-medium text-gray-700 mb-2">Departamento *</label>
                            <input type="text" name="departamento" id="departamento" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Dirección -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                                <input type="text" name="address" id="address"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('address') }}"
                                    required>
                            </div>
                            
                        <!-- Lengua Materna -->
                        <div>
                            <label for="lengua_materna" class="block text-sm font-medium text-gray-700 mb-2">Lengua Materna *</label>
                            <select name="lengua_materna" id="lengua_materna" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="Español">Español</option>
                                <option value="Quechua">Quechua</option>
                                <option value="Aymara">Aymara</option>
                                <option value="Ashuar">Ashuar</option>
                                <option value="Awajún">Awajún</option>
                                <option value="Shipibo">Shipibo</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <!-- Religión -->
                        <div>
                            <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religión</label>
                            <select name="religion" id="religion" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="Católica">Católica</option>
                                <option value="Evangélica">Evangélica</option>
                                <option value="Testigo de Jehová">Testigo de Jehová</option>
                                <option value="Adventista">Adventista</option>
                                <option value="Otra">Otra</option>
                                <option value="Ninguna">Ninguna</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Información de Matrícula -->
            <div id="seccion_info_matricula" class="bg-white rounded-lg shadow-sm border border-gray-200" style="display: none;">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-book-line text-green-500 mr-2"></i>
                        Información de Matrícula
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Nivel Educativo -->
                        <div>
                            <label for="nivel_educativo_id" class="block text-sm font-medium text-gray-700 mb-2">Nivel Educativo *</label>
                            <select name="nivel_educativo_id" id="nivel_educativo_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required onchange="cargarGrados()">
                                <option value="" disabled selected>Seleccione el nivel</option>
                                @foreach($nivelesEducativos as $nivel)
                                    <option value="{{ $nivel->id_nivel_educativo }}">
                                        {{ $nivel->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Grado -->
                        <div>
                            <label for="grado_id" class="block text-sm font-medium text-gray-700 mb-2">Grado *</label>
                            <select name="grado_id" id="grado_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required disabled onchange="cargarSecciones()">
                                <option value="" disabled selected>Primero seleccione el nivel</option>
                            </select>
                        </div>

                        <!-- Sección -->
                        <div>
                            <label for="seccion_id" class="block text-sm font-medium text-gray-700 mb-2">Sección *</label>
                            <select name="seccion_id" id="seccion_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required disabled>
                                <option value="" disabled selected>Primero seleccione el grado</option>
                            </select>
                        </div>

                        <!-- Fecha de Matrícula -->
                        <div>
                            <label for="fecha_matricula" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Matrícula *</label>
                            <input type="datetime-local" name="fecha_matricula" id="fecha_matricula" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required>
                        </div>

                        <!-- Estado de Matrícula -->
                        <div>
                            <label for="estado_matricula" class="block text-sm font-medium text-gray-700 mb-2">Estado de Matrícula *</label>
                            <select name="estado_matricula" id="estado_matricula" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <!-- Estado de Pago -->
                        <div>
                            <label for="estado_pago" class="block text-sm font-medium text-gray-700 mb-2">Estado de Pago *</label>
                            <select name="estado_pago" id="estado_pago" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                <option value="Pendiente" selected>Pendiente</option>
                                <option value="Finalizado">Finalizado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 5: Relación Estudiante-Tutor -->
            <div id="seccion_tutor" class="bg-white rounded-lg shadow-sm border border-gray-200" style="display: none;">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-parent-line text-purple-500 mr-2"></i>
                        Relación con Tutor
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tutor -->
                        <div>
                            <label for="tutor_id" class="block text-sm font-medium text-gray-700 mb-2">Tutor *</label>
                            <select name="tutor_id" id="tutor_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                <option value="" disabled selected>Seleccione un tutor</option>
                                @foreach($tutores as $tutor)
                                    <option value="{{ $tutor->id_tutor }}">
                                        {{ $tutor->user->persona->name }} {{ $tutor->user->persona->lastname }}
                                        ({{ $tutor->user->persona->dni }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de Relación -->
                        <div>
                            <label for="tipo_relacion" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Relación *</label>
                            <select name="tipo_relacion" id="tipo_relacion" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="Padre">Padre</option>
                                <option value="Madre">Madre</option>
                                <option value="Tutor Legal">Tutor Legal</option>
                                <option value="Abuelo/a">Abuelo/a</option>
                                <option value="Tío/a">Tío/a</option>
                                <option value="Hermano/a">Hermano/a</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div id="seccion_botones" class="bg-white rounded-lg shadow-sm border border-gray-200" style="display: none;">
                <div class="px-6 py-4">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('matriculas.index') }}"
                           class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-center">
                            <i class="ri-arrow-left-line mr-2"></i>
                            Cancelar
                        </a>
                        
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <button type="button" 
                                    class="w-full sm:w-auto px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                                <i class="ri-save-line mr-2"></i>
                                Guardar Borrador
                            </button>
                            
                            <button type="submit" 
                                    class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-semibold">
                                <i class="ri-check-line mr-2"></i>
                                <span id="texto_boton_submit">Matricular Estudiante</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function cambiarTipoMatricula() {
        const tipoSelect = document.getElementById('tipo_matricula_id');
        const selectedOption = tipoSelect.options[tipoSelect.selectedIndex];
        const nombreTipo = selectedOption.dataset.nombre || '';
        
        // Ocultar todas las secciones
        document.getElementById('seccion_busqueda').style.display = 'none';
        document.getElementById('seccion_datos_estudiante').style.display = 'none';
        document.getElementById('seccion_info_matricula').style.display = 'none';
        document.getElementById('seccion_tutor').style.display = 'none';
        document.getElementById('seccion_botones').style.display = 'none';
        
        // Ocultar todas las informaciones
        document.querySelectorAll('.info-tipo').forEach(el => el.style.display = 'none');
        document.getElementById('info_tipo').style.display = 'none';
        
        if (tipoSelect.value) {
            // Mostrar información del tipo seleccionado
            document.getElementById('info_tipo').style.display = 'block';
            
            // Determinar el tipo basado en el nombre
            if (nombreTipo.includes('ingreso') || nombreTipo.includes('nuevo') || nombreTipo.includes('primera')) {
                document.getElementById('info_ingreso').style.display = 'block';
                // Para ingreso: mostrar formulario completo
                document.getElementById('seccion_datos_estudiante').style.display = 'block';
                document.getElementById('seccion_info_matricula').style.display = 'block';
                document.getElementById('seccion_tutor').style.display = 'block';
                document.getElementById('seccion_botones').style.display = 'block';
                document.getElementById('titulo_datos').textContent = 'Datos del Nuevo Estudiante';
                document.getElementById('texto_boton_submit').textContent = 'Matricular Nuevo Estudiante';
                
                // Hacer campos requeridos
                hacerCamposRequeridos(true);
                
            } else if (nombreTipo.includes('regular') || nombreTipo.includes('continuidad')) {
                document.getElementById('info_regular').style.display = 'block';
                // Para regular: mostrar búsqueda primero
                document.getElementById('seccion_busqueda').style.display = 'block';
                document.getElementById('texto_boton_submit').textContent = 'Matricular para Continuidad';
                
            } else if (nombreTipo.includes('traslado')) {
                document.getElementById('info_traslado').style.display = 'block';
                // Para traslado: mostrar búsqueda primero
                document.getElementById('seccion_busqueda').style.display = 'block';
                document.getElementById('texto_boton_submit').textContent = 'Matricular por Traslado';
                
            } else if (nombreTipo.includes('reincorporacion') || nombreTipo.includes('reingreso')) {
                document.getElementById('info_reincorporacion').style.display = 'block';
                // Para reincorporación: mostrar búsqueda primero
                document.getElementById('seccion_busqueda').style.display = 'block';
                document.getElementById('texto_boton_submit').textContent = 'Reincorporar Estudiante';
                
            } else {
                // Tipo desconocido, mostrar búsqueda por defecto
                document.getElementById('info_regular').style.display = 'block';
                document.getElementById('seccion_busqueda').style.display = 'block';
                document.getElementById('texto_boton_submit').textContent = 'Matricular Estudiante';
            }
        }
    }
    
    function buscarEstudiante() {
        const dni = document.getElementById('dni_busqueda').value;
        const tipoMatricula = document.getElementById('tipo_matricula').value;
        
        if (!dni || dni.length !== 8) {
            alert('Por favor ingrese un DNI válido de 8 dígitos');
            return;
        }
        
        // Aquí iría la llamada AJAX para buscar el estudiante
        // Por ahora simulamos la respuesta
        
        // Simular búsqueda exitosa
        const encontrado = Math.random() > 0.3; // 70% de probabilidad de encontrar
        
        if (encontrado) {
            // Estudiante encontrado
            document.getElementById('resultado_busqueda').style.display = 'block';
            document.getElementById('no_encontrado').style.display = 'none';
            document.getElementById('datos_estudiante').textContent = `Juan Pérez Gómez - DNI: ${dni} - Último grado: 3° Grado`;
            
            // Llenar datos del estudiante (simulado)
            document.getElementById('dni').value = dni;
            document.getElementById('nombre').value = 'Juan';
            document.getElementById('apellidos').value = 'Pérez Gómez';
            // El código se genera automáticamente, no se llena
            
            // Mostrar secciones correspondientes
            if (tipoMatricula === 'traslado') {
                document.getElementById('seccion_datos_estudiante').style.display = 'block';
                document.getElementById('titulo_datos').textContent = 'Actualizar Datos del Estudiante';
                hacerCamposRequeridos(false); // Para traslado algunos campos pueden no ser requeridos
            } else {
                document.getElementById('titulo_datos').textContent = 'Datos del Estudiante (Solo lectura)';
                hacerCamposReadonly(true);
            }
            
            document.getElementById('seccion_info_matricula').style.display = 'block';
            document.getElementById('seccion_tutor').style.display = 'block';
            document.getElementById('seccion_botones').style.display = 'block';
            
        } else {
            // Estudiante no encontrado
            document.getElementById('resultado_busqueda').style.display = 'none';
            document.getElementById('no_encontrado').style.display = 'block';
        }
    }
    
    function hacerCamposRequeridos(requeridos) {
        const campos = ['nombre', 'apellidos', 'dni', 'sexo', 'fecha_nacimiento', 'pais', 'provincia', 'distrito', 'departamento', 'lengua_materna'];
        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            if (elemento) {
                if (requeridos) {
                    elemento.setAttribute('required', 'required');
                } else {
                    elemento.removeAttribute('required');
                }
            }
        });
    }
    function cargarGrados() {
        const nivelSelect = document.getElementById('nivel_educativo_id');
        const gradoSelect = document.getElementById('grado_id');
        const seccionSelect = document.getElementById('seccion_id');
        
        const nivelId = nivelSelect.value;
        console.log('📊 Cargando grados para nivel:', nivelId);
        
        // Limpiar grados y secciones
        gradoSelect.innerHTML = '<option value="" disabled selected>Cargando grados...</option>';
        gradoSelect.disabled = true;
        seccionSelect.innerHTML = '<option value="" disabled selected>Primero seleccione el grado</option>';
        seccionSelect.disabled = true;
        
        if (nivelId) {
            fetch(`/obtener-grados?nivel_id=${nivelId}`)
                .then(response => response.json())
                .then(data => {
                    gradoSelect.innerHTML = '<option value="" disabled selected>Seleccione el grado</option>';
                    
                    if (data.grados && data.grados.length > 0) {
                        data.grados.forEach(grado => {
                            const option = document.createElement('option');
                            option.value = grado.id_grado;
                            option.textContent = `${grado.grado}° Grado`;
                            gradoSelect.appendChild(option);
                        });
                        gradoSelect.disabled = false;
                        console.log('✅ Grados cargados:', data.grados.length);
                    } else {
                        gradoSelect.innerHTML = '<option value="" disabled selected>No hay grados disponibles</option>';
                    }
                })
                .catch(error => {
                    console.error('❌ Error al cargar grados:', error);
                    gradoSelect.innerHTML = '<option value="" disabled selected>Error al cargar grados</option>';
                });
        }
    }

    function cargarSecciones() {
        const gradoSelect = document.getElementById('grado_id');
        const seccionSelect = document.getElementById('seccion_id');
        
        const gradoId = gradoSelect.value;
        console.log('📊 Cargando secciones para grado:', gradoId);
        
        // Limpiar secciones
        seccionSelect.innerHTML = '<option value="" disabled selected>Cargando secciones...</option>';
        seccionSelect.disabled = true;
        
        if (gradoId) {
            fetch(`/obtener-secciones?grado_id=${gradoId}`)
                .then(response => {
                    console.log('📡 Respuesta status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Datos recibidos:', data);
                    
                    seccionSelect.innerHTML = '<option value="" disabled selected>Seleccione la sección</option>';
                    
                    if (data.secciones && data.secciones.length > 0) {
                        data.secciones.forEach(seccion => {
                            const option = document.createElement('option');
                            option.value = seccion.id_seccion;
                            option.textContent = `Sección ${seccion.seccion}`;
                            seccionSelect.appendChild(option);
                        });
                        seccionSelect.disabled = false;
                        console.log('✅ Secciones cargadas:', data.secciones.length);
                    } else {
                        console.log('⚠️ No hay secciones para este grado');
                        seccionSelect.innerHTML = '<option value="" disabled selected>No hay secciones disponibles</option>';
                    }
                })
                .catch(error => {
                    console.error('❌ Error al cargar secciones:', error);
                    seccionSelect.innerHTML = '<option value="" disabled selected>Error al cargar secciones</option>';
                    
                    // Mostrar más detalles del error en consola
                    console.error('Detalles del error:', {
                        message: error.message,
                        gradoId: gradoId,
                        url: `/obtener-secciones?grado_id=${gradoId}`
                    });
                });
        }
    }
    function hacerCamposReadonly(readonly) {
        const campos = ['nombre', 'apellidos', 'dni', 'sexo', 'fecha_nacimiento', 'pais', 'provincia', 'distrito', 'departamento', 'lengua_materna', 'religion'];
        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            if (elemento) {
                if (readonly) {
                    elemento.setAttribute('readonly', 'readonly');
                    elemento.classList.add('bg-gray-100', 'cursor-not-allowed');
                } else {
                    elemento.removeAttribute('readonly');
                    elemento.classList.remove('bg-gray-100', 'cursor-not-allowed');
                }
            }
        });
    }
    
    // Establecer fecha y hora actual para la matrícula
    document.addEventListener('DOMContentLoaded', function() {
        const fechaMatricula = document.getElementById('fecha_matricula');
        const ahora = new Date();
        const fechaLocal = new Date(ahora.getTime() - ahora.getTimezoneOffset() * 60000);
        fechaMatricula.value = fechaLocal.toISOString().slice(0, 16);
    });
</script>
@endsection