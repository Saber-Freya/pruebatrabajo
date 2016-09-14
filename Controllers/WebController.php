<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mitul\Controller\AppBaseController;
use App\Libraries\Repositories\ServicioRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebController extends AppBaseController {

	private $servicioRepository;

	function __construct(ServicioRepository $servicioRepo){
		$this->servicioRepository = $servicioRepo;
		$this->middleware('auth');
	}

	public function index(){
		return view('web/inicio');
	}

	public function nosotros(){
		return view('web/nosotros');
	}

	public function servicios(){
		return view('web/servicios');
	}

	public function contacto(){
		return view('web/contacto');
	}

	public function getCiudadesByEdoId(Request $request){
		$input = $request->all();
		return DB::table('ciudades')
				->where('estado_id', $input['id'])
				->orderBy('title', 'asc')
				->get();
	}

	public function create(){
		//
	}

	public function store(){
		//
	}

	public function show($id){
		//
	}

	public function edit($id){
		//
	}

	public function update($id){
		//
	}

	public function destroy($id){
		//
	}

	public function EnviarCorreo(Request $request){
		$dominio = $_SERVER['SERVER_NAME'];
		session_start();

		$input = $request->all();

		if($input['code'] != $_SESSION['key'] )
			return ("-1"); //Código CAPTCHA erróneo

		else {
			$nombre		= $input['nombre'];
			$telefono	= $input['telefono'];
			$correo		= $input['email'];
			$mensaje	= $input['mensaje'];
			$asunto		= $input['asunto'];

			$datos		= [
						'nombre'	=> $nombre,
						'telefono'	=> $telefono,
						'correo'	=> $correo,
						'mensaje'	=> $mensaje
			];

			if (Mail::send('web/template.correo', $datos, function ($message) use ($correo, $asunto, $dominio) {
				$message->subject	('Asunto: '.$dominio.' CONTACTO');
				/*$message->from		('no-reply@'.$dominio, $dominio);*/
				$message->to		(env('CORREO'));
			})){
				return ("1"); //Envío exitoso
			}else{
				return ("0"); //Problema al enviar el correo
			}
		}
	}

	public function guardarFechas(Request $request){
		$input = $request->all();
		$hoy = Carbon::now();
		foreach($input as $fechas);

		foreach($fechas as $fecha){

			$existe = DB::table('disponibilidad')
					/*->where('estatus',1)*/
					->where('fecha',$fecha)->get();

			dump($existe);
			if ($existe == []){
				/*dump("Guardar");*/
				DB::table('disponibilidad')
						->insert(['fecha'=>$fecha, 'estatus'=>'1', 'created_at'=>$hoy]);
			}else{
				/*dump("No guardar");*/
			}
		}
	}

	public function excel($inicio,$final){
		$servicios = $this->servicioRepository->excel($inicio, $final);

		/*dd($servicios);*/
		$fecha = Carbon::now();

		if ($servicios[0]['id'] == ''){
			dd('no hay fechas');
		}else{

			return Excel::create("web/template.reporte", function($excel) use ($servicios,$inicio, $final,$fecha){
				$excel->sheet('Reporte', function($sheet) use ($servicios,$inicio, $final,$fecha) {

					$sheet->loadView('web/template.reporte')
							->with('servicios', $servicios)
							->with('inicio', $inicio)
							->with('final', $final)
							->with('fecha', $fecha);

					$sheet->setStyle(array(
							'font' => array(
									'name'      =>  'Calibri',
									'size'      =>  12
							)));
					$sheet->setAutoSize(false);
					$sheet->setAutoFilter('A4:H4');
				});

			})
				->store('xls', public_path('reporte'))
				->export('xls');

			/*return view('web/template.reporte')
					->with('servicios', $servicios)
					->with('inicio', $inicio)
					->with('final', $final);*/

		}
	}

	public function disponibles(){
		$hoy = Carbon::now();
		$hoy = $hoy->format('Y-m-d');

		$fechas = DB::table('disponibilidad')
				->select('*')
				->where('estatus', 1)
				->where('fecha','>=', $hoy)
				->orderBy('fecha', 'asc')
				->get();
		return $fechas;
	}

	public function borrarFecha($fecha){
		DB::table('disponibilidad')
				->where('fecha',$fecha)
				->delete();
	}

	public function guardarInfoConsulta(Request $request,$id_servicio){
		$input = $request->all();

		DB::table('preconsultas')
			->insert([
				'id_servicio'=>$id_servicio,
				'id_cliente'=>$input['id_cliente'],
				'sintomas'=>$input['sintomas'],
				'temperatura'=>$input['temperatura'],
				'presion'=>$input['presion'],
				'glucosa'=>$input['glucosa'],
				'peso'=>$input['peso'],
				'estatura'=>$input['estatura'],
			]);

		if($input['id_padecimiento'] != 0){
				DB::table('servicios')->where('id', $id_servicio)
						->update(['id_padecimiento'=>$input['id_padecimiento']]);
		}

	}
	public function actualizarInfoConsulta(Request $request,$id){
		$input = $request->all();

		DB::table('padecimientos')
				->where('id', $id)
				->update(['nombre'=>$input['nombre'],'sintomas'=>$input['sintomas'],'descripcion'=>$input['descripcion']]);
	}

	public function guardarArchivos(Request $request){
		$input = $request->all();
		/*dd($input);*/

		if (empty($_FILES['archivo'])) {
			echo json_encode(['error'=>'No hay archivo que subir.']);
			// or you can throw an exception
			return; // terminate
		}

		$archivos = $_FILES['archivo'];

		$id = empty($_POST['id']) ? '' : $_POST['id'];
		$id_padecimiento = $_POST['id_padecimiento'];
		$titulo = empty($_POST['titulo']) ? '' : $_POST['titulo'];
		$des_arch = empty($_POST['des_arch']) ? '' : $_POST['des_arch'];

		$success = null;
		$paths= [];
		$nombres_archivos = $archivos['name'];
		for($i=0; $i < count($nombres_archivos); $i++){

			$ext = explode('.', basename($nombres_archivos[$i]));
			$nombre_archivo = md5(uniqid()) . "." . array_pop($ext);
			$ruta = public_path().'/img/uploads/archivos' . DIRECTORY_SEPARATOR . $nombre_archivo;

			if(move_uploaded_file($archivos['tmp_name'][$i], $ruta)) {
				$success = true;
				$paths[] = $nombre_archivo;
			}else{
				$success = false;
				break;
			}
		}

		if ($success === true) {
			$documento = $this->guardar($id, $titulo, $des_arch, $paths,$id_padecimiento);
			$salida = [];
		}elseif($success === false){
			$salida = ['error'=>'Error al cargar imágenes. Póngase en contacto con el administrador del sistema.'];
			foreach ($paths as $file) {
				unlink($file);
			}
		}else{
			$salida = ['error'=>'No fueron procesados los archivos.'];
		}
		echo json_encode($salida);
	}

	public function guardarFoto(Request $request){
		$input = $request->all();

		/*dd($input);*/

		if (empty($_FILES['archivo'])) {
			echo json_encode(['error'=>'No hay foto que guardar.']);
			return;
		}

		$archivos = $_FILES['archivo'];

		$id = empty($_POST['id']) ? '' : $_POST['id'];
		/*$titulo = empty($_POST['titulo']) ? '' : $_POST['titulo'];
		$des_arch = empty($_POST['des_arch']) ? '' : $_POST['des_arch'];*/

		$success = null;
		$paths= [];
		$nombres_archivos = $archivos['name'];
		for($i=0; $i < count($nombres_archivos); $i++){

			$ext = explode('.', basename($nombres_archivos[$i]));
			$nombre_archivo = md5(uniqid()) . "." . array_pop($ext);
			$ruta = public_path().'/img/uploads/archivos' . DIRECTORY_SEPARATOR . $nombre_archivo;

			if(move_uploaded_file($archivos['tmp_name'][$i], $ruta)) {
				$success = true;
				$paths[] = $nombre_archivo;
			}else{
				$success = false;
				break;
			}
		}

		if ($success === true) {
			$documento = $this->guardarFotoBD($id, $paths);
			$salida = [];
		}elseif($success === false){
			$salida = ['error'=>'Error al cargar foto. Póngase en contacto con el administrador del sistema.'];
			foreach ($paths as $file) {
				unlink($file);
			}
		}else{
			$salida = ['error'=>'No fueron procesados los archivos.'];
		}
		echo json_encode($salida);
	}

	public function guardar($id, $titulo, $des_arch, $paths,$id_padecimiento){
		$hoy = date("Y-m-d H:i:s");
		foreach($paths as $path){
			DB::table('archivos')
				->insert([
					'id_padecimiento' => $id_padecimiento,
					'titulo'   	=> $titulo,
					'descripcion' 	=> $des_arch,
					'archivo'   => $path,
					'created_at'=> $hoy,
					'updated_at'=> $hoy
				]);
		}
	}

	public function guardarFotoBD($id, $paths){
		$hoy = date("Y-m-d H:i:s");
		foreach ($paths as $foto) {
			DB::table('clientes')
				->where('id', $id)
				->update(['foto'=>$foto,'updated_at'=>$hoy]);
		}
	}

}
