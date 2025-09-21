@extends('layout.admin.plantilla')
@section('title', 'Gestión de Personas')
@section('contenido')

<div class="max-w-screen-2xl mx-auto my-8 px-4">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-[#2e5382] mb-2">Gestión de Tutores</h1>
        <div class="w-24 mx-auto h-1 bg-[#64d423] rounded-full"></div>
        <p class="text-gray-600 mt-3">Administra las solicitudes de registro de tutores</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->where('estado', false)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Approved -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aprobados</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $users->where('estado', true)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                    <option value="">Todos los estados</option>
                    <option value="pending">Pendientes</option>
                    <option value="approved">Aprobados</option>
                </select>
                
                <select id="relationshipFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2e5382] focus:border-transparent">
                    <option value="">Todos los parentescos</option>
                    <option value="padre">Padre</option>
                    <option value="madre">Madre</option>
                    <option value="abuelo">Abuelo/a</option>
                    <option value="tutor">Tutor Legal</option>
                </select>
            </div>
            
            <div class="text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                <span class="font-medium">Total: {{ $users->total() }}</span>
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
                                <span>Tutor</span>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Estado</span>
                            </div>
                        </th>
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
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150" data-status="{{ $user->estado ? 'approved' : 'pending' }}" data-relationship="{{ strtolower($user->tutor->parentesco) }}">
                            <!-- Tutor Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if ($user->persona->photo)
                                            <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200" 
                                                 src="{{ Storage::url($user->persona->photo) }}" 
                                                 alt="{{ $user->persona->name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-[#2e5382] flex items-center justify-center text-white font-semibold">
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
                            
                            <!-- Contact Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="flex items-center text-sm text-gray-900">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="truncate max-w-xs">{{ $user->email }}</span>
                                    </div>
                                    @if($user->persona->phone)
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            {{ $user->persona->phone }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Additional Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ 
                                                strtolower($user->tutor->parentesco) === 'padre' ? 'bg-blue-100 text-blue-800' :
                                                (strtolower($user->tutor->parentesco) === 'madre' ? 'bg-pink-100 text-pink-800' :
                                                (strtolower($user->tutor->parentesco) === 'abuelo' ? 'bg-purple-100 text-purple-800' :
                                                'bg-gray-100 text-gray-800'))
                                            }}">
                                            @switch(strtolower($user->tutor->parentesco))
                                                @case('padre')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    @break
                                                @case('madre')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    @break
                                                @default
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                            @endswitch
                                            {{ ucfirst($user->tutor->parentesco) }}
                                        </span>
                                    </div>
                                    @if($user->persona->address)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">
                                            <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $user->persona->address }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Photo -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($user->persona->photo)
                                    <button class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-150"
                                            onclick="openModal('{{ Storage::url($user->persona->photo) }}', 'image')"
                                            title="Ver foto">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-sm text-gray-400">Sin foto</span>
                                @endif
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if (!$user->estado)
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                                        Pendiente
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                        Aprobado
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if (!$user->estado)
                                        <!-- Approve Button -->
                                        <form action="{{ route('person.approve', $user->user_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-lg transition-all duration-150"
                                                    onclick="return confirm('¿Está seguro de que desea aprobar este tutor?')"
                                                    title="Aprobar tutor">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Reject Button -->
                                        <button type="button" 
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-150"
                                                onclick="openRejectModal({{ $user->user_id }})"
                                                title="Rechazar tutor">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <!-- View Details Button -->
                                        <button type="button" 
                                                class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-150"
                                                onclick="viewTutorDetails({{ $user->user_id }})"
                                                title="Ver detalles">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('person.destroy_person', $user->user_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-150"
                                                    onclick="return confirm('¿Está seguro de que desea eliminar este tutor? Esta acción no se puede deshacer.')"
                                                    title="Eliminar tutor">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
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

<!-- Modal para Ver Imagen -->
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

<!-- Modal de Rechazo -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Rechazar Solicitud</h3>
                </div>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeRejectModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del rechazo <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="reason" 
                        id="reason" 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                        placeholder="Explique detalladamente el motivo del rechazo..."
                        required
                        maxlength="500"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            onclick="closeRejectModal()">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                            onclick="return confirm('¿Está seguro de rechazar esta solicitud? Esta acción no se puede deshacer.')">
                        Rechazar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Detalles del Tutor -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-xl font-bold text-[#2e5382]">Detalles del Tutor</h3>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeDetailsModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="tutorDetailsContent" class="space-y-4">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Scripts de SweetAlert -->
@if (session('success-approve'))
    <script>
        Swal.fire({
            title: "¡Aprobado Exitosamente!",
            text: "{{ session('success-approve') }}",
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
@elseif (session('success-reject'))
    <script>
        Swal.fire({
            title: "Solicitud Rechazada",
            text: "{{ session('success-reject') }}",
            icon: "info",
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
    let currentUserId = null;

    // Función para abrir modal de imagen
    function openModal(imageUrl, type) {
        const modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `<img src="${imageUrl}" class="max-w-full max-h-[70vh] object-contain mx-auto rounded-lg">`;
        document.getElementById('archivoModal').classList.remove('hidden');
    }

    // Función para cerrar modal de imagen
    function closeModal() {
        document.getElementById('archivoModal').classList.add('hidden');
        document.getElementById('modalContent').innerHTML = '';
    }

    // Función para abrir modal de rechazo
    function openRejectModal(userId) {
        currentUserId = userId;
        document.getElementById('rejectForm').action = `/tutores/${userId}/reject`; 
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('reason').value = '';
        document.getElementById('reason').focus();
    }

    // Función para cerrar modal de rechazo
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        currentUserId = null;
    }

    // Función para ver detalles del tutor
    function viewTutorDetails(userId) {
        // Esta función se puede expandir para mostrar más detalles del tutor
        // Por ahora, muestra información básica del tutor
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (row) {
            const tutorName = row.querySelector('td:first-child .font-semibold').textContent;
            const email = row.querySelector('td:nth-child(2) .truncate').textContent;
            const phone = row.querySelector('td:nth-child(2) .text-gray-500')?.textContent || 'No disponible';
            const parentesco = row.querySelector('td:nth-child(3) .rounded-full').textContent;
            
            document.getElementById('tutorDetailsContent').innerHTML = `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Información Personal</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Nombre:</span>
                            <span class="ml-2 font-medium">${tutorName}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Parentesco:</span>
                            <span class="ml-2 font-medium">${parentesco}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <span class="ml-2 font-medium">${email}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Teléfono:</span>
                            <span class="ml-2 font-medium">${phone}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="font-semibold text-green-900 mb-2">Estado</h4>
                    <p class="text-sm text-green-700">Este tutor ha sido aprobado y puede acceder al sistema.</p>
                </div>
            `;
            document.getElementById('detailsModal').classList.remove('hidden');
        }
    }

    // Función para cerrar modal de detalles
    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }

    // Filtros
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('relationshipFilter').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const statusFilter = document.getElementById('statusFilter').value;
        const relationshipFilter = document.getElementById('relationshipFilter').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const relationship = row.getAttribute('data-relationship');
            
            let showRow = true;

            // Filter by status
            if (statusFilter && statusFilter !== '') {
                if (statusFilter === 'pending' && status !== 'pending') showRow = false;
                if (statusFilter === 'approved' && status !== 'approved') showRow = false;
            }

            // Filter by relationship
            if (relationshipFilter && relationshipFilter !== '' && relationship !== relationshipFilter) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        });
    }

    // Cerrar modales con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeRejectModal();
            closeDetailsModal();
        }
    });

    // Cerrar modales al hacer clic fuera
    document.addEventListener('click', function(event) {
        const modals = [
            { id: 'archivoModal', closeFunc: closeModal },
            { id: 'rejectModal', closeFunc: closeRejectModal },
            { id: 'detailsModal', closeFunc: closeDetailsModal }
        ];
        
        modals.forEach(modal => {
            const modalElement = document.getElementById(modal.id);
            if (event.target === modalElement) {
                modal.closeFunc();
            }
        });
    });

    // Animaciones de entrada para las filas
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });
</script>

@endsection