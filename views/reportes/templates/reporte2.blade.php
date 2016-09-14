<!DOCTYPE html>
<html>
    <head>
        <meta charset = "utf-8">
        @include("reportes/templates.style")
    </head>
    <?php
    $resp = [
        '<i class="fa fa-square-o" aria-hidden="true"></i>',
        '<i class="fa fa-check-square-o" aria-hidden="true"></i>'
    ];
            $sexo = ["","Masculino","Femenino"];
            $sangre = ["","O-","O+","A-","A+","B-","B+","AB-","AB+"];
            $receta = ["","Receta Medica","Venta directa"];
            $estado = ["Por llegar","Espera","Consulta","Finalizada","Falta"];
    ?>
    <body>
        <div class="footer">
            Pagina <span class="pagenum"></span>
        </div>
        <div>
            <div class="grupoFicha">
                <table>
                    <tr>
                        <td><span class="titulo">HISTORIAL MEDICO</span></td>
                        <td class="right"><span class="anotacion">{!! date("d-m-Y H:i a") !!}</span></td>
                    </tr>
                </table>

            </div>
            <!--DATOS PERSONALES-->
            <div class="grupoFicha">
                <div class="page-header-sub"><strong>Datos personales</strong></div>
                <table>
                    <tr>
                        <td style="width: 100px">
                            @if(file_exists(public_path("/img/uploads/archivos/".$datos["datosCliente"]->foto)))
                                <img class="foto" src='{!! public_path("/img/uploads/archivos/".$datos["datosCliente"]->foto) !!}' class="img-responsive">
                            @else
                                <div class="fotoFicha"></div>
                            @endif
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td class="field">Nombre:</td><td>{!! $datos["datosCliente"]->nombre !!} {!! $datos["datosCliente"]->apellido !!}</td>
                                    <td class="field">Email:</td><td>{!! $datos["datosCliente"]->email !!}</td>
                                </tr>
                                <tr>
                                    <td class="field">Teléfono:</td><td>{!! $datos["datosCliente"]->tel !!}</td>
                                    <td class="field">Fecha de nacimiento:</td><td>{!! $datos["datosCliente"]->fecha_nacimiento !!}</td>
                                </tr>
                                <tr>
                                    <td class="field">Sexo:</td><td>{!! $sexo[$datos["datosCliente"]->sexo] !!}</td>
                                    <td class="field">Etnicidad:</td><td>{!! $datos["datosCliente"]->etnicidad !!}</td>
                                </tr>
                                <tr>
                                    <td class="field">Tipo de sangre:</td><td>{!! $sangre[$datos["datosCliente"]->sangre] !!}</td>
                                    <td class="field">Fecha Alta:</td><td>{!! $datos["datosCliente"]->fecha_alta !!}</td>
                                </tr>
                                <tr>
                                    <td class="field">Direccion:</td><td colspan="3">{!! $datos["datosCliente"]->calle !!} {!! $datos["datosCliente"]->num_ext !!}
                                        {!! $datos["datosCliente"]->num_int !!} {!! $datos["datosCliente"]->col !!} {!! $datos["datosCliente"]->cp !!}<br>
                                        {!! $datos["datosCliente"]->cd !!} {!! $datos["datosCliente"]->edo !!} {!! $datos["datosCliente"]->pais !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <!--HISTORIA CLINICA-->
            <div class="grupoFicha">
                <div class="page-header-sub"><strong> Historia Clinica</strong></div>
                <div class="page-body">
                    <table>
                        @if($datos["datosCliente"]->asma==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->asma] !!} Asma</td>
                            <td>{!! $datos["datosCliente"]->asma_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->ulsera==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->ulsera] !!} Ulcera</td>
                            <td>{!! $datos["datosCliente"]->ulsera_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->fiebre==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->fiebre] !!} Fiebre</td>
                            <td>{!! $datos["datosCliente"]->fiebre_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->diabetes==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->diabetes] !!} Diabetes</td>
                            <td>{!! $datos["datosCliente"]->diabetes_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->cardiacas==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->cardiacas] !!} Cardiacas</td>
                            <td>{!! $datos["datosCliente"]->cardiacas_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->convulsiones==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->convulsiones] !!} Convulsiones</td>
                            <td>{!! $datos["datosCliente"]->convulsiones_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->tuberculosis==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->tuberculosis] !!} Tuberculosis</td>
                            <td>{!! $datos["datosCliente"]->tuberculosis_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->mareos==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->mareos] !!} Mareos</td>
                            <td>{!! $datos["datosCliente"]->mareos_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->dolor_cabeza==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->dolor_cabeza] !!} Dolor cabeza</td>
                            <td>{!! $datos["datosCliente"]->dolor_cabeza_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->emocionales==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->emocionales] !!} Emocionales</td>
                            <td>{!! $datos["datosCliente"]->emocionales_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->hernias==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->hernias] !!} Hernias</td>
                            <td>{!! $datos["datosCliente"]->hernias_coment !!}</td>
                        </tr>
                        @endif
                        @if($datos["datosCliente"]->arterial==1)
                        <tr>
                            <td class="field">{!! $resp[$datos["datosCliente"]->arterial] !!} Arterial</td>
                            <td>{!! $datos["datosCliente"]->arterial_coment !!}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            <!--HISTORIAL DE CONSULTAS-->
            <div class="grupoFicha">
                <div class="page-header-sub"><i class="fa fa-stethoscope"></i><strong> Historial de Consultas</strong></div>
                <div class="page-body">

                    @foreach($datos["consultas"] as $consulta)
                        <table>
                            <tr class="trppal">
                                <td class="field">Fecha:</td><td>{!! $consulta->fecha !!}</td>
                                <td class="field">Hora:</td><td>{!! $consulta->hora !!}</td>
                                <td class="field">Estado:</td><td>{!! $estado[$consulta->estado] !!}</td>
                            </tr>
                            <tr>
                                <td class="field">Sintomas:</td><td>{!! $consulta->sintomas !!}</td>
                                <td class="field">Temperatura:</td><td>{!! $consulta->temperatura !!}</td>
                                <td class="field">Presion:</td><td>{!! $consulta->presion !!}</td>
                            </tr>
                            <tr>
                                <td class="field">Glucosa:</td><td>{!! $consulta->glucosa !!}</td>
                                <td class="field">Peso:</td><td>{!! $consulta->peso !!}</td>
                                <td class="field">Estatura:</td><td>{!! $consulta->estatura !!}</td>
                            </tr>
                            <tr>
                                <td class="field">Inicio Consulta:</td><td colspan="2">{!! $consulta->inicioHora !!}</td>
                                <td class="field">Fin Consulta:</td><td colspan="2">{!! $consulta->finHora !!}</td>
                            </tr>
                            @if(isset($consulta->padecimiento))
                                <tr>
                                    <td class="field">Padecimiento:</td><td>{!! $consulta->padecimiento !!}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="field">Diagnostico:</td><td>{!! $consulta->diagnostico !!}</td>
                                <td class="field">Observaciones:</td><td>{!! $consulta->observaciones !!}</td>
                            </tr>
                        </table>
                    @endforeach

                </div>
            </div>
            <!--CIRUGIAS-->
            <div class="grupoFicha">
                            <div class="page-header-sub"><strong>Cirugias</strong></div>
                            <div class="page-body">
                                <table>
                                @foreach($datos["cirugias"] as $cirugia)
                                    <tr>
                                        <td class="field">Fecha:</td><td>{!! $cirugia->fecha !!}</td>
                                        <td class="field">Hora:</td><td>{!! $cirugia->hora !!}</td>
                                        <td class="field">Estado:</td><td>{!! $estado[$cirugia->estado] !!}</td>
                                        <td class="field">Inicio:</td><td>{!! $cirugia->inicioHora !!}</td>
                                        <td class="field">Fin:</td><td>{!! $cirugia->finHora !!}</td>
                                        @if(isset($cirugia->padecimiento))
                                        <td class="field">Padecimiento:</td><td>{!! $cirugia->padecimiento !!}</td>
                                        @endif
                                        <td class="field">Cirugia:</td><td>{!! $cirugia->cirugia !!}</td>
                                    </tr>
                                @endforeach
                                </table>
                            </div>
                        </div>
            <!--PADECIMIENTOS-->
            <div class="grupoFicha">
                <div class="page-header-sub"><strong> Padecimientos</strong></div>
                <div class="page-body">
                    <table>
                    @foreach($datos["padecimientos"] as $padecimiento)
                        <tr>
                            <td class="field">Padecimiento:</td><td>{!! $padecimiento->padecimiento !!}</td>
                            <td class="field">Ultima cita:</td><td>{!! $padecimiento->ultima_cita !!}</td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
            <!--RECETAS-->
            <div class="grupoFicha">
                <div class="page-header-sub"><strong> Recetas</strong></div>
                <div class="page-body">
                    <table>
                    @foreach($datos["recetas"] as $receta)
                        <tr>
                            <td class="field">Fecha:</td><td>{!! $receta->fecha !!}</td>
                            <td class="field">Receta:</td><td>{!! $receta->receta !!}</td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>

        </div>
    </body>
</html>