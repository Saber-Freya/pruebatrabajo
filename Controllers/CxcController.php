<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Libraries\Repositories\CxcRepository;
use App\Libraries\Repositories\GeneralRepository;
use Mitul\Controller\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Response;
use Flash;

class CxcController extends AppBaseController
{

	private $CxcRepository;

	function __construct(CxcRepository $CxcRepo)
	{
		$this->CxcRepository = $CxcRepo;
		$this->middleware('auth');
	}
  public function condiciones(Request $request) {
    $input = $request->paginate(30);
    return view('cxc.fields')->with("datos",$input);
  }
  public function cuentasPorCobrar(Request $request){
    $input = $request->all();

    $input["seccion"] = (!isset($input["seccion"]))? "Cuentas por Cobrar":$input["seccion"];
    $pagos = $this->CxcRepository->presupuestosAprobados($input);
    $factura = DB::table('empresas')->where('id', 1)->select('modulo_facturacion')->pluck('modulo_facturacion');
    $clientes = GeneralRepository::getClientes2();
    $metodos = GeneralRepository::getMetodosDePago();
    $totales = GeneralRepository::getTotalesFromResult($pagos[1]);

    return view('cxc.index')
      ->with('pagos', $pagos[0])
      ->with('totales',$totales)
      ->with('metodos', $metodos[1])
      ->with('clientes',$clientes)
      ->with('q',$input)
      ->with('fact', $factura);
  }
  public function ingresosTotales(Request $request){
    $input = $request->all();
    $input["seccion"] = "Ingresos Totales";
    $request = new Request($input);
    return $this->cuentasPorCobrar($request);
  }
  public function guardarFacturaExterna(Request $request){
    $input = $request->all();
    $recibo = $input['recibo'];
    $id_cliente = $input['id_cliente'];
    $id_pagos = $input['id_pagos'];
    $aseguradora = $input['aseguradora'];
    $id_folio = $input['idFact'];
    $empresa = $this->CxcRepository->getDatosEmpresa();
    $demo = true;
    $cliente = $this->CxcRepository->getClienteById($id_cliente);
    $datosFacturacion = $this->CxcRepository->getdatosFacturacion($id_cliente);
    $folio = $this->CxcRepository->getFolioById($id_folio);
    $total = $this->CxcRepository->getTotalByIdPagos($id_pagos);
    $id_factura = $this->CxcRepository->guardarFacturaExt($total, $id_cliente, $folio,$id_folio);
    foreach($id_pagos as $id_pago){
      $this->CxcRepository->guardarIdFacturaEnPagosExt($id_factura, $id_pago, $recibo, $id_folio, $aseguradora);
    }
    return;
  }
  public function generarRecibo(Request $request){
    $input = $request->all();
    $recibo = $input['recibo'];
    $id_cliente = $input['id_cliente'];
    $id_pagos = $input['id_pagos'];
    $aseguradora = $input['aseguradora'];
    $empresa = $this->CxcRepository->getDatosEmpresa();
    $demo = true;
    $cliente = $this->CxcRepository->getClienteById($id_cliente);
    $datosFacturacion = $this->CxcRepository->getdatosFacturacion($id_cliente);
    // -1. -- Seleccionar el Id de Folio basado en el Recibo
    $id_folio = "";
    switch($recibo)
    {
      case "nota": $id_folio = 2; break;
      case "invoice": $id_folio = 3; break;
      case "factura": $id_folio = 1; break;
      default : $id_folio= 0; break;
    }

    // 0. -- Obtener la informacion del folio con el id_folio que obtuvimos (serie, folio_actual, id)
    $folio = $this->CxcRepository->getFolioById($id_folio);

    // 1. -- Seleccionar el total de los pagos ligados --
    $total = $this->CxcRepository->getTotalByIdPagos($id_pagos);

    // 1.5 -- Verificar si sera factura o sera Nota o Invoice --

    if($recibo == "factura"){
      //DATOS NECESARIOS
      $fecha = date('Y-m-d\TH:i:s');
      $lugar = $empresa->cd.', '.$empresa->edo.', Mexico';
      //$cer = "PRUEBA00000301410596.cer";
      $cer = $empresa->certificado;
      $cer = str_replace(".cer", "", $cer);
      $pem = "CSD_prueba_G0uk931014452_20131125_180812.key.pem";

      //PLANTILLA DEL XML
      $schemaLocation = 'http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd';

      if ($demo) {
        $cfdi = "<cfdi:Comprobante xmlns:cfdi='http://www.sat.gob.mx/cfd/3' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xs='http://www.w3.org/2001/XMLSchema' xsi:schemaLocation='http://www.sat.gob.mx/cfd/3 $schemaLocation' version='3.2' fecha='fechaEcons' serie='serieEcons' folio='folioEcons' subTotal='subtotalEcons' descuento='0' total='totalEcons' Moneda='monedaEcons' TipoCambio='tipoCambioEcons' condicionesDePago='Contado' tipoDeComprobante='ingreso' noCertificado='20001000000200000192' certificado='' formaDePago='PAGO EN UNA SOLA EXHIBICION' metodoDePago='metodoPagoEcons' NumCtaPago='cuentaEcons' LugarExpedicion='lugarEcons' sello=''><cfdi:Emisor nombre='emisorEcons' rfc='rfcEmisorEcons'><cfdi:DomicilioFiscal calle='calleEmisorEcons' noExterior='noExtEmisorEcons' colonia='colEmisorEcons' localidad='localidadEmisorEcons' municipio='municipioEmisorEcons' estado='estadoEmisorEcons' pais='México' codigoPostal='cpEmisorEcons'/><cfdi:ExpedidoEn calle='calleExpedidoEcons' noExterior='noExtExpedidoEcons' colonia='coloniaExpedidoEcons' localidad='localidadExpedidoEcons' municipio='municipioExpedidoEcons' estado='estadoExpedidoEcons' pais='México' codigoPostal='cpExpedidoEcons'/><cfdi:RegimenFiscal Regimen='regimenEmisorEcons'/></cfdi:Emisor><cfdi:Receptor nombre='receptorEcons' rfc='rfcReceptorEcons'><cfdi:Domicilio calle='calleReceptorEcons' noExterior='noExtReceptorEcons' colonia='colReceptorEcons' localidad='localidadReceptorEcons' municipio='minucipioReceptorEcons' estado='estadoReceptorEcons' pais='paisCli' codigoPostal='cpReceptorEcons'/></cfdi:Receptor><cfdi:Conceptos>conceptosEcons </cfdi:Conceptos><cfdi:Impuestos totalImpuestosTrasladados='ivaEcons'><cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='tasaEcons' importe='ivaEcons2'/></cfdi:Traslados></cfdi:Impuestos></cfdi:Comprobante>";
      } else {
        $cfdi = "<cfdi:Comprobante xmlns:cfdi='http://www.sat.gob.mx/cfd/3' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xs='http://www.w3.org/2001/XMLSchema' xsi:schemaLocation='http://www.sat.gob.mx/cfd/3 $schemaLocation' version='3.2' fecha='fechaEcons' serie='serieEcons' folio='folioEcons' subTotal='subtotalEcons' descuento='0' total='totalEcons' Moneda='monedaEcons' TipoCambio='tipoCambioEcons' condicionesDePago='Contado' tipoDeComprobante='ingreso' noCertificado='' certificado='' formaDePago='PAGO EN UNA SOLA EXHIBICION' metodoDePago='metodoPagoEcons' NumCtaPago='cuentaEcons' LugarExpedicion='lugarEcons' sello=''><cfdi:Emisor nombre='emisorEcons' rfc='rfcEmisorEcons'><cfdi:DomicilioFiscal calle='calleEmisorEcons' noExterior='noExtEmisorEcons' colonia='colEmisorEcons' localidad='localidadEmisorEcons' municipio='municipioEmisorEcons' estado='estadoEmisorEcons' pais='México' codigoPostal='cpEmisorEcons'/><cfdi:ExpedidoEn calle='calleExpedidoEcons' noExterior='noExtExpedidoEcons' colonia='coloniaExpedidoEcons' localidad='localidadExpedidoEcons' municipio='municipioExpedidoEcons' estado='estadoExpedidoEcons' pais='México' codigoPostal='cpExpedidoEcons'/><cfdi:RegimenFiscal Regimen='regimenEmisorEcons'/></cfdi:Emisor><cfdi:Receptor nombre='receptorEcons' rfc='rfcReceptorEcons'><cfdi:Domicilio calle='calleReceptorEcons' noExterior='noExtReceptorEcons' colonia='colReceptorEcons' localidad='localidadReceptorEcons' municipio='minucipioReceptorEcons' estado='estadoReceptorEcons' pais='paisCli' codigoPostal='cpReceptorEcons'/></cfdi:Receptor><cfdi:Conceptos>conceptosEcons </cfdi:Conceptos><cfdi:Impuestos totalImpuestosTrasladados='ivaEcons'><cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='tasaEcons' importe='ivaEcons2'/></cfdi:Traslados></cfdi:Impuestos></cfdi:Comprobante>";
      }


      //CONCEPTOS PARA LA PLANTILLA
      $conceptos = $this->CxcRepository->findproductosByMultipleIdPagos($id_pagos);
      $conceptos = GeneralRepository::limpiaCadena($conceptos);

      $conceptos_cfdi = "";
      $conceptos_cadena = "<cfdi:Concepto cantidad='cantidadEcons' unidad='unidadEcons' noIdentificacion='noIdentificacionEcons' descripcion='descripcionEcons' valorUnitario='valorUnitarioEcons' importe='importeEcons'/>";
      for ($i = 0; $i < count($conceptos); $i++) {
        if($conceptos[$i]->descripcion == "")
          $conceptos[$i]->descripcion ="null";
        $conceptos_cfdi .= $conceptos_cadena;
        $id_prod = $conceptos[$i]->id;
        $concepto = $conceptos[$i]->descripcion;
        $unidad = "Pieza";
        $cant_tmp = 1;
        //$precio_unitario = $conceptos[$i]->precio_u;
        //$importe_tmp = $conceptos[$i]->precio_u*1;
        $precio_unitario = round($conceptos[$i]->pago/1.16, 2);
        $importe_tmp = round($conceptos[$i]->pago/1.16, 2);
        $conceptos_cfdi = str_replace("cantidadEcons", $cant_tmp, $conceptos_cfdi);
        $conceptos_cfdi = str_replace("unidadEcons", $unidad, $conceptos_cfdi);
        $conceptos_cfdi = str_replace("noIdentificacionEcons", $id_prod, $conceptos_cfdi);
        $conceptos_cfdi = str_replace("descripcionEcons", $concepto, $conceptos_cfdi);
        $conceptos_cfdi = str_replace("valorUnitarioEcons", $precio_unitario, $conceptos_cfdi);
        $conceptos_cfdi = str_replace("importeEcons", $importe_tmp, $conceptos_cfdi);
      }
      //REMPLAZAR VALORES EN LA PLANTILLA
      /* REPLACE DE CABECERA */
      /*$tipo_pago = "Efectivo";
      if($conceptos[0]->metodo_pago)
        $tipo_pago = "Transferencia";*/
      $subtotal = round($total/1.16, 2);
      $cfdi = str_replace("fechaEcons", $fecha, $cfdi);
      $cfdi = str_replace("folioEcons", $folio->folio_actual+1, $cfdi);
      $cfdi = str_replace("serieEcons", "NO", $cfdi);
      $cfdi = str_replace("subtotalEcons", number_format($subtotal,2,'.',''), $cfdi);
      $cfdi = str_replace("totalEcons", number_format($total,2,'.',''), $cfdi);
      $cfdi = str_replace("monedaEcons", "MXN", $cfdi);
      $cfdi = str_replace("tipoCambioEcons", "17.50", $cfdi);
      $cfdi = str_replace("tipoDocEcons", $recibo, $cfdi);
      $cfdi = str_replace("metodoPagoEcons", $datosFacturacion->metodoPago, $cfdi);//------------------- FORMA DE PAGO -------//
      if($datosFacturacion->numCuenta=="")
        $cfdi = str_replace("cuentaEcons", "No identificado", $cfdi);
      else{
        $numCuenta = $datosFacturacion->numCuenta;
        $numCuenta = substr($numCuenta, -4);
        $cfdi = str_replace("cuentaEcons", $numCuenta, $cfdi);
      }
      $cfdi = str_replace("lugarEcons", $lugar, $cfdi);


      /* REPLACE DE EMISOR */
      $cfdi = str_replace("emisorEcons", $empresa->nom_comercial, $cfdi);
      if ($demo)
        $cfdi = str_replace("rfcEmisorEcons", 'ESI920427886', $cfdi);
      else
        $cfdi = str_replace("rfcEmisorEcons", $empresa->nom_comercial, $cfdi);

      $cfdi = str_replace("calleEmisorEcons", $empresa->calle, $cfdi);
      $cfdi = str_replace("noExtEmisorEcons", $empresa->num_ext, $cfdi);
      $cfdi = str_replace("colEmisorEcons", $empresa->colonia, $cfdi);
      $cfdi = str_replace("localidadEmisorEcons", $empresa->localidad, $cfdi);
      $cfdi = str_replace("municipioEmisorEcons", $empresa->cd, $cfdi);
      $cfdi = str_replace("estadoEmisorEcons", $empresa->edo, $cfdi);
      $cfdi = str_replace("cpEmisorEcons", $empresa->codigo_postal, $cfdi);
      $cfdi = str_replace("regimenEmisorEcons", $empresa->regimen, $cfdi);
      /* REPLACE DE EXPEDIDO */
      $cfdi = str_replace("calleExpedidoEcons", $empresa->calle, $cfdi);
      $cfdi = str_replace("noExtExpedidoEcons", $empresa->num_ext, $cfdi);
      $cfdi = str_replace("coloniaExpedidoEcons", $empresa->colonia, $cfdi);
      $cfdi = str_replace("localidadExpedidoEcons", $empresa->localidad, $cfdi);
      $cfdi = str_replace("municipioExpedidoEcons", $empresa->cd, $cfdi);
      $cfdi = str_replace("estadoExpedidoEcons", $empresa->edo, $cfdi);
      $cfdi = str_replace("cpExpedidoEcons", $empresa->codigo_postal, $cfdi);

      /* REPLACE DE RECEPTOR ->aprobado JGVS*/
      $cfdi = str_replace("receptorEcons", $datosFacturacion->razon_social, $cfdi);
      $cfdi = str_replace("rfcReceptorEcons", $datosFacturacion->rfc, $cfdi);
      $cfdi = str_replace("calleReceptorEcons", $datosFacturacion->calle, $cfdi);
      $cfdi = str_replace("noExtReceptorEcons", $datosFacturacion->num_ext, $cfdi);
      $cfdi = str_replace("colReceptorEcons", $datosFacturacion->col, $cfdi);
      $cfdi = str_replace("localidadReceptorEcons", $datosFacturacion->ciudad, $cfdi);
      $cfdi = str_replace("minucipioReceptorEcons", $datosFacturacion->ciudad, $cfdi);
      $cfdi = str_replace("estadoReceptorEcons", $datosFacturacion->estado, $cfdi);
      $cfdi = str_replace("paisCli", "Mexico", $cfdi);
      $cfdi = str_replace("cpReceptorEcons", $datosFacturacion->cp, $cfdi);
      /* REPLACE DE CONCEPTOS */

      $cfdi = str_replace("conceptosEcons", $conceptos_cfdi, $cfdi);

      /* REPLACE DE IMPUESTOS */
      $iva = round($total-$subtotal, 2);
      $cfdi = str_replace("ivaEcons2", $iva, $cfdi);
      $cfdi = str_replace("ivaEcons", "16", $cfdi);
      $cfdi = str_replace("tasaEcons", "16", $cfdi);

      if (!include(app_path() . '/Facturacion/timbrarXML.php')) {
        echo 'error al cargar clase timbrarXML.';
        exit;
      }

      //dump($cfdi);
      $rs = TimbrarXML($datosFacturacion->rfc, $cer, $pem, $cfdi, $demo);
      //dump($rs);

      if ($rs["exito"] == 1) {
        $id_factura = $this->CxcRepository->guardarFactura($total, $id_cliente, $folio, $rs["uuid"]);
        // 3. -- Actualizar la tabla pagos, para asignarles el tipo de documento, el id de factura y el Status adecuado (PAGAR)
        foreach($id_pagos as $id_pago){
          $this->CxcRepository->guardarIdFacturaEnPagos($id_factura, $id_pago, $recibo, $folio, $aseguradora);
        }

        $pdf = $this->facturemosPro($id_factura, $recibo, $id_folio, $id_cliente, $id_pagos, $rs, $total, $demo);



        return $rs["uuid"];
      }
      else{
        //return "error rs";
        $rs["cfdi"] = $cfdi;
        return $rs;
      }


      //dump($rs);
    }
    else{
      // 2. -- Generar el nombre uuid que tendra el archivo --
      $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
      $numerodeletras=36; //numero de letras para generar el texto
      $uuid = ""; //variable para almacenar la cadena generada
      for($i=0;$i<$numerodeletras;$i++)
      {
        $uuid .= substr($caracteres,rand(0,strlen($caracteres)),1);
      }

      // 2. -- Guardar la informacion de la factura --
      $id_factura = $this->CxcRepository->guardarFactura($total, $id_cliente, $folio, $uuid);

      // 3. -- Actualizar la tabla pagos, para asignarles el tipo de documento, el id de factura y el Status adecuado (PAGAR)
      foreach($id_pagos as $id_pago){
        $this->CxcRepository->guardarIdFacturaEnPagos($id_factura, $id_pago, $recibo, $folio, $aseguradora);
      }

      // 4. -- Aqui obtenemos lo necesario para generar la factura, (La informacion del pago) --
      $pdf = $this->facturemos2($recibo, $id_folio, $id_cliente, $id_pagos, $uuid, $total);



    }



    return $uuid;
  }
  public function facturemosPro($id_factura, $recibo, $id_folio, $id_cliente, $id_pagos, $rs, $total, $demo){
    //dump($rs);
    //usar ventas.facturaPro
    $empresa = $this->CxcRepository->getDatosEmpresa();
    $fecha = date('Y-m-d\TH:i:s');
    $lugar = $empresa->cd.', '.$empresa->edo.', Mexico';
    $cer = $empresa->certificado;
    $cer = str_replace(".cer", "", $cer);
    $pem = "CSD_prueba_G0uk931014452_20131125_180812.key.pem";
    $conceptos = $this->CxcRepository->findproductosByMultipleIdPagos($id_pagos);
    $cliente = $this->CxcRepository->getClienteById($id_cliente);
    $datosFacturacion = $this->CxcRepository->getdatosFacturacion($id_cliente);
    $factura = $this->CxcRepository->getFacturaById($id_factura);
    $v =new EnLetras();

    $subtotal = $total;
    $iva = round($total*0.16,2);
    $total = round($total*1.16,2);

    $letra = strtoupper($v->ValorEnLetras($total,"pesos"));


    //DATOS PARA LA FACTURA
    $uuid = $rs["uuid"];
    $path = storage_path()."/pdf/facturas";
    $pathDemo = storage_path().'/pdf/facturas/demos';

    if($demo){
      $xml = simplexml_load_file($pathDemo.'/'.$uuid.'.xml');
      $dirPng = $pathDemo.'/'.$uuid.'.png';
    }
    else{
      $xml = simplexml_load_file($path.'/'.$uuid.'.xml');
      $dirPng = $path.'/'.$uuid.'.png';
    }

    $mostrar_moneda = "<b>Moneda: </b>MXN<br>";

    $ns = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('c', $ns['cfdi']);
    $xml->registerXPathNamespace('t', $ns['tfd']);
    $tfd = array();
    foreach ($xml->xpath('//t:TimbreFiscalDigital') as $dato) {
      $tfd = $dato;
    }
    $selloCFDI = $tfd['selloCFD'];
    $CFDI1 = substr($selloCFDI, 0,130);
    $CFDI2 = substr($selloCFDI, 130,strlen($selloCFDI));
    $CFDI1_1 = substr($CFDI1, 0,82);
    $CFDI1_2 = substr($CFDI1, 82,89);

    $selloSAT = $tfd['selloSAT']; //Captura la cadena del SAT
    $selloSAT1 = substr($selloSAT, 0, 130); //Corta la cadena en 2
    $selloSAT2 = substr($selloSAT, 130, strlen($selloSAT));



    //GENERAMOS PDF DE LA FACTURA
    $vista = view('cxc.facturaPro')
      ->with('doc', GeneralRepository::limpiaCadena($recibo))
      ->with('rs', GeneralRepository::limpiaCadena($rs))
      ->with('conceptos', GeneralRepository::limpiaCadena($conceptos))
      ->with('fecha', $fecha)
      ->with('cliente', GeneralRepository::limpiaCadena($cliente))
      ->with('datosFacturacion',GeneralRepository::limpiaCadena($datosFacturacion))
      ->with('letra', GeneralRepository::limpiaCadena($letra))
      ->with('tfd', $tfd)
      ->with('cer', $cer)
      ->with('CFDI1', $CFDI1)
      ->with('CFDI2', $CFDI2)
      ->with('CFDI1_1', $CFDI1_1)
      ->with('CFDI1_2', $CFDI1_2)
      ->with('selloSAT1', $selloSAT1)
      ->with('selloSAT2', $selloSAT2)
      ->with('factura', $factura)
      ->with('empresa', GeneralRepository::limpiaCadena($empresa))
      ->with('subtotal', $subtotal)
      ->with('iva', $iva)
      ->with('total', $total);



    //return $vista;

    //Constructor de la ruta para almacenar en la BD
    if($demo) {
      $path = storage_path().'/pdf/facturas/'. $uuid . ".pdf";
      $pathShow = storage_path().'/pdf/facturas/demos/'.$uuid.".pdf";
    }
    else {
      $path = $path . "/" . $uuid . ".pdf";
      $pathShow = "pdf/facturas/".$uuid.".pdf";
    }

    //return $content;

    //		print_r($cotizaciones);
    $pdf = App::make('dompdf.wrapper');
    $pdf->loadHTML($vista);

    $pdf->save($pathShow);

    //enviar por correo
    $input["uuid"] = $uuid;
    $input["correos"] = $datosFacturacion->email;
    /*dd($input["correos"]);*/
    $request = new Request($input);
    $this->enviarFacturaEmail($request);

    return $uuid;
  }
  public function facturemos2($recibo, $id_folio, $id_cliente, $id_pagos, $uuid, $total){
    $tipoDoc = $recibo;
    //OBTENER DATOS
    $cliente = $this->CxcRepository->getClienteById($id_cliente);
    $conceptos = $this->CxcRepository->findproductosByMultipleIdPagos($id_pagos);
    $doc = $this->CxcRepository->getFolioById($id_folio);
    $empresa = $this->CxcRepository->getDatosEmpresa();
    if ($tipoDoc == 1 ){
      //Proximamente
    }else{
      //DATOS NECESARIOS
      $fecha = date('Y-m-d\TH:i:s');

      //DATOS PARA COMPROBANTE
      $path = public_path()."/comprobantes/demo";
      $v =new EnLetras();
      $letra = strtoupper($v->ValorEnLetras($total,"pesos"));


      //return limpiaCadena($conceptos);
      //dd($empresa);
        $vista = view('cxc.factura')
          ->with("doc", GeneralRepository::limpiaCadena($recibo))
          ->with("folio", GeneralRepository::limpiaCadena($doc))
          ->with("uuid", $uuid)
          ->with("cliente", GeneralRepository::limpiaCadena($cliente))
          ->with("conceptos", GeneralRepository::limpiaCadena($conceptos))
          ->with("letra", GeneralRepository::limpiaCadena($letra))
          ->with("total", $total)
          ->with("empresa", GeneralRepository::limpiaCadena($empresa))
          ->with("fecha", $fecha);

      //return $vista;






      //Constructor de la ruta para almacenar en la BD

      $path = $path . "/" . $uuid . ".pdf";
      $pathShow = storage_path()."/pdf/facturas/".$uuid.".pdf";
      $pdf = App::make('dompdf.wrapper');

      $pdf->loadHTML($vista);
      $pdf->save($pathShow);
      //enviar por correo
      $input["uuid"] = $uuid;
      $input["correos"] = $cliente->email;
      /*dd($input["correos"]);*/
      $request = new Request($input);
      $this->enviarFacturaEmail($request);

      //ob_end_flush();
      //return $pdf->stream($uuid . ".pdf");

      return Response::make(file_get_contents($pathShow),200,[
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$pathShow.'"',
      ]);
    }
  }
  public function verRecibo($uuid){
    $name = storage_path()."/pdf/facturas/".$uuid.".pdf";
    if(\Request::is('*/f/*'))
      $name = storage_path()."/pdf/facturas/demos/".$uuid.".pdf";


    if (File::exists($name)){
      return Response::make(file_get_contents($name),200,[
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$name.'"'
      ]);
    }
    else{
      $id_pagos = $this->CxcRepository->getPagosByUuid($uuid);
      $recibo = $this->CxcRepository->getReciboByUuid($uuid);
      switch($recibo)
      {
        case "nota": $id_folio = 2; break;
        case "invoice": $id_folio = 3; break;
        case "factura": $id_folio = 1; break;
        default : $id_folio= 0; break;
      }
      $id_cliente = $this->CxcRepository->getIdClienteByUuid($uuid);
      $total =$this->CxcRepository->getTotalByIdPagos($id_pagos);
      //Hablarle al Facturemos 2 aqui.
      $pdf = $this->facturemos2($recibo, $id_folio, $id_cliente, $id_pagos, $uuid, $total);
      //$pdf = $this->productosRepository->generarRecibo($id_pagos, $recibo, $uuid);
      return $pdf;
    }

  }
  public function descargarXML($uuid){
    $fn = storage_path()."/pdf/facturas/demos/".$uuid.".xml";

    $nombre = $uuid.".xml";
    header("Content-Disposition: attachment; filename=\"" . $nombre . "\";" );
    header('Content-Type: text/xml');
    readfile($fn);
  }
  public function enviarFacturaEmail(Request $request){
    $input = $request->all();

    $uuid = $input['uuid'];
    $factura = $this->CxcRepository->getReciboByUuid($uuid);

    //array de correos
    $correos = $input['correos'];

    if($factura == "factura") {
      $name = storage_path() . "/pdf/facturas/demos/" . $uuid . ".pdf";
      $xml = storage_path()."/pdf/facturas/demos/".$uuid.".xml";
      $adjuntos[0] = $name;
      $adjuntos[1] = $xml;
      $vista = 'emails.factura';
      $subject = "Factura electronica";
    } else {
      $name = storage_path() . "/pdf/facturas/" . $uuid . ".pdf";
      $adjuntos[0] = $name;
      $vista = 'emails.nota';
      $subject = "Comprobante electronico";
    }


    $domain = $_SERVER['SERVER_NAME'];
    $from = "no-reply@".$domain;

    //Verifico si existe el archivo pdf de la factura

    if (!File::exists($name))
    {
      $id_pagos = $this->CxcRepository->getPagosByUuid($uuid);
      switch($factura)
      {
        case "nota": $id_folio = 2; break;
        case "invoice": $id_folio = 3; break;
        case "factura": $id_folio = 1; break;
        default : $id_folio= 0; break;
      }
      $id_cliente = $this->CxcRepository->getIdClienteByUuid($uuid);
      $total =$this->CxcRepository->getTotalByIdPagos($id_pagos);
      //Hablarle al Facturemos 2 aqui.
      $this->facturemos2($factura, $id_folio, $id_cliente, $id_pagos, $uuid, $total);

    }
    if ($correos != ''){
      Mail::send($vista, array("empresa"=>$domain), function($message) use ($adjuntos,$correos,$from,$subject) {
        $message->from($from)->to($correos)->subject($subject);
        $size = sizeOf($adjuntos); //get the count of number of attachments

        for ($i=0; $i < $size; $i++) {
          $message->attach($adjuntos[$i]);
        }
      },true);
      return Mail::failures();
    }
  }
  public function registrarPago(Request $request){
    $input = $request->all();

    $pago = $this->CxcRepository->registrarPago($input);
    Flash::message("Pago registrado exitosamente");
    return redirect('/cuentas_por_cobrar');
  }
  public function registrarAbono(Request $request){
    $input = $request->all();
    $saldo = $this->CxcRepository->registrarAbono($input);
    return $saldo;
  }
  public function getAbonosByPago(Request $request){
    $input = $request->all();
    $pago = $this->CxcRepository->getPagoById($input["idpago"]);
    $abonos = $this->CxcRepository->getAbonosByPago($input);
    $totales = GeneralRepository::getTotalesFromResult($abonos);
    return [$abonos,$totales,$pago];
  }
  public function cancelarPago(Request $request){
    $input = $request->all();
    $idpago = $input['idpago'];

    $pago=$this->CxcRepository->getPagoById($idpago);
    $factura = $this->CxcRepository->getFacturaById($pago->id_factura);

    if($factura->serie == "NO" && $factura->demo == 0 && $pago->documento=="factura") {
      $rfc = $factura->rfc;
      $uuid = $factura->uuid;
      $demo = $factura->demo;
      //Cancelar factura Real :O
      if(!include(app_path().'/Facturacion/cancelarEcons.php'))
      {
        echo 'error al cargar clase Cancelar.';
        exit;
      }
      $rs=pruebaCancelacion($rfc,$uuid,$demo);
      if($rs["exito"]==1 || $rs["facModerna"]=="[202] - El UUID ha sido previamente cancelado.\n")
        $this->CxcRepository->cancelarFactura($uuid);

      $this->CxcRepository->cancelarPagos($factura->id);

      return $rs;
    }
    else{
      //Cancelar documento de mentiritas
      $this->CxcRepository->cancelarFactura($factura->uuid);
      $this->CxcRepository->cancelarPagos($factura->id);
      return "true";
    }
  }
  public function cancelarOrden(Request $request){
    $input = $request->all();
    $idOrden = $input['id'];
    //Codigo para cancelacion de Orden.

    //0. Verificar si no hay pagos realizados de la orden
    $pagados = $this->CxcRepository->getPagosByOrden($idOrden,"PAGADO");
    if(count($pagados)>0){
      return "Tiene pagos ya realizados";
    }
    //0.1 Obtener los pagos relacionados a la orden
    $pagos = $this->CxcRepository->getPagosByOrden($idOrden);

    //1. Cancelar los pagos de cuentas por cobrar que estan generados
    $request = new Request();
    foreach($pagos as $pago){
      $status = $pago->status;
      if($status=="PAGAR") {
        $request["idpago"] = $pago->id;
        $this->cancelarPago($request);
      }
      else if($status=="RECIBO"){
        //1.1 "Borrar" los pagos que estan en recibo
        $this->CxcRepository->deletePago($pago->id);
      }
    }

    //2. Cancelar de la tabla de ordenes. AKA venta_dinero
    $this->CxcRepository->cancelOrden($idOrden);
    return "true";

  }
  public function guardarPagos(Request $request){
    //Aqui guardo los pagos que se generan al aceptar la cotizacion y pongo el presupuesto en estado de aprobado para que pasen a cuentas por cobrar
    $input = $request->all();


    $venta = $this->CxcRepository->guardarVenta($input);
    $pagos = $this->CxcRepository->guardarPagos($input,$venta);

    return [$venta,$pagos];
  }
}
class EnLetras
{
  var $Void = "";
  var $SP = " ";
  var $Dot = ".";
  var $Zero = "0";
  var $Neg = "Menos";

