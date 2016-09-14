@extends('app')@section('content')
<div class="container">
    @include('flash::message')
    <div class="row">
        <h1 class="pull-left">Horarios</h1>
        @if(Entrust::can('crear_horarios'))<a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('horarios.create') !!}">Agregar</a>@endif
        <a class="btn btn-pdf link-pdf pull-right invisible" style="margin-right: 10px; margin-top: 25px" href="" id="reporte-link" target="_blank">
            <i class="fa fa-file-pdf-o"></i>
        </a>
    </div>
    <div class="row">
        @if($horarios->isEmpty())
            <div class="well text-center">No hay registros.</div>
        @else
            <div class="table-responsive col-xs-12">
                <table class="table">
                    <thead>
                        <th>Día</th>
                        <th>De</th>
                        <th>A</th>
                        <th>Tipo</th>
                        @if(Entrust::can('eliminar_horarios'))<th width="50px">Acción</th>@endif
                    </thead>
                    <tbody>
                    @foreach($horarios as $horario)
                        <?php
                            switch ($horario->dia){
                                    case 0: $dia = "Domingo";
                                break;
                                    case 1: $dia = "Lunes";
                                break;
                                    case 2: $dia = "Martes";
                                break;
                                    case 3: $dia = "Miércoles";
                                break;
                                    case 4: $dia = "Jueves";
                                break;
                                    case 5: $dia = "Viernes";
                                break;
                                    case 6: $dia = "Sábado";
                                break;
                                    default: $dia = "Sin Asignar";
                                break;
                            }
                            if($horario->tipo == 1){$tipo = "Consulta";}else{$tipo = "Cirugia";}
                        ?>
                        <tr>
                            <td>{!! $dia!!}</td>
                            <td>{!! $horario->horaDe !!}</td>
                            <td>{!! $horario->horaA !!}</td>
                            <td>{!! $tipo !!}</td>
                            <td>
                                {{--<a title="Editar" href="{!! route('horarios.edit', [$horario->id]) !!}"><i class="glyphicon glyphicon-edit"></i></a>--}}
                                @if(Entrust::can('eliminar_horarios'))<a title="Borrar" href="#" data-slug="horarios" data-id="{!! $horario->id !!}"  onclick="return borrarElemento(this)"><i class="fa fa-trash-o"></i></a>@endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    {!!  str_replace('/horarios', '/dr_basico/horarios', $horarios->render()) !!}
</div>
@endsection