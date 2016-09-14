<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ReporteRepository;
use App\Libraries\Repositories\GeneralRepository;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Mitul\Controller\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Response;
use Flash;

class ReporteController extends AppBaseController
{

  private $ReporteRepository;

  function __construct(ReporteRepository $ReporteRepo)
  {
    $this->ReporteRepository = $ReporteRepo;
    $this->middleware('auth');
  }
  public function index(Request $request){
    $input = $request->all();
    $pacientes = GeneralRepository::getClientes2();

    return view('reportes.index')
      ->with('pacientes',$pacientes);
  }
  public function generarReporte(Request $request){
    $input = $request->all();
    $usuario = Auth::user()->id;
    $tipo = $input["tipo"];
    $datos = "";
    $titulo="";
    $template = "reportes/templates.reporte".$tipo;
    $cols = "";
    $fecha2 = date("d/m/Y H:i");
    //demo
    $usuario=1;

    switch($tipo){
      case '1':
        $filename = "ReporteProcedimientosConsultas_".$usuario;
        $titulo = "Reporte de procedimientos y consultas";
        $cols = 'A4:H4';
        //$datos = $this->ReporteRepository->getReporteProcedimientosConsultas($input);
        break;
      case '2':
        $filename = "ReporteHistorialMedico_".$usuario;
        $titulo = "Reporte de Historial Medico";
        $datos = $this->ReporteRepository->getReporteHistorialMedico($input);

        //dd($datos);
        //Este reporte se presenta en PDF
        $this->generarReportePDF($datos,$input,$fecha2,$template,$titulo,$filename);
        break;
      case '3':
        $filename = "ReporteIngresos_".$usuario;
        $titulo = "Reporte de ingresos";
        $cols = 'A4:H4';
        //$datos = $this->ReporteRepository->getReporteIngresos($input);
        break;
      case '4':
        $filename = "ReporteCuentasPorCobrar_".$usuario;
        $titulo = "Reporte de Deuda en Cuentas por cobrar";
        $cols = 'A4:K4';
        //$datos = $this->ReporteRepository->getReporteCuentasPorCobrar($input);
        break;
      case '5':
        $filename = "ReporteGastosMaterial_".$usuario;
        $titulo = "Reporte de gastos material";
        $cols = 'A4:H4';
       // $datos = $this->ReporteRepository->getReporteGastosMaterial($input);
        break;
      case '6':
        $filename = "ReporteCitas_".$usuario;
        $titulo = "Reporte de citas";
        $cols = 'A4:C4';
        //$datos = $this->ReporteRepository->getReporteCitas($input);
        break;

    }


    //demo

    return $filename;
    //$creador = Auth::user()->nombre;


    if(count($datos)==0 || $datos=="")
    {
      return 0;
    }

    /*pasar de objeto a array*/
    foreach($datos as $key=>$concepto)
    {
      $datos[$key]=json_decode(json_encode($concepto),true);
    }

    //dd($datos);
    Excel::create($filename, function($excel) use ($datos,$input,$fecha2,$template,$titulo,$cols){
      $excel->sheet('Reporte', function($sheet) use ($datos,$input,$fecha2,$template,$titulo,$cols) {

        $sheet->loadView($template)
          ->with('datos', $datos)
          ->with('input', $input)
          ->with('fecha',$fecha2)
          ->with('titulo',$titulo);

        $sheet->setStyle(array(
          'font' => array(
            'name'      =>  'Calibri',
            'size'      =>  12
          )));
        $sheet->setAutoSize(true);
        $sheet->setAutoFilter($cols);
      });

    })->store('xls', false, true);
    return $filename;
  }
  public function descargaReporte($file){

    $name = storage_path()."/exports/".$file;

    return Response::make(file_get_contents($name),200,[

      'Content-Type' => 'application/vnd.ms-excel',
      'Content-Disposition' => 'inline; '.$name,

    ]);

  }

  public function descargaReportePDF($file){

    $name = storage_path()."/exports/".$file;

    return Response::make(file_get_contents($name),200,[
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="'.$name.'"',
    ]);

  }

  public function generarReportePDF($datos,$input,$fecha2,$template,$titulo,$filename){

    //GENERAMOS VISTA DEL PDF
    $vista = view($template)
      ->with('datos', $datos)
      ->with('input', $input)
      ->with('fecha',$fecha2)
      ->with('titulo',$titulo);

    //return $vista;

    $pathShow = storage_path()."/exports/".$filename.".pdf";
    $pdf = App::make('dompdf.wrapper');

    $pdf->loadHTML($vista);
    $pdf->save($pathShow);

    Response::make(file_get_contents($pathShow),200,[
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="'.$pathShow.'"',
    ]);

    return $filename;

  }
}
