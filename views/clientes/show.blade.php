@extends('app')@section('content')
<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];
function getBrowser($user_agent){
    if(strpos($user_agent, 'MSIE') !== FALSE)
        return 'Internet explorer';
    elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
        return 'Internet explorer';
    elseif(strpos($user_agent, 'Firefox') !== FALSE)
        return 'Mozilla Firefox';
    elseif(strpos($user_agent, 'Chrome') !== FALSE)
        return 'Google Chrome';
    elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
        return "Opera Mini";
    elseif(strpos($user_agent, 'Opera') !== FALSE)
        return "Opera";
    elseif(strpos($user_agent, 'Safari') !== FALSE)
        return "Safari";
    else
        return 'No hemos podido detectar su navegador';
}
$navegador = getBrowser($user_agent);
?>
<div class="container">
    <div class = "row">
        @if(isset($preconsulta))
            <div class = "col-xs-12 col-sm-3">
                <h3 class = "page-header pull-left">Consulta</h3>
            </div>

            <div class = "col-xs-12 col-sm-6" align="center" style="margin-top: 44px;">
                <a class="botonRay grupo text-center cursor btn-default removerDec botonPreconsulta" style="margin: 33px 5px 10px;" onclick="mostrarOcultar('preconsulta')"
                   title="Mostrar u ocultar los Datos de la pre-consulta"><i class = "fa fa-product-hunt"></i>
                </a>
                {{--@if(isset($consulta))--}}
                    <a class="grupo text-center cursor btn-default removerDec" style="margin: 33px 5px 10px;"
                       onclick="archivos(this)" title="Agregar Archivos" id="0" id_cliente="{!! $cliente->id !!}"
                       id_padecimiento="{!! $padecimientoC->id_pade !!}" data-toggle="modal" data-target="#modalArchivos">
                        <i class="fa fa-file-pdf-o"> </i>
                    </a>
                {{--@endif--}}
                <a class="grupo text-center cursor btn-default removerDec" style="margin: 33px 5px 10px;" onclick="receta(this)"
                   paciente="{!! $cliente->nombre !!} {!! $cliente->apellido !!}" title="Crear Receta" href="#"
                   id_cliente="{!! $cliente->id !!}" data-toggle="modal" data-target="#modalReceta">
                    <i class="fa fa-pencil-square-o"> </i>
                </a>
                <a class="grupo text-center cursor btn-default removerDec" style="margin: 33px 5px 10px;"
                   title="Seguimiento" href="/dr_basico/servicios/{!! $cliente->id_servicio !!}/seguimiento/cita" class="removerDec">
                    <i class="fa fa-share-square-o"> </i>
                </a>
            </div>
        @else
            <div class = "col-xs-12 col-sm-3">
                <h3 class = "page-header pull-left">Historial del Paciente</h3>
            </div>

            <div class = "col-xs-12 col-sm-6" align="center" style="margin-top: 44px;">
                <a class="botonRay grupo text-center cursor btn-default botonPersonales" style="margin-top: 33px;" onclick="mostrarOcultar('datos')" title="Mostrar u ocultar los Datos personales"><i class = "fa fa-user"></i><strong></strong></a>
                <a class="botonRay grupo text-center cursor btn-default botonListas" style="margin: 33px 5px 20px;" onclick="mostrarOcultar('listas')" title="Mostrar u ocultar Lista de correos y contactos"><i class = "fa fa-list"></i><strong></strong></a>
                <a class="botonRay grupo text-center cursor btn-default botonComentarios" style="margin: 33px 0px 20px;" onclick="mostrarOcultar('comentarios')" title="Mostrar u ocultar Comentarios completos de Ficha Médica"><i class = "fa fa-comment-o"></i><strong></strong></a>
                @if(!empty($recetas))
                    <a class="grupo text-center cursor btn-default" style="margin: 33px 5px 20px;" title="Historial Completo de recetas" data-toggle="modal" href="#historialRecetas"><i class="fa fa-list-alt"></i></a>
                @endif
            </div>
        @endif


        <div class = "col-sm-3 hidden-xs">
            <h3 class = "page-header pull-right text-right">{!! $cliente->nombre !!} {!! $cliente->apellido !!}</h3>
        </div>

        <div class = "col-xs-12 visible-xs">
            <h3 class = "page-header text-right">{!! $cliente->nombre !!} {!! $cliente->apellido !!}</h3>
        </div>
    </div>

    @if(isset($preconsulta))
        <div class = "row">
            <div class = "col-xs-12 col-sm-4 col-md-3">
                <a class="botonRay col-xs-2 grupo text-center cursor btn-default botonPersonales" style="margin-top: 0px;padding: 0px!important;" onclick="mostrarOcultar('datos')" title="Mostrar u ocultar los Datos personales"><i class = "fa fa-user"></i><strong></strong></a>
                <a class="botonRay col-xs-2 grupo text-center cursor btn-default botonListas" style="margin: 0px 5px 20px;padding: 0px!important;" onclick="mostrarOcultar('listas')" title="Mostrar u ocultar Lista de correos y contactos"><i class = "fa fa-list"></i><strong></strong></a>
                <a class="botonRay col-xs-2 grupo text-center cursor btn-default botonComentarios" style="margin: 0px 5px 20px;padding: 0px!important;" onclick="mostrarOcultar('comentarios')" title="Mostrar u ocultar Comentarios completos de Ficha Médica"><i class = "fa fa-comment-o"></i><strong></strong></a>
                <a class="botonRay col-xs-2 grupo text-center cursor btn-default segundoRenglon @if($navegador != 'Mozilla Firefox')posicion2 @else posicion1 @endif" style="margin: 0px -5px 20px;padding: 0px!important;" onclick="reagendarAlerta(this)" para="2" title="Mandar alerta para dar Seguimiento a esta Cita" id_servicio="{!! $cliente->id_servicio !!}"><i class = "fa fa-share-square-o"></i><strong></strong></a>

                @if(!empty($recetas))
                    <a class="col-xs-2 grupo text-center cursor btn-default" style="margin: 0px 5px 20px;padding: 0px!important;" title="Historial Completo de recetas" data-toggle="modal" href="#historialRecetas"><i class="fa fa-list-alt"></i></a>
                    <a class="col-xs-2 grupo text-center cursor btn-default segundoRenglon posicion2" style="margin: 0px 5px 20px;padding: 0px!important;" target="_blank" href="/dr_basico/clientes/historial/{!! $cliente->id !!}" title="Historial del Paciente"><i class="glyphicon glyphicon-paste"></i></a>
                @else
                    <a class="col-xs-2 grupo text-center cursor btn-default" style="margin: 0px 5px 20px;padding: 0px!important;" target="_blank" href="/dr_basico/clientes/historial/{!! $cliente->id !!}" title="Historial del Paciente"><i class="glyphicon glyphicon-paste"></i></a>
                @endif
            </div>
        </div>
    @else
    @endif

    <div class = "row col-xs-12">
        <div class = "col-sm-2 margentop20" id="areaFoto" align="center" style="max-height: 230px;max-width: 230px;">
            <img class="img-responsive" id="fotoTomada">
        </div>
        <?php
        switch($cliente->sexo ){
            case 1:
                $sexo = "Masculino";
                break;
            case 2:
                $sexo = "Femenino";
                break;
            default:
                $sexo = "Sin dato";
        }
        switch($cliente->sangre ){
            case 1:
                $sangre = "O-";
                break;
            case 2:
                $sangre = "O+";
                break;
            case 3:
                $sangre = "A-";
                break;
            case 4:
                $sangre = "A+";
                break;
            case 5:
                $sangre = "B-";
                break;
            case 6:
                $sangre = "B+";
                break;
            case 7:
                $sangre = "AB-";
                break;
            case 8:
                $sangre = "AB+";
                break;
            default:
                $sangre = "Sin dato";
        }
            if($cliente->tel == ''){$tel = "Sin dato";}else{$tel = $cliente->tel;}
            //ficha medica
            if($cliente->asma != 0){$asma = 'SI'; $positivoAs = 'positivoFicha';}else{$asma = 'NO'; $positivoAs = '';}
            if($cliente->ulsera != 0){$ulsera = 'SI'; $positivoUl = 'positivoFicha';}else{$ulsera = 'NO'; $positivoUl = '';}
            if($cliente->fiebre != 0){$fiebre = 'SI'; $positivoFi = 'positivoFicha';}else{$fiebre = 'NO'; $positivoFi = '';}
            if($cliente->diabetes != 0){$diabetes = 'SI'; $positivoDi = 'positivoFicha';}else{$diabetes = 'NO';  $positivoDi = '';}
            if($cliente->cardiacas != 0){$cardiacas = 'SI'; $positivoCa = 'positivoFicha';}else{$cardiacas = 'NO'; $positivoCa = '';}
            if($cliente->convulsiones != 0){$convulsiones = 'SI'; $positivoCo = 'positivoFicha';}else{$convulsiones = 'NO'; $positivoCo = '';}
            if($cliente->tuberculosis != 0){$tuberculosis = 'SI'; $positivoTu = 'positivoFicha';}else{$tuberculosis = 'NO';  $positivoTu = '';}
            if($cliente->mareos != 0){$mareos = 'SI'; $positivoMa = 'positivoFicha';}else{$mareos = 'NO'; $positivoMa = '';}
            if($cliente->dolor_cabeza != 0){$dolor_cabeza = 'SI'; $positivoDo = 'positivoFicha';}else{$dolor_cabeza = 'NO'; $positivoDo = '';}
            if($cliente->emocionales != 0){$emocionales = 'SI'; $positivoEm = 'positivoFicha';}else{$emocionales = 'NO'; $positivoEm = '';}
            if($cliente->hernias != 0){$hernias = 'SI'; $positivoHe = 'positivoFicha';}else{$hernias = 'NO'; $positivoHe = '';}
            if($cliente->arterial != 0){$arterial = 'SI'; $positivoAr = 'positivoFicha';}else{$arterial = 'NO'; $positivoAr = '';}
        ?>

        <div class = "col-sm-10 grupo margentop20 datos datosListasFicha hidden datosPersonales">
            <div class="col-xs-12 page-header-sub"><i class = "fa fa-user"></i><strong> Datos personales</strong></div>
            <div class="row">
                <div class="form-group col-sm-3">
                    {!! Form::label('', 'Teléfono:') !!}<span> {!!$tel!!}</span>
                </div>
                <div class="form-group col-sm-3">
                    {!! Form::label('', 'Edad: ') !!} <span class="edad"></span>
                </div>
                <div class="form-group col-sm-3">
                    {!! Form::label('', 'Sexo:') !!}<span> {!!$sexo!!}</span>
                </div>
                <div class="form-group col-sm-3">
                    {!! Form::label('', 'Grupo sanguíneo:') !!}<span> {!!$sangre!!}</span>
                </div>
            </div>
        </div>
        @include('clientes.fichaMin')
        @include('clientes.fichaMax')
    </div>

    @if(isset($preconsulta))
        @include('clientes.consulta')
    @endif

    <div class="row col-xs-12">
        @if(isset($padecimientos[0]->ultimaCitaPadecimiento))
            <div class = "col-xs-12 grupo historialCitas">
                <div class="col-xs-12 page-header-sub"><i class = "fa fa-book"></i><strong class="historial"> Historial de Padecimientos</strong></div>
                <div class="row">
                    <div class = "table-responsive col-xs-12">
                        <table class="table">
                            <thead>
                                <th>Padecimiento</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                {{--<th>Diagnostico</th>--}}
                                <th>Observaciones</th>
                                <th>Recetas</th>
                            </thead>
                            <tbody>
                            @for($i = 0; $i < count($padecimientos); ++$i)
                                <?php
                                $ultima = $padecimientos[$i]->ultimaCitaPadecimiento;
                                $todas = $ultima->todasCitasPadecimiento;
                                if($ultima->tipo == 1){$tipo = "Consulta";}else{$tipo = "Cirugía";}
                                if($ultima->cirugia == null){$cirugia = "N/A";}else{$cirugia = $ultima->cirugia;}
                                ?>
                                <tr class="{!! $ultima->id_padecimiento !!} todosPade" onclick="mostrar({!! $ultima->id_padecimiento !!})">
                                    <td>
                                        {!! $ultima->padecimiento !!}
                                        @if (count($todas)>1)
                                            <i class = "fa fa-chevron-down {!! $ultima->id_padecimiento !!}flechaArriba flechaArr"></i>
                                            <i class = "fa fa-chevron-up {!! $ultima->id_padecimiento !!}flechaAbajo hidden pade"></i>
                                        @endif
                                    </td>
                                    <td>{!! $ultima->fecha !!}</td>
                                    <td>{!! $tipo !!}</td>
                                    {{--<td>{!! $ultima->diagnostico !!}</td>--}}
                                    <td>{!! $ultima->observaciones !!}</td>
                                    <td>
                                        @if(isset($ultima->recetasEstaFecha))
                                            @foreach($ultima->recetasEstaFecha as $receta)
                                                <a class="removerDec" href="#" onclick="recetaReimpresion(this)"
                                                   dato-fecha="{!! $receta->fecha !!}" dato-receta="{!! $receta->receta !!}" dato-nombre="{!! $receta->nombre_receta !!}"
                                                   paciente="{!! $cliente->nombre !!} {!! $cliente->apellido !!}"
                                                   tipoReceta="{!! $receta->tipoReceta !!}"
                                                   data-toggle="modal" data-target="#modalReceta" title="{!! $receta->receta !!}" >
                                                    <i class="fa fa-print"> </i>
                                                </a>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                @for($j = 1; $j < count($todas); ++$j)
                                    <?php
                                        if($todas[$j]->tipo == 1){$tipoCita = "Consulta";}else{$tipoCita = "Cirugía";}
                                    ?>
                                    <tr class="{!! $todas[$j]->id_padecimiento !!}oculto hidden pade">
                                        <td>{!! $todas[$j]->padecimiento !!}</td>
                                        <td>{!! $todas[$j]->fecha !!}</td>
                                        <td>{!! $tipoCita !!}</td>
                                        {{--<td>{!! $todas[$j]->diagnostico !!}</td>--}}
                                        <td>{!! $todas[$j]->observaciones !!}</td>
                                        <td>
                                            @if(isset($todas[$j]->recetasEstaFecha))

                                                @foreach($todas[$j]->recetasEstaFecha as $recetaT)
                                                    <a class="removerDec" href="#" onclick="recetaReimpresion(this)"
                                                       dato-fecha="{!! $recetaT->fecha !!}" dato-receta="{!! $recetaT->receta !!}"
                                                       paciente="{!! $cliente->nombre !!} {!! $cliente->apellido !!}"
                                                       tipoReceta="{!! $recetaT->tipoReceta !!}"
                                                       data-toggle="modal" data-target="#modalReceta" title="{!! $recetaT->receta !!}" >
                                                        <i class="fa fa-print"> </i>
                                                    </a>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endfor
                            @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row col-xs-12 hidden">
        @if ($historial != 'vacio')
            <div class = "col-xs-12 grupo historialCitas">
                <div class="col-xs-12 page-header-sub"><i class = "fa fa-book"></i><strong> Historial de Padecimientos</strong>
                </div>
                <div class="row">
                    <div class = "table-responsive col-xs-12">
                        <table class="table">
                            <thead>
                                <th>Padecimiento</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                {{--<th>Diagnostico</th>--}}
                                <th>Observaciones</th>
                            </thead>
                            <tbody>
                                @foreach($historial as $servicio)
                                    <?php
                                        if($servicio->tipo == 1){$tipo = "Consulta";}else{$tipo = "Cirugía";}
                                        if($servicio->cirugia == null){$cirugia = "N/A";}else{$cirugia = $servicio->cirugia;}
                                    ?>
                                    <tr>
                                        <td>{!! $servicio->padecimiento !!}</td>
                                        <td>{!! $servicio->fecha !!}</td>
                                        <td>{!! $tipo !!}</td>
                                        {{--<td>{!! $servicio->diagnostico !!}</td>--}}
                                        <td>{!! $servicio->observaciones !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row col-xs-12">
        @if(isset($padecimientos[0]->archivo))
            <div class = "col-xs-12 grupo archivos">
                <div class="col-xs-12 page-header-sub"><i class = "fa fa-file"></i><strong> Archivos</strong></div>
                <div class="row">
                    <div class = "table-responsive col-xs-12">
                        <table class="table">
                            <thead>
                                <th>Padecimiento</th>
                                <th class="text-center">Imagenes</th>
                                <th class="text-center">Archivos</th>
                            </thead>
                            <tbody>
                                @foreach($padecimientos as $padecimiento)
                                    <tr class="{!! $padecimiento->id !!}oculto hidden pade">
                                        <td>{!! $padecimiento->padecimiento !!}</td>
                                        <td align="center">
                                            @if(empty($padecimiento->archivo))
                                                <div class="text-center">Aun no se agrega.</div>
                                            @else
                                            @foreach($padecimiento->archivo as $archivo)
                                                <?php
                                                    $nombreArchivo = $archivo->archivo;
                                                    $extension = explode(".", $nombreArchivo);
                                                    $extension = $extension[1];
                                                ?>
                                                @if($extension == "png" || $extension == "jpg" || $extension == "jpeg" || $extension == "gif")
                                                    <div class="col-sm-1">
                                                        <a data-toggle="modal" href="#{!!$archivo->id!!}">
                                                            <img class="img-responsive" height="auto" width = "150px"
                                                                 src="{{asset('img/uploads/archivos/'.$archivo->archivo)}}"
                                                                 title="{!! $archivo->titulo !!} {!!$archivo->created_at!!}"
                                                                 alt="{!! $archivo->titulo !!}"
                                                            />
                                                        </a>
                                                        <div class="modal fade" id="{!!$archivo->id!!}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                                                                            <span class="sr-only">Close</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel">{!! $archivo->titulo !!}</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <img class="img-responsive" height="auto" width = "100%"
                                                                             src="{{asset('img/uploads/archivos/'.$archivo->archivo)}}"
                                                                             title="{!! $archivo->titulo !!} {!!$archivo->created_at!!}"
                                                                             alt="{!! $archivo->titulo !!}"
                                                                        />
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else

                                                @endif
                                            @endforeach
                                            @endif
                                        </td>
                                        <td align="center">
                                            @foreach($padecimiento->archivo as $archivo)
                                                <?php
                                                $nombreArchivo = $archivo->archivo;
                                                $extension = explode(".", $nombreArchivo);
                                                $extension = $extension[1];
                                                ?>
                                                @if($extension == "png" || $extension == "jpg" || $extension == "jpeg" || $extension == "gif")

                                                @else
                                                    <div class="col-sm-1">
                                                        <a target="_blank" href="/dr_basico/img/uploads/archivos/{!!$archivo->archivo!!}"
                                                           download="{!!$archivo->titulo!!}"
                                                           title="{!! $archivo->titulo !!} {!!$archivo->created_at!!}"
                                                           class="removerDec"> <i class="fa fa-file-image-o"> </i>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row col-xs-12">
        @if(isset($padecimientos[0]->estudio))
            <div class = "col-xs-12 grupo archivos">
                <div class="col-xs-12 page-header-sub"><i class = "fa fa-stethoscope"></i><strong> Estudios</strong></div>
                <div class="row">
                    <div class = "table-responsive col-xs-12">
                        <table class="table">
                            <thead>
                                <th>Padecimiento</th>
                                <th class="text-center"><div class="col-xs-6">Examen</div> <div class="col-xs-6">Proveedor</div></th>
                            </thead>
                            <tbody>
                                @foreach($padecimientos as $padecimiento)
                                    <tr class="{!! $padecimiento->id !!}oculto hidden pade">
                                        <td>{!! $padecimiento->padecimiento !!}</td>
                                        <td align="center">
                                            @if(empty($padecimiento->archivo))
                                                <div class="text-center">Aun no se agrega.</div>
                                            @else
                                            @foreach($padecimiento->estudio as $estudio)
                                                <div class="col-xs-6">
                                                    {!! $estudio->estudio_nombre !!}
                                                </div>
                                                <div class="col-xs-6">
                                                    {!! $estudio->proveedor_nombre !!}
                                                </div>
                                            @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row col-xs-12">
        <div class = "col-xs-12 grupo listas datosListasFicha datosListas hidden">
            <div class="col-xs-12 page-header-sub"><i class = "fa fa-list"></i><strong> Lista de Correos y Contactos</strong></div>
            <div class="row">
                <div class="col-sm-12">
                    <strong>Correos: </strong><br>
                    @foreach($correos as $correo)
                        <span> {!! $correo->email !!} </span><br>
                    @endforeach
                </div>
                <div class="col-sm-12 @if(!isset($cliente->contactos)) hidden @endif margentop20">
                    <strong class="visible-xs">Contactos: </strong>
                    <div class="col-xs-12 hidden-xs" style="font-weight: bold">
                        <div class="col-sm-2">Contacto</div>
                        <div class="col-sm-1">Parentesco</div>
                        <div class="col-sm-1">Calle</div>
                        <div class="col-sm-1">Núm. Ext.</div>
                        <div class="col-sm-1">Núm. Int.</div>
                        <div class="col-sm-1">Colonia</div>
                        <div class="col-sm-1">Código Postal</div>
                        <div class="col-sm-1">Teléfono</div>
                        <div class="col-sm-3">Correo</div>
                    </div>
                    @if(isset($cliente->contactos))
                        @foreach($cliente->contactos as $con)
                            <div class="contacto grupo col-xs-12">
                                <div class="col-xs-12 col-sm-2 contacto">{!! $con->nombre !!}</div>
                                <div class="col-xs-12 col-sm-1 parentesco" id="{!! $con->parentesco_id !!}">{!! $con->parentesco !!}</div>
                                <div class="col-xs-12 col-sm-1 calleE">{!! $con->calleE !!}</div>
                                <div class="col-xs-12 col-sm-1 no_intE">{!! $con->no_intE !!}</div>
                                <div class="col-xs-12 col-sm-1 no_extE">{!! $con->no_extE!!}</div>
                                <div class="col-xs-12 col-sm-1 coloniaE">{!! $con->coloniaE !!}</div>
                                <div class="col-xs-12 col-sm-1 cpE">{!! $con->cpE !!}</div>
                                <div class="col-xs-12 col-sm-1 tel_per">{!! $con->telefono_personal !!}</div>
                                <div class="col-xs-12 col-sm-3 email_per">{!! $con->email_personal !!}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="table-responsive tablaArchivo">
            <div class="tablaAdmin"></div>
        </div>
    </div>
    <div class="row col-xs-12">
        @if(Entrust::can('atender_consulta'))
            @if(isset($finalizadoDatos))
                @if($finalizadoDatos->isEmpty())
                    <a href="#" class="btn btn-warning finalizar pull-right" data-toggle="modal" data-target="#modalFinalizar" title="Finalizar Historial">
                        Finalizar Historial <i class = "fa fa-fast-forward"></i>
                    </a>
                @else
                    <a href="#" class="btn btn-primary finalizar pull-right" data-toggle="modal" data-target="#modalFinalizar" title="Ver detalles del motivo o causa del finalizado del historial">
                        Causa <i class = "fa fa-fast-forward"></i>
                    </a>
                @endif
            @endif
        @endif
    </div>
</div>
@include('modals.receta')
@if(isset($finalizadoDatos))
    @include('modals.finalizarHistorial')
@endif
<script>
    $(document).ready( function() {

        var tipoVista = 'ver';

        var fechaNacimiento = '{!! $cliente->fecha_nacimiento !!}';

        if (fechaNacimiento != '0000-00-00'){
            var nacimiento = edad(fechaNacimiento);
            $('.edad').html(nacimiento);
        }else{
            $('.edad').html('Sin dato');
        }

        @if(isset($preconsulta))
        if('{!! $preconsulta->id !!}' != null){ tipoVista = 'consulta'; }
        @endif

        if (tipoVista == 'ver'){
            /*mostrarOcultar('datos');*/
        }else{
            @if(isset($padecimientoC))
                var id_padeConsulta = {!! $padecimientoC->id_pade !!};
                var nom_pade = '{!! $padecimientoC->padecimiento !!}';
                $('tr.todosPade:not(.'+id_padeConsulta+')').addClass('hidden');
                $('.historial').html(' Historial de '+nom_pade);
            @else
                $('.todosPade').addClass('hidden');
            @endif
        }
        //Borones Activos
        checarBotones();
    });

    if ( '{!! $cliente->foto !!}' != ''){
        if ('{!! $cliente->foto !!}' != 'undefined'){
            $('.Archivo').addClass('hidden');
            $('#areaFoto').removeClass('hidden');

            var foto = '/{!! $cliente->foto !!}';
            /*console.log(foto);*/
            if (foto != '/null'){
                var url = '{{asset('img/uploads/archivos/')}}';
                var urlfoto = url+foto;
                /*console.log(urlfoto);*/
                $('#fotoTomada').attr('src', urlfoto);
            }
        }
        console.log('sin foto');
    }

    function edad(fecha){
        // Si la fecha es correcta, calculamos la edad
        var values=fecha.split("-");
        var dia = values[2];
        var mes = values[1];
        var ano = values[0];

        // cogemos los valores actuales
        var fecha_hoy = new Date();
        var ahora_ano = fecha_hoy.getYear();
        var ahora_mes = fecha_hoy.getMonth()+1;
        var ahora_dia = fecha_hoy.getDate();

        // realizamos el calculo
        var edad = (ahora_ano + 1900) - ano;
        if ( ahora_mes < mes ){
            edad--;
        }
        if ((mes == ahora_mes) && (ahora_dia < dia)){
            edad--;
        }
        if (edad > 1900){
            edad -= 1900;
        }

        // calculamos los meses
        var meses=0;
        if(ahora_mes>mes)
            meses=ahora_mes-mes;
        if(ahora_mes<mes)
            meses=12-(mes-ahora_mes);
        if(ahora_mes==mes && dia>ahora_dia)
            meses=11;

        // calculamos los dias
        var dias=0;
        if(ahora_dia>dia)
            dias=ahora_dia-dia;
        if(ahora_dia<dia){
            var ultimoDiaMes=new Date(ahora_ano, ahora_mes, 0);
            dias=ultimoDiaMes.getDate()-(dia-ahora_dia);
        }
        /*console.log("Tienes "+edad+" años, "+meses+" meses y "+dias+" días");*/
        return edad;
    }

    function mostrar(id_pade){
        var tieneClase = $("."+id_pade+"oculto").hasClass('hidden');
        $('.pade').addClass('hidden');
        $('.flechaArr').removeClass('hidden');
        /*console.log(tieneClase);*/
        if (tieneClase == true) {
            $("."+id_pade+"oculto").removeClass('hidden');
            $("."+id_pade+"flechaArriba").addClass('hidden');
            $("."+id_pade+"flechaAbajo").removeClass('hidden');
        }else {
            $("."+id_pade+"oculto").addClass('hidden');
            $("."+id_pade+"flechaArriba").removeClass('hidden');
            $("."+id_pade+"flechaAbajo").addClass('hidden');
        }
    }

    function mostrarOcultar(que){
        var tieneClase = "";
        switch (que) {
            case 'datos':
                tieneClase = $(".datos").hasClass('hidden');
                if (tieneClase == true) {
                    $(".datos").removeClass('hidden');
                }else {
                    $(".datos").addClass('hidden');
                }
                break;
            case 'listas':
                tieneClase = $(".listas").hasClass('hidden');
                if (tieneClase == true) {
                    $(".listas").removeClass('hidden');
                }else {
                    $(".listas").addClass('hidden');
                }
                break;
            case 'comentarios':
                tieneClase = $(".minimizada").hasClass('hidden');
                if (tieneClase == true) {
                    $(".minimizada").removeClass('hidden');
                    $(".maximizada").addClass('hidden');
                }else {
                    $(".minimizada").addClass('hidden');
                    $(".maximizada").removeClass('hidden');
                }
                break;
            case 'preconsulta':
                tieneClase = $(".preconsulta").hasClass('hidden');
                if (tieneClase == true) {
                    $(".preconsulta").removeClass('hidden');
                }else {
                    $(".preconsulta").addClass('hidden');
                }
                break;
            default:
                tieneClase = $(".datosListasFicha").hasClass('hidden');
                if (tieneClase == true) {
                    $(".datosListasFicha").removeClass('hidden');
                }else {
                    $(".datosListasFicha").addClass('hidden');
                }
        }
    }

    $('.botonRay').on('click', function () {
        checarBotones();
    });

    function checarBotones(){

        if ($('.datosPersonales').hasClass('hidden')){
            $('.botonPersonales').removeClass('botonActivo');
        }else{$('.botonPersonales').addClass('botonActivo');}

        if ($('.datosListas').hasClass('hidden')){
            $('.botonListas').removeClass('botonActivo');
        }else{$('.botonListas').addClass('botonActivo');}

        if ($('.datosComentarios').hasClass('hidden')){
            $('.botonComentarios').removeClass('botonActivo');
        }else{$('.botonComentarios').addClass('botonActivo');}

        if ($('.preconsulta').hasClass('hidden')){
            $('.botonPreconsulta').removeClass('botonActivo');
        }else{$('.botonPreconsulta').addClass('botonActivo');}

    }

    function reagendarAlerta(elemento){
        var id_servicio = $(elemento).attr('id_servicio');
        var para = $(elemento).attr('para');
        $.ajax({
            type: 'GET',
            url: '/dr_basico/reagendarAlerta/'+id_servicio+'/'+para,
            success: function(data) {
                if (para == 1) {
                    swal("Enviada", "Se envió alerta para reagendar esta Cita", "success");
                } else {
                    swal("Enviada", "Se envió alerta para dar seguimiento a esta Cita", "success");
                }
            },error: function (ajaxContext) {
                swal("Espere","Algo salio mal, reintente de nuevo","warning");
            }
        });
    }

</script>
@endsection
