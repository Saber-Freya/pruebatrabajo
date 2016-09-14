@extends('app')@section('content')
<div class="container">
    @include('flash::message')
    <div class="row">
            <h2 class="text-center">Usuarios</h2>
        @if(Entrust::can('crear_usuarios'))
            <a class="btn btn-primary pull-right" href="{!! route('usuarios.create') !!}">
                <i class = "fa fa-user-plus"></i> Agregar Usuario
            </a>
        @endif
    </div>

    <div class="row">
        <div class="row tabla table-responsive">
            @if($usuarios->isEmpty())
                <div class="well text-center">No hay registros.</div>
            @else
                <table class="table">
                    <thead>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Perfil de Usuario</th>
                    <th width="50px">Acción</th>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                            @if($usuario->id != 1)
                                <tr>
                                    <td>{!! $usuario->name !!}</td>
                                    <td>{!! $usuario->email !!}</td>
                                    <td>{!! $usuario->rol !!}</td>
                                    <td>
                                        @if(Entrust::can('editar_usuarios'))
                                            <a href="{!! route('usuarios.edit', [$usuario->id]) !!}" >
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                        @endif
                                        @if(Entrust::can('eliminar_usuarios'))
                                            <a title="Borrar" href="#" data-slug="usuarios" data-id="{!! $usuario->id !!}"
                                               onclick="return borrarElemento(this)"><i class="glyphicon glyphicon-remove"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection