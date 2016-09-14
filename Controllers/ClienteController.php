<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateClienteRequest;
use App\Libraries\Repositories\EstudioRepository;
use App\Libraries\Repositories\MedicamentoRepository;
use App\Libraries\Repositories\PadecimientoRepository;
use App\Libraries\Repositories\ServicioRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ClienteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;
use App\Libraries\Repositories\GeneralRepository;

class ClienteController extends AppBaseController{

	private $clienteRepository;

	function __construct(ClienteRepository $clienteRepo,ServicioRepository $servicioRepo,EstudioRepository $estudioRepo,
						 MedicamentoRepository $medicamentoRepo,PadecimientoRepository $padecimientoRepo){
		$this->clienteRepository = $clienteRepo;
		$this->servicioRepository = $servicioRepo;
		$this->estudioRepository = $estudioRepo;
		$this->estudioRepository = $estudioRepo;
		$this->medicamentoRepository = $medicamentoRepo;
		$this->padecimientoRepository = $padecimientoRepo;
		$this->middleware('auth');

		$this->beforeFilter('ver_pacientes', array('only' => 'index') );
		$this->beforeFilter('crear_pacientes', array('only' => 'create') );
		$this->beforeFilter('crear_pacientes', array('only' => 'store') );
		$this->beforeFilter('editar_pacientes', array('only' => 'edit') );
		$this->beforeFilter('editar_pacientes', array('only' => 'update') );
		$this->beforeFilter('eliminar_pacientes', array('only' => 'delete') );
	}

	public function index(Request $request){
	    $input = $request->all();
		$result = $this->clienteRepository->search($input);
		$clientes = $result[0];
		$attributes = $result[1];

		return view('clientes.index')
		    ->with('clientes', $clientes)
		    ->with('attributes', $attributes);
	}

	public function create(){
		$emails = [];
		$estados = $this->clienteRepository->getEstados();
    	$formaPago = GeneralRepository::getFormaPago();
		$listaParentescos = $this->clienteRepository->getParentescos();
		return view('clientes.create')
					->with('estados', $estados)
					->with('emails', $emails)
					->with('listaParentescos', $listaParentescos)
		  			->with('formaPago',$formaPago);
	}

	public function store(CreateClienteRequest $request){
        $input = $request->all();
		/*dd($input);*/
		$hoy = strftime( "%Y-%m-%d", time() );
		$input['fecha_alta'] = $hoy;
		$cliente = $this->clienteRepository->store($input);
		$id_cliente = $cliente['id'];
		$emails = $input["emails"];
		$emails = json_decode( $emails, true );
		$contactos = $input["contactos"];
		$contactos = json_decode( $contactos, true );
		if (!empty($emails)) {
			$this->clienteRepository->multiEmails($id_cliente,$emails);

			//guardar correo principal
			for ($i = 0; $i <= count($emails); $i++) {$email = $emails[0]['id'];}
			$this->clienteRepository->correoPrincipal($id_cliente,$email);

		}
		if (!empty($contactos)) {
			$this->clienteRepository->guardarContactos($id_cliente, $contactos);
		}

		if (empty($_FILES['foto'])) {
			Flash::message('Guardado');
			return redirect(route('clientes.index'));
		}else{
			$archivos = $_FILES['foto'];
			$success = null;
			$paths= [];
			$nombres_archivos = $archivos['name'];
			for($i=0; $i < count($nombres_archivos); $i++){
				$ext = explode('.', basename($nombres_archivos));
				$nombre_archivo = md5(uniqid()) . "." . array_pop($ext);
				$ruta = public_path().'/img/uploads/archivos' . DIRECTORY_SEPARATOR . $nombre_archivo;

				if(move_uploaded_file($archivos['tmp_name'], $ruta)) {
					$success = true;
					$paths[] = $nombre_archivo;
				}else{
					$success = false;
					break;
				}
			}

			if ($success === true) {
				$this->clienteRepository->guardarFotoBD($id_cliente, $paths);
				$salida = [];
			}elseif($success === false){
				$salida = ['error'=>'Error al cargar foto. Póngase en contacto con el administrador del sistema.'];
				foreach ($paths as $file) {
					unlink($file);
				}
			}else{
				$salida = ['error'=>'No fueron procesados los archivos.'];
			}

			Flash::message('Guardado');
			return redirect(route('clientes.index'));
		}
	}

	public function show($id){
		$cliente = $this->clienteRepository->findClienteById($id);
		if(empty($cliente)){
			Flash::error('No se encontro');
			return redirect(route('clientes.index'));
		}
		return view('clientes.show')
				->with('cliente', $cliente);
	}

	public function edit($id){
		$estados = $this->clienteRepository->getEstados();
		$cliente = $this->clienteRepository->findClienteById($id);
		$cliente->contactos = $this->clienteRepository->getContactosByEmpresa($id);
		$emails = $this->clienteRepository->buscarEmails($id);
    	$formaPago = GeneralRepository::getFormaPago();
		$listaParentescos = $this->clienteRepository->getParentescos();
		if(empty($cliente)){
			Flash::error('No se encontro');
			return redirect(route('clientes.index'));
		}
		return view('clientes.edit')
				->with('estados', $estados)
				->with('cliente', $cliente)
				->with('listaParentescos', $listaParentescos)
				->with('emails', $emails) ->with('formaPago',$formaPago);
	}

	public function update($id, CreateClienteRequest $request){
		$input = $request->all();
		/*dd($input);*/

		$emails = $input["emails"];
		$emails = json_decode( $emails, true );
		$contactos = $input["contactos"];
		$contactos = json_decode( $contactos, true );

		$cliente = $this->clienteRepository->findClienteUp($id);

		if ($input["foto"] == "undefined"){$input["foto"] = $cliente->foto;}

		if(empty($cliente)){
			Flash::error('No se encontro');
			return redirect(route('clientes.index'));
		}

		if(!empty($emails)) {
			$this->clienteRepository->multiEmailsEdit($emails, $id);
			//guardar correo principal
			for ($i = 0; $i <= count($emails); $i++) {$email = $emails[0]['id'];}
			$this->clienteRepository->correoPrincipal($id,$email);
		}else {
			$emails = $this->clienteRepository->buscarEmails($id);
			foreach($emails as $email){
				DB::table('correos')->where('id',$email->id)->delete();
			}
			//borra correo Principal... Pero no estaria bien que se quedara sin correo principal si ya lo tiene
			//$this->clienteRepository->correoPrincipal($id,"");
		}

		if(!empty($contactos)) {
			$this->clienteRepository->multiContactosEdit($contactos, $id);
		}else {
			$contactos = $this->clienteRepository->buscarContactos($id);
			foreach($contactos as $contacto){
				DB::table('contactos')->where('id_empresa',$contacto->id)->delete();
			}
		}

		if($request->hasFile('foto')){
			$archivos = $_FILES['foto'];
			$nombres_archivos = $archivos['name'];
			$ext = explode('.', basename($nombres_archivos));
			$nombre_archivo = md5(uniqid()) . "." . array_pop($ext);
			$ruta = public_path().'/img/uploads/archivos' . DIRECTORY_SEPARATOR . $nombre_archivo;
			if(move_uploaded_file($archivos['tmp_name'], $ruta)) {
				$input['foto'] = $nombre_archivo;
			}
		}

		$this->clienteRepository->update($cliente, $input);

		Flash::message('Actualizado');
		return redirect(route('clientes.index'));

	}

	public function destroy($id){
		$cliente = $this->clienteRepository->findClienteById($id);
		$ventaAsignadas = $this->clienteRepository->VentaConCliente($id);
		$citasAsignadas = $this->clienteRepository->ServicioConCliente($id);

		if(empty($cliente)){
			Flash::error('No se encontro');
			return redirect(route('clientes.index'));
		}

		if($ventaAsignadas == null && $citasAsignadas == null){
			$cliente->delete();
			Flash::message('Borrado.');
			return redirect(route('proveedores.index'));

		}else{
			return $mensaje = 'error';
		}
	}

	public function buscarCliente(Request $request){

		$input = $request->all();
		$busqueda = $input['busqueda'];

		$busquedas = $this->clienteRepository->busqueda($busqueda);

		if (empty($busquedas[0]->nombre)) {
			/*Flash::error('No se encuentra cliente con ese nombre.');*/
			return view('clientes.index');
		}else{
			return view('clientes.index')
					->with('clientes', $busquedas);
		}
	}

	public function excelReporte($inicio,$final){
		return $this->clienteRepository->excelReporte($inicio,$final);
	}

	public function alertaCumple(){
		$alertasCliente = $this->clienteRepository->alertaCumple();
		return $alertasCliente;
	}

