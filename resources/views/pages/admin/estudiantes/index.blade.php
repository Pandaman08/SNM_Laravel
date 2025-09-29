@extends('layout.admin.plantilla')

@section('title', 'Gestión de Estudiantes')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#2e5382] mb-2">Gestión de Estudiantes</h1>
            <div class="w-24 mx-auto h-1 bg-[#64d423] rounded-full"></div>
            <p class="text-gray-600 mt-3">Administra todos los estudiantes del sistema</p>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Barra de búsqueda -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" placeholder="Buscar por nombre, apellido o DNI..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-all"
                            oninput="buscarEstudiantes(this.value)">
                    </div>
                </div>
                <div class="text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                    <span class="font-medium">Total: {{ $estudiantes->total() }}</span>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Estudiante
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Información</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tutores</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ubicación</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">QR</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($estudiantes as $estudiante)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if ($estudiante->persona->photo)
                                                <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200" 
                                                    src="{{ Storage::url($estudiante->persona->photo) }}" alt="">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-[#2e5382] flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($estudiante->persona->name, 0, 1)) }}{{ strtoupper(substr($estudiante->persona->lastname, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $estudiante->persona->name }} {{ $estudiante->persona->lastname }}
                                            </div>
                                            <div class="text-xs text-gray-500">DNI: {{ $estudiante->persona->dni ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $estudiante->persona->fecha_nacimiento ? \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $estudiante->persona->sexo === 'M' ? 'Masculino' : 'Femenino' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($estudiante->tutores && $estudiante->tutores->count() > 0)
                                        <div class="space-y-1.5">
                                            @foreach($estudiante->tutores as $tutor)
                                                <div class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg p-2 hover:bg-blue-100 transition-colors">
                                                    <div class="flex-1 min-w-0 mr-2">
                                                        <div class="text-xs font-medium text-gray-900 truncate">
                                                            {{ $tutor->user->persona->name }} {{ $tutor->user->persona->lastname }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 truncate">
                                                            {{ $tutor->user->email }}
                                                        </div>
                                                        @if($tutor->pivot->tipo_relacion)
                                                            <div class="mt-1">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                    {{ $tutor->pivot->tipo_relacion }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <button type="button" 
                                                        onclick="openEmailModal({{ $estudiante->codigo_estudiante }}, '{{ addslashes($estudiante->persona->name . ' ' . $estudiante->persona->lastname) }}', '{{ addslashes($tutor->user->persona->name . ' ' . $tutor->user->persona->lastname) }}', '{{ $tutor->user->email }}')"
                                                        class="flex-shrink-0 p-1.5 bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
                                                        title="Enviar correo">
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded">Sin tutores</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-900 space-y-0.5">
                                        <div><span class="font-medium">País:</span> {{ $estudiante->pais ?? 'N/A' }}</div>
                                        <div><span class="font-medium">Dpto:</span> {{ $estudiante->departamento ?? 'N/A' }}</div>
                                        <div><span class="font-medium">Dist:</span> {{ $estudiante->distrito ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($estudiante->persona->photo)
                                        <button onclick="openModal('{{ Storage::url($estudiante->persona->photo) }}', 'image')" 
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">Sin foto</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($estudiante->qrImageRender())
                                        <button onclick="openModal('{{ Storage::url('qrcodes/' . $estudiante->qr_code . '.png') }}', 'qr')" 
                                            class="inline-block p-1 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                                            <img src="{{ Storage::url('qrcodes/' . $estudiante->qr_code . '.png') }}" 
                                                class="w-14 h-14" alt="QR">
                                        </button>
                                    @else
                                        <span class="text-xs text-yellow-600">Sin QR</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick='openEditModal(@json($estudiante))' 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm hover:shadow">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="flex justify-between items-center mt-6">
            <div class="text-sm text-gray-600">
                Mostrando {{ $estudiantes->firstItem() ?? 0 }} a {{ $estudiantes->lastItem() ?? 0 }} de {{ $estudiantes->total() }}
            </div>
            {{ $estudiantes->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Modal Edición -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 z-10 flex justify-between items-center p-5 border-b bg-gradient-to-r from-[#2e5382] to-[#1e3a5f]">
                    <div class="text-white">
                        <h2 class="text-xl font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar Estudiante
                        </h2>
                        <p class="text-sm text-blue-100 mt-1">Actualiza la información del estudiante</p>
                    </div>
                    <button onclick="closeEditModal()" class="text-white hover:text-blue-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Preview Foto -->
                    <div class="mb-6 pb-6 border-b">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Foto de Perfil</label>
                        <div class="flex items-center gap-4">
                            <img id="edit_photo_preview" src="" alt="Preview" 
                                class="w-20 h-20 rounded-full object-cover border-2 border-gray-300">
                            <div>
                                <label for="edit_photo" 
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Cambiar Foto
                                </label>
                                <input type="file" id="edit_photo" name="photo" class="hidden" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG (máx. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Campos del formulario -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombres <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_name" name="name" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellidos <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_lastname" name="lastname" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">DNI <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_dni" name="dni" required maxlength="8" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sexo <span class="text-red-500">*</span></label>
                            <select id="edit_sexo" name="sexo" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                                <option value="">Seleccionar</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Nacimiento <span class="text-red-500">*</span></label>
                            <input type="date" id="edit_fecha_nacimiento" name="fecha_nacimiento" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">País <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_pais" name="pais" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departamento <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_departamento" name="departamento" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provincia <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_provincia" name="provincia" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Distrito <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_distrito" name="distrito" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lengua Materna <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_lengua_materna" name="lengua_materna" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Religión <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_religion" name="religion" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                        <textarea id="edit_address" name="address" required rows="2" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                        <button type="button" onclick="closeEditModal()" 
                            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                            Cancelar
                        </button>
                        <button type="submit" 
                            class="px-5 py-2 bg-gradient-to-r from-[#2e5382] to-[#1e3a5f] text-white rounded-lg hover:from-[#1e3a5f] hover:to-[#2e5382] transition-all shadow-md hover:shadow-lg font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Imagen/QR -->
    <div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl max-w-4xl mx-4 overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="modal-title" class="text-lg font-semibold text-gray-900"></h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="p-4"></div>
        </div>
    </div>

    <!-- Modal Email -->
    <div id="emailModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center p-5 border-b bg-gradient-to-r from-[#2e5382] to-[#1e3a5f]">
                <div class="text-white">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Enviar Mensaje al Tutor
                    </h3>
                    <p class="text-sm text-blue-100 mt-1">Estudiante: <span id="estudianteNombre" class="font-medium"></span></p>
                    <p class="text-xs text-blue-200">Para: <span id="tutorName"></span> (<span id="tutorEmail"></span>)</p>
                </div>
                <button onclick="closeEmailModal()" class="text-white hover:text-blue-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('estudiantes.enviar-correo-tutor') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="codigo_estudiante" id="codigo_estudiante">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Asunto <span class="text-red-500">*</span></label>
                        <input type="text" name="asunto" id="asunto" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent"
                            placeholder="Ej: Reunión de padres, Boleta de notas">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje <span class="text-red-500">*</span></label>
                        <textarea name="mensaje" id="mensaje" rows="5" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent"
                            placeholder="Escriba el mensaje que desea enviar al tutor..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEmailModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-[#2e5382] text-white rounded-lg hover:bg-[#1e3a5f] transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Enviar Correo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal para imágenes y QR
        function openModal(url, type) {
            document.getElementById('modal-title').textContent = type === 'image' ? 'Foto de Perfil' : 'Código QR';
            document.getElementById('modalContent').innerHTML = `<img src="${url}" class="max-h-[70vh] w-auto mx-auto rounded-lg">`;
            document.getElementById('archivoModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('archivoModal').classList.add('hidden');
        }

        // Modal para email
        function openEmailModal(codigo, estudiante, tutor, email) {
            document.getElementById('codigo_estudiante').value = codigo;
            document.getElementById('estudianteNombre').textContent = estudiante;
            document.getElementById('tutorName').textContent = tutor;
            document.getElementById('tutorEmail').textContent = email;
            document.getElementById('asunto').value = '';
            document.getElementById('mensaje').value = '';
            document.getElementById('emailModal').classList.remove('hidden');
        }

        function closeEmailModal() {
            document.getElementById('emailModal').classList.add('hidden');
        }

        // Modal de edición
        function openEditModal(est) {
            document.getElementById('editForm').action = `/estudiantes/${est.codigo_estudiante}`;
            document.getElementById('edit_name').value = est.persona.name || '';
            document.getElementById('edit_lastname').value = est.persona.lastname || '';
            document.getElementById('edit_dni').value = est.persona.dni || '';
            document.getElementById('edit_sexo').value = est.persona.sexo || '';
            document.getElementById('edit_fecha_nacimiento').value = est.persona.fecha_nacimiento || '';
            document.getElementById('edit_address').value = est.persona.address || '';
            document.getElementById('edit_pais').value = est.pais || '';
            document.getElementById('edit_departamento').value = est.departamento || '';
            document.getElementById('edit_provincia').value = est.provincia || '';
            document.getElementById('edit_distrito').value = est.distrito || '';
            document.getElementById('edit_lengua_materna').value = est.lengua_materna || '';
            document.getElementById('edit_religion').value = est.religion || '';
            
            // Preview de foto
            const photoPreview = document.getElementById('edit_photo_preview');
            if (est.persona.photo) {
                photoPreview.src = `/storage/${est.persona.photo}`;
            } else {
                photoPreview.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjE4IiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+U2luIGZvdG88L3RleHQ+PC9zdmc+';
            }
            
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editForm').reset();
            document.body.style.overflow = 'auto';
        }

        // Búsqueda en tiempo real
        function buscarEstudiantes(query) {
            fetch(`/estudiantes/buscar?search=${query}`, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const tbody = doc.querySelector('tbody');
                if (tbody) document.querySelector('tbody').innerHTML = tbody.innerHTML;
            })
            .catch(err => console.error('Error:', err));
        }

        // Preview de foto al seleccionar
        document.getElementById('edit_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2097152) {
                    alert('La imagen no debe superar los 2MB');
                    this.value = '';
                    return;
                }
                if (!file.type.match('image.*')) {
                    alert('El archivo debe ser una imagen');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('edit_photo_preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Validación de DNI
        document.getElementById('edit_dni').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
        });

        // Cerrar modales con ESC
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal();
                closeEmailModal();
                closeEditModal();
            }
        });

        // Cerrar modales al hacer clic fuera
        ['archivoModal', 'emailModal', 'editModal'].forEach(modalId => {
            document.getElementById(modalId).addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modalId === 'archivoModal') closeModal();
                    else if (modalId === 'emailModal') closeEmailModal();
                    else closeEditModal();
                }
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: "¡Éxito!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                html: "{!! session('error') !!}",
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection