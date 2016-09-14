<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateServicioRequest;
use App\Libraries\Repositories\AuxiliarRepository;
use App\Libraries\Repositories\CirugiaRepository;
use App\Libraries\Repositories\ClienteRepository;
use App\Libraries\Repositories\productosRepository;
use App\Models\Cliente;
use App\Models\Costos;
use App\Models\Hospital;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ServicioRepository;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use Response;
use App\Libraries\Repositories\GeneralRepository;

class ServicioController extends AppBaseController{

	private $servicioRepository;

	function __construct(ServicioRepository $servicioRepo,ClienteRepository $clienteRepo,AuxiliarRepository $auxiliarRepo,
						 productosRepository $productosRepo,CirugiaRepository $cirugiaRepo){
		$this->servicioRepository = $servicioRepo;
		$this->clienteRepository = $clienteRepo;
		$this->auxiliarRepository = $auxiliarRepo;
		$this->productosRepository = $productosRepo;
		$this->cirugiaRepository = $cirugiaRepo;
		$this->middleware('auth');

		$this->beforeFilter('ver_servicios', array('only' => 'index') );
		$this->beforeFilter('crear_servicios', array('only' => 'create') );
		$this->beforeFilter('crear_servicios', array('only' => 'store') );
		$this->beforeFilter('editar_servicios', array('only' => 'edit') );
		$this->beforeFilter('editar_servicios', array('only' => 'update') );
		$this->beforeFilter('eliminar_servicios', array('only' => 'delete') );
	}

    public function index(){
		$servicios = $this->servicioRepository->todos();
		return view('servicios.index')
				->with('servicios', $servicios);
	}

	public function pendientes(){
		$servicios = $this->servicioRepository->pendientes();
		return view('servicios.index')
				->with('servicios', $servicios);
	}

	public function serviciosHoy(){
		$servicios = $this->servicioRepository->serviciosHoy();
		return view('servicios.index')
				->with('servicios', $servicios);
	}

	public function pendientesHoyAlerta(){
		$servicios = $this->servicioRepository->pendientesHoy();
		return $servicios;
	}

	public function serviciosHoyAlerta(){
		$servicios = $this->servicioRepository->serviciosHoy();
		return $servicios;
	}

	public function reagendar(){
		$servicios = $this->servicioRepository->reagendar();
		return $servicios;
	}