	public function envioRecordatorio($id,$id_servicio){
		$dominio = $_SERVER['SERVER_NAME'];
		$asegurado = $this->clienteRepository->findClienteById($id);
		$correos = $this->clienteRepository->buscarEmails($id);
		$servicios = $this->servicioRepository->buscarServicio($id_servicio);
		/*dd($correos);*/
		if (!count($correos)) {
			//correo vacio
			return 2;
		}

		foreach ($servicios as $servicio)
		$datos = [
				'nombre'	=> $asegurado->nombre,
				'apellido'	=> $asegurado->apellido,
				'fecha_nacimiento'	=> $asegurado->fecha_nacimiento,
				'servicio'	=> $servicio,
		];

		if (Mail::send('web/template.recordatorio', $datos, function ($message) use ($correos,$dominio) {
			$message->subject	('Asunto: Recordatorio | '.$dominio);
			foreach ($correos as $correo) {
				$message->to($correo->email);
			}
		})){
			return 1;
		}else{
			return 0;
		}
	}

	public function guardarCliente(CreateClienteRequest $request){
		$input = $request->all();

		$hoy = strftime( "%Y-%m-%d", time() );
		$input['fecha_alta'] = $hoy;

		$cliente = $this->clienteRepository->store($input);

		$id_cliente = $cliente['id'];
		$this->clienteRepository->multiEmails($input["emails"],$id_cliente);

		return $cliente;
	}

	public function historial($id){

		$historial = $this->servicioRepository->historialPaciente($id);
		if ($historial->isEmpty()) { $historial = 'vacio'; }

		$clientes = $this->clienteRepository->noMames($id);
		foreach ($clientes as $cliente);

		$padecimientos = $this->servicioRepository->padecimientos($id);

		foreach ($padecimientos as $padecimiento) {
			$padecimiento->archivo = $this->servicioRepository->archivos($padecimiento->id);
			$padecimiento->estudio = $this->servicioRepository->estudios($padecimiento->id);
			$padecimiento->ultimaCitaPadecimiento = $this->servicioRepository->ultimaCitaPadecimiento($padecimiento->id);
			if ($padecimiento->ultimaCitaPadecimiento != null){

				//meter en la ultima cita del padecimiento las recetas de ese paciente en determinada fecha
				$padecimiento->ultimaCitaPadecimiento->recetasEstaFecha = $this->servicioRepository->recetasEstaFecha(
				$padecimiento->ultimaCitaPadecimiento->id_cliente,$padecimiento->ultimaCitaPadecimiento->fecha);

				//todas las citas del padecimiento
				$padecimiento->ultimaCitaPadecimiento->todasCitasPadecimiento = $this->servicioRepository->todasCitasPadecimiento($padecimiento->id);

				//meter en todas las cita del padecimiento las recetas de ese paciente en determinada fecha
				foreach ($padecimiento->ultimaCitaPadecimiento->todasCitasPadecimiento as $cita) {
					$cita->recetasEstaFecha = $this->servicioRepository->recetasEstaFecha(
							$cita->id_cliente, $cita->fecha);
				}
			}
		}

		/*dd($padecimientos);*/

		if (!isset($padecimientos[0])) {
			$padecimientos = 'vacio';
		} else {
			$padecimientos = $this->esNull($padecimientos);
		}

		$recetas = DB::table('recetas')->where('id_cliente', $id)->orderBy('fecha', 'dec')->get();
		$cliente->contactos = $this->clienteRepository->getContactosByEmpresa($id);
		$correos = $this->clienteRepository->buscarEmails($cliente->id);

		$finalizadoDatos = $this->clienteRepository->finalizadoDatos($cliente->id);
		$medicamentos = $this->medicamentoRepository->all();

		//para poder agregar medicamentos desde la receta
		$padecimientosM = $this->padecimientoRepository->all();

		return view('clientes.show')
			->with('historial', $historial)
			->with('padecimientos', $padecimientos)
			->with('recetas', $recetas)
			->with('cliente', $cliente)
			->with('finalizadoDatos', $finalizadoDatos)
			->with('medicamentos', $medicamentos)
			->with('padecimientosM', $padecimientosM)
			->with('correos', $correos);
	}

