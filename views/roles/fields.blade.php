<div class = "row">
    <div class = "col-xs-12 col-md-4">
        <h1 class = "page-header">Perfil de Usuario</h1>
    </div>
</div>

<!--- Display Name Field --->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('display_name', 'Nombre del Perfil:') !!}
    {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
</div>
<!--- Description Field --->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('description', 'Descripción:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<div class="col-xs-12">
    <div class="col-sm-4 col-xs-12">
        <div class="pad0 col-xs-12 text-center">Módulos</div>
        <ul class="col-xs-12 modulitos">
            <li class="modi active" modulo="todos">Ver Todos los permisos</li>
            @foreach($modulos as $modulo)
                <?php
                $nuevo= explode(' ', $modulo->display_name);
                unset($nuevo[0]);
                $nuevo = implode(" ", $nuevo);
                ?>
                <li class="modi" modulo="{!! $modulo->modulo !!}">
                    {!! $modulo->modulo !!} {{--Si se requiere sacar del Display entonces se pone $nuevo--}}
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-sm-8 col-xs-12">
        <select id="select-permisos" multiple="multiple">
            @if(isset($todos))
                <?php
                if(isset($permisos)){
                    $per_asignados = array();
                    foreach($permisos as $per){
                        array_push($per_asignados, $per->id);
                    }
                }
                ?>
                @foreach($todos as $permiso)
                    <option value="{!! $permiso->id !!}" modulo="{!! $permiso->modulo!!}" id_permiso="{!! $permiso->id !!}" @if(isset($permisos) && in_array($permiso->id, $per_asignados)) selected @endif>{!! $permiso->display_name !!}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
@include('roles.script')