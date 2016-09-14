@extends('app')@section('content')
    <div class="container">
        @include('flash::message')

        <div class="row">
            <h1 class="pull-left">Perfiles de Usuarios</h1>
            @if(Entrust::can('crear_roles'))
                <a class="btn btn-primary pull-right alta" style="margin-top: 25px" href="{!! route('roles.create') !!}">Agregar</a>
            @endif
        </div>

        <div class="row tabla table-responsive">
            @if($roles->isEmpty())
                <div class="well text-center">No se encontraron perfiles de usuario.</div>
            @else
                <table class="table">
                    <thead>
                    {{--<th>Nombre</th>--}}
                    <th>Perfil</th>
                    <th>Descripción</th>
                    @if ((Entrust::can('editar_roles')) or (Entrust::can('eliminar_roles')))
                        <th width="50px">Acción</th>
                    @endif
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        @if ($role->id != 1)
                            <tr>
                                <td>{!! $role->display_name !!}</td>
                                <td>{!! $role->description !!}</td>
                                <td>
                                    @if(Entrust::can('editar_roles'))
                                        <a href="{!! route('roles.edit', [$role->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                                    @endif
                                    @if(Entrust::can('eliminar_roles'))
                                        <a title="Borrar" href="#" data-slug="roles" data-id="{!! $role->id !!}" onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        {!!  str_replace('/roles', '/dr_basico/roles', $roles->render()) !!}
    </div>
@endsection