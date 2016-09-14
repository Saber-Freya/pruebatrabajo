<div class = "row">
    <div class = "col-xs-6 col-md-3"> <h2 class = "page-header">Alta de Usuario</h2> </div>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('apellido', 'Apellido:') !!}
    {!! Form::text('apellido', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('calle', 'Calle:') !!}
    {!! Form::text('calle', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('no_ext', 'No. Exterior:') !!}
    {!! Form::text('no_ext', null, ['class' => 'form-control', 'maxlength' => 10]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('no_int', 'No. Interior:') !!}
    {!! Form::text('no_int', null, ['class' => 'form-control', 'maxlength' => 10]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('colonia', 'Colonia:') !!}
    {!! Form::text('colonia', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('tel', 'Telefono de Casa:') !!}
    {!! Form::text('tel', null, ['class' => 'form-control inputTelefono required', 'maxlength' => 10]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('cel', 'Celular:') !!}
    {!! Form::text('cel', null, ['class' => 'form-control inputTelefono required', 'maxlength' => 10]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('estado', 'Estado:') !!}
    {!! Form::select('estado', $listaEstados, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('ciudad', 'Ciudad:') !!}
    <select name="ciudad" id="ciudad" class="form-control"></select>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('cp', 'Codigo Postal:') !!}
    {!! Form::text('cp', null, ['class' => 'form-control inputTelefono required','maxlength' => 5]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('puesto', 'Puesto:') !!}
    {!! Form::text('puesto', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    <i class="fa fa-asterisk"></i> {!! Form::label('email', 'Correo:') !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    <i class="fa fa-asterisk"></i> {!! Form::label('name', 'Usuario:') !!}<i class="info fa fa-info-circle" title="Este es el nombre de usuario con el que ingresara al sistema."></i>
    {!! Form::text('name', null, ['class' => 'form-control', 'maxlength' => 60]) !!}
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('password', 'Contraseña:') !!}<i class="info fa fa-info-circle hidden PassBlanco" title="Dejar en blanco para no cambiar contraseña"></i>
    <input type="text" class="form-control" id="password" name="password" maxlength="60" disabled>
</div>

<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('role', 'Perfil de Usuario:') !!}
    {!! Form::select('role',$lista_roles, null, ['class' => 'form-control','name' => 'role']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::button('Guardar <i class = "glyphicon glyphicon-floppy-save"></i>', ['class' => 'btn btn-success', 'type'=>'submit']) !!}
    <a class = "btn btn-danger" href = "{!! route('usuarios.index') !!}"> Cancelar <i class = "glyphicon glyphicon-floppy-remove"></i></a>
</div>

<script>
    $(document).on('ready',function(){
        var estado = $('#estado').val();
        cargarCiudades(estado);
    });

    $("#estado").on("change", function(){
        var estado = $(this).val();
        cargarCiudades(estado);
    });

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

    function cargarCiudades(estado){
        $.ajax({
            type   : "GET",
            url    : "/dr_basico/usuarios/ciudades/"+estado,
            async: false,
            success: function (data) {
                console.log(data);
                if(data){
                    var res = "";
                    for(var i=0; i<data.length; i++){
                        res += "<option value='"+data[i]['id']+"'>"+data[i]['ciudad']+"</option>";
                    }
                    $("#ciudad").html(res);
                    if(usuario != 0){
                        $("#ciudad").val(usuario.ciudad);
                    }
                }
            },error: function (data) {
                var errors = data.responseJSON;
                var mensaje = "";
                $.each(errors, function(index, value) {
                    mensaje += value+'\n';
                });
                console.log(mensaje);
                swal("Espere",mensaje,"warning");
            }
        });
    }
</script>