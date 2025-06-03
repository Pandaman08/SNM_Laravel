@extends('layout.admin.plantilla')

@section('titulo', 'Gestión de Docentes')

@section('contenido')
<div class="card shadow-lg rounded border-0">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">📋 Listado de Docentes</h3>
                <a href="" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nuevo Registro
                </a>
        </div>

        <div class="card-body">

                <!-- Buscador -->
                <div class="row mb-3">
                <div class="col-md-6">
                        <form method="GET" class="input-group">
                        <input class="form-control" type="search" placeholder="🔎 Buscar por descripción" >
                        <div class="input-group-append">
                                <button class="btn btn-success" type="submit"><i class="fas fa-search"></i> Buscar</button>
                        </div>
                        </form>
                </div>
                </div>

                <!-- Mensajes -->
                @if (session('datos'))
                <div id="mensaje" class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                {{ session('datos') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
                </div>
                @endif

                <!-- Tabla -->
                <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="thead-dark">
                        <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Especialidad</th>
                                <th scope="col">Jornada Laboral</th>
                                <th scope="col">Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($docente) <= 0)
                        <tr>
                                <td colspan="3" class="text-muted">No hay registros disponibles.</td>
                        </tr>
                        @else
                        @foreach($docente as $itemdocente)
                        <tr>
                                <td>{{ $itemdocente->codigo_docente}}</td>
                                <td>{{ $itemdocente->especialidad}}</td>
                                <td>{{ $itemdocente->jornada_laboral}}</td>
                                <td>
                                <a href=" " class="btn btn-info btn-sm mx-1">
                                        <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href=" " class="btn btn-danger btn-sm mx-1">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                                </td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-3">
                        {{ $docente->links() }}
                </div>

        </div>

        <div class="card-footer text-muted text-center">
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