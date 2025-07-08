@extends('layout.admin.plantilla')

@section('title', 'Solicitar Matrícula')

@section('contenido')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Solicitud de Matrícula</h1>
                        <p class="text-gray-600 mt-1">Complete los datos para solicitar la matrícula de su estudiante</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Año Escolar:</span>
                        <span class="font-semibold text-blue-600">2025</span>
                        <br>
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                            Estado: Pendiente de Aprobación
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información importante para tutores -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <div class="flex">
                <i class="ri-information-line text-blue-500 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">Información importante</h3>
                    <ul class="text-blue-700 text-sm space-y-1">
                        <li>• Su solicitud será revisada por la administración del colegio</li>
                        <li>• El código del estudiante se generará automáticamente al aprobar la matrícula</li>
                        <li>• Recibirá una notificación cuando su solicitud sea aprobada o rechazada</li>
                        <li>• El estado de pago quedará como "Pendiente" hasta completar el proceso</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mostrar mensajes -->
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
        <form action="{{ route('matriculas.store-tutor') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Campos ocultos para tutor -->
            <input type="hidden" name="tutor_id" value="{{ auth()->user()->tutor->id_tutor }}">
            <input type="hidden" name="estado_pago" value="Pendiente">
            
            <!-- Sección 1: Información Básica de Matrícula -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-file-list-line text-blue-500 mr-2"></i>
                        Información de Matrícula
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tipo de Matrícula -->
                        <div>
                            <label for="id_tipo_matricula" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Matrícula *</label>
                            <select name="id_tipo_matricula" id="id_tipo_matricula" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                <option value="" disabled selected>Seleccione el tipo de matrícula</option>
                                @foreach($tiposMatricula as $tipo)
                                    <option value="{{ $tipo->id_tipo_matricula }}">
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Año Escolar -->
                        <div>
                            <label for="id_anio_escolar" class="block text-sm font-medium text-gray-700 mb-2">Año Escolar *</label>
                            <select name="id_anio_escolar" id="id_anio_escolar" 
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
                </div>
            </div>

            <!-- Sección 2: Datos del Estudiante -->
            <div id="seccion-datos-estudiante" class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-user-line text-green-500 mr-2"></i>
                        <span id="titulo-datos-estudiante">Datos del Estudiante</span>
                    </h2>
                </div>
                <div class="p-6">
                    
                    <!-- Sección para buscar estudiante existente (solo para no-ingreso) -->
                    <div id="seccion-buscar-estudiante" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg" style="display: none;">
                        <h4 class="font-semibold text-blue-800 mb-3">Buscar Estudiante Existente</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="dni_buscar" class="block text-sm font-medium text-gray-700 mb-2">DNI del Estudiante *</label>
                                <input type="text" id="dni_buscar" maxlength="8" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ingrese el DNI del estudiante" pattern="[0-9]{8}">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="buscarEstudianteExistente()" 
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                    <i class="ri-search-line mr-2"></i>
                                    Buscar Estudiante
                                </button>
                            </div>
                        </div>
                        <div id="resultado-busqueda" class="mt-4"></div>
                    </div>

                    <!-- Formulario de datos del estudiante -->
                    <div id="formulario-estudiante">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                                <input type="text" name="nombre" id="nombre" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Apellidos -->
                            <div>
                                <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">Apellidos *</label>
                                <input type="text" name="apellidos" id="apellidos" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- DNI -->
                            <div>
                                <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">DNI *</label>
                                <input type="text" name="dni" id="dni" maxlength="8" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required pattern="[0-9]{8}" 
                                       title="Ingrese 8 dígitos numéricos">
                            </div>

                            <!-- Sexo -->
                            <div>
                                <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">Sexo *</label>
                                <select name="sexo" id="sexo" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="M">M</option>
                                    <option value="F">F</option>
                                </select>
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div>
                                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- País -->
                            <div>
                                <label for="pais" class="block text-sm font-medium text-gray-700 mb-2">País *</label>
                                <input type="text" name="pais" id="pais" value="Perú" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Provincia -->
                            <div>
                                <label for="provincia" class="block text-sm font-medium text-gray-700 mb-2">Provincia *</label>
                                <input type="text" name="provincia" id="provincia" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Distrito -->
                            <div>
                                <label for="distrito" class="block text-sm font-medium text-gray-700 mb-2">Distrito *</label>
                                <input type="text" name="distrito" id="distrito" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Departamento -->
                            <div>
                                <label for="departamento" class="block text-sm font-medium text-gray-700 mb-2">Departamento *</label>
                                <input type="text" name="departamento" id="departamento" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
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
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
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
            </div>

            <!-- Información Académica -->
            <div id="seccion-info-academica" class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-book-line text-purple-500 mr-2"></i>
                        Información Académica
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                        <!-- Grado - CORREGIDO -->
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
                            <label for="id_seccion" class="block text-sm font-medium text-gray-700 mb-2">Sección *</label>
                            <select name="seccion_id" id="seccion_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required disabled>
                                <option value="" disabled selected>Primero seleccione el grado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fecha de Solicitud (readonly) -->
                    <div class="mt-6">
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Solicitud</label>
                        <input type="datetime-local" name="fecha" id="fecha" 
                            class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed" 
                            required readonly>
                    </div>
                </div>
            </div>

            <!-- Información del Tutor Responsable -->
            <div id="seccion-tutor" class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="ri-parent-line text-orange-500 mr-2"></i>
                        Información del Tutor Responsable
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">Tutor</h4>
                            <p class="text-gray-700">{{ auth()->user()->persona->name }} {{ auth()->user()->persona->lastname }}</p>
                            <p class="text-gray-500 text-sm">DNI: {{ auth()->user()->persona->dni }}</p>
                            <p class="text-gray-500 text-sm">Email: {{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">Tipo de Relación *</h4>
                            <select name="tipo_relacion" id="tipo_relacion" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                                <option value="" disabled selected>Seleccione su relación</option>
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
                    
                    <!-- Información sobre el estado -->
                    <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex">
                            <i class="ri-information-line text-orange-500 mr-2 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-orange-800">Solicitud Pendiente</h4>
                                <p class="text-orange-700 text-sm mt-1">Su solicitud de matrícula quedará en estado "PENDIENTE" hasta ser revisada y aprobada por la administración del colegio.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div id="seccion-botones" class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('matriculas.mis-matriculas') }}"
                           class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-center">
                            <i class="ri-arrow-left-line mr-2"></i>
                            Cancelar
                        </a>
                        
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-semibold">
                            <i class="ri-send-plane-line mr-2"></i>
                            Enviar Solicitud de Matrícula
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Variables globales
    let tipoMatriculaSeleccionado = '';
    let esMatriculaIngreso = false;
    //esMatriculaIngreso es true se crea un estudiante nuevo; si "false" requiere buscar el estudiante

    // Función para manejar el cambio de tipo de matrícula
    function manejarTipoMatricula() {
        const tipoSelect = document.getElementById('id_tipo_matricula');
        const tipoTexto = tipoSelect.options[tipoSelect.selectedIndex]?.text || '';
        tipoMatriculaSeleccionado = tipoSelect.value;
        
        // Determinar si es matrícula de ingreso
        esMatriculaIngreso = tipoTexto.toLowerCase().includes('ingreso');
        
        console.log('🔄 Tipo seleccionado:', tipoTexto, '| Es ingreso:', esMatriculaIngreso);
        
        // IDs de las secciones a mostrar/ocultar
        const secciones = [
            'seccion-datos-estudiante',
            'seccion-info-academica', 
            'seccion-tutor',
            'seccion-botones'
        ];
        
        if (tipoMatriculaSeleccionado && tipoMatriculaSeleccionado !== '') {
            // Mostrar secciones
            secciones.forEach(id => {
                const elemento = document.getElementById(id);
                if (elemento) {
                    elemento.style.display = 'block';
                    console.log('✅ Mostrando:', id);
                }
            });
            
            // Configurar la sección de datos según el tipo de matrícula
            configurarSeccionDatos();
            
        } else {
            // Ocultar secciones
            secciones.forEach(id => {
                const elemento = document.getElementById(id);
                if (elemento) {
                    elemento.style.display = 'none';
                }
            });
        }
    }

    // Función para configurar la sección de datos según el tipo de matrícula
    function configurarSeccionDatos() {
        const seccionBuscar = document.getElementById('seccion-buscar-estudiante');
        const formularioEstudiante = document.getElementById('formulario-estudiante');
        const tituloSeccion = document.getElementById('titulo-datos-estudiante');
        
        if (esMatriculaIngreso) {
            // MATRÍCULA DE INGRESO: Estudiante nuevo
            seccionBuscar.style.display = 'none';
            formularioEstudiante.style.display = 'block';
            tituloSeccion.textContent = 'Datos del Estudiante (Nuevo Ingreso)';
            
            // Limpiar formulario
            limpiarFormularioEstudiante();
            
            // Hacer campos editables
            habilitarCamposEstudiante(true);
            
            console.log('📝 Configurado para estudiante NUEVO');
            
        } else {
            // OTRAS MATRÍCULAS: Buscar estudiante existente
            seccionBuscar.style.display = 'block';
            formularioEstudiante.style.display = 'block';
            tituloSeccion.textContent = 'Datos del Estudiante (Existente)';
            
            // Limpiar formulario y resultado de búsqueda
            limpiarFormularioEstudiante();
            limpiarResultadoBusqueda();
            
            // Hacer campos solo lectura hasta encontrar estudiante
            habilitarCamposEstudiante(false);
            
            console.log('🔍 Configurado para buscar estudiante EXISTENTE');
        }
    }

    // Función para buscar estudiante existente
    function buscarEstudianteExistente() {
        const dniBuscar = document.getElementById('dni_buscar').value;
        
        if (!dniBuscar || dniBuscar.length !== 8) {
            mostrarResultadoBusqueda('error', 'Por favor ingrese un DNI válido de 8 dígitos');
            return;
        }
        
        mostrarResultadoBusqueda('loading', 'Buscando estudiante...');
        
        fetch(`/buscar-estudiante?dni=${dniBuscar}`)
            .then(response => response.json())
            .then(data => {
                if (data.found) {
                    // Estudiante encontrado
                    mostrarResultadoBusqueda('success', 
                        `✅ Estudiante encontrado: ${data.estudiante.nombre} ${data.estudiante.apellidos}`);
                    
                    // Llenar formulario con datos del estudiante
                    llenarFormularioEstudiante(data.estudiante);
                    
                    //#2
                    //agregarCampoDniBusqueda(data.estudiante.dni);

                    // Hacer campos solo lectura (no editables para estudiante existente)
                    habilitarCamposEstudiante(false);
                    
                } else {
                    // Estudiante no encontrado
                    mostrarResultadoBusqueda('error', 
                        '❌ No se encontró ningún estudiante con ese DNI. Verifique el número o contacte con la administración.');
                    
                    limpiarFormularioEstudiante();
                    //#2
                    //removerCampoDniBusqueda();
                    habilitarCamposEstudiante(false);
                }
            })
            .catch(error => {
                console.error('Error en búsqueda:', error);
                mostrarResultadoBusqueda('error', 'Error al buscar el estudiante. Intente nuevamente.');
                limpiarFormularioEstudiante();
                //#2
                //removerCampoDniBusqueda();
                habilitarCamposEstudiante(false);
            });
    }

    // Función para mostrar resultado de búsqueda
    function mostrarResultadoBusqueda(tipo, mensaje) {
        const resultadoDiv = document.getElementById('resultado-busqueda');
        let clases = 'p-3 rounded-lg text-sm ';
        
        switch(tipo) {
            case 'success':
                clases += 'bg-green-100 text-green-700 border border-green-300';
                break;
            case 'error':
                clases += 'bg-red-100 text-red-700 border border-red-300';
                break;
            case 'loading':
                clases += 'bg-blue-100 text-blue-700 border border-blue-300';
                break;
        }
        
        resultadoDiv.innerHTML = `<div class="${clases}">${mensaje}</div>`;
    }

    // Función para limpiar resultado de búsqueda
    function limpiarResultadoBusqueda() {
        document.getElementById('resultado-busqueda').innerHTML = '';
    }

    // Función para llenar formulario con datos del estudiante
    function llenarFormularioEstudiante(estudiante) {
        document.getElementById('nombre').value = estudiante.nombre || '';
        document.getElementById('apellidos').value = estudiante.apellidos || '';
        document.getElementById('dni').value = estudiante.dni || '';
        document.getElementById('sexo').value = estudiante.sexo || '';
        document.getElementById('fecha_nacimiento').value = estudiante.fecha_nacimiento || '';
        document.getElementById('pais').value = estudiante.pais || '';
        document.getElementById('provincia').value = estudiante.provincia || '';
        document.getElementById('distrito').value = estudiante.distrito || '';
        document.getElementById('departamento').value = estudiante.departamento || '';
        document.getElementById('lengua_materna').value = estudiante.lengua_materna || '';
        document.getElementById('religion').value = estudiante.religion || '';
        document.getElementById('address').value = estudiante.address || '';
    }

    // Función para limpiar formulario del estudiante
    function limpiarFormularioEstudiante() {
        const campos = ['nombre', 'apellidos', 'dni', 'sexo', 'fecha_nacimiento', 
                       'pais', 'provincia', 'distrito', 'departamento', 'lengua_materna', 'religion', 'address'];
        
        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            if (elemento.tagName === 'SELECT') {
                elemento.selectedIndex = 0;
            } else {
                elemento.value = campo === 'pais' ? 'Perú' : '';
            }
        });
    }

    // Función para habilitar/deshabilitar campos del estudiante
    function habilitarCamposEstudiante(habilitar) {
        const campos = ['nombre', 'apellidos', 'dni', 'sexo', 'fecha_nacimiento', 
                       'pais', 'provincia', 'distrito', 'departamento', 'lengua_materna', 'religion', 'address'];
        
        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            elemento.readOnly = !habilitar;
            //#2
            //elemento.disabled = !habilitar;
            
            if (habilitar) {
                elemento.style.backgroundColor = '';
                elemento.style.cursor = '';
                
            } else {
                elemento.style.backgroundColor = '#f9fafb';
                elemento.style.cursor = 'not-allowed';
                
            }
        });
    }

    // Función para cargar grados según el nivel educativo seleccionado
    function cargarGrados() {
        console.log('🚀 INICIANDO cargarGrados()');
        const nivelSelect = document.getElementById('nivel_educativo_id'); // ✅ ID correcto
        const gradoSelect = document.getElementById('grado_id'); // ✅ ID correcto
        const seccionSelect = document.getElementById('seccion_id');
        const nivelId = nivelSelect.value;
        
        
        console.log('🔄 Cargando grados para nivel ID:', nivelId); // Debug
        
        // Limpiar opciones de grado y sección
        gradoSelect.innerHTML = '<option value="" disabled selected>Cargando grados...</option>';
        gradoSelect.disabled = true;
        seccionSelect.innerHTML = '<option value="" disabled selected>Primero seleccione el grado</option>';
        seccionSelect.disabled = true;
        
        if (nivelId) {
            // Hacer petición AJAX para obtener grados
            fetch(`/obtener-grados?nivel_id=${nivelId}`)
                .then(response => {
                    console.log('📡 Respuesta recibida:', response.status); // Debug
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Datos recibidos:', data); // Debug
                    gradoSelect.innerHTML = '<option value="" disabled selected>Seleccione el grado</option>';
                    
                    if (data.grados && data.grados.length > 0) {
                        data.grados.forEach(grado => {
                            const option = document.createElement('option');
                            option.value = grado.id_grado;
                            option.textContent = `${grado.grado}° Grado`;
                            gradoSelect.appendChild(option);
                        });
                        
                        gradoSelect.disabled = false;
                        console.log('✅ Grados cargados correctamente'); // Debug
                    } else {
                        gradoSelect.innerHTML = '<option value="" disabled selected>No hay grados disponibles</option>';
                        console.log('⚠️ No se encontraron grados'); // Debug
                    }
                })
                .catch(error => {
                    console.error('❌ Error al cargar grados:', error);
                    gradoSelect.innerHTML = '<option value="" disabled selected>Error al cargar grados</option>';
                    
                    // Mostrar mensaje de error al usuario
                    alert('Error al cargar los grados. Por favor, intente nuevamente.');
                });
        }
    }
    
    // Función para cargar secciones según el grado seleccionado
    function cargarSecciones() {
        const gradoSelect = document.getElementById('grado_id');
        const seccionSelect = document.getElementById('seccion_id');
        const gradoId = gradoSelect.value;
        
        // Limpiar opciones de sección
        seccionSelect.innerHTML = '<option value="" disabled selected>Cargando secciones...</option>';
        seccionSelect.disabled = true;
        
        if (gradoId) {
            fetch(`/obtener-secciones?grado_id=${gradoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    seccionSelect.innerHTML = '<option value="" disabled selected>Seleccione la sección</option>';
                    
                    if (data.secciones && data.secciones.length > 0) {
                        data.secciones.forEach(seccion => {
                            const option = document.createElement('option');
                            option.value = seccion.id_seccion;
                            option.textContent = `Sección ${seccion.seccion}`;
                            seccionSelect.appendChild(option);
                        });
                        
                        seccionSelect.disabled = false;
                    } else {
                        seccionSelect.innerHTML = '<option value="" disabled selected>No hay secciones disponibles</option>';
                        console.log('⚠️ No se encontraron secciones'); // Debug
                    }
                })
                .catch(error => {
                    console.error('❌ Error al cargar secciones:', error);
                    seccionSelect.innerHTML = '<option value="" disabled selected>Error al cargar secciones</option>';
                    
                    // Mostrar mensaje de error al usuario
                    alert('Error al cargar las secciones. Por favor, intente nuevamente.');
                });
        }
    }
    
    // Función para validar DNI del estudiante
    function validarDNI() {
        const dniInput = document.getElementById('dni');
        const dni = dniInput.value;
        
        if (dni.length === 8 && /^\d{8}$/.test(dni)) {
            // Solo validar para matrícula de ingreso (estudiante nuevo)
            if (esMatriculaIngreso) {
                // Hacer petición AJAX para verificar si el estudiante ya existe
                fetch(`/buscar-estudiante?dni=${dni}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.found) {
                            alert('⚠️ Este estudiante ya está registrado en el sistema.\n\nPara este DNI debe usar "Matrícula Regular" o "Reincorporación".');
                            dniInput.style.borderColor = '#ef4444';
                            dniInput.focus();
                        } else {
                            dniInput.style.borderColor = '#22c55e';
                        }
                    })
                    .catch(error => {
                        console.error('Error al validar DNI:', error);
                        dniInput.style.borderColor = '#d1d5db';
                    });
            } else {
                // Para otros tipos de matrícula, el DNI debe existir
                dniInput.style.borderColor = '#22c55e';
            }
        } else if (dni.length > 0 && dni.length < 8) {
            dniInput.style.borderColor = '#f59e0b';
        } else {
            dniInput.style.borderColor = '#d1d5db';
        }
    }
    
    // Ejecutar cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        
        // Establecer fecha y hora actual para la solicitud
        const fechaMatricula = document.getElementById('fecha');
        if (fechaMatricula) {
            const ahora = new Date();
            const fechaLocal = new Date(ahora.getTime() - ahora.getTimezoneOffset() * 60000);
            fechaMatricula.value = fechaLocal.toISOString().slice(0, 16);
            console.log('📅 Fecha establecida');
        }
        
        // Agregar event listener al select de tipo de matrícula
        const tipoSelect = document.getElementById('id_tipo_matricula');
        if (tipoSelect) {
            console.log('🎯 Select encontrado, agregando listener...');
            tipoSelect.addEventListener('change', function() {
                console.log('🔄 Change event triggered');
                manejarTipoMatricula();
            });
        } else {
        }
        
        // Agregar event listener al campo DNI de búsqueda
        const dniBuscarInput = document.getElementById('dni_buscar');
        if (dniBuscarInput) {
            dniBuscarInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    buscarEstudianteExistente();
                }
            });
        }
        
        // Agregar event listener al DNI para validación (solo para matrícula de ingreso)
        const dniInput = document.getElementById('dni');
        if (dniInput) {
            dniInput.addEventListener('blur', function() {
                if (esMatriculaIngreso) {
                    validarDNI();
                }
            });
        }
        
        // Ocultar secciones inicialmente
        const seccionesToHide = [
            'seccion-datos-estudiante', 
            'seccion-info-academica', 
            'seccion-tutor', 
            'seccion-botones'
        ];
        
        seccionesToHide.forEach(id => {
            const elemento = document.getElementById(id);
            if (elemento) {
                elemento.style.display = 'none';
            } else {
                alert(`⚠️ No se encontró el elemento con ID: ${id}`);
            }
        });
    });
</script>
@endsection