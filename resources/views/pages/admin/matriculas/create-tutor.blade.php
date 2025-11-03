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
                            <span class="font-semibold text-blue-600">{{ $anioActual->anio }}</span>
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
                                <label for="id_tipo_matricula" class="block text-sm font-medium text-gray-700 mb-2">Tipo de
                                    Matrícula *</label>
                                <select name="id_tipo_matricula" id="id_tipo_matricula"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    <option value="" disabled selected>Seleccione el tipo de matrícula</option>
                                    @foreach ($tiposMatricula as $tipo)
                                        <option value="{{ $tipo->id_tipo_matricula }}">
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Año Escolar -->
                            <div>

                                <input name="id_anio_escolar" id="id_anio_escolar"
                                    value="{{ $anioActual->id_anio_escolar }}"
                                    class="hidden w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>

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
                        <div id="seccion-buscar-estudiante" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg"
                            style="display: none;">
                            <h4 class="font-semibold text-blue-800 mb-3">Buscar Estudiante Existente</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="dni_buscar" class="block text-sm font-medium text-gray-700 mb-2">DNI del
                                        Estudiante *</label>
                                    <input type="text" id="dni_buscar" name="dni_busqueda"  maxlength="8"
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
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre
                                        *</label>
                                    <input type="text" name="nombre" id="nombre"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>

                                <!-- Apellidos -->
                                <div>
                                    <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-2">Apellidos
                                        *</label>
                                    <input type="text" name="apellidos" id="apellidos"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>

                                <!-- DNI -->
                                <div>
                                    <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">DNI *</label>
                                    <input type="text" name="dni" id="dni" maxlength="8"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required pattern="[0-9]{8}" title="Ingrese 8 dígitos numéricos">
                                </div>

                                <!-- Sexo -->
                                <div>
                                    <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">Sexo
                                        *</label>
                                    <select name="sexo" id="sexo"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                        <option value="" disabled selected>Seleccione</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>

                                <!-- Fecha de Nacimiento -->
                                <div>
                                    <label for="fecha_nacimiento"
                                        class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento *</label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>

                                <!-- País -->
                                <div>
                                    <label for="pais" class="block text-sm font-medium text-gray-700 mb-2">País
                                        *</label>
                                    <input type="text" name="pais" id="pais" value="Perú"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>


                                <!-- Departamento -->
                                <div>
                                    <label for="departamento"
                                        class="block text-sm font-medium text-gray-700 mb-2">Departamento *</label>
                                    <select name="departamento" id="departamento"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="cargarProvincias()" required>
                                        <option value="" disabled selected>Seleccione departamento</option>
                                        <option value="Amazonas">Amazonas</option>
                                        <option value="Ancash">Áncash</option>
                                        <option value="Apurimac">Apurímac</option>
                                        <option value="Arequipa">Arequipa</option>
                                        <option value="Ayacucho">Ayacucho</option>
                                        <option value="Cajamarca">Cajamarca</option>
                                        <option value="Callao">Callao</option>
                                        <option value="Cusco">Cusco</option>
                                        <option value="Huancavelica">Huancavelica</option>
                                        <option value="Huanuco">Huánuco</option>
                                        <option value="Ica">Ica</option>
                                        <option value="Junin">Junín</option>
                                        <option value="La Libertad">La Libertad</option>
                                        <option value="Lambayeque">Lambayeque</option>
                                        <option value="Lima">Lima</option>
                                        <option value="Loreto">Loreto</option>
                                        <option value="Madre de Dios">Madre de Dios</option>
                                        <option value="Moquegua">Moquegua</option>
                                        <option value="Pasco">Pasco</option>
                                        <option value="Piura">Piura</option>
                                        <option value="Puno">Puno</option>
                                        <option value="San Martin">San Martín</option>
                                        <option value="Tacna">Tacna</option>
                                        <option value="Tumbes">Tumbes</option>
                                        <option value="Ucayali">Ucayali</option>
                                    </select>
                                </div>

                                <!-- Provincia -->
                                <div>
                                    <label for="provincia" class="block text-sm font-medium text-gray-700 mb-2">Provincia
                                        *</label>
                                    <select name="provincia" id="provincia"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        onchange="cargarDistritos()" disabled required>
                                        <option value="" disabled selected>Primero seleccione un departamento
                                        </option>
                                    </select>
                                </div>

                                <!-- Distrito -->
                                <div>
                                    <label for="distrito" class="block text-sm font-medium text-gray-700 mb-2">Distrito
                                        *</label>
                                    <select name="distrito" id="distrito"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        disabled required>
                                        <option value="" disabled selected>Primero seleccione una provincia</option>
                                    </select>
                                </div>

                                <!-- Dirección -->
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Dirección
                                        *</label>
                                    <input type="text" name="address" id="address"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ old('address') }}">
                                </div>

                                <!-- Lengua Materna -->
                                <div>
                                    <label for="lengua_materna"
                                        class="block text-sm font-medium text-gray-700 mb-2">Lengua Materna *</label>
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
                                    <label for="religion"
                                        class="block text-sm font-medium text-gray-700 mb-2">Religión</label>
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
                                <label for="nivel_educativo_id" class="block text-sm font-medium text-gray-700 mb-2">Nivel
                                    Educativo *</label>
                                <select name="nivel_educativo_id" id="nivel_educativo_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required onchange="cargarGrados()">
                                    <option value="" disabled selected>Seleccione el nivel</option>
                                    @foreach ($nivelesEducativos as $nivel)
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
                                <label for="id_seccion" class="block text-sm font-medium text-gray-700 mb-2">Sección
                                    *</label>
                                <select name="seccion_id" id="seccion_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required disabled>
                                    <option value="" disabled selected>Primero seleccione el grado</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fecha de Solicitud (readonly) -->
                        <div class="mt-6">
                            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha de
                                Solicitud</label>
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
                                <p class="text-gray-700">{{ auth()->user()->persona->name }}
                                    {{ auth()->user()->persona->lastname }}</p>
                                <p class="text-gray-500 text-sm">DNI: {{ auth()->user()->persona->dni }}</p>
                                <p class="text-gray-500 text-sm">Email: {{ auth()->user()->email }}</p>
                            </div>
                            <diwv class="p-4 bg-gray-50 rounded-lg">
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
                                    <p class="text-orange-700 text-sm mt-1">Su solicitud de matrícula quedará en estado
                                        "PENDIENTE" hasta ser revisada y aprobada por la administración del colegio.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NUEVO: Contactos de emergencia (Parientes) -->
                <div id="seccion-parientes" class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="ri-phone-line text-red-500 mr-2"></i>
                            Contactos de Emergencia (Parientes) <span class="text-sm text-gray-500 ml-3">Opcional</span>
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">Agregue uno o más contactos de emergencia. Estos se
                            vincularán a su perfil y podrán ser consultados por la institución.</p>

                        <div id="parientes-container" class="space-y-4">
                            <!-- Plantilla clonable -->
                            <template id="pariente-template">
                                <div class="pariente-item grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm text-gray-700">Nombre</label>
                                        <input type="text" name="parientes[][nombre]"
                                            class="nombre-pariente w-full px-3 py-2 border rounded-lg"
                                            placeholder="Nombre completo">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm text-gray-700">Celular</label>
                                        <input type="text" name="parientes[][celular]"
                                            class="celular-pariente w-full px-3 py-2 border rounded-lg"
                                            placeholder="9 dígitos" maxlength="9" pattern="[0-9]{9}">
                                    </div>
                                    <div class="md:col-span-2 flex space-x-2">
                                        <button type="button"
                                            class="add-pariente inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            <i class="ri-add-line mr-2"></i> Agregar
                                        </button>
                                        <button type="button"
                                            class="remove-pariente inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                            <i class="ri-subtract-line mr-2"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <!-- Un item por defecto (debe enviarse vacío si no se completa) -->
                            <div class="pariente-item grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
                                <div class="md:col-span-2">
                                    <label class="block text-sm text-gray-700">Nombre</label>
                                    <input type="text" name="parientes[][nombre]"
                                        class="nombre-pariente w-full px-3 py-2 border rounded-lg"
                                        placeholder="Nombre completo">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm text-gray-700">Celular</label>
                                    <input type="text" name="parientes[][celular]"
                                        class="celular-pariente w-full px-3 py-2 border rounded-lg"
                                        placeholder="9 dígitos" maxlength="9" pattern="[0-9]{9}">
                                </div>
                                <div class="md:col-span-2 flex space-x-2">
                                    <button type="button" id="btn-add-pariente"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                        <i class="ri-add-line mr-2"></i> Agregar
                                    </button>
                                    <button type="button"
                                        class="remove-pariente inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                        <i class="ri-subtract-line mr-2"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-400 mt-3">Nota: deje los campos vacíos si no desea agregar contactos.
                        </p>
                    </div>
                </div>
                <!-- FIN NUEVO: Contactos de emergencia (Parientes) -->

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
                            '❌ No se encontró ningún estudiante con ese DNI. Verifique el número o contacte con la administración.'
                        );

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

            switch (tipo) {
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
        async function llenarFormularioEstudiante(estudiante) {
            // Declarar referencias a los elementos primero
            const nombreEl = document.getElementById('nombre');
            const apellidosEl = document.getElementById('apellidos');
            const dniEl = document.getElementById('dni');
            const sexoEl = document.getElementById('sexo');
            const fechaEl = document.getElementById('fecha_nacimiento');
            const paisEl = document.getElementById('pais');
            const departamentoEl = document.getElementById('departamento');
            const provinciaEl = document.getElementById('provincia');
            const distritoEl = document.getElementById('distrito');
            const lenguaEl = document.getElementById('lengua_materna');
            const religionEl = document.getElementById('religion');
            const addressEl = document.getElementById('address');

            // Asignar valores simples
            nombreEl.value = estudiante.nombre || '';
            apellidosEl.value = estudiante.apellidos || '';
            dniEl.value = estudiante.dni || '';
            sexoEl.value = estudiante.sexo || '';
            fechaEl.value = estudiante.fecha_nacimiento || '';
            paisEl.value = estudiante.pais || 'Perú';

            // Departamento -> cargar provincias -> asignar provincia -> cargar distritos -> asignar distrito
            departamentoEl.value = estudiante.departamento || '';

            // Reset y estado por defecto
            // provinciaEl.innerHTML = '<option value="" disabled selected>Primero seleccione un departamento</option>';
            // provinciaEl.disabled = true;
            // distritoEl.innerHTML = '<option value="" disabled selected>Primero seleccione una provincia</option>';
            // distritoEl.disabled = true;
            departamentoEl.disabled = true;
            if (departamentoEl.value) {
                try {
                    // cargarProvincias usa el valor del select departamento para poblar provincia
                    await cargarProvincias();

                    // Si viene provincia en los datos, asignarla y habilitar
                    if (estudiante.provincia) {
                        provinciaEl.value = estudiante.provincia;
                        provinciaEl.disabled = true;

                        // cargarDistritos depende de la provincia seleccionada
                        await cargarDistritos();

                        if (estudiante.distrito) {
                            distritoEl.value = estudiante.distrito;
                            distritoEl.disabled = true;
                        }
                    }
                } catch (err) {
                    console.error('Error al asignar provincia/distrito:', err);
                    // mantener selects en estado seguro
                    provinciaEl.disabled = true;
                    distritoEl.disabled = true;
                }
            }

            // Campos restantes
            lenguaEl.value = estudiante.lengua_materna || '';
            religionEl.value = estudiante.religion || '';
            addressEl.value = estudiante.address || '';

            console.log('🚀 Llenado de formulario completado:', {
                departamento: departamentoEl.value,
                provincia: provinciaEl.value,
                distrito: distritoEl.value
            });
        }

        // Función para limpiar formulario del estudiante
        function limpiarFormularioEstudiante() {
            const campos = ['nombre', 'apellidos', 'dni', 'sexo', 'fecha_nacimiento',
                'pais', 'provincia', 'distrito', 'departamento', 'lengua_materna', 'religion', 'address'
            ];

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
                'pais', 'provincia', 'distrito', 'departamento', 'lengua_materna', 'religion', 'address'
            ];

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
            const nivelSelect = document.getElementById('nivel_educativo_id');
            const gradoSelect = document.getElementById('grado_id');
            const seccionSelect = document.getElementById('seccion_id');
            const nivelId = nivelSelect.value;


            console.log('🔄 Cargando grados para nivel ID:', nivelId);

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
                            gradoSelect.innerHTML =
                                '<option value="" disabled selected>No hay grados disponibles</option>';
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
                            seccionSelect.innerHTML =
                                '<option value="" disabled selected>No hay secciones disponibles</option>';
                            console.log('⚠️ No se encontraron secciones'); // Debug
                        }
                    })
                    .catch(error => {
                        console.error('❌ Error al cargar secciones:', error);
                        seccionSelect.innerHTML =
                            '<option value="" disabled selected>Error al cargar secciones</option>';

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
                                alert(
                                    '⚠️ Este estudiante ya está registrado en el sistema.\n\nPara este DNI debe usar "Matrícula Regular" o "Reincorporación".'
                                );
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
            } else {}

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



        async function cargarProvincias() {
            const departamentoSelect = document.getElementById('departamento');
            const provinciaSelect = document.getElementById('provincia');
            const distritoSelect = document.getElementById('distrito');

            const departamento = departamentoSelect.value;

            // Limpiar y deshabilitar selects dependientes
            provinciaSelect.innerHTML = '<option value="" disabled selected>Seleccione provincia</option>';
            provinciaSelect.disabled = true;

            distritoSelect.innerHTML = '<option value="" disabled selected>Primero seleccione una provincia</option>';
            distritoSelect.disabled = true;

            if (departamento) {
                try {
                    // Cargar datos desde el archivo JSON en public/data/ubigeo.json
                    const response = await fetch('/data/ubigeo.json');
                    const data = await response.json();

                    if (data.provinciasPorDepartamento[departamento]) {
                        // Cargar provincias del departamento seleccionado
                        data.provinciasPorDepartamento[departamento].forEach(provincia => {
                            const option = document.createElement('option');
                            option.value = provincia;
                            option.textContent = provincia;
                            provinciaSelect.appendChild(option);
                        });

                        provinciaSelect.disabled = false;
                    }
                } catch (error) {
                    console.error('Error al cargar las provincias:', error);
                }
            }
        }

        async function cargarDistritos() {
            const provinciaSelect = document.getElementById('provincia');
            const distritoSelect = document.getElementById('distrito');
            const provincia = provinciaSelect.value;

            // Limpiar select de distritos
            distritoSelect.innerHTML = '<option value="" disabled selected>Seleccione distrito</option>';
            distritoSelect.disabled = true;
            if (provincia) {
                try {
                    // Cargar datos desde el archivo JSON en public/data/ubigeo.json
                    const response = await fetch('/data/ubigeo.json');
                    const data = await response.json();

                    if (data.distritosPorProvincia[provincia]) {
                        // Cargar distritos de la provincia seleccionada
                        data.distritosPorProvincia[provincia].forEach(distrito => {
                            const option = document.createElement('option');
                            option.value = distrito;
                            option.textContent = distrito;
                            distritoSelect.appendChild(option);
                        });

                        distritoSelect.disabled = false;
                    }
                } catch (error) {
                    console.error('Error al cargar los distritos:', error);
                }
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('parientes-container');
            const template = document.getElementById('pariente-template');

            // Contador para asegurar índices únicos
            let parentCounter = 1; // Empezamos en 1 porque el primer item ya existe
            const MAX_CONTACTOS = 4;

            // Función para actualizar botones de todos los items
            function actualizarBotones() {
                const items = container.querySelectorAll('.pariente-item');
                const totalItems = items.length;

                items.forEach((item, index) => {
                    const btnAgregar = item.querySelector('.add-pariente, #btn-add-pariente');
                    const btnEliminar = item.querySelector('.remove-pariente');

                    // Solo el último item muestra el botón agregar
                    if (index === totalItems - 1) {
                        if (btnAgregar) {
                            btnAgregar.style.display = 'inline-flex';

                            // Deshabilitar si se alcanzó el máximo
                            if (totalItems >= MAX_CONTACTOS) {
                                btnAgregar.disabled = true;
                                btnAgregar.classList.add('opacity-50', 'cursor-not-allowed');
                                btnAgregar.title = `Máximo ${MAX_CONTACTOS} contactos permitidos`;
                            } else {
                                btnAgregar.disabled = false;
                                btnAgregar.classList.remove('opacity-50', 'cursor-not-allowed');
                                btnAgregar.title = '';
                            }
                        }
                    } else {
                        // Los demás items ocultan el botón agregar
                        if (btnAgregar) {
                            btnAgregar.style.display = 'none';
                        }
                    }

                    // Siempre mostrar botón eliminar (excepto si es el único)
                    if (btnEliminar) {
                        if (totalItems === 1) {
                            btnEliminar.disabled = true;
                            btnEliminar.classList.add('opacity-50', 'cursor-not-allowed');
                            btnEliminar.title = 'Debe mantener al menos un campo disponible';
                        } else {
                            btnEliminar.disabled = false;
                            btnEliminar.classList.remove('opacity-50', 'cursor-not-allowed');
                            btnEliminar.title = '';
                        }
                    }
                });

                console.log(`📊 Total contactos: ${totalItems}/${MAX_CONTACTOS}`);
            }

            // Función para reindexar todos los items
            function reindexarItems() {
                const items = container.querySelectorAll('.pariente-item');
                items.forEach((item, index) => {
                    const inputs = item.querySelectorAll('input');
                    inputs.forEach(input => {
                        const currentName = input.getAttribute('name');
                        if (currentName) {
                            // Actualizar el índice en el name attribute
                            if (currentName.includes('[nombre]')) {
                                input.setAttribute('name', `parientes[${index}][nombre]`);
                            } else if (currentName.includes('[celular]')) {
                                input.setAttribute('name', `parientes[${index}][celular]`);
                            }
                        }
                    });
                });
                console.log('🔄 Items reindexados');
            }

            // Función para agregar un nuevo pariente
            function agregarPariente() {
                const totalItems = container.querySelectorAll('.pariente-item').length;

                // Verificar límite máximo
                if (totalItems >= MAX_CONTACTOS) {
                    alert(`⚠️ Solo puede agregar un máximo de ${MAX_CONTACTOS} contactos de emergencia.`);
                    return;
                }

                // Clonar el template
                const nuevoPariente = template.content.cloneNode(true);

                // Actualizar los atributos name con índice correcto
                const inputs = nuevoPariente.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.classList.contains('nombre-pariente')) {
                        input.setAttribute('name', `parientes[${parentCounter}][nombre]`);
                    } else if (input.classList.contains('celular-pariente')) {
                        input.setAttribute('name', `parientes[${parentCounter}][celular]`);
                    }
                });

                parentCounter++;

                // Agregar al contenedor
                container.appendChild(nuevoPariente);

                // Reindexar todos los items para mantener consistencia
                reindexarItems();

                // Actualizar visibilidad de botones
                actualizarBotones();

                console.log('✅ Nuevo contacto agregado');
            }

            // Función para eliminar un pariente
            function eliminarPariente(button) {
                const parienteItem = button.closest('.pariente-item');
                const totalItems = container.querySelectorAll('.pariente-item').length;

                // No permitir eliminar si solo queda uno
                if (totalItems <= 1) {
                    alert(
                        '⚠️ Debe mantener al menos un campo de contacto disponible (puede dejarlo vacío si no desea agregar contactos).');
                    return;
                }

                parienteItem.remove();

                // Reindexar todos los items después de eliminar
                reindexarItems();

                // Actualizar visibilidad de botones
                actualizarBotones();

                console.log('🗑️ Contacto eliminado. Total restante:', totalItems - 1);
            }

            // Event delegation para botones
            container.addEventListener('click', function(e) {
                // Botón agregar
                if (e.target.closest('.add-pariente') || e.target.closest('#btn-add-pariente')) {
                    e.preventDefault();
                    const button = e.target.closest('.add-pariente') || e.target.closest(
                        '#btn-add-pariente');
                    if (!button.disabled) {
                        agregarPariente();
                    }
                }

                // Botón eliminar
                if (e.target.closest('.remove-pariente')) {
                    e.preventDefault();
                    const button = e.target.closest('.remove-pariente');
                    if (!button.disabled) {
                        eliminarPariente(button);
                    }
                }
            });

            // Validación opcional: solo números en celular
            container.addEventListener('input', function(e) {
                if (e.target.classList.contains('celular-pariente')) {
                    // Eliminar cualquier caracter que no sea número
                    e.target.value = e.target.value.replace(/\D/g, '');
                }
            });

            // Validación antes de enviar el formulario
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const parientes = container.querySelectorAll('.pariente-item');
                    let hayErrores = false;

                    parientes.forEach((item, index) => {
                        const nombre = item.querySelector('.nombre-pariente').value.trim();
                        const celular = item.querySelector('.celular-pariente').value.trim();

                        // Si uno está lleno pero el otro vacío, es un error
                        if ((nombre && !celular) || (!nombre && celular)) {
                            hayErrores = true;

                            // Resaltar campos con error
                            if (!nombre) {
                                item.querySelector('.nombre-pariente').classList.add(
                                    'border-red-500');
                            }
                            if (!celular) {
                                item.querySelector('.celular-pariente').classList.add(
                                    'border-red-500');
                            }
                        }

                        // Validar formato de celular si está presente
                        if (celular && celular.length !== 9) {
                            hayErrores = true;
                            item.querySelector('.celular-pariente').classList.add('border-red-500');
                        }
                    });

                    if (hayErrores) {
                        e.preventDefault();
                        alert('⚠️ Por favor complete correctamente los contactos de emergencia:\n\n' +
                            '• Si ingresa un nombre, debe ingresar también el celular\n' +
                            '• El celular debe tener exactamente 9 dígitos\n' +
                            '• Puede dejar ambos campos vacíos si no desea agregar contactos');

                        // Scroll al primer error
                        const primerError = container.querySelector('.border-red-500');
                        if (primerError) {
                            primerError.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }
                });

                // Limpiar estilos de error al escribir
                container.addEventListener('input', function(e) {
                    if (e.target.classList.contains('nombre-pariente') ||
                        e.target.classList.contains('celular-pariente')) {
                        e.target.classList.remove('border-red-500');
                    }
                });
            }

            // Inicializar: reindexar el item por defecto y actualizar botones
            reindexarItems();
            actualizarBotones();
        });
    </script>
@endsection