  function ValorEnLetras($x, $Moneda )
  {
    $s="";
    $Ent="";
    $Frc="";
    $Signo="";

    if(floatVal($x) < 0)
      $Signo = $this->Neg . " ";
    else
      $Signo = "";

    if(intval(number_format($x,2,'.','') )!=$x) //<- averiguar si tiene decimales
      $s = number_format($x,2,'.','');
    else
      $s = number_format($x,2,'.','');

    $Pto = strpos($s, $this->Dot);

    if ($Pto === false)
    {
      $Ent = $s;
      $Frc = $this->Void;
    }
    else
    {
      $Ent = substr($s, 0, $Pto );
      $Frc =  substr($s, $Pto+1);
    }

    if($Ent == $this->Zero || $Ent == $this->Void)
      $s = "Cero ";
    elseif( strlen($Ent) > 7)
    {
      $s = $this->SubValLetra(intval( substr($Ent, 0,  strlen($Ent) - 6))) .
        "Millones " . $this->SubValLetra(intval(substr($Ent,-6, 6)));
    }
    else
    {
      $s = $this->SubValLetra(intval($Ent));
    }

    if (substr($s,-9, 9) == "Millones " || substr($s,-7, 7) == "Mill�n ")
      $s = $s . "de ";

    $s = $s . $Moneda;

    if($Frc != $this->Void)
    {
      $s = $s . " " . $Frc. "/100";
      //$s = $s . " " . $Frc . "/100";
    }
    $letrass=$Signo . $s . " M.N.";
    return ($Signo . $s . " M.N.");

  }


