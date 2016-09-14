<?php
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "utf-8">
    <style>
        @page { margin: 180px 40px; }
        html {
            margin:100px;
            font-family: sans-serif;
            font-size: 10px;
        }
        body {
            margin: 0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333333;
            background-color: #ffffff;
        }
        img {
            vertical-align: middle;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        td,
        th {
            padding: 0;
            font-size: 12px;
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
        #footer td{
            padding: 5px;
        }
        #datosbajologo td{
            text-align: left !important;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <table style='width: 100%;'>
        <tr style='width: 100%'>
           <td style='width: 40%; overflow: hidden; height: 97px; text-align: center;' align='center'>
               <img src="<?= asset('img/uploads/empresa/logo.png') ?>" style="max-width:300px; height:auto"/>
           </td>
           <td style='width: 20%; vertical-align: text-top; font-weight: bold; text-align: center; font-size: 15px'>
               {!! ucwords($doc) !!}
           </td>
           <td id="datosfactura">
               <b>Folio: </b>{!! $folio->serie.$folio->folio_actual !!}<br>
               <b>Fecha y Hora: </b><br>{!! $fecha !!}<br>
           </td>
        </tr>
    </table>
    <table style='width: 100%;'>
        <tr style='width: 100%' id="datosbajologo">
            <td>
                <b>Régimen Fiscal:</b> {!! $empresa->regimen !!}<br>
                <b>Razón Social: </b>{!! $empresa->nom_comercial !!}<br>
                <b>Domicilio: </b>{!! $empresa->calle !!} #{!! $empresa->num_ext !!} - {!! $empresa->num_int !!}, Col. {!! $empresa->colonia !!}
                 <br>{!! $empresa->edo !!}, {!! $empresa->cd !!}, Mexico<br>
                <b>Tel.</b>{!! $empresa->telefono !!}<br>
            </td>
            <td>
                <b>Tipo de comprobante: </b>Ingreso<br>
                <b>Forma de pago: </b>Pago en una sola exhibición<br>
                <b>Moneda: </b>MXN<br>
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
            <td style='width: 60%; vertical-align: text-top; font-size: 11px; text-align: left !important'>
                <span><b>Cliente: </b><?=$cliente->nombre?></span><br>
                <b>Domicilio: </b><?=$cliente->calle?>, <?=$cliente->num_ext?> <?=$cliente->num_int?><br>
               <!-- <b>Ubicación: </b><?=$cliente->ciudad?>, <?=$cliente->estado?>, <?=$cliente->pais?>-->
            </td>
            <td style='width: 40%; vertical-align: text-top; text-align: left !important'>
                <!--<b>C.P.: </b><?=$cliente->cp?><br>
                <b>E-mail: </b><?=$cliente->email?>-->
            </td>
        </tr>
    </table>
    <table style='width: 100%; border-collapse: collapse; margin-top:10px;'>
        <tr style='width: 100%; background-color: #EBEBEB; font-size: 11px; font-weight: bold; text-align: center; border: 0; height: 17px;'>
            <td style='width: 20%; height: 17px;'>PROYECTO.</td>
            <td style='width: 40%; height: 17px;'>DESCRIPCIÓN</td>
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
                $nombre .= "<br> Pago: ".$concepto->numeracion;
            echo "<tr style='text-align: center'>";
            echo "<td style='width: 20%; border-bottom: 1px solid black;'>".$nombre."</td>";
            echo "<td style='width: 40%; border-bottom: 1px solid black;'> ".$concepto->descripcion."</td>";
            echo "<td style='width: 20%; border-bottom: 1px solid black; text-align: center'> $ 0.00 </td>";
            echo "<td style='width: 20%; border-bottom: 1px solid black; text-align: center'>$ ".$concepto->pago."</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <br>
    <table style='width: 100%'>
        <tr>
            <td style='width: 23% !important;'>
                <!--<img width='100%' id="cbb" src='<?/*=$dirPng*/?>'>-->
            </td>
            <td style='width: 40%; vertical-align: text-top; text-align: left !important; font-size: 11px;'>
                <b>IMPORTE TOTAL EN LETRA</b><br>
                <span style='text-transform: uppercase;'>{!! $letra !!}</span>
            </td>
            <td style='width: 30%; vertical-align: text-top; text-align: right !important'>
                <b>TOTAL </b>$ {!! $total !!}
            </td>
        </tr>
    </table>
</body>
</html>