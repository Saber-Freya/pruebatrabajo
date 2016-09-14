@extends('app')@section('content')
<div class="container">
    @include('flash::message')

    <div class="row">
        <h2 class="pull-left">Materiales</h2>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('productos.create') !!}"> Agregar </a>
        <a class="btn btn-pdf link-pdf pull-right invisible" style="margin-right: 10px; margin-top: 25px" href="" id="reporte-link" target="_blank">
            <i class="fa fa-file-pdf-o"></i>
        </a>
    </div>

    <div class="col-xs-12">Hay {{ sizeof($productos) }} material(es)</div>

    <div class="row">

        <div class="col-xs-12" align="right">
            <i class="fa fa-info-circle" title="Dejar vacío para mostrar todos los registros"></i>
            <input class="busqueda" id="busquedaProducto" type="text" placeholder="Búsqueda">
            <a class="btn-buscar" style="cursor:pointer" title="Buscar" onclick="buscarProducto()"><i class="fa fa-search"></i></a>
        </div>

        @if($productos->isEmpty())
            <div class="text-center">No hay registros.</div>
        @else
            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                    <th># Material</th>
                    <th>Nombre Material</th>
                    <th>Descripción</th>
                    <th>Precio Unitario</th>
                    @if(Auth::user()->hasRole('admin'))
                        <th width="50px">Acción</th>
                    @endif
                    </thead>
                    <tbody>
                    @foreach($productos as $prod)
                        <tr class='<?php if ($prod->precio_unitario == 0) echo "red-line"; ?>'>
                            <td>{!! $prod->id !!}</td>
                            <td>{!! $prod->nom_prod !!}</td>
                            <td>{!! $prod->descripcion_prod !!}</td>
                            <td>$ {!! number_format($prod->precio_unitario, 2, '.', ',') !!}</td>
                            @if(Auth::user()->hasRole('admin'))
                                <td>
                                    <a title="Editar" href="{!! route('productos.edit', [$prod->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a title="Borrar" href="#" data-slug="productos" data-id="{!! $prod->id !!}"  onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/productos', '/dr_basico/productos', $productos->render()) !!}
</div>
<script>
    $("#document").ready(function(){
        $("#busquedaProducto").on("keyup",function(e){
            if(e.key == "Enter"){
                buscarProducto();
            }
        });
    });

    function buscarProducto(){
        $.post('productos/buscarProducto', {
            _token: $('meta[name=csrf-token]').attr('content'),
            busqueda: $("#busquedaProducto").val()
        }).done(function (data) {
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