  function SubValLetra($numero)
  {
    $Ptr="";
    $n=0;
    $i=0;
    $x ="";
    $Rtn ="";
    $Tem ="";

    $x = trim("$numero");
    $n = strlen($x);

    $Tem = $this->Void;
    $i = $n;

    while( $i > 0)
    {
      $Tem = $this->Parte(intval(substr($x, $n - $i, 1).
        str_repeat($this->Zero, $i - 1 )));
      If( $Tem != "Cero" )
        $Rtn .= $Tem . $this->SP;
      $i = $i - 1;
    }


    //--------------------- GoSub FiltroMil ------------------------------
    $Rtn=str_replace(" Mil Mil", " Un Mil", $Rtn );
    while(1)
    {
      $Ptr = strpos($Rtn, "Mil ");
      If(!($Ptr===false))
      {
        If(! (strpos($Rtn, "Mil ",$Ptr + 1) === false ))
          $this->ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
        Else
          break;
      }
      else break;
    }

    //--------------------- GoSub FiltroCiento ------------------------------
    $Ptr = -1;
    do{
      $Ptr = strpos($Rtn, "Cien ", $Ptr+1);
      if(!($Ptr===false))
      {
        $Tem = substr($Rtn, $Ptr + 5 ,1);
        if( $Tem == "M" || $Tem == $this->Void)
          ;
        else
          $this->ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
      }
    }while(!($Ptr === false));

    //--------------------- FiltroEspeciales ------------------------------
    $Rtn=str_replace("Diez Un", "Once", $Rtn );
    $Rtn=str_replace("Diez Dos", "Doce", $Rtn );
    $Rtn=str_replace("Diez Tres", "Trece", $Rtn );
    $Rtn=str_replace("Diez Cuatro", "Catorce", $Rtn );
    $Rtn=str_replace("Diez Cinco", "Quince", $Rtn );
    $Rtn=str_replace("Diez Seis", "Dieciseis", $Rtn );
    $Rtn=str_replace("Diez Siete", "Diecisiete", $Rtn );
    $Rtn=str_replace("Diez Ocho", "Dieciocho", $Rtn );
    $Rtn=str_replace("Diez Nueve", "Diecinueve", $Rtn );
    $Rtn=str_replace("Veinte Un", "Veintiun", $Rtn );
    $Rtn=str_replace("Veinte Dos", "Veintidos", $Rtn );
    $Rtn=str_replace("Veinte Tres", "Veintitres", $Rtn );
    $Rtn=str_replace("Veinte Cuatro", "Veinticuatro", $Rtn );
    $Rtn=str_replace("Veinte Cinco", "Veinticinco", $Rtn );
    $Rtn=str_replace("Veinte Seis", "Veintise�s", $Rtn );
    $Rtn=str_replace("Veinte Siete", "Veintisiete", $Rtn );
    $Rtn=str_replace("Veinte Ocho", "Veintiocho", $Rtn );
    $Rtn=str_replace("Veinte Nueve", "Veintinueve", $Rtn );

    //--------------------- FiltroUn ------------------------------
    If(substr($Rtn,0,1) == "M") $Rtn = "Un " . $Rtn;
    //--------------------- Adicionar Y ------------------------------
    for($i=65; $i<=88; $i++)
    {
      If($i != 77)
        $Rtn=str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
    }
    $Rtn=str_replace("*", "a" , $Rtn);
    return($Rtn);
  }


