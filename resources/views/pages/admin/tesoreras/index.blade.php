@extends('layout.admin.plantilla')

@section('title', 'Gestión de Secretarias')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#2e5382] mb-2">Gestión de Secretarias</h1>
            <div class="w-24 mx-auto h-1 bg-[#64d423] rounded-full"></div>
            <p class="text-gray-600 mt-3">Administra todas las secretarias del sistema</p>
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
                        <input type="text" id="search" placeholder="Buscar por nombre o email..." maxlength="8"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent transition-all duration-200"
                            oninput="buscarSecretarias(this.value)">
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
                        <span>Registrar Secretaria</span>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Secretaria</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Contacto</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Información</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Foto</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <!-- Secretaria Info -->
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

                                <!-- Información -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-900">
                                            {{ $user->persona->fecha_nacimiento ? \Carbon\Carbon::parse($user->persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        @if ($user->persona->address)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ $user->persona->address }}
                                            </div>
                                        @endif
                                        <div class="flex items-center space-x-2">
                                            @if ($user->persona->sexo)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $user->persona->sexo === 'M' ? 'Masculino' : 'Femenino' }}
                                                </span>
                                            @endif
                                        </div>
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

                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" onclick="openEditModal(this)"
                                            data-user='@json($user->load(["persona", "secretaria"]))'
                                            class="inline-flex items-center justify-center w-8 h-8 text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-150"
                                            title="Editar secretaria">
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
                                            title="Eliminar secretaria">
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

    <!-- Modal para visualizar imágenes -->
    <div id="archivoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl max-h-[90vh] overflow-hidden mx-4">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Foto de Perfil</h3>
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
        <h2 class="text-xl font-bold mb-4">Registrar Nueva Secretaria</h2>
        <form action="{{ route('secretarias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna 1 -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block">Nombre </label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded" 
                               required  >
                    </div>
                    <div>
                        <label for="lastname" class="block">Apellido </label>
                        <input type="text" id="lastname" name="lastname" class="w-full px-4 py-2 border rounded" 
                               required >
                    </div>
                    <div>
                        <label for="dni" class="block">DNI (8 dígitos)</label>
                        <input type="text" id="dni" name="dni" class="w-full px-4 py-2 border rounded" 
                               required maxlength="8" pattern="[0-9]{8}">
                    </div>
                    <div>
                        <label for="phone" class="block">Teléfono (9 dígitos)</label>
                        <input type="text" id="phone" name="phone" class="w-full px-4 py-2 border rounded" 
                               required maxlength="9" pattern="[0-9]{9}">
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
                        <input type="text" id="address" name="address" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="fecha_nacimiento" class="block">Fecha Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="area_responsabilidad" class="block">Área de Responsabilidad</label>
                        <input type="text" id="area_responsabilidad" name="area_responsabilidad" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="jornada_laboral" class="block">Jornada Laboral (horas)</label>
                        <input type="number" step="0.1" id="jornada_laboral" name="jornada_laboral" class="w-full px-4 py-2 border rounded" required>
                    </div>
                </div>
            </div>

            <!-- Fila adicional -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label for="fecha_contratacion" class="block">Fecha de Contratación</label>
                    <input type="date" id="fecha_contratacion" name="fecha_contratacion" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div>
                    <label for="photo" class="block">Foto de Perfil</label>
                    <input type="file" id="photo" name="photo" class="w-full px-2 py-1 border rounded" accept="image/*">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Registrar Secretaria
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
                <h2 class="text-2xl font-semibold text-blue-800">Editar Secretaria</h2>
                <div class="mx-auto mt-2 w-1/5 h-1 bg-green-400"></div>
            </div>

            <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda -->
                    <div class="space-y-4">
                        <div>
                            <label for="edit_name" class="block text-gray-700">Nombres</label>
                            <input type="text" id="edit_name" name="edit_name" 
                                   class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_lastname" class="block text-gray-700">Apellidos </label>
                            <input type="text" id="edit_lastname" name="edit_lastname" 
                                   class="w-full px-4 py-2 border rounded" required >
                        </div>
                        <div>
                            <label for="edit_dni" class="block text-gray-700">DNI:</label>
                            <input type="text" id="edit_dni" name="edit_dni" 
                                   class="w-full px-4 py-2 border rounded" required maxlength="8">
                        </div>
                        <div>
                            <label for="edit_phone" class="block text-gray-700">Teléfono:</label>
                            <input type="text" id="edit_phone" name="edit_phone" 
                                   class="w-full px-4 py-2 border rounded" required maxlength="9">
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
                            <input type="email" id="edit_email" name="edit_email" class="w-full px-4 py-2 border rounded bg-gray-100" readonly>
                        </div>
                        <div>
                            <label for="edit_fecha_nacimiento" class="block text-gray-700">Fecha Nacimiento:</label>
                            <input type="date" id="edit_fecha_nacimiento" name="edit_fecha_nacimiento" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_address" class="block text-gray-700">Dirección:</label>
                            <input type="text" id="edit_address" name="edit_address" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_area_responsabilidad" class="block text-gray-700">Área de Responsabilidad:</label>
                            <input type="text" id="edit_area_responsabilidad" name="edit_area_responsabilidad" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div>
                            <label for="edit_jornada_laboral" class="block text-gray-700">Jornada Laboral (horas):</label>
                            <input type="number" step="0.1" id="edit_jornada_laboral" name="edit_jornada_laboral" class="w-full px-4 py-2 border rounded" required>
                        </div>
                    </div>
                </div>

                <!-- Segunda fila de campos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label for="edit_fecha_contratacion" class="block text-gray-700">Fecha de Contratación:</label>
                        <input type="date" id="edit_fecha_contratacion" name="edit_fecha_contratacion" class="w-full px-4 py-2 border rounded" required>
                    </div>
                </div>

                <!-- Foto de perfil -->
                <div class="mt-6">
                    <label class="block text-gray-700 mb-1">Foto de Perfil:</label>
                    <div id="edit-image-upload" class="border-2 border-dashed border-gray-300 w-full h-52 flex flex-col items-center justify-center cursor-pointer relative text-center rounded-md" onclick="document.getElementById('edit_photo').click()">
                        <span id="edit-image-placeholder" class="text-gray-500 flex flex-col items-center">
                            <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            Selecciona o arrastra una imagen
                        </span>
                        <img id="previewImg" src="" alt="Vista previa" class="hidden w-52 h-full object-cover rounded shadow mx-auto">
                        <input type="file" id="edit_photo" name="edit_photo" class="hidden" accept="image/*">
                    </div>
                </div>

                <div class="flex justify-center gap-4 mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Actualizar
                    </button>
                    <button type="button" onclick="closeEditModal()" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">
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
            <h2 class="text-xl font-bold mb-4">Eliminar Secretaria</h2>
            <p>¿Estás seguro de que deseas eliminar a la secretaria "<span id="secretariaNombre"></span>"?</p>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-700">Aceptar</button>
                <button type="button" onclick="closeDeleteModal()" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-700">Cancelar</button>
            </form>
        </div>
    </div>