	public function consulta($id,$servicio){

		$this->servicioRepository->cambioEstado($servicio,2);

		$historial = $this->servicioRepository->historialPaciente($id);
		if ($historial->isEmpty()) {
			$historial = 'vacio';
		}

		$clientes = $this->clienteRepository->noMames($id);
		foreach ($clientes as $cliente);
		$id_cliente = $cliente->id;

		$preconsulta = $this->servicioRepository->preconsulta($servicio);
		$padecimientos = $this->padecimientosCliente($id);

		foreach ($padecimientos as $padecimiento) {
			$padecimiento->archivo = $this->servicioRepository->archivos($padecimiento->id);
			$padecimiento->estudio = $this->servicioRepository->estudios($padecimiento->id);
			$padecimiento->ultimaCitaPadecimiento = $this->servicioRepository->ultimaCitaPadecimiento($padecimiento->id);
			if ($padecimiento->ultimaCitaPadecimiento != null){
				//meter en la ultima cita del padecimiento las recetas de ese paciente en determinada fecha
				$padecimiento->ultimaCitaPadecimiento->recetasEstaFecha = $this->servicioRepository->recetasEstaFecha(
						$padecimiento->ultimaCitaPadecimiento->id_cliente,$padecimiento->ultimaCitaPadecimiento->fecha);
				//todas las citas del padecimiento
				$padecimiento->ultimaCitaPadecimiento->todasCitasPadecimiento = $this->servicioRepository->todasCitasPadecimiento($padecimiento->id);

				//meter en todas las cita del padecimiento las recetas de ese paciente en determinada fecha
				foreach ($padecimiento->ultimaCitaPadecimiento->todasCitasPadecimiento as $cita) {
					$cita->recetasEstaFecha = $this->servicioRepository->recetasEstaFecha(
							$cita->id_cliente, $cita->fecha);
				}
			}
		}

		$recetas = DB::table('recetas')->leftjoin('clientes','recetas.id_cliente','=','clientes.id')
				->select('recetas.*','clientes.nombre','clientes.apellido')
				->where('id_cliente', $id)->orderBy('fecha', 'dec')->get();
		$cliente->contactos = $this->clienteRepository->getContactosByEmpresa($id);
		$cliente->id_servicio = $servicio;

		$correos = $this->clienteRepository->buscarEmails($id_cliente);

		$consulta = $this->servicioRepository->existeMismaConsulta($id,$servicio);

		$padecimientoSelect = $this->servicioRepository->padecimientoSelect($id_cliente);
		$padecimientoC = $this->servicioRepository->padecimientoServicio($servicio);

		if (!isset($padecimientos[0])) {
			$padecimientos = 'vacio';
		} else {
			$padecimientos = $this->esNull($padecimientos);
		}

		$estudios = $this->estudioRepository->all();

		if($consulta != null){
			$estudiosEC = $this->estudioRepository->estudioEC($consulta);
		}else{
			$estudiosEC = null;
		}

		$medicamentos = $this->medicamentoRepository->all();
		//para poder agregar medicamentos desde la receta
		$padecimientosM = $this->padecimientoRepository->all();

		return view('clientes.show')
				->with('historial', $historial)
				->with('preconsulta', $preconsulta)
				->with('cliente', $cliente)
				->with('padecimientos', $padecimientos)
				->with('padecimientoSelect', $padecimientoSelect)
				->with('consulta', $consulta)
				->with('recetas', $recetas)
				->with('padecimientoC', $padecimientoC)
				->with('estudios', $estudios)
				->with('estudiosEC', $estudiosEC)
				->with('medicamentos', $medicamentos)
				->with('padecimientosM', $padecimientosM)
				->with('correos', $correos);
	}

	public function esNull($padecimientos){

		if ($padecimientos[0]->ultimaCitaPadecimiento == null){
			array_splice($padecimientos,0,1);
			if (isset($padecimientos[0])){
				$this->esNull($padecimientos);
			}else{
				return $padecimientos;
			}
		}else{
			return $padecimientos;
		}

	}

	public function padecimientosCliente($id){
		return $this->servicioRepository->padecimientos($id);
	}

	public function getContactosByCliente(Request $request){
		$input = $request->all();
		$id = $input["id"];
		/*$contactos = $this->clienteRepository->getContactosByCliente($id);*/
		$correos = $this->clienteRepository->getCorreosByCliente($id);
		/*dd($contactos);*/
		/*dd($correos);*/
		/*return $contactos;*/
		return $correos;
	}

}