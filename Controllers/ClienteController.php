<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateClienteRequest;
use App\Libraries\Repositories\ServicioRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ClienteRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;

class ClienteController extends AppBaseController{

	private $clienteRepository;

	function __construct(ClienteRepository $clienteRepo,ServicioRepository $servicioRepo){
		$this->clienteRepository = $clienteRepo;
		$this->servicioRepository = $servicioRepo;
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
		return view('clientes.create')
				->with('estados', $estados)
				->with('emails', $emails);
	}

	public function store(CreateClienteRequest $request){
        $input = $request->all();
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
		if(empty($cliente)){
			Flash::error('No se encontro');
			return redirect(route('clientes.index'));
		}
		return view('clientes.edit')
				->with('estados', $estados)
				->with('cliente', $cliente)
				->with('emails', $emails);
	}

	public function update($id, CreateClienteRequest $request){
		$input = $request->all();
		/*dd($input);*/

		$emails = $input["emails"];
		$emails = json_decode( $emails, true );
		$contactos = $input["contactos"];
		$contactos = json_decode( $contactos, true );

		$cliente = $this->clienteRepository->findClienteById($id);

		if ($input["foto"] == "undefined"){
			$input["foto"] = $cliente->foto;
		}

		if(empty($cliente)){
			Flash::error('No se encontro');
			return redirect(route('clientes.index'));
		}

		if(!empty($emails)) {
			$this->clienteRepository->multiEmailsEdit($emails, $id);
		}else {
			$emails = $this->clienteRepository->buscarEmails($id);
			foreach($emails as $email){
				DB::table('correos')->where('id',$email->id)->delete();
			}
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
		if(empty($cliente)){
			Flash::error('No se encontro');
			return redirect(route('clientes.index'));
		}
		$cliente->delete();
		Flash::message('Borrado');
		return redirect(route('clientes.index'));
	}

	public function buscarCliente(Request $request){

		$input = $request->all();
		$busqueda = $input['busqueda'];

		$busquedas = $this->clienteRepository->busqueda($busqueda);

		/*dd($busquedas);*/

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
		if ($historial->isEmpty()) {
			$historial = 'vacio';
		}

		$clientes = $this->clienteRepository->noMames($id);
		foreach ($clientes as $cliente);
		$id_cliente = $cliente->id;

		$estados = $this->clienteRepository->getUnEstado($cliente->edo);

		if (!count($estados)) {
			$cliente->estado = 'vacio';
		} else { foreach($estados as $estado) $cliente->estado = $estado; }
		$ciudades = $this->clienteRepository->getUnaCiudad($cliente->cd);
		if (!count($ciudades)) {
			$cliente->ciudad = 'vacio';
		} else { foreach ($ciudades as $ciudad) $cliente->ciudad = $ciudad; }

		$padecimientos = $this->servicioRepository->padecimientos($id);

		foreach ($padecimientos as $padecimiento) {
			$padecimiento->archivo = $this->servicioRepository->archivos($padecimiento->id);
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

		if (!isset($padecimientos[0])) {
			$padecimientos = 'vacio';
		} else {
			$padecimientos = $this->esNull($padecimientos);
		}

		$recetas = DB::table('recetas')->where('id_cliente', $id)->orderBy('fecha', 'dec')->get();
		$cliente->contactos = $this->clienteRepository->getContactosByEmpresa($id);
		$correos = $this->clienteRepository->buscarEmails($cliente->id);

		return view('clientes.show')
			->with('historial', $historial)
			->with('cliente', $cliente)
			->with('padecimientos', $padecimientos)
			->with('recetas', $recetas)
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

		$estados = $this->clienteRepository->getUnEstado($cliente->edo);
		$preconsulta = $this->servicioRepository->preconsulta($servicio);

		if (!count($estados)) {
			$cliente->estado = 'vacio';
		} else { foreach($estados as $estado) $cliente->estado = $estado; }

		$ciudades = $this->clienteRepository->getUnaCiudad($cliente->cd);
		if (!count($ciudades)) {
			$cliente->ciudad = 'vacio';
		} else { foreach ($ciudades as $ciudad) $cliente->ciudad = $ciudad; }

		$padecimientos = $this->padecimientosCliente($id);


		/*foreach ($padecimientos as $padecimiento) {
			$padecimiento->archivo = $this->servicioRepository->archivos($padecimiento->id);
			$padecimiento->ultimaCitaPadecimiento = $this->servicioRepository->ultimaCitaPadecimiento($padecimiento->id);
			if ($padecimiento->ultimaCitaPadecimiento != null){
				$padecimiento->ultimaCitaPadecimiento->todasCitasPadecimiento = $this->servicioRepository->todasCitasPadecimiento($padecimiento->id);
			}
		}*/

		foreach ($padecimientos as $padecimiento) {
			$padecimiento->archivo = $this->servicioRepository->archivos($padecimiento->id);
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


		$recetas = DB::table('recetas')->where('id_cliente', $id)->orderBy('fecha', 'dec')->get();
		$cliente->contactos = $this->clienteRepository->getContactosByEmpresa($id);
		$cliente->id_servicio = $servicio;

		$correos = $this->clienteRepository->buscarEmails($id_cliente);

		$consulta = $this->servicioRepository->existeMismaConsulta($id,$servicio);

		$padecimientoSelect = DB::table('padecimientos')
				->where('id_cliente',$id_cliente)
				->lists('padecimiento', 'id');

		$padecimientoC = $this->servicioRepository->padecimientoServicio($servicio);

		if (!isset($padecimientos[0])) {
			$padecimientos = 'vacio';
		} else {
			$padecimientos = $this->esNull($padecimientos);
		}

		return view('clientes.show')
				->with('historial', $historial)
				->with('preconsulta', $preconsulta)
				->with('cliente', $cliente)
				->with('padecimientos', $padecimientos)
				->with('padecimientoSelect', $padecimientoSelect)
				->with('consulta', $consulta)
				->with('recetas', $recetas)
				->with('padecimientoC', $padecimientoC)
				->with('correos', $correos);
	}

	public function esNull($padecimientos){
		if ($padecimientos[0]->ultimaCitaPadecimiento == null){
			array_splice($padecimientos,0,1);
			if (!isset($padecimientos[0])){
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

}