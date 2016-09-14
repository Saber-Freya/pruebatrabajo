<!--- Titulo Field --->
<div class = "row">
    <div class = "col-xs-6 col-md-3">
        <h1 class = "page-header">Altas de Usuarios</h1>
    </div>
</div>

<!--- Name Field --->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('name', 'Usuario:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!--- Email Field --->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('email', 'Correo:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!--- Password Field --->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('password', 'Contraseña:') !!}<i class="info fa fa-info-circle hidden PassBlanco" title="Dejar en blanco para no cambiar contraseña"></i>
    <input type="text" class="form-control" id="password" name="password" disabled>
</div>

<!--- Tipo Field --->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('role', 'Perfil de Usuario:') !!}
    {!! Form::select('role',$lista_roles, null, ['class' => 'form-control','name' => 'role']) !!}
</div>

<!--- Formulario Field --->
<div class="form-group col-sm-12">
    {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success',
    'type'=>'submit']) !!}
    <a class = "btn btn-danger" href = "{!! route('usuarios.index') !!}">
        Cancelar
        <i class = "glyphicon glyphicon-floppy-remove"></i>
    </a>
</div>
<script>
    var usuario = 0;
    @if(isset($usuarios))
        usuario = {!! $usuarios !!};
    console.log(usuario);
        $('.PassBlanco').removeClass('hidden');
        $(function(){
            $('#password').val("");
            $('#role').val(usuario.id_rol);
        });
    @endif
    $(function(){
        $('#password').prop('disabled', false);
    });
</script>