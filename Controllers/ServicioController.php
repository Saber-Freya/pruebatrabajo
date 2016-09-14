<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateServicioRequest;
use App\Libraries\Repositories\ClienteRepository;
use App\Models\Cliente;
use App\Models\Costos;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ServicioRepository;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use Response;

class ServicioController extends AppBaseController{

	private $servicioRepository;

	function __construct(ServicioRepository $servicioRepo,ClienteRepository $clienteRepo){
		$this->servicioRepository = $servicioRepo;
		$this->clienteRepository = $clienteRepo;
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

    public function inicio(){
		$servicios = $this->servicioRepository->anterior();
		return view('servicios.inicio')
			->with('servicios', $servicios);
	}

	public function reagendar(){
		$servicios = $this->servicioRepository->reagendar();
		return $servicios;
	}

	public function create(){
		$emails = [];
		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))
				->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();
		$listaFechas = DB::table('disponibilidad')
				->where('estatus', 1)
				->lists('fecha','id');

		return view('servicios.create')
				->with('listaClientes',$listaClientes)
				->with('listaFechas',$listaFechas)
				->with('emails',$emails)
				->with('fechaante',"")
				->with('estados',$estados);
	}

	public function citaDesdeCalen($enviofecha,$envioinicio,$enviofin){
		$emails = [];
		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();
		/*$listaCostos = Costos::lists('titulo', 'id');*/

		$listaFechas = DB::table('disponibilidad')
				->where('estatus', 1)
				->lists('fecha','id');

		return view('servicios.create')
				->with('listaClientes',$listaClientes)
				/*->with('listaCostos',$listaCostos)*/
				->with('listaFechas',$listaFechas)
				->with('emails',$emails)
				->with('fechaante',"")
				->with('estados',$estados)
				->with('recibofecha',$enviofecha)
				->with('reciboinicio',$envioinicio)
				->with('recibofin',$enviofin);
	}

    public function store(CreateServicioRequest $request){
		$input = $request->all();

		$hoy = Carbon::now();
		$input['created_at'] = $hoy;
		$input['update_at'] = $hoy;
		$input['ultima_cita'] = $hoy;
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

		Flash::message('Guardado');
		return redirect(route('servicios.index'));
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

		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))
				->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();

		$servicios = $this->servicioRepository->buscarServicio($id);
		foreach ($servicios as $servicio)
		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}

		return view('servicios.edit')
				->with('listaClientes',$listaClientes)
				->with('estados',$estados)
				->with('emails', $emails)
				->with('servicio', $servicio)
				->with('reagendar', 'reagendar');
	}

	public function seguimiento($id){
		$emails = [];
		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))
				->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();

		$servicios = $this->servicioRepository->buscarServicio($id);
		foreach ($servicios as $servicio)

		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}

		return view('servicios.edit')
				->with('listaClientes',$listaClientes)
				->with('emails', $emails)
				->with('servicio', $servicio)
				->with('estados', $estados)
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

		if(empty($servicio)){
			Flash::error('No se encontro');
			return redirect(route('servicios.index'));
		}

		if ($input['accion'] != 's'){
			//Si es reagendar
			$input['reagendar'] = 0;
			$servicio = $this->servicioRepository->update($servicio, $input);
		}else{
			//Si es seguimiento
			$input['fecha_pago'] = '0000-00-00 00:00:00';
			$this->servicioRepository->store($input);
		}

		$this->servicioRepository->guardarFechaUltima($id_padecimiento,$input);

		Flash::message('Actualizado.');
		return redirect(route('servicios.index'));
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

	public function busquedaAvanzadaAnterior(Request $request){
		$input = $request->all();
		$busqueda = $input['busqueda'];

		$busquedas = $this->servicioRepository->busquedaAnterior($busqueda);

		/*dd($busquedas[0]->nombre);*/

		if (empty($busquedas[0]->paciente)) {
			/*Flash::error('No se encuentra cliente con ese nombre.');*/
			return view('servicios.inicio');
		}else{
			return view('servicios.inicio')
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

	public function cambioEstado($id,$estado){
		$this->servicioRepository->cambioEstado($id,$estado);
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
		//Si se lleno el campo de nuevo padecimiento
		if(!empty($input['padecimiento'])){
			$existePadecimientoNuevo = $this->servicioRepository->existePadecimientoNuevo($input);
			if(empty($existePadecimientoNuevo)){
				$id_padecimiento = $this->servicioRepository->guardarPadecimiento($input);
				$this->servicioRepository->actualizaServicio($input['id_servicio'],$id_padecimiento);
			}else{
				$id_padecimiento = $existePadecimientoNuevo->id;
				$this->servicioRepository->actualizaServicio($input['id_servicio'],$id_padecimiento);
			}
		}//Si no se lleno el campo de nuevo padecimiento toma el valor del select
		else{
			$id_padecimiento = $input['id_padecimiento'];
			$this->servicioRepository->actualizaServicio($input['id_servicio'],$id_padecimiento);
		}

		$input['id_padecimiento'] = $id_padecimiento;
		$id_preconsulta = $input['id_preconsulta'];

		$existeConsulta = $this->servicioRepository->existeConsulta($input);
		if(empty($existeConsulta)){
			$this->servicioRepository->almacenarConsulta($input);
		}else{
			$this->servicioRepository->actualizarConsulta($input);
		}
		$this->servicioRepository->actualizarPreconsulta($id_preconsulta,$input);
	}

}