  function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr)
  {
    $x = substr($x, 0, $Ptr)  . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
  }


  function Parte($x)
  {
    $Rtn='';
    $t='';
    $i='';
    Do
    {
      switch($x)
      {
        Case 0:  $t = "Cero";break;
        Case 1:  $t = "Un";break;
        Case 2:  $t = "Dos";break;
        Case 3:  $t = "Tres";break;
        Case 4:  $t = "Cuatro";break;
        Case 5:  $t = "Cinco";break;
        Case 6:  $t = "Seis";break;
        Case 7:  $t = "Siete";break;
        Case 8:  $t = "Ocho";break;
        Case 9:  $t = "Nueve";break;
        Case 10: $t = "Diez";break;
        Case 20: $t = "Veinte";break;
        Case 30: $t = "Treinta";break;
        Case 40: $t = "Cuarenta";break;
        Case 50: $t = "Cincuenta";break;
        Case 60: $t = "Sesenta";break;
        Case 70: $t = "Setenta";break;
        Case 80: $t = "Ochenta";break;
        Case 90: $t = "Noventa";break;
        Case 100: $t = "Cien";break;
        Case 200: $t = "Doscientos";break;
        Case 300: $t = "Trescientos";break;
        Case 400: $t = "Cuatrocientos";break;
        Case 500: $t = "Quinientos";break;
        Case 600: $t = "Seiscientos";break;
        Case 700: $t = "Setecientos";break;
        Case 800: $t = "Ochocientos";break;
        Case 900: $t = "Novecientos";break;
        Case 1000: $t = "Mil";break;
        Case 1000000: $t = "Mill�n";break;
      }

      If($t == $this->Void)
      {
        $i = $i + 1;
        $x = $x / 1000;
        If($x== 0) $i = 0;
      }
      else
        break;

    }while($i != 0);

    $Rtn = $t;
    Switch($i)
    {
      Case 0: $t = $this->Void;break;
      Case 1: $t = " Mil";break;
      Case 2: $t = " Millones";break;
      Case 3: $t = " Billones";break;
    }
    return($Rtn . $t);
  }

}