@extends('app')@section('content')
<div class="container">
    @include('flash::message')

    <div class="row">
        <h2 class="pull-left">Auxiliares</h2>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('auxiliars.create') !!}">Agregar</a>
    </div>
    <div class="col-xs-12">Hay {{ sizeof($auxiliars) }} Auxiliar(es)</div>

    <div class="row">

        <div class="col-xs-12" align="right">
            <i class="fa fa-info-circle" title="Dejar vacío para mostrar todos los registros"></i>
            <input class="busqueda" id="busquedaAuxiliar" type="text" placeholder="Búsqueda">
            <a class="btn-buscar" style="cursor:pointer" title="Buscar" onclick="buscarAuxiliar()"><i class="fa fa-search"></i></a>
        </div>

        @if($auxiliars->isEmpty())
            <div class="well text-center">No hay registros.</div>
        @else

            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th width="50px">Acción</th>
                    </thead>
                    <tbody>
                    @foreach($auxiliars as $auxiliar)
                        <tr>
                            <td>{!! $auxiliar->nombre !!}</td>
                            <td>{!! $auxiliar->apellido !!}</td>
                            <td>{!! $auxiliar->domicilio !!}</td>
                            <td>{!! $auxiliar->telefono !!}</td>
                            <td>
                                <a title="Editar" href="{!! route('auxiliars.edit', [$auxiliar->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                                <a title="Borrar" href="#" data-slug="auxiliars" data-id="{!! $auxiliar->id !!}"  onclick="return borrarElemento(this)"><i class="glyphicon glyphicon-remove"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <?php echo $auxiliars->render(); ?>
</div>
<script>
    $("#document").ready(function(){
        $("#busquedaAuxiliar").on("keyup",function(e){
            if(e.key == "Enter"){
                buscarAuxiliar();
            }
        });
    });

    function buscarAuxiliar(){
        $.post('auxiliars/buscarAuxiliar', {
            _token: $('meta[name=csrf-token]').attr('content'),
            busqueda: $("#busquedaAuxiliar").val()
        })
        .done(function (data) {
            if(!data){
                alert("Error, No se pudo cargar la busqueda, intente nuevamente");
            }
            var newdoc = document.open("text/html", "replace");
            newdoc.write(data);
            newdoc.close();
        }).fail(function () {
            swal("Upps", "No se encontraron resultados, intenta con otra busqueda", "info");
        });
    }
</script>
@endsection