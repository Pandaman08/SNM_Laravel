@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Competencia')

@section('contenido')
<div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
    <!-- Encabezado -->
    <div class="bg-green-600 text-white flex justify-between items-center px-6 py-4">
        <h3 class="text-lg font-semibold m-0">📋 Listado de Competencia</h3>
        <a href="" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center">
            <i class="fas fa-plus-circle mr-2"></i> Nuevo Registro
        </a>
    </div>

    <div class="p-6">
        <!-- Buscador -->
        <div class="mb-4">
            <form method="GET" class="flex max-w-md space-x-2">
                <input class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" type="search" placeholder="🔎 Buscar por descripción">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded" type="submit">
                    <i class="fas fa-search mr-1"></i> Buscar
                </button>
            </form>
        </div>

        <!-- Mensajes -->
        @if (session('datos'))
        <div id="mensaje" class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-center mb-4" role="alert">
            {{ session('datos') }}
            <button type="button" class="absolute top-0 right-0 mt-2 mr-4 text-yellow-700" onclick="this.parentElement.remove()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 text-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">Código</th>
                        <th class="px-4 py-2">Codigo Asignatura</th>
                        <th class="px-4 py-2">Descripcion</th>
                        <th class="px-4 py-2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($docente) <= 0)
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-gray-500">No hay registros disponibles.</td>
                    </tr>
                    @else
                    @foreach($competencia as $itemcompetencia)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $itemdocente->codigo_docente }}</td>
                        <td class="px-4 py-2">{{ $itemdocente->especialidad }}</td>
                        <td class="px-4 py-2">{{ $itemdocente->jornada_laboral }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </a>
                            <a href="" class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded">
                                <i class="fas fa-trash-alt mr-1"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="flex justify-center mt-6">
            {{ $docente->links() }}
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-100 text-center text-sm text-gray-600 py-3">
        © {{ date('Y') }} Sistema de Gestión de Docentes
    </div>
</div>
@endsection

@section('script')
<script>
    setTimeout(function () {
        let mensaje = document.querySelector('#mensaje');
        if (mensaje) mensaje.remove();
    }, 2500);
</script>
@endsection
