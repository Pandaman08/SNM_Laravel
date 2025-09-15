@extends('layout.admin.plantilla')

@section('title', 'Gestión de Usuarios')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#2e5382] mb-2">Gestión de Usuarios</h1>
            <div class="w-24 mx-auto h-1 bg-[#64d423] rounded-full"></div>
            <p class="text-gray-600 mt-3">Administra todos los usuarios del sistema</p>
        </div>

        <!-- Controls Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search Bar -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" placeholder="Buscar por email..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-all duration-200"
                               oninput="buscarUsuarios(this.value)">
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                        <span class="font-medium">Total: {{ $users->total() }}</span>
                    </div>
                    <button class="bg-[#2e5382] text-white px-5 py-2.5 rounded-lg hover:bg-[#1e3a5f] transition-all duration-200 flex items-center space-x-2"
                            onclick="openCreateModal()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Crear Usuario</span>
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
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Usuario</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span>Rol</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Contacto</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Información</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Acciones</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $usuario)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Usuario Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if ($usuario->persona->photo)
                                                <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200" 
                                                     src="{{ Storage::url($usuario->persona->photo) }}" 
                                                     alt="{{ $usuario->persona->name }}">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-[#2e5382] flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($usuario->persona->name, 0, 1)) }}{{ strtoupper(substr($usuario->persona->lastname, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $usuario->persona->name }} {{ $usuario->persona->lastname }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                DNI: {{ $usuario->persona->dni ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Rol -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $usuario->rol === 'admin' ? 'bg-[#2e5382] text-white' : 'bg-gray-100 text-gray-800' }}">
                                        @if($usuario->rol === 'admin')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1zM5.618 4.504a1 1 0 01-.372 1.364L5.016 6l.23.132a1 1 0 11-.992 1.736L3 7.723V8a1 1 0 01-2 0V6a.996.996 0 01.52-.878l1.734-.99a1 1 0 011.364.372zm8.764 0a1 1 0 011.364-.372l1.733.99A1.002 1.002 0 0118 6v2a1 1 0 11-2 0v-.277l-1.254.145a1 1 0 11-.992-1.736L14.984 6l-.23-.132a1 1 0 01-.372-1.364z" clip-rule="evenodd"></path>
                                                <path d="M1 8a1 1 0 011-1h1v1a1 1 0 11-2 0V8zM12 8a1 1 0 011-1h1v1a1 1 0 11-2 0V8zM7 12a1 1 0 011-1h1v1a1 1 0 11-2 0v-1z"></path>
                                            </svg>
                                        @endif
                                      {{ $usuario->rol}}
                                    </span>
                                </td>
                                
                                <!-- Contacto -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="truncate max-w-xs">{{ $usuario->email }}</span>
                                        </div>
                                        @if($usuario->persona->phone)
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $usuario->persona->phone }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Información -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-900">
                                            {{ $usuario->persona->fecha_nacimiento ? \Carbon\Carbon::parse($usuario->persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        @if($usuario->persona->address)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ $usuario->persona->address }}
                                            </div>
                                        @endif
                                        <div class="flex items-center space-x-2">
                                            @if($usuario->persona->sexo)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $usuario->persona->sexo === 'M' ? 'Masculino' : 'Femenino' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Foto -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($usuario->persona->photo)
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-150"
                                                onclick="openModal('{{ Storage::url($usuario->persona->photo) }}', 'image')"
                                                title="Ver foto">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-400">Sin foto</span>
                                    @endif
                                </td>
                                
                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" 
                                                onclick="openEditModal(this)"
                                                data-user='@json($usuario->load('persona'))'
                                                class="inline-flex items-center justify-center w-8 h-8 text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-150"
                                                title="Editar usuario">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>

                                        <button onclick="openDeleteModal({{ $usuario->user_id }}, '{{ $usuario->persona->name }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-150"
                                                title="Eliminar usuario">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3M4 7h16"></path>
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
                Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} resultados
            </div>
            <div class="flex justify-end">
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal de Creación -->
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-5xl w-full max-h-[90vh] overflow-y-auto mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-[#2e5382]">Crear Nuevo Usuario</h2>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeCreateModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda -->
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                            <input type="text" id="name" name="name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                   required>
                        </div>
                        <div>
                            <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Apellido *</label>
                            <input type="text" id="lastname" name="lastname" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                   required>
                        </div>
                        <div id="email-preview" class="text-sm text-gray-600 bg-gray-50 p-2 rounded-lg"></div>
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-700 mb-1">DNI *</label>
                            <input type="text" id="dni" name="dni" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                   required maxlength="8" pattern="[0-9]{8}">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                            <input type="text" id="phone" name="phone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                   required maxlength="9" pattern="[0-9]{9}">
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="space-y-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                            <input type="text" id="address" name="address" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                   required>
                        </div>
                        <div>
                            <label for="sexo" class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
                            <select id="sexo" name="sexo" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors">
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div>
                            <label for="estado_civil" class="block text-sm font-medium text-gray-700 mb-1">Estado Civil</label>
                            <select id="estado_civil" name="estado_civil"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors">
                                <option value="S">Soltero/a</option>
                                <option value="C">Casado/a</option>
                                <option value="D">Divorciado/a</option>
                                <option value="V">Viudo/a</option>
                            </select>
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha Nacimiento *</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                   required>
                        </div>
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Foto de Perfil</label>
                            <input type="file" id="photo" name="photo" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                   accept="image/jpeg,image/png">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#2e5382] text-white rounded-lg hover:bg-[#1e3a5f] transition-colors">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
        <div class="flex items-center justify-center w-full h-full p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-[#2e5382]">Editar Usuario</h2>
                        <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeEditModal()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="editForm" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Columna Izquierda -->
                            <div class="space-y-4">
                                <div>
                                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Nombres *</label>
                                    <input type="text" id="edit_name" name="edit_name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                           required>
                                </div>

                                <div>
                                    <label for="edit_lastname" class="block text-sm font-medium text-gray-700 mb-1">Apellidos *</label>
                                    <input type="text" id="edit_lastname" name="edit_lastname"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                           required>
                                </div>

                                <div>
                                    <label for="edit_dni" class="block text-sm font-medium text-gray-700 mb-1">DNI *</label>
                                    <input type="text" id="edit_dni" name="edit_dni"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                           required maxlength="8" pattern="[0-9]{8}">
                                </div>

                                <div>
                                    <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                                    <input type="text" id="edit_phone" name="edit_phone"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                           required maxlength="9" pattern="[0-9]{9}">
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="space-y-4">
                                <div>
                                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" id="edit_email" name="edit_email"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                           required>
                                </div>

                                <div>
                                    <label for="edit_fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha Nacimiento *</label>
                                    <input type="date" id="edit_fecha_nacimiento" name="edit_fecha_nacimiento"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                           required>
                                </div>

                                <div>
                                    <label for="edit_sexo" class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
                                    <select id="edit_sexo" name="edit_sexo"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors">
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="edit_estado_civil" class="block text-sm font-medium text-gray-700 mb-1">Estado Civil</label>
                                    <select id="edit_estado_civil" name="edit_estado_civil"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors">
                                        <option value="S">Soltero/a</option>
                                        <option value="C">Casado/a</option>
                                        <option value="D">Divorciado/a</option>
                                        <option value="V">Viudo/a</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="edit_address" class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                                    <input type="text" id="edit_address" name="edit_address"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-colors"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Foto -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Perfil</label>
                            <div id="edit-image-upload"
                                 class="border-2 border-dashed border-gray-300 rounded-lg w-full h-48 flex flex-col items-center justify-center cursor-pointer hover:border-[#2e5382] transition-colors relative"
                                 onclick="document.getElementById('edit_photo').click()" 
                                 ondragover="handleDragOver(event)"
                                 ondrop="handleDrop(event, 'edit_photo')">

                                <div id="edit-image-placeholder" class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-gray-500">Selecciona o arrastra una imagen</p>
                                    <p class="text-xs text-gray-400 mt-1">(PNG, JPEG, JPG)</p>
                                </div>

                                <img id="previewImg" src="" alt="Vista previa"
                                     class="hidden w-full h-full object-cover rounded-lg">

                                <button type="button" id="edit_remove_image"
                                        class="hidden absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors"
                                        onclick="removeImageEdit(event)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3M4 7h16"></path>
                                    </svg>
                                </button>
                                
                                <input type="file" id="edit_photo" name="photo" class="hidden"
                                       accept="image/png, image/jpeg, image/jpg" onchange="previewImageEdit(event)">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                            <button type="button" onclick="closeEditModal()"
                                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 bg-[#2e5382] text-white rounded-lg hover:bg-[#1e3a5f] transition-colors">
                                Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 w-full h-full">
        <div class="flex items-center justify-center w-full h-full p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Eliminar Usuario</h3>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeDeleteModal()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        ¿Estás seguro de que deseas eliminar al usuario "<span id="usuarioNombre" class="font-semibold"></span>"? 
                        Esta acción no se puede deshacer.
                    </p>
                    
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeDeleteModal()"
                                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar imágenes -->
    <div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl max-h-[90vh] overflow-hidden mx-4">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Foto de Perfil</h3>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="p-4"></div>
        </div>
    </div>

    <!-- Scripts de SweetAlert -->
    @if (session('success-update'))
        <script>
            Swal.fire({
                title: "¡Actualizado!",
                text: "{{ session('success-update') }}",
                icon: "success",
                confirmButtonColor: '#2e5382',
                customClass: {
                    confirmButton: 'bg-[#2e5382] text-white hover:bg-[#1e3a5f] focus:ring-2 focus:ring-blue-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @elseif (session('success'))
        <script>
            Swal.fire({
                title: "¡Registrado!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: '#2e5382',
                customClass: {
                    confirmButton: 'bg-[#2e5382] text-white hover:bg-[#1e3a5f] focus:ring-2 focus:ring-blue-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @elseif (session('success-destroy'))
        <script>
            Swal.fire({
                title: "¡Eliminado!",
                text: "{{ session('success-destroy') }}",
                icon: "success",
                confirmButtonColor: '#2e5382',
                customClass: {
                    confirmButton: 'bg-[#2e5382] text-white hover:bg-[#1e3a5f] focus:ring-2 focus:ring-blue-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Hubo un error!',
                html: "{!! session('error') !!}",
                showConfirmButton: true,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc2626',
                customClass: {
                    confirmButton: 'bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-300 rounded-lg py-2 px-4'
                }
            });
        </script>
    @endif

    <script>
        // Función para abrir modal de creación
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');

            // Mostrar preview del email generado
            const nameInput = document.getElementById('name');
            const lastnameInput = document.getElementById('lastname');
            const emailPreview = document.getElementById('email-preview');

            const updateEmailPreview = () => {
                const name = nameInput.value.toLowerCase().trim();
                const lastname = lastnameInput.value.toLowerCase().trim();
                if (name && lastname) {
                    const randomNumbers = Math.floor(1000 + Math.random() * 9000);
                    emailPreview.innerHTML = `<strong>Email que se generará:</strong> ${name}${lastname.charAt(0)}_${randomNumbers}@bruning.com`;
                    emailPreview.classList.add('border', 'border-gray-200');
                } else {
                    emailPreview.textContent = '';
                    emailPreview.classList.remove('border', 'border-gray-200');
                }
            };

            nameInput.addEventListener('input', updateEmailPreview);
            lastnameInput.addEventListener('input', updateEmailPreview);
        }

        // Función para cerrar modal de creación
        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.querySelector('#createModal form').reset();
            document.getElementById('email-preview').textContent = '';
        }

        // Función para abrir modal de edición
        function openEditModal(button) {
            const user = JSON.parse(button.getAttribute('data-user'));

            // Llenar campos del formulario
            document.getElementById('edit_name').value = user.persona.name || '';
            document.getElementById('edit_lastname').value = user.persona.lastname || '';
            document.getElementById('edit_dni').value = user.persona.dni || '';
            document.getElementById('edit_phone').value = user.persona.phone || '';
            document.getElementById('edit_sexo').value = user.persona.sexo || 'M';
            document.getElementById('edit_estado_civil').value = user.persona.estado_civil || 'S';
            document.getElementById('edit_address').value = user.persona.address || '';
            document.getElementById('edit_email').value = user.email || '';

            // Manejar fecha de nacimiento
            if (user.persona.fecha_nacimiento) {
                const fechaNacimiento = new Date(user.persona.fecha_nacimiento);
                const formattedDate = fechaNacimiento.toISOString().split('T')[0];
                document.getElementById('edit_fecha_nacimiento').value = formattedDate;
            }

            // Manejar la foto de perfil
            const previewImg = document.getElementById('previewImg');
            const editImagePlaceholder = document.getElementById('edit-image-placeholder');
            const removeBtn = document.getElementById('edit_remove_image');

            if (user.persona.photo) {
                previewImg.src = `/storage/${user.persona.photo}`;
                previewImg.classList.remove('hidden');
                editImagePlaceholder.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            } else {
                previewImg.src = "";
                previewImg.classList.add('hidden');
                editImagePlaceholder.classList.remove('hidden');
                removeBtn.classList.add('hidden');
            }

            // Establecer la acción del formulario
            document.getElementById('editForm').action = `/users/${user.user_id}`;

            // Mostrar el modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Función para cerrar modal de edición
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editForm').reset();
            removeImageEdit();
        }

        // Función para preview de imagen en edición
        function previewImageEdit(event) {
            const input = event.target;
            const previewImage = document.getElementById("previewImg");
            const imagePlaceholder = document.getElementById("edit-image-placeholder");
            const removeBtn = document.getElementById("edit_remove_image");

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten archivos PNG, JPEG y JPG.',
                        confirmButtonColor: '#2e5382'
                    });
                    input.value = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    imagePlaceholder.classList.add('hidden');
                    removeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Función para remover imagen en edición
        function removeImageEdit(event) {
            if (event) event.stopPropagation();
            
            const imageInput = document.getElementById("edit_photo");
            imageInput.value = "";
            const previewImage = document.getElementById("previewImg");
            previewImage.src = "";
            previewImage.classList.add('hidden');
            document.getElementById("edit-image-placeholder").classList.remove('hidden');
            document.getElementById("edit_remove_image").classList.add('hidden');
        }

        // Funciones para drag and drop
        function handleDragOver(event) {
            event.preventDefault();
            event.currentTarget.classList.add('border-[#2e5382]');
        }

        function handleDrop(event, inputId) {
            event.preventDefault();
            event.currentTarget.classList.remove('border-[#2e5382]');
            
            const inputElement = document.getElementById(inputId);
            if (event.dataTransfer.files && event.dataTransfer.files[0]) {
                inputElement.files = event.dataTransfer.files;
                if (inputId === 'edit_photo') {
                    previewImageEdit({ target: inputElement });
                }
            }
        }

        // Función para abrir modal de eliminación
        function openDeleteModal(id, firstname) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('usuarioNombre').textContent = firstname;
            document.getElementById('deleteForm').action = `/users/${id}/delete`;
        }

        // Función para cerrar modal de eliminación
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Función de búsqueda
        function buscarUsuarios(query) {
            fetch(`/users/buscar?search=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableBody = doc.querySelector('tbody');
                
                if (newTableBody) {
                    const currentTableBody = document.querySelector('tbody');
                    currentTableBody.innerHTML = newTableBody.innerHTML;
                }
            })
            .catch(error => {
                console.error('Error en la búsqueda:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo realizar la búsqueda. Intente nuevamente.',
                    confirmButtonColor: '#2e5382'
                });
            });
        }

        // Función para abrir modal de visualización de imagen
        function openModal(imageUrl, type) {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `<img src="${imageUrl}" class="max-w-full max-h-[70vh] object-contain mx-auto rounded-lg">`;
            document.getElementById('archivoModal').classList.remove('hidden');
        }

        // Función para cerrar modal de visualización
        function closeModal() {
            document.getElementById('archivoModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = '';
        }

        // Cerrar modales con tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCreateModal();
                closeEditModal();
                closeDeleteModal();
                closeModal();
            }
        });

        // Cerrar modales al hacer click fuera
        document.addEventListener('click', function(event) {
            const modals = ['createModal', 'editModal', 'deleteModal', 'archivoModal'];
            
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    if (modalId === 'createModal') closeCreateModal();
                    else if (modalId === 'editModal') closeEditModal();
                    else if (modalId === 'deleteModal') closeDeleteModal();
                    else if (modalId === 'archivoModal') closeModal();
                }
            });
        });
    </script>
@endsection