@extends('layout.admin.plantilla')

@section('title', 'Gestión de Docentes')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#2e5382] mb-2">Gestión de Docentes</h1>
            <div class="w-24 mx-auto h-1 bg-[#64d423] rounded-full"></div>
            <p class="text-gray-600 mt-3">Administra todos los docentes del sistema</p>
        </div>

        <!-- Controls Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search Bar -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" placeholder="Buscar por nombre o email..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-all duration-200"
                            oninput="buscarDocentes(this.value)">
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                        <span class="font-medium">Total: {{ $users->total() }}</span>
                    </div>
                    <button
                        class="bg-[#2e5382] text-white px-5 py-2.5 rounded-lg hover:bg-[#1e3a5f] transition-all duration-200 flex items-center space-x-2"
                        onclick="openCreateModal()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Registrar Docente</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Docente</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <span>Código</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m2-12h2m-2 8h2M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <span>Especialidades</span>
                                </div>
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Nivel Educativo</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Contacto</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Foto</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Firma</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Acciones</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Docente Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if ($user->persona->photo)
                                                <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200"
                                                    src="{{ Storage::url($user->persona->photo) }}"
                                                    alt="{{ $user->persona->name }}">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-full bg-[#2e5382] flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($user->persona->name, 0, 1)) }}{{ strtoupper(substr($user->persona->lastname, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $user->persona->name }} {{ $user->persona->lastname }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                DNI: {{ $user->persona->dni ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Código -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 font-medium">
                                        {{ $user->docente->codigo_docente }}
                                    </span>
                                </td>

                                <!-- Especialidades -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @forelse ($user->docente->especialidades as $especialidad)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $especialidad->nombre }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-400">Sin especialidades</span>
                                        @endforelse
                                    </div>
                                </td>

                                <!-- Nivel Educativo -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->docente->nivelEducativo)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $user->docente->nivelEducativo->nombre == 'Primaria'
                                                ? 'bg-green-100 text-green-800'
                                                : ($user->docente->nivelEducativo->nombre == 'Secundaria'
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $user->docente->nivelEducativo->nombre }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">Sin nivel</span>
                                    @endif
                                </td>

                                <!-- Contacto -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="truncate max-w-xs">{{ $user->email }}</span>
                                        </div>
                                        @if ($user->persona->phone)
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                    </path>
                                                </svg>
                                                {{ $user->persona->phone }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Foto -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($user->persona->photo)
                                        <button
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-150"
                                            onclick="openModal('{{ Storage::url($user->persona->photo) }}', 'image')"
                                            title="Ver foto">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-400">Sin foto</span>
                                    @endif
                                </td>

                                <!-- Firma -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($user->docente->firma_docente)
                                        <button
                                            class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-150"
                                            onclick="openModal('{{ Storage::url($user->docente->firma_docente) }}', 'image')"
                                            title="Ver firma">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-400">Sin firma</span>
                                    @endif
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" onclick="openEditModal(this)"
                                            data-user='{!! json_encode($user->load(['persona', 'docente', 'docente.especialidades', 'docente.nivelEducativo'])) !!}'
                                            class="inline-flex items-center justify-center w-8 h-8 text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-150"
                                            title="Editar docente">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <button
                                            onclick="openDeleteModal({{ $user->user_id }}, '{{ $user->persona->name }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-150"
                                            title="Eliminar docente">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-6">
            <div class="text-sm text-gray-600">
                Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }}
                resultados
            </div>
            <div class="flex justify-end">
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl max-h-[90vh] overflow-hidden mx-4">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Visualizador</h3>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="p-4"></div>
        </div>
    </div>

    <!-- Modal de Creación -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded shadow-lg max-w-4xl w-full max-h-screen overflow-y-auto relative">
            <button class="absolute top-2 right-2 text-gray-500 hover:text-black text-3xl p-2"
                onclick="closeCreateModal()">
                &times;
            </button>
            <h2 class="text-xl font-bold mb-4">Registrar Nuevo Docente</h2>
            <form id="createForm" action="{{ route('docentes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna 1 -->
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block">Nombre</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded"
                                required>
                            <div id="name-error" class="text-red-500 text-sm mt-1 hidden">El nombre es requerido</div>
                        </div>
                        <div>
                            <label for="lastname" class="block">Apellido</label>
                            <input type="text" id="lastname" name="lastname" class="w-full px-4 py-2 border rounded"
                                required>
                            <div id="lastname-error" class="text-red-500 text-sm mt-1 hidden">El apellido es requerido
                            </div>
                        </div>
                        <div>
                            <label for="dni" class="block">DNI <span class="text-gray-500 text-sm">(8
                                    dígitos)</span></label>
                            <input type="text" id="dni" name="dni" class="w-full px-4 py-2 border rounded"
                                inputmode="numeric" maxlength="8" required>
                            <div id="dni-error" class="text-red-500 text-sm mt-1 hidden">El DNI debe tener 8 dígitos
                                numéricos</div>
                        </div>
                        <div>
                            <label for="phone" class="block">Teléfono <span class="text-gray-500 text-sm">(9
                                    dígitos)</span></label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border rounded"
                                inputmode="numeric" maxlength="9" required>
                            <div id="phone-error" class="text-red-500 text-sm mt-1 hidden">El teléfono debe tener 9
                                dígitos numéricos</div>
                        </div>
                        <div>
                            <label for="sexo" class="block">Sexo</label>
                            <select id="sexo" name="sexo" class="w-full px-4 py-2 border rounded">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>

                    <!-- Columna 2 -->
                    <div class="space-y-4">
                        <div>
                            <label for="address" class="block">Dirección</label>
                            <input type="text" id="address" name="address" class="w-full px-4 py-2 border rounded"
                                required>
                            <div id="address-error" class="text-red-500 text-sm mt-1 hidden">La dirección es requerida
                            </div>
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="block">Fecha Nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                class="w-full px-4 py-2 border rounded" required>
                            <div id="fecha_nacimiento-error" class="text-red-500 text-sm mt-1 hidden">La fecha de
                                nacimiento es requerida</div>
                        </div>
                        <div>
                            <label for="nivel_educativo_id" class="block">Nivel Educativo</label>
                            <select id="nivel_educativo_id" name="nivel_educativo_id"
                                class="w-full px-4 py-2 border rounded" required>
                                <option value="">Seleccionar nivel</option>
                                @foreach ($nivelesEducativos as $nivel)
                                    <option value="{{ $nivel->id_nivel_educativo }}">{{ $nivel->nombre }}</option>
                                @endforeach
                            </select>
                            <div id="nivel-error" class="text-red-500 text-sm mt-1 hidden">Debe seleccionar un nivel
                                educativo</div>
                        </div>
                        <div>
                            <label for="firma_docente" class="block">Firma Digital (imagen)</label>
                            <input type="file" id="firma_docente" name="firma_docente"
                                class="w-full px-2 py-1 border rounded" accept="image/*">
                            <div id="firma-error" class="text-red-500 text-sm mt-1 hidden">La firma debe ser una imagen
                                (jpg, png, jpeg)</div>
                        </div>
                        <!-- Foto de perfil -->
                        <div>
                            <label class="block mb-1">Foto de Perfil</label>
                            <input type="file" id="photo" name="photo" class="w-full px-2 py-1 border rounded"
                                accept="image/*">
                            <div id="photo-error" class="text-red-500 text-sm mt-1 hidden">La foto debe ser una imagen
                                (jpg, png,
                                jpeg)</div>
                        </div>
                    </div>
                </div>

                <!-- Especialidades -->
                <div class="mt-6">
                    <label class="block mb-2">Especialidades</label>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="relative mb-4">
                            <input type="text" id="searchEspecialidades" placeholder="Buscar especialidades..."
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent"
                                oninput="filterEspecialidades()">
                        </div>
                        <div id="especialidadesContainer"
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-2">
                            @foreach ($especialidades as $especialidad)
                                <div class="flex items-center p-2 border rounded hover:bg-gray-100 especialidad-item">
                                    <input type="checkbox" id="especialidad_{{ $especialidad->id_especialidad }}"
                                        name="especialidades[]" value="{{ $especialidad->id_especialidad }}"
                                        class="h-4 w-4 text-[#2e5382] focus:ring-[#2e5382] border-gray-300 rounded">
                                    <label for="especialidad_{{ $especialidad->id_especialidad }}"
                                        class="ml-2 text-sm text-gray-700 cursor-pointer especialidad-nombre">
                                        {{ $especialidad->nombre }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div id="especialidades-error" class="text-red-500 text-sm mt-2 hidden">Debe seleccionar al menos
                            una especialidad</div>
                    </div>
                </div>



                <div class="mt-6">
                    <button type="button" onclick="validateCreateForm()"
                        class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Registrar Docente
                    </button>
                    <button type="button" onclick="closeCreateModal()"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-700 ml-2">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
        <div class="flex items-center justify-center w-full h-full">
            <div class="bg-white px-8 py-6 rounded-lg shadow-xl max-w-4xl w-full relative max-h-screen overflow-y-auto">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-blue-800">Editar Docente</h2>
                    <div class="mx-auto mt-2 w-1/5 h-1 bg-green-400"></div>
                </div>

                <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna Izquierda -->
                        <div class="space-y-4">
                            <div>
                                <label for="edit_name" class="block text-gray-700">Nombres:</label>
                                <input type="text" id="edit_name" name="edit_name"
                                    class="w-full px-4 py-2 border rounded" required>
                                <div id="edit_name-error" class="text-red-500 text-sm mt-1 hidden">El nombre es requerido
                                </div>
                            </div>
                            <div>
                                <label for="edit_lastname" class="block text-gray-700">Apellidos:</label>
                                <input type="text" id="edit_lastname" name="edit_lastname"
                                    class="w-full px-4 py-2 border rounded" required>
                                <div id="edit_lastname-error" class="text-red-500 text-sm mt-1 hidden">El apellido es
                                    requerido</div>
                            </div>
                            <div>
                                <label for="edit_dni" class="block text-gray-700">DNI:</label>
                                <input type="text" id="edit_dni" name="edit_dni"
                                    class="w-full px-4 py-2 border rounded" inputmode="numeric" maxlength="8" required>
                                <div id="edit_dni-error" class="text-red-500 text-sm mt-1 hidden">El DNI debe tener 8
                                    dígitos numéricos</div>
                            </div>
                            <div>
                                <label for="edit_phone" class="block text-gray-700">Teléfono:</label>
                                <input type="tel" id="edit_phone" name="edit_phone"
                                    class="w-full px-4 py-2 border rounded" inputmode="numeric" maxlength="9" required>
                                <div id="edit_phone-error" class="text-red-500 text-sm mt-1 hidden">El teléfono debe tener
                                    9 dígitos numéricos</div>
                            </div>
                            <div>
                                <label for="edit_sexo" class="block text-gray-700">Sexo:</label>
                                <select id="edit_sexo" name="edit_sexo" class="w-full px-4 py-2 border rounded">
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="space-y-4">
                            <div>
                                <label for="edit_email" class="block text-gray-700">Email:</label>
                                <input type="email" id="edit_email" name="edit_email"
                                    class="w-full px-4 py-2 border rounded bg-gray-100" readonly>
                            </div>
                            <div>
                                <label for="edit_fecha_nacimiento" class="block text-gray-700">Fecha Nacimiento:</label>
                                <input type="date" id="edit_fecha_nacimiento" name="edit_fecha_nacimiento"
                                    class="w-full px-4 py-2 border rounded" required>
                                <div id="edit_fecha_nacimiento-error" class="text-red-500 text-sm mt-1 hidden">La fecha de
                                    nacimiento es requerida</div>
                            </div>
                            <div>
                                <label for="edit_address" class="block text-gray-700">Dirección:</label>
                                <input type="text" id="edit_address" name="edit_address"
                                    class="w-full px-4 py-2 border rounded" required>
                                <div id="edit_address-error" class="text-red-500 text-sm mt-1 hidden">La dirección es
                                    requerida</div>
                            </div>
                            <div>
                                <label for="edit_nivel_educativo_id" class="block text-gray-700">Nivel Educativo:</label>
                                <select id="edit_nivel_educativo_id" name="edit_nivel_educativo_id"
                                    class="w-full px-4 py-2 border rounded" required>
                                    <option value="">Seleccionar nivel</option>
                                    @foreach ($nivelesEducativos as $nivel)
                                        <option value="{{ $nivel->id_nivel_educativo }}">{{ $nivel->nombre }}</option>
                                    @endforeach
                                </select>
                                <div id="edit_nivel-error" class="text-red-500 text-sm mt-1 hidden">Debe seleccionar un
                                    nivel educativo</div>
                            </div>
                            <div>
                                <label for="edit_firma_docente" class="block text-gray-700">Firma Digital:</label>
                                <input type="file" id="edit_firma_docente" name="edit_firma_docente"
                                    class="w-full px-2 py-1 border rounded" accept="image/*">
                                <small class="text-gray-500">Dejar vacío para mantener la firma actual</small>
                                <div id="edit_firma-error" class="text-red-500 text-sm mt-1 hidden">La firma debe ser una
                                    imagen (jpg, png, jpeg)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Especialidades en edición -->
                    <div class="mt-6">
                        <label class="block mb-2">Especialidades</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="relative mb-4">
                                <input type="text" id="editSearchEspecialidades"
                                    placeholder="Buscar especialidades..."
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent"
                                    oninput="filterEditEspecialidades()">
                            </div>
                            <div id="editEspecialidadesContainer"
                                class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-2">
                                @foreach ($especialidades as $especialidad)
                                    <div
                                        class="flex items-center p-2 border rounded hover:bg-gray-100 edit-especialidad-item">
                                        <input type="checkbox"
                                            id="edit_especialidad_{{ $especialidad->id_especialidad }}"
                                            name="edit_especialidades[]" value="{{ $especialidad->id_especialidad }}"
                                            class="h-4 w-4 text-[#2e5382] focus:ring-[#2e5382] border-gray-300 rounded edit-especialidad-checkbox">
                                        <label for="edit_especialidad_{{ $especialidad->id_especialidad }}"
                                            class="ml-2 text-sm text-gray-700 cursor-pointer edit-especialidad-nombre">
                                            {{ $especialidad->nombre }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div id="edit_especialidades-error" class="text-red-500 text-sm mt-2 hidden">Debe seleccionar
                                al menos una especialidad</div>
                        </div>
                    </div>

                    <!-- Foto de perfil -->
                    <div class="mt-6">
                        <label class="block text-gray-700 mb-1">Foto de Perfil:</label>
                        <div id="edit-image-upload"
                            class="border-2 border-dashed border-gray-300 w-full h-52 flex flex-col items-center justify-center cursor-pointer relative text-center rounded-md"
                            onclick="document.getElementById('edit_photo').click()">
                            <span id="edit-image-placeholder" class="text-gray-500 flex flex-col items-center">
                                <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                Selecciona o arrastra una imagen
                            </span>
                            <img id="previewImg" src="" alt="Vista previa"
                                class="hidden w-52 h-full object-cover rounded shadow mx-auto">
                            <input type="file" id="edit_photo" name="edit_photo" class="hidden" accept="image/*">
                        </div>
                        <div id="edit_photo-error" class="text-red-500 text-sm mt-1 hidden">La foto debe ser una imagen
                            (jpg, png, jpeg)</div>
                    </div>

                    <!-- Vista previa de firma -->
                    <div class="mt-6">
                        <label class="block text-gray-700 mb-1">Firma Actual:</label>
                        <div id="firmaPreview"
                            class="border border-gray-300 w-full h-40 flex items-center justify-center bg-gray-50 rounded-md">
                            <img id="previewFirma" src="" alt="Firma actual"
                                class="hidden max-h-36 object-contain">
                            <span id="firma-placeholder" class="text-gray-500">Sin firma registrada</span>
                        </div>
                    </div>

                    <div class="flex justify-center gap-4 mt-6">
                        <button type="button" onclick="validateEditForm()"
                            class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Actualizar
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal de Eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
        <div class="flex items-center justify-center w-full h-full">
            <div class="bg-white p-7 rounded shadow-lg max-w-md w-full relative">
                <button class="absolute top-2 right-2 text-gray-500 hover:text-black text-3xl p-2"
                    onclick="closeDeleteModal()">&times;</button>
                <h2 class="text-xl font-bold mb-4">Eliminar Docente</h2>
                <p>¿Estás seguro de que deseas eliminar al docente "<span id="docenteNombre"></span>"?</p>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">Aceptar</button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-700">Cancelar</button>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                title: "Éxito!",
                text: "{{ session('success') }}",
                icon: "success",
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @elseif (session('success-update'))
        <script>
            Swal.fire({
                title: "Actualizado!",
                text: "{{ session('success-update') }}",
                icon: "success",
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @elseif (session('success-destroy'))
        <script>
            Swal.fire({
                title: "Eliminado!",
                text: "{{ session('success-destroy') }}",
                icon: "success",
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: "{!! session('error') !!}",
                customClass: {
                    confirmButton: 'bg-red-500 text-white hover:bg-red-600 focus:ring-2 focus:ring-red-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @endif

    <script>
        // Funciones para los modales
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(button) {
            const userJson = button.getAttribute('data-user');
            const user = JSON.parse(userJson);

            // Datos personales
            document.getElementById('edit_name').value = user.persona.name;
            document.getElementById('edit_lastname').value = user.persona.lastname;
            document.getElementById('edit_dni').value = user.persona.dni;
            document.getElementById('edit_phone').value = user.persona.phone;
            document.getElementById('edit_sexo').value = user.persona.sexo;
            document.getElementById('edit_address').value = user.persona.address;

            // Formatear fechas
            const fechaNacimiento = new Date(user.persona.fecha_nacimiento);
            document.getElementById('edit_fecha_nacimiento').value = fechaNacimiento.toISOString().split('T')[0];

            // Datos de usuario
            document.getElementById('edit_email').value = user.email;

            // Datos de docente
            if (user.docente.nivel_educativo_id) {
                document.getElementById('edit_nivel_educativo_id').value = user.docente.nivel_educativo_id;
            }

            // Foto de perfil
            const previewImg = document.getElementById('previewImg');
            if (user.persona.photo) {
                previewImg.src = `/storage/${user.persona.photo}`;
                previewImg.classList.remove('hidden');
                document.getElementById('edit-image-placeholder').classList.add('hidden');
            } else {
                previewImg.src = '';
                previewImg.classList.add('hidden');
                document.getElementById('edit-image-placeholder').classList.remove('hidden');
            }

            // Firma actual
            const previewFirma = document.getElementById('previewFirma');
            const firmaPlaceholder = document.getElementById('firma-placeholder');
            if (user.docente.firma_docente) {
                previewFirma.src = `/storage/${user.docente.firma_docente}`;
                previewFirma.classList.remove('hidden');
                firmaPlaceholder.classList.add('hidden');
            } else {
                previewFirma.src = '';
                previewFirma.classList.add('hidden');
                firmaPlaceholder.classList.remove('hidden');
            }

            // Especialidades seleccionadas
            document.querySelectorAll('input[name="edit_especialidades[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            if (user.docente.especialidades) {
                user.docente.especialidades.forEach(especialidad => {
                    const checkbox = document.getElementById(`edit_especialidad_${especialidad.id_especialidad}`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }

            // Establecer acción del formulario
            document.getElementById('editForm').action = `/docentes/${user.user_id}`;

            // Mostrar modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openDeleteModal(id, nombre) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('docenteNombre').textContent = nombre;
            document.getElementById('deleteForm').action = `/docentes/${id}/delete`;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function openModal(imageUrl, type) {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `<img src="${imageUrl}" class="w-full max-h-[75vh] object-contain">`;
            document.getElementById('archivoModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('archivoModal').classList.add('hidden');
        }

        function buscarDocentes(query) {
            fetch(`/docentes/buscar?search=${query}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const tableBody = doc.querySelector('tbody');
                    document.querySelector('tbody').innerHTML = tableBody.innerHTML;
                })
                .catch(error => console.error('Error:', error));
        }

        // Preview de imagen en edición
        document.getElementById('edit_photo').addEventListener('change', function(e) {
            const previewImg = document.getElementById('previewImg');
            const placeholder = document.getElementById('edit-image-placeholder');

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        // Preview de firma en edición
        document.getElementById('edit_firma_docente').addEventListener('change', function(e) {
            const previewFirma = document.getElementById('previewFirma');
            const firmaPlaceholder = document.getElementById('firma-placeholder');

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewFirma.src = e.target.result;
                    previewFirma.classList.remove('hidden');
                    firmaPlaceholder.classList.add('hidden');
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        // Filtro de especialidades
        function filterEspecialidades() {
            const searchTerm = document.getElementById('searchEspecialidades').value.toLowerCase();
            const items = document.querySelectorAll('.especialidad-item');

            items.forEach(item => {
                const nombre = item.querySelector('.especialidad-nombre').textContent.toLowerCase();
                if (nombre.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filterEditEspecialidades() {
            const searchTerm = document.getElementById('editSearchEspecialidades').value.toLowerCase();
            const items = document.querySelectorAll('.edit-especialidad-item');

            items.forEach(item => {
                const nombre = item.querySelector('.edit-especialidad-nombre').textContent.toLowerCase();
                if (nombre.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Validación para formulario de CREAR
        function validateCreateForm() {
            let isValid = true;

            // Ocultar todos los errores
            hideAllErrors('create');

            // Validar campos requeridos
            const name = document.getElementById('name');
            const lastname = document.getElementById('lastname');
            const dni = document.getElementById('dni');
            const phone = document.getElementById('phone');
            const address = document.getElementById('address');
            const fechaNacimiento = document.getElementById('fecha_nacimiento');
            const nivelEducativo = document.getElementById('nivel_educativo_id');
            const especialidades = document.querySelectorAll('input[name="especialidades[]"]:checked');

            // Validar nombre
            if (!name.value.trim()) {
                showError('name-error', 'El nombre es requerido');
                name.classList.add('border-red-500');
                isValid = false;
            }

            // Validar apellido
            if (!lastname.value.trim()) {
                showError('lastname-error', 'El apellido es requerido');
                lastname.classList.add('border-red-500');
                isValid = false;
            }

            // Validar DNI (8 dígitos)
            const dniRegex = /^\d{8}$/;
            if (!dniRegex.test(dni.value)) {
                showError('dni-error', 'El DNI debe tener exactamente 8 dígitos numéricos');
                dni.classList.add('border-red-500');
                isValid = false;
            }

            // Validar teléfono (9 dígitos)
            const phoneRegex = /^\d{9}$/;
            if (!phoneRegex.test(phone.value)) {
                showError('phone-error', 'El teléfono debe tener exactamente 9 dígitos numéricos');
                phone.classList.add('border-red-500');
                isValid = false;
            }

            // Validar dirección
            if (!address.value.trim()) {
                showError('address-error', 'La dirección es requerida');
                address.classList.add('border-red-500');
                isValid = false;
            }

            // Validar fecha de nacimiento
            if (!fechaNacimiento.value) {
                showError('fecha_nacimiento-error', 'La fecha de nacimiento es requerida');
                fechaNacimiento.classList.add('border-red-500');
                isValid = false;
            }

            // Validar nivel educativo
            if (!nivelEducativo.value) {
                showError('nivel-error', 'Debe seleccionar un nivel educativo');
                nivelEducativo.classList.add('border-red-500');
                isValid = false;
            }

            // Validar especialidades (al menos una)
            if (especialidades.length === 0) {
                showError('especialidades-error', 'Debe seleccionar al menos una especialidad');
                isValid = false;
            }

            // Validar archivos si se seleccionaron
            const foto = document.getElementById('photo');
            const firma = document.getElementById('firma_docente');

            if (foto.files.length > 0) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(foto.files[0].type)) {
                    showError('photo-error', 'La foto debe ser una imagen (JPG, PNG o JPEG)');
                    isValid = false;
                }
            }

            if (firma.files.length > 0) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(firma.files[0].type)) {
                    showError('firma-error', 'La firma debe ser una imagen (JPG, PNG o JPEG)');
                    isValid = false;
                }
            }

            // Si todo es válido, enviar formulario
            if (isValid) {
                document.getElementById('createForm').submit();
            }
        }

        // Validación para formulario de EDITAR
        function validateEditForm() {
            let isValid = true;

            // Ocultar todos los errores
            hideAllErrors('edit');

            // Validar campos requeridos
            const name = document.getElementById('edit_name');
            const lastname = document.getElementById('edit_lastname');
            const dni = document.getElementById('edit_dni');
            const phone = document.getElementById('edit_phone');
            const address = document.getElementById('edit_address');
            const fechaNacimiento = document.getElementById('edit_fecha_nacimiento');
            const nivelEducativo = document.getElementById('edit_nivel_educativo_id');
            const especialidades = document.querySelectorAll('input[name="edit_especialidades[]"]:checked');

            // Validar nombre
            if (!name.value.trim()) {
                showError('edit_name-error', 'El nombre es requerido');
                name.classList.add('border-red-500');
                isValid = false;
            }

            // Validar apellido
            if (!lastname.value.trim()) {
                showError('edit_lastname-error', 'El apellido es requerido');
                lastname.classList.add('border-red-500');
                isValid = false;
            }

            // Validar DNI (8 dígitos)
            const dniRegex = /^\d{8}$/;
            if (!dniRegex.test(dni.value)) {
                showError('edit_dni-error', 'El DNI debe tener exactamente 8 dígitos numéricos');
                dni.classList.add('border-red-500');
                isValid = false;
            }

            // Validar teléfono (9 dígitos)
            const phoneRegex = /^\d{9}$/;
            if (!phoneRegex.test(phone.value)) {
                showError('edit_phone-error', 'El teléfono debe tener exactamente 9 dígitos numéricos');
                phone.classList.add('border-red-500');
                isValid = false;
            }

            // Validar dirección
            if (!address.value.trim()) {
                showError('edit_address-error', 'La dirección es requerida');
                address.classList.add('border-red-500');
                isValid = false;
            }

            // Validar fecha de nacimiento
            if (!fechaNacimiento.value) {
                showError('edit_fecha_nacimiento-error', 'La fecha de nacimiento es requerida');
                fechaNacimiento.classList.add('border-red-500');
                isValid = false;
            }

            // Validar nivel educativo
            if (!nivelEducativo.value) {
                showError('edit_nivel-error', 'Debe seleccionar un nivel educativo');
                nivelEducativo.classList.add('border-red-500');
                isValid = false;
            }

            // Validar especialidades (al menos una)
            if (especialidades.length === 0) {
                showError('edit_especialidades-error', 'Debe seleccionar al menos una especialidad');
                isValid = false;
            }

            // Validar archivos si se seleccionaron
            const foto = document.getElementById('edit_photo');
            const firma = document.getElementById('edit_firma_docente');

            if (foto.files.length > 0) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(foto.files[0].type)) {
                    showError('edit_photo-error', 'La foto debe ser una imagen (JPG, PNG o JPEG)');
                    isValid = false;
                }
            }

            if (firma.files.length > 0) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!allowedTypes.includes(firma.files[0].type)) {
                    showError('edit_firma-error', 'La firma debe ser una imagen (JPG, PNG o JPEG)');
                    isValid = false;
                }
            }

            // Si todo es válido, enviar formulario
            if (isValid) {
                document.getElementById('editForm').submit();
            }
        }

        // Funciones auxiliares
        function showError(elementId, message) {
            const element = document.getElementById(elementId);
            element.textContent = message;
            element.classList.remove('hidden');
        }

        function hideAllErrors(type) {
            const errors = document.querySelectorAll(`[id$="-error"]`);
            errors.forEach(error => {
                if (error.id.startsWith(type === 'create' ? '' : 'edit_')) {
                    error.classList.add('hidden');
                    const inputId = error.id.replace('-error', '');
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.classList.remove('border-red-500');
                    }
                }
            });
        }

        // Validación en tiempo real para DNI y teléfono
        function validateDNI(input) {
            // Solo permite números
            input.value = input.value.replace(/[^0-9]/g, '');

            // Limita a 8 dígitos
            if (input.value.length > 8) {
                input.value = input.value.slice(0, 8);
            }

            // Cambia color según validación
            const errorElement = document.getElementById(input.id + '-error');
            if (input.value.length === 8) {
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
                if (errorElement) errorElement.classList.add('hidden');
            } else if (input.value.length > 0) {
                input.classList.remove('border-green-500');
                input.classList.add('border-red-500');
                if (errorElement) {
                    errorElement.textContent = 'Debe tener 8 dígitos';
                    errorElement.classList.remove('hidden');
                }
            } else {
                input.classList.remove('border-red-500', 'border-green-500');
                if (errorElement) errorElement.classList.add('hidden');
            }
        }

        function validatePhone(input) {
            // Solo permite números
            input.value = input.value.replace(/[^0-9]/g, '');

            // Limita a 9 dígitos
            if (input.value.length > 9) {
                input.value = input.value.slice(0, 9);
            }

            // Cambia color según validación
            const errorElement = document.getElementById(input.id + '-error');
            if (input.value.length === 9) {
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
                if (errorElement) errorElement.classList.add('hidden');
            } else if (input.value.length > 0) {
                input.classList.remove('border-green-500');
                input.classList.add('border-red-500');
                if (errorElement) {
                    errorElement.textContent = 'Debe tener 9 dígitos';
                    errorElement.classList.remove('hidden');
                }
            } else {
                input.classList.remove('border-red-500', 'border-green-500');
                if (errorElement) errorElement.classList.add('hidden');
            }
        }

        // Aplicar validación en tiempo real a los inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Para crear
            const dniCreate = document.getElementById('dni');
            const phoneCreate = document.getElementById('phone');

            if (dniCreate) {
                dniCreate.addEventListener('input', function() {
                    validateDNI(this);
                });
            }

            if (phoneCreate) {
                phoneCreate.addEventListener('input', function() {
                    validatePhone(this);
                });
            }

            // Para editar
            const dniEdit = document.getElementById('edit_dni');
            const phoneEdit = document.getElementById('edit_phone');

            if (dniEdit) {
                dniEdit.addEventListener('input', function() {
                    validateDNI(this);
                });
            }

            if (phoneEdit) {
                phoneEdit.addEventListener('input', function() {
                    validatePhone(this);
                });
            }
        });

        // Filtro de especialidades
        function filterEspecialidades() {
            const searchTerm = document.getElementById('searchEspecialidades').value.toLowerCase();
            const items = document.querySelectorAll('.especialidad-item');

            items.forEach(item => {
                const nombre = item.querySelector('.especialidad-nombre').textContent.toLowerCase();
                if (nombre.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filterEditEspecialidades() {
            const searchTerm = document.getElementById('editSearchEspecialidades').value.toLowerCase();
            const items = document.querySelectorAll('.edit-especialidad-item');

            items.forEach(item => {
                const nombre = item.querySelector('.edit-especialidad-nombre').textContent.toLowerCase();
                if (nombre.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
@endsection