</div>
uiui

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
    const user = JSON.parse(button.getAttribute('data-user'));
    
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
    
    // Datos de secretaria
    document.getElementById('edit_area_responsabilidad').value = user.secretaria.area_responsabilidad;
    document.getElementById('edit_jornada_laboral').value = user.secretaria.jornada_laboral;
    
    const fechaContratacion = new Date(user.secretaria.fecha_contratacion);
    document.getElementById('edit_fecha_contratacion').value = fechaContratacion.toISOString().split('T')[0];
    
    // Foto de perfil
    const previewImg = document.getElementById('previewImg');
    if(user.persona.photo) {
        previewImg.src = `/storage/${user.persona.photo}`;
        previewImg.classList.remove('hidden');
        document.getElementById('edit-image-placeholder').classList.add('hidden');
    } else {
        previewImg.src = '';
        previewImg.classList.add('hidden');
        document.getElementById('edit-image-placeholder').classList.remove('hidden');
    }
    
    // Establecer acción del formulario
    document.getElementById('editForm').action = `/secretarias/${user.user_id}`;
    
    // Mostrar modal
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openDeleteModal(id, nombre) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('secretariaNombre').textContent = nombre;
    document.getElementById('deleteForm').action = `/secretarias/${id}/delete`;
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

function buscarSecretarias(query) {
    fetch(`/secretarias/buscar?search=${query}`, {
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


</script>
@endsection