	public function create(){
		$emails = [];
    	$formaPago = GeneralRepository::getFormaPago();
		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))->where('estatus_fin',0)->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();
		$listaFechas = DB::table('inhabiles')
				->where('estatus', 1)
				->lists('fecha','id');

		$listaHospitales = Hospital::orderBy('principal','dec')->lists('nombre','id');

		return view('servicios.create')
				->with('listaClientes',$listaClientes)
				->with('listaFechas',$listaFechas)
				->with('emails',$emails)
				->with('fechaante',"")
				->with('estados',$estados)
				->with('formaPago',$formaPago)
				->with('listaHospitales',$listaHospitales)
				->with('estados',$estados);
	}

	public function citaDesdeCalen($enviofecha,$envioinicio,$enviofin){
		$emails = [];
    	$formaPago = GeneralRepository::getFormaPago();
		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))->where('estatus_fin',0)->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();

		$listaFechas = DB::table('inhabiles')->where('estatus', 1)->lists('fecha','id');

		$listaHospitales = Hospital::orderBy('principal','dec')->lists('nombre','id');

		$auxiliares = $this->auxiliarRepository->all();
		$materiales = $this->productosRepository->all();

		$listaParentescos = $this->clienteRepository->getParentescos();

		$clientesNlista = Cliente::
		select('id',DB::raw('CONCAT(nombre," ",apellido) as nombre'))->where('estatus_fin',0)->get('paciente','id');

		return view('servicios.create')
				->with('listaClientes',$listaClientes)
				->with('listaFechas',$listaFechas)
				->with('emails',$emails)
				->with('fechaante',"")
				->with('estados',$estados)
				->with('recibofecha',$enviofecha)
				->with('reciboinicio',$envioinicio)
				->with('recibofin',$enviofin)
				->with('listaHospitales',$listaHospitales)
				->with("auxiliares", $auxiliares)
				->with("materiales", $materiales)
				->with('recibofin',$enviofin)
				->with('estados',$estados)
				->with('listaParentescos', $listaParentescos)
				->with('clientesNlista', $clientesNlista)
				->with('formaPago',$formaPago);
	}

    public function store(CreateServicioRequest $request){
		$input = $request->all();

		//Cita
		$hoy = Carbon::now();
		$input['created_at'] = $hoy;
		$input['update_at'] = $hoy;
		$input['ultima_cita'] = $hoy;
		$fecha_pago = $input['fecha_pago'];
		$input['fecha_pago'] = '0000-00-00 00:00:00';

		if (isset($input['id_padecimiento'])) {
			if ($input['id_padecimiento'] == 0) {
				$existePendiente = $this->servicioRepository->existePadecimientoPendiente($input);
				if (empty($existePendiente)) {
					$input['padecimiento'] = 'Pendiente';
					$id_padecimiento = $this->servicioRepository->guardarPadecimiento($input);
					//solo actualiza la fecha de ultima cita sobre el padecimiento
					$this->servicioRepository->guardarFechaUltima($id_padecimiento,$input);
				}else{
					$id_padecimiento = $existePendiente->id;
					//solo actualiza la fecha de ultima cita sobre el padecimiento
					$this->servicioRepository->guardarFechaUltima($id_padecimiento,$input);
				}
			}else{
				$id_padecimiento = $input['id_padecimiento'];
				//solo actualiza la fecha de ultima cita sobre el padecimiento
				$this->servicioRepository->guardarFechaUltima($id_padecimiento,$input);
			}
		} else {
			$input['padecimiento'] = 'Pendiente';
			$id_padecimiento = $this->servicioRepository->guardarPadecimiento($input);
			//solo actualiza la fecha de ultima cita sobre el padecimiento
			$this->servicioRepository->guardarFechaUltima($id_padecimiento,$input);
		}

		$input['id_padecimiento'] = $id_padecimiento;
		$servicio = $this->servicioRepository->store($input);

		//Cirugia
		if($input['checkCirugia'] == 'true'){
			$input['id_servicio'] = $servicio->id;
			$input['total_material'] = $input['total'];
			$input['fecha_pago'] = $fecha_pago;
			$materiales = $input['materiales'];
			$auxiliares = $input['auxiliares'];

			$cirugia = $this->cirugiaRepository->store($input);

			if ($materiales != 'vacio') { $this->cirugiaRepository->guardarMateriales($cirugia->id, $materiales); }
			if ($auxiliares != 'vacio'){ $this->cirugiaRepository->guardarAuxiliares($cirugia->id,$auxiliares); }
		}

		return $servicio;
	}

    public function show($id){
		$servicio = $this->servicioRepository->findServicioById($id);
		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}
		return view('servicios.show')->with('servicio', $servicio);
	}

    public function edit($id){
		$emails = [];
    	$formaPago = GeneralRepository::getFormaPago();
		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))->where('estatus_fin',0)->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();

		$servicios = $this->servicioRepository->buscarServicio($id);
		foreach ($servicios as $servicio)
		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}

		$auxiliares = $this->auxiliarRepository->all();
		$materiales = $this->productosRepository->all();

		$listaHospitales = Hospital::orderBy('principal','dec')
			->lists('nombre','id');

		$listaParentescos = $this->clienteRepository->getParentescos();

		$clientesNlista = Cliente::
		select('id',DB::raw('CONCAT(nombre," ",apellido) as nombre'))->where('estatus_fin',0)->get('paciente','id');

		return view('servicios.edit')
				->with('listaClientes',$listaClientes)
				->with('estados',$estados)
				->with('emails', $emails)
				->with('servicio', $servicio)
				->with('listaHospitales', $listaHospitales)
				->with('reagendar', 'reagendar')
				->with("auxiliares", $auxiliares)
				->with("materiales", $materiales)
				->with("listaParentescos", $listaParentescos)
				->with("clientesNlista", $clientesNlista)
				->with('formaPago',$formaPago);

	}

	public function seguimiento($id){
		$emails = [];
		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))->where('estatus_fin',0)->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();

		$servicios = $this->servicioRepository->buscarServicio($id);
		foreach ($servicios as $servicio)

		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}

		$listaHospitales = Hospital::orderBy('principal','dec')
			->lists('nombre','id');

		$auxiliares = $this->auxiliarRepository->all();
		$materiales = $this->productosRepository->all();
		$formaPago = GeneralRepository::getFormaPago();

		$listaParentescos = $this->clienteRepository->getParentescos();

		$clientesNlista = Cliente::
		select('id',DB::raw('CONCAT(nombre," ",apellido) as nombre'))->where('estatus_fin',0)->get('paciente','id');

		return view('servicios.edit')
				->with('listaClientes',$listaClientes)
				->with('emails', $emails)
				->with('servicio', $servicio)
				->with('estados', $estados)
				->with('listaHospitales', $listaHospitales)
				->with("auxiliares", $auxiliares)
				->with("materiales", $materiales)
				->with("listaParentescos", $listaParentescos)
				->with("clientesNlista", $clientesNlista)
				->with("formaPago", $formaPago)
				->with('seguimiento', 'seguimiento');
	}

    public function update($id, CreateServicioRequest $request){
		$input = $request->all();

		$servicio = $this->servicioRepository->findServicioById($id);

		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}

		$servicio = $this->servicioRepository->update($servicio, $input);
		Flash::message('Actualizado.');
		return redirect(route('servicios.index'));
	}

    public function destroy($id){
		$servicio = $this->servicioRepository->findServicioById($id);

		$cirugia = $this->servicioRepository->CirugiaConServicio($id);
		/*dd($cirugia);*/
		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}
		if ($cirugia->isEmpty()) {
			$servicio->delete();
			Flash::message('Borrado');
			return redirect(route('servicios.index'));
		}else {
			return 'errorBorrarCita';
		}
	}

	public function editar(CreateServicioRequest $request){
		$input = $request->all();
		$id = $input['id_servicio'];
		$id_padecimiento = $input['id_padecimiento'];
		$hoy = Carbon::now();
		$input['ultima_cita'] = $hoy;

		$servicio = $this->servicioRepository->findServicioById($id);
		$id_servicio = $servicio->id;

		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}

		if ($input['accion'] != 's'){
			//Si es reagendar
			$input['reagendar'] = 0;
			$this->servicioRepository->update($servicio, $input);
		}else{
			//Si es seguimiento
			DB::table('servicios')->where('id', $id_servicio)->update(['reagendar' => 0]);
			$input['fecha_pago'] = '0000-00-00 00:00:00';
			$servicio = $this->servicioRepository->store($input);

			//Cirugia
			if($input['checkCirugia'] == 'true'){
				$input['id_servicio'] = $servicio->id;
				$input['total_material'] = $input['total'];
				$materiales = $input['materiales'];
				$auxiliares = $input['auxiliares'];

				$cirugia = $this->cirugiaRepository->store($input);

				if ($materiales != 'vacio') { $this->cirugiaRepository->guardarMateriales($cirugia->id, $materiales); }
				if ($auxiliares != 'vacio'){ $this->cirugiaRepository->guardarAuxiliares($cirugia->id,$auxiliares); }
			}
		}

		$this->servicioRepository->guardarFechaUltima($id_padecimiento,$input);
		return $servicio;
	}

	public function pagar($id_servicio){
		$hoy = Carbon::now();

		DB::table('servicios')
			->where('id', $id_servicio)
			->update(['estatus'=>1,'fecha_pago'=>$hoy]);
	}

	public function pagarAnterior($id_servicio){
		$hoy = Carbon::now();
		/*$hoy = $hoy->format('Y-m-d');

		dd($hoy);*/

		DB::table('tb_cirugias')
			->where('id', $id_servicio)
			->update(['RECIBO_PAGO'=>'PAGADO','FECHA_DE_PAGO'=>$hoy]);
	}

	public function busquedaAvanzada(Request $request){
		$input = $request->all();
		$busqueda = $input['busqueda'];

		$busquedas = $this->servicioRepository->busqueda($busqueda);

		if (empty($busquedas[0]->fecha)) {
			/*Flash::error('No se encuentra cliente con ese nombre.');*/
			return view('servicios.index');
		}else{
			return view('servicios.index')
					->with('servicios', $busquedas);
		}
	}

	public function Aprobar($id){
		$servicio = $this->servicioRepository->findServicioById($id);
		dd($servicio);
	}

	public function reagendarAlerta($id,$para){
		$servicio = $this->servicioRepository->findServicioById($id);
		$input['reagendar'] = $para;
		$servicio = $this->servicioRepository->update($servicio, $input);
		return $servicio;
	}

	public function periodo($inicio,$fin){
		$servicio = $this->servicioRepository->periodo($inicio,$fin);
		return $servicio;
	}

	public function fecha($inicio){
		$servicio = $this->servicioRepository->fecha($inicio);
		return $servicio;
	}

	public function fechaHora($fecha,$inicio,$fin){
		$servicio = $this->servicioRepository->fechaHora($fecha,$inicio,$fin);
		/*dd($servicio);*/
		return $servicio;
	}

	public function entreFechas($inicio,$fin){
		$servicio = $this->servicioRepository->entreFechas($inicio,$fin);
		/*dd($servicio);*/
		return $servicio;
	}

	public function receta(Request $request){
		$input = $request->all();
		$receta = $this->servicioRepository->crearReceta($input);
		return $receta;
	}

	public function finalizarHistorial(Request $request){
		$input = $request->all();
		$this->servicioRepository->finalizarHistorial($input);
	}

	public function padecimientoServicio($id){
		$id_padecimiento = $this->servicioRepository->padecimientoServicio($id);

		return $id_padecimiento;
	}

	public function hoyDelante(){
		$servicios = $this->servicioRepository->hoyDelante();
		return $servicios;
	}

	public function cargarHorario($diaSemana,$tipo){
		$servicios = $this->servicioRepository->cargarHorario($diaSemana,$tipo);
		return $servicios;
	}

	public function cambioEstado($id,$estado, Request $request){
		$input = $request->all();
		if(isset($input['inicioConsulta'])) {
			$this->servicioRepository->guardarHoras($id, $input);
		}

		$this->servicioRepository->cambioEstado($id, $estado);


	}

	public function ultimaVisita($id_cliente){
		$datosUltimas = $this->servicioRepository->ultimaVisita($id_cliente);

		$super   = json_encode( $datosUltimas );
		$datosUltimas = json_decode( $super, true );

		return $datosUltimas;
	}

	public function almacenarConsulta(Request $request){
		$input = $request->all();
		/*dd($input);*/
		$input['diagnostico'] = "";
		//Si se lleno el campo de nuevo padecimiento
		if(!empty($input['padecimiento'])){
			$existePadecimientoNuevo = $this->servicioRepository->existePadecimientoNuevo($input);

			if(empty($existePadecimientoNuevo)){
				/*dd($existePadecimientoNuevo);*/
				$id_padecimiento = $this->servicioRepository->guardarPadecimiento($input);
				$this->servicioRepository->actualizaServicio($input['id_servicio'],$id_padecimiento);
			}else{
				/*dd('no es null');*/
				$id_padecimiento = $existePadecimientoNuevo->id;
				$this->servicioRepository->actualizaServicio($input['id_servicio'],$id_padecimiento);
			}

		}//Si no se lleno el campo de nuevo padecimiento toma el valor del select
		else{
			/*dd('no hay padecimiento nuevo');*/
			$id_padecimiento = $input['id_padecimiento'];
			$this->servicioRepository->actualizaServicio($input['id_servicio'],$id_padecimiento);
		}

		$input['id_padecimiento'] = $id_padecimiento;
		$id_preconsulta = $input['id_preconsulta'];

		$existeConsulta = $this->servicioRepository->existeConsulta($input);
		if(empty($existeConsulta)){
			$consulta_id = $this->servicioRepository->almacenarConsulta($input);

			//se almacena el estudio ligado al proveedor que el id esta en tabla proveedor_control y se almacena en consulta_estudios
			if($input['estudios'] != 'vacio'){
				$this->servicioRepository->almacenarEstudios($input,$consulta_id);
			}
		}else{
			$this->servicioRepository->actualizarConsulta($input);
			$consulta_id = $existeConsulta->id;

			//borrar y agregar los nuevos estudios
			$this->servicioRepository->actualizarEstudios($input,$consulta_id);
		}
		$this->servicioRepository->actualizarPreconsulta($id_preconsulta,$input);
	}

}
