@extends('app')@section('content')@include('productos.script')
<div class="container">
    @include('flash::message')

    <div class="row">
        <h2 class="pull-left">Materiales</h2>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('productos.create') !!}">
            Agregar
        </a>
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
            <div class="well text-center">No hay registros.</div>
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
                                    <a title="Borrar" href="#" data-slug="productos" data-id="{!! $prod->id !!}"  onclick="return borrarElemento(this)"><i class="glyphicon glyphicon-remove"></i></a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <?php echo $productos->render(); ?>
</div>
    <script>
        $("#document").ready(function(){
            $("#busquedaProducto").on("keyup",function(e){
                if(e.key == "Enter"){
                    buscarProducto();
                }
            });
        });

        $('.fecha').datepicker({
            dateFormat: "yy-mm-dd",
            todayBtn: "linked",
            clearBtn: true,
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        function mostrarImg(x) {
            if ($(x).siblings(".bigImg").hasClass("invisible"))
                $(x).siblings(".bigImg").removeClass("invisible").addClass("show");
            else
                $(x).siblings(".bigImg").removeClass("show").addClass("invisible");
        }
        function openModal() {
            $("#modalProds").modal("show");
        }
        function repoProductos() {
            $("#modalProds").modal("toggle");
            var fecha = new Date();
            fecha = (fecha.getUTCMonth() + 1) + "/" + fecha.getDate() + "/" + fecha.getFullYear() + " " + fecha.getHours() + ":" + fecha.getMinutes();
            var nombre = fecha.replace("/", "_");
            nombre = nombre.replace("/", "_");
            nombre = nombre.replace(" ", "");
            nombre = nombre.replace(":", "");
            var inicio = $("#fecha_inicio").datepicker({dateFormat: 'YYYY-mm-dd'}).val();
            var final = $("#fecha_final").datepicker({dateFormat: 'YYYY-mm-dd'}).val();
            console.log("Inicio: " + inicio + "\n Final: " + final);
            $.post('/admin/productos/reporte', {
                _token: $('meta[name=csrf-token]').attr('content'),
                fecha: fecha,
                inicio: inicio,
                final: final,
                nombre: nombre
            }).done(function (data) {
                $("#reporte-link").attr("href", "productos/reporte/" + nombre);
                $("#reporte-link").addClass("show").removeClass("invisible");
                verPDF("productos/reporte/" + nombre); //post a ventasController(a)buscarReporte, devuelve el reporte en la vista de pdf
            })
                    .fail(function () {
                        alert("No se pudo generar el reporte, intentelo de nuevo");
                    });
        }
        (function (a) {
            a.createModal = function (b) {
                defaults = {title: "", message: "Your Message Goes Here!", closeButton: true, scrollable: false};
                var b = a.extend({}, defaults, b);
                var c = (b.scrollable === true) ? 'style="max-height: 600px;overflow-y: auto;"' : "";
                html = '<div class="modal fade" id="myModal">';
                html += '<div class="modal-dialog">';
                html += '<div class="modal-content">';
                html += '<div class="modal-header">';
                html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>';
                if (b.title.length > 0) {
                    html += '<h4 class="modal-title">' + b.title + "</h4>"
                }
                html += "</div>";
                html += '<div class="modal-body" ' + c + ">";
                html += b.message;
                html += "</div>";
                html += '<div class="modal-footer">';
                if (b.closeButton === true) {
                    html += '<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>'
                }
                html += "</div>";
                html += "</div>";
                html += "</div>";
                html += "</div>";
                a("body").prepend(html);
                a("#myModal").modal().on("hidden.bs.modal", function () {
                    a(this).remove()
                })
            }
        })(jQuery);
        function verPDF(pdf_link) {
            var iframe = '<object type="application/pdf" data="' + pdf_link + '" width="100%" height="500">No Support</object>'
            var title = " mats " + pdf_link;
            partes = title.split('/');
            title = partes[partes.length - 1];
            title = title.replace('.pdf', '');
            $.createModal({title: title, message: iframe, closeButton: true, scrollable: false});
            return false;
        }
        function buscarProducto(){
            $.post('productos/buscarProducto', {
                _token: $('meta[name=csrf-token]').attr('content'),
                busqueda: $("#busquedaProducto").val()
            })
            .done(function (data) {
                if(!data)
                {
                    alert("Error, No se pudo cargar la busqueda, intente nuevamente");
                }
                var newdoc = document.open("text/html", "replace");
                newdoc.write(data);
                newdoc.close();
            })
            .fail(function () {
                /*alert("Upps, No se encontraron resultados, intenta con otra busqueda", "info");*/
                swal("Upps", "No se encontraron resultados, intenta con otra busqueda", "info");
            });
        }
    </script>
@endsection