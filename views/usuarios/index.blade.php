@extends('app')@section('content')
<div class="container-fluid">
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
        @if($usuarios->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else
            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th>Tel. Casa / Cel.</th>
                        <th>Código Postal</th>
                        <th>Puesto</th>
                        <th>Perfil de Usuario</th>
                        @if(Entrust::can('editar_usuarios')|| Entrust::can('eliminar_usuarios'))
                            <th width="50px">Acción</th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                            <?php
                                if($usuario->no_int == ''){$no_int = 'S/N';}else{$no_int = $usuario->no_int;}
                            ?>
                            @if($usuario->id != 1)
                                <tr>
                                    <td>{!! $usuario->nombre !!} {!! $usuario->apellido !!}</td>
                                    <td>{!! $usuario->name !!}</td>
                                    <td>{!! $usuario->email !!}</td>
                                    <td>Col. {!! $usuario->colonia !!}, Calle {!! $usuario->calle !!}, No. Exterior
                                        {!! $usuario->no_ext !!}, No. Interno {!! $no_int !!}, {!! $usuario->estado !!},
                                        {!! $usuario->nombreCiudad !!}.</td>
                                    <td>{!! $usuario->tel !!} / {!! $usuario->cel !!}</td>
                                    <td>{!! $usuario->cp !!}</td>
                                    <td>{!! $usuario->puesto !!}</td>
                                    <td>{!! $usuario->rol !!}</td>
                                    <td>
                                        @if(Entrust::can('editar_usuarios'))
                                            <a href="{!! route('usuarios.edit', [$usuario->id]) !!}" >
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                        @endif
                                        @if(Entrust::can('eliminar_usuarios'))
                                            <a title="Borrar" href="#" data-slug="usuarios" data-id="{!! $usuario->id !!}"
                                               onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/usuarios', '/dr_basico/usuarios', $usuarios->render()) !!}
</div>
@endsection