@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Periodos')

@section('contenido')
    <div class="max-w-screen-2xl mx-auto my-8 px-4">
        <!-- Encabezado mejorado -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#2e5382]">Gestión de Periodos Académicos</h1>
            <div class="w-1/4 mx-auto h-1 bg-gradient-to-r from-[#98C560] to-[#2e5382] rounded-full mt-2"></div>
            <p class="text-gray-600 mt-2">Administra los periodos académicos del sistema</p>
        </div>

        <!-- Barra de búsqueda y acciones -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <form method="GET" class="flex w-full md:w-1/2">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input name="buscarpor" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-[#98C560] focus:border-[#98C560]" 
                        type="search" placeholder="Buscar por nombre, año o fechas..." value="{{ $buscarpor }}">
                </div>
                <button class="ml-2 px-4 py-2 bg-[#98C560] text-white rounded-lg hover:bg-[#7aa94f] transition duration-200 flex items-center" type="submit">
                    <span class="hidden md:inline">Buscar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>

            <a href="{{route('periodos.create')}}" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-sm font-medium transition duration-200 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nuevo Periodo
            </a>
        </div>

        <!-- Tarjeta de tabla -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-[#2e5382] text-white">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">Nombre</th>
                            <th class="px-6 py-3 text-left font-medium">Año Escolar</th>
                            <th class="px-6 py-3 text-left font-medium">Fecha Inicio</th>
                            <th class="px-6 py-3 text-left font-medium">Fecha Fin</th>
                            <th class="px-6 py-3 text-center font-medium">Estado</th>
                            <th class="px-6 py-3 text-center font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($periodos as $periodo)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#98C560] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $periodo->nombre }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $periodo->anioEscolar ? $periodo->anioEscolar->anio : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $now = now();
                                        $start = \Carbon\Carbon::parse($periodo->fecha_inicio);
                                        $end = \Carbon\Carbon::parse($periodo->fecha_fin);
                                        
                                        if ($now->between($start, $end)) {
                                            $status = 'Activo';
                                            $color = 'bg-green-100 text-green-800';
                                        } elseif ($now->lt($start)) {
                                            $status = 'Pendiente';
                                            $color = 'bg-yellow-100 text-yellow-800';
                                        } else {
                                            $status = 'Finalizado';
                                            $color = 'bg-gray-100 text-gray-800';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{route('periodos.edit',$periodo->id_periodo)}}" 
                                           class="text-blue-600 hover:text-blue-800 transition duration-200 p-1 rounded-full hover:bg-blue-50"
                                           title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <button onclick="confirmDelete({{ $periodo->id_periodo }}, '{{ $periodo->nombre }}')"
                                                class="text-red-600 hover:text-red-800 transition duration-200 p-1 rounded-full hover:bg-red-50"
                                                title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="mt-2 text-lg font-medium">No se encontraron periodos</p>
                                        <p class="text-sm">Intenta con otros parámetros de búsqueda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        @if($periodos->hasPages())
        <div class="mt-6 bg-white p-4 rounded-lg shadow-sm">
            {{ $periodos->links('pagination::tailwind') }}
        </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    function confirmDelete(id, nombre) {
        Swal.fire({
            title: '¿Eliminar Periodo?',
            html: `Estás a punto de eliminar el periodo <b>${nombre}</b>. Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            backdrop: `
                rgba(0,0,0,0.7)
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ffffff'%3E%3Cpath d='M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'/%3E%3C/svg%3E")
                center top
                no-repeat
            `
        }).then((result) => {
            if (result.isConfirmed) {
                // Crear formulario dinámico para enviar la solicitud DELETE
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/periodos/${id}/delete`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Mostrar notificaciones de sesión
    @if(session('success'))
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#98C560',
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    @elseif(session('success-update'))
        Swal.fire({
            title: '¡Actualizado!',
            text: '{{ session('success-update') }}',
            icon: 'success',
            confirmButtonColor: '#98C560',
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    @elseif(session('success-destroy'))
        Swal.fire({
            title: '¡Eliminado!',
            text: '{{ session('success-destroy') }}',
            icon: 'success',
            confirmButtonColor: '#98C560',
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    @elseif(session('error'))
        Swal.fire({
            title: '¡Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#d33',
            timer: 4000,
            timerProgressBar: true
        });
    @endif
</script>
@endsection