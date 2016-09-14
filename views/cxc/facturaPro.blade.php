<meta charset="utf-8">
<style>
    @page { margin: 40px 40px; }
    html {
        font-family: sans-serif;
        font-size: 10px;
    }
    body {
        margin: 0;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 10px;
        color: #333333;
        background-color: #ffffff;
    }
    img {
        vertical-align: middle;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }
    td,
    th {
        padding: 0;
        font-size: 10px;
    }
    table, th{
        text-align: center !important;
    }
    table {
        background-color: transparent;
    }
    caption {
        padding-top: 8px;
        padding-bottom: 8px;
        color: #777777;
        text-align: left;
    }
    th {
        text-align: left;
    }
    #logo{
        width:250px;
    }
    table col[class*="col-"] {
        position: static;
        float: none;
        display: table-column;
    }
    table td[class*="col-"],
    table th[class*="col-"] {
        position: static;
        float: none;
        display: table-cell;
    }
    #datosfactura{
        width: 40%; text-align: right !important;
    }
    #datos1{
        width: 60%; text-align: left !important; font-size: 11px;
        padding: 10px;
    }
    #datos2{
        width: 40%; text-align: left !important; font-size: 11px;
        padding: 10px;
    }
    #cbb{
        width: 156px !important;
    }
    #footer {
            position: fixed;
            bottom: 20px;
            left: 0px;
            right: 0px;
            height: 200px;
            text-align: center;
        }
    #footer td{
        padding: 5px 0;
        margin: 10px 0;
    }
