<!DOCTYPE html>
<html>
    <head>
        <meta charset = "utf-8">
    </head>
    <body>
        <table style="width: 800px;">
            <tr><td colspan="9" style="width: 25px; font-size: 34px" class="in-line">Sistema Medico</td></tr>
            <tr>
                <td colspan="6" style="font-size: 20px; font-weight: bold;" class="in-line">{!! $titulo !!}</td>
                <td colspan="2" style="text-align: right">{!! $fecha !!}</td>
            </tr>
            <tr style="height: 30px">
                <td colspan="3"></td>
                <td >Periodo:</td>
                <td >{!! $input["fechaini"] !!}</td>
                <td >{!! $input["fechafin"] !!}</td>
            </tr>
            <tr style="background-color: #d7d7d7">
                <td style="width: 18%"><b>FECHA</b></td>
                <td style="width: 40%"><b>NOMBRE DEL PACIENTE</b></td>
                <td style="width: 18%"><b>CONVENIO</b></td>
                <td style="width: 40%"><b>CIRUGIA REALIZADA</b></td>
                <td style="width: 20%"><b>RENTA CON IVA</b></td>
                <td style="width: 20%"><b>RECIBO MATERIAL</b></td>
                <td style="width: 18%"><b>RECIBO</b></td>
                <td style="width: 23%"><b>RECIBO PAG.</b></td>
                <td style="width: 30px"><b>FECHA DE PAGO</b></td>
            </tr>

            @foreach($datos as $servicio)
                <tr style="border-bottom: 1px solid grey;">
                    <td style="width: 12%;">{!! $servicio['fecha'] !!}</td>
                    <td style="width: 40%;">{!! $servicio['paciente'] !!}</td>
                    <td style="width: 12%;">{!! $servicio['convenio'] !!}</td>
                    <td style="width: 40%;">{!! $servicio['cirugia'] !!}</td>
                    <td style="width: 20%;">$ {!! $servicio['renta'] !!}</td>
                    <td style="width: 20%;">$ {!! $servicio['total_material'] !!}</td>
                    <td style="width: 18%;">{!! $servicio['recibo'] !!}</td>
                    <?php if ($servicio['estatus'] == 1) $estatus = 'PAGADO'; ELSE $estatus = ''; ?>
                    <td style="width: 23%;">{!! $estatus !!}</td>
                    <?php if ($servicio['fecha_pago'] == '0000-00-00 00:00:00') $pago = 'PENDIENTE'; ELSE $pago = $servicio['fecha_pago']; ?>
                    <td style="width: 30%;">{!! $pago !!}</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>