</style>
<table style='width: 100%;'>
        <tr style='width: 100%'>
            <td style='width: 40%; overflow: hidden; height: 97px; text-align: center;' align='center'>
                <img width='300px' id="logo" src='{!! asset("img/logo.png") !!}'>
            </td>
            <td style='width: 20%; vertical-align: text-top; font-weight: bold; text-align: center; font-size: 15px'>
                {!! ucwords($doc) !!}
            </td>
            <td id="datosfactura">
                <b>Folio Fiscal:</b> {!! $factura->uuid !!}<br>
                <b>Fecha y Hora de Cerficación:</b> {!! $fecha !!}<br>
                <b>Fecha y Hora de Emisión:</b> {!! $fecha !!}<br>
                <b>Número de serie CSD del SAT: </b> {!! $tfd['noCertificadoSAT'] !!}<br>
                <b>Número de serie CSD del Emisor: </b> {!! $cer !!}
            </td>
        </tr>
    </table>
    <table style='width: 100%;'>
        <tr style='width: 100%'>
            <td id="datos1">
                <b>Régimen Fiscal: </b>{!! $empresa->regimen !!}<br>
                <b>Razón Social: </b>{!! $empresa->nom_comercial !!}<br>
                <b>R.F.C.: </b>{!! $empresa->rfc !!}<br>
                <b>Lugar de expedición: </b>{!! $empresa->localidad !!}<br>
                <b>Domicilio: </b>{!! $empresa->calle !!} #{!! $empresa->num_ext !!} - {!! $empresa->num_int !!}, Col. {!! $empresa->colonia !!}, {!! $empresa->edo !!}, {!! $empresa->cd !!}, Mexico<br>
                <b>Tel.</b>{!! $empresa->telefono !!}<br>
                <b>Expedido en: </b>{!! $empresa->calle !!} #{!! $empresa->num_ext !!} - {!! $empresa->num_int !!}, Col. {!! $empresa->colonia !!}, {!! $empresa->edo !!}, {!! $empresa->cd !!}, Mexico<br>
            </td>
            <td id="datos2">
                <b>Tipo de comprobante: </b>Ingreso<br>
                <b>Forma de pago: </b>Pago en una sola exhibición<br>
                <b>Moneda: </b>MXN<br>
                <b>Número de documento: </b>{!! $factura->idFact !!}<br>
                <b>Método de pago: </b>{!! $datosFacturacion->metodoPago  !!}<br>
                <b>No. cuenta de pago: </b>{!! $datosFacturacion->numCuenta !!}<br>
                <b>Condición de pago: </b>Contado
            </td>
        </tr>
    </table>
    <table style='width: 100%; margin: 10px 0'>
        <tr style='width: 100%; border: 0; height: 27px;'>
            <td style='width: 100%; background-color: #EBEBEB; font-weight: bold; text-align: center; height: 17px;'>
                RECEPTOR
            </td>
        </tr>
    </table>
    <table style='width: 100%'>
        <tr style='width: 100%'>
            <td style='width: 60%; vertical-align: text-top; text-align: left !important'>
                <span><b>Cliente: </b>{!! $cliente->nombre !!}</span><br>
                <b>Domicilio: </b>{!! $datosFacturacion->calle !!}, {!! $datosFacturacion->num_ext !!} {!! $datosFacturacion->num_int !!}<br>
                <b>Ubicación:</b>{!! $datosFacturacion->ciudad !!}, {!! $datosFacturacion->estado !!}, México
            </td>
            <td style='width: 40%; vertical-align: text-top; text-align: left !important'>
                <b>R.F.C.: </b>{!! $datosFacturacion->rfc !!}<br>
                <b>C.P.: </b>{!! $datosFacturacion->cp !!}<br>
            </td>
        </tr>
    </table>
    <table style='width: 100%; border-collapse: collapse; margin-top:10px;'>
        <tr style='width: 100%; background-color: #EBEBEB; font-size: 11px; font-weight: bold; text-align: center; border: 0; height: 17px;'>
            <td style='width: 10%; height: 17px;'>CANT.</td>
            <td style='width: 20%; height: 17px;'>PRODUCTO.</td>
            <td style='width: 30%; height: 17px;'>DESCRIPCIÓN</td>
            <td style='width: 20%; height: 17px;'>DESCUENTO</td>
            <td style='width: 20%; height: 17px;'>PAGO</td>
        </tr>
    </table>
    <table style='width: 100%; border-collapse: collapse;'>
        <?php
        $numitems = 0;
        foreach($conceptos as $concepto)
        {
            $nombre = $concepto->nombre;
            if($concepto->numeracion != "")
                $nombre .= " Pago: ".$concepto->numeracion;
            echo "<tr style='text-align: center'>";
            echo "<td style='width: 10%; border-bottom: 1px solid black;'>1</td>";
            echo "<td style='width: 20%; border-bottom: 1px solid black;'>".$nombre."</td>";
            echo "<td style='width: 30%; border-bottom: 1px solid black;'> ".$concepto->descripcion."</td>";
            echo "<td style='width: 20%; border-bottom: 1px solid black; text-align: center'> $ 0.00 </td>";
            echo "<td style='width: 20%; border-bottom: 1px solid black; text-align: center'>$ ".$concepto->pago."</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <br>
    <table style='width: 100%'>
        <tr>
            <td style='width: 15% !important;'></td>
            <td style='width: 40%'></td>
            <td style='width: 30%; vertical-align: text-top; text-align: right !important; font-size: 12px'>
                <b>SUBTOTAL </b>$ {!! number_format($subtotal,2) !!}<br>
                <b>IVA 16% </b>$ {!! number_format($iva,2) !!}
            </td>
            <td style='width: 5% !important;'></td>
        </tr>
        <tr>
            <td style='width: 15% !important;'>
                <!--<img width='100%' id="cbb" src='<?/*=$dirPng*/?>'>-->
            </td>
            <td style='width: 40%; vertical-align: text-top; text-align: left !important; font-size: 11px;'>
                <b>IMPORTE TOTAL EN LETRA</b><br>
                <span style='text-transform: uppercase;'>{!! $letra !!}</span>
            </td>
            <td style='width: 30%; vertical-align: text-top; text-align: right !important; font-size: 12px'>
                <b>TOTAL </b>$ {!! number_format($total,2) !!}
            </td>
            <td style='width: 5% !important;'></td>
        </tr>
    </table>
   <table style='width: 100%' id="footer" >
       <tr><td colspan="3">
               <table><tr style='width: 100%; font-size: 8px;'>
                       <td style='width: 30%; text-align: left!important;'>
                           <b>Este documento es una representación impresa de un CFDI</b>
                       </td>
                       <td style='width: 20%; text-align: left!important;'></td>
                       <td style='width: 50%; text-align: right !important;'>
                           <b>Al efectuar el pago acepta los Terminos y Condiciones publicados en la página web del emisor.</b>
                       </td>
                   </tr></table>
           </td></tr>


       <tr style='width: 100%; font-size: 8px; text-align: center;  background-color: #EBEBEB;'>
           <td style='width: 44%'><hr width='100%' noshade='noshade' size='1' style='border-color: slategrey; opacity: 0.2; height: 2px;'></td>
           <td style='width: 12%'><b>Sello Digital CFDI</b></td>
           <td style='width: 44%'><hr width='100%' noshade='noshade' size='1' style='border-color: slategrey; opacity: 0.2; height: 2px'></td>
       </tr>

       <tr style='width: 100%; font-size: 7px; text-align: left !important;'>
           <td colspan="3"><?=$CFDI1?></td>
       </tr>
       <tr style='width: 100%; font-size: 7px; text-align: left !important;'>
           <td colspan="3"><?=$CFDI2?></td>
       </tr>

       <tr style='width: 100%; font-size: 8px; text-align: center; background-color: #EBEBEB;'>
           <td style='width: 43%'><hr width='100%' noshade='noshade' size='1' style='border-color: slategrey; opacity: 0.2; height: 2px'></td>
           <td style='width: 14%'><b>Sello Digital del SAT</b></td>
           <td style='width: 43%'><hr width='100%' noshade='noshade' size='1' style='border-color: slategrey; opacity: 0.2; height: 2px'></td>
       </tr>

       <tr style='width: 100%; font-size: 7px;  text-align: left !important;'>
           <td colspan="3"><?=$selloSAT1?></td>
       </tr>
       <tr style='width: 100%; font-size: 7px;  text-align: left !important;'>
           <td colspan="3"><?=$selloSAT2?></td>
       </tr>

       <tr style='width: 100%; font-size:8px; text-align: center; background-color: #EBEBEB;'>

           <td colspan="3"><b>Cadena original del complemento de certificación digital del SAT</b></td>

       </tr>

       <tr style='width: 100%; font-size: 7px;  text-align: left !important;'>
           <td colspan="3">||1.0|{!! $factura->uuid !!}|<?=$fecha?>| <?=$CFDI1_1?></td>
       </tr>
       <tr style='width: 100%; font-size: 7px; text-align: left !important;'>
           <td colspan="3"><?=$CFDI1_2?>|<?=$cer?>||</td>
       </tr>
   </table>