<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateCirugiaRequest;
use App\Libraries\Repositories\ServicioRepository;
use App\Libraries\Repositories\AuxiliarRepository;
use App\Libraries\Repositories\productosRepository;
use App\Models\Cirugia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Repositories\CirugiaRepository;
use Illuminate\Support\Facades\DB;
use Response;
use Flash;
class CirugiaController extends Controller {

	private $cirugiaRepository,$servicioRepository;

	function __construct(CirugiaRepository $cirugiaRepo,ServicioRepository $servicioRepo,
						 AuxiliarRepository $auxiliarRepo,productosRepository $productosRepo){
		$this->cirugiaRepository = $cirugiaRepo;
		$this->servicioRepository = $servicioRepo;
		$this->auxiliarRepository = $auxiliarRepo;
		$this->productosRepository = $productosRepo;
		$this->middleware('auth');

		/*$this->beforeFilter('ver_pacientes', array('only' => 'index') );
		$this->beforeFilter('crear_pacientes', array('only' => 'create') );
		$this->beforeFilter('crear_pacientes', array('only' => 'store') );
		$this->beforeFilter('editar_pacientes', array('only' => 'edit') );
		$this->beforeFilter('editar_pacientes', array('only' => 'update') );
		$this->beforeFilter('eliminar_pacientes', array('only' => 'delete') );*/
	}

	public function index(Request $request){
		$cirugias = $this->cirugiaRepository->all();
		foreach ($cirugias as $cirugia){
			$id_cirugia = $cirugia->id;
			$cirugia->materiales = $this->cirugiaRepository->materiales($id_cirugia);
			$cirugia->auxiliares = $this->cirugiaRepository->auxiliares($id_cirugia);
		}

		return view('cirugias.index')
				->with('cirugias', $cirugias);
	}

	public function create(){
		return view('cirugias.create');
	}

	public function preparar($id_servicio){
		$citas = $this->servicioRepository->buscarServicio($id_servicio);
		$auxiliares = $this->auxiliarRepository->all();
		$materiales = $this->productosRepository->all();

		$cirugia = $this->existeCirugiaGuardada($id_servicio);
		if($cirugia == null){$id_cirugia = 0;}else{$id_cirugia = $cirugia->id;}
		$auxiliaresE = $this->cirugiaRepository->editarAuxiliares($id_cirugia);
		$materialesE = $this->cirugiaRepository->editarMateriales($id_cirugia);
		/*dd($auxiliaresE);*/

		foreach ($citas as $cita)
		return view('cirugias.create')
				->with("auxiliares", $auxiliares)
				->with("materiales", $materiales)
				->with("cirugia", $cirugia)
				->with("auxiliaresE", $auxiliaresE)
				->with("materialesE", $materialesE)
				->with('cita',$cita);
	}

	public function store(CreateCirugiaRequest $request){
		$input = $request->all();
		$date = Carbon::now();
		$hora = $date->toTimeString();

		$input['total_material'] = $input['total'];
		$materiales = $input['materiales'];
		$auxiliares = $input['auxiliares'];
		$id_cita = $input['id_servicio'];
		$cita = $this->servicioRepository->buscarServicio($id_cita);

		DB::table('servicios')
				->where('id',$input['id_servicio'])
				->update([
						'estado'=>3,
						'finHora'=>$hora,
				]);

		if($input['accion'] != 'e'){
			$cirugia = $this->cirugiaRepository->store($input);

			if ($materiales != 'vacio') { $this->cirugiaRepository->guardarMateriales($cirugia->id, $materiales); }
			if ($auxiliares != 'vacio'){ $this->cirugiaRepository->guardarAuxiliares($cirugia->id,$auxiliares); }
		}else{
			$cirugia = $this->cirugiaRepository->findCirugiaById($input['id_cirugia']);
			$this->cirugiaRepository->update($cirugia,$input);

			//borra materiales y auxiliares
			$this->cirugiaRepository->borrarMaterialesAuxiliares($cirugia->id);

			//guarda de los nuevos
			if ($materiales != 'vacio'){$this->cirugiaRepository->guardarMateriales($cirugia->id, $materiales); }
			if ($auxiliares != 'vacio'){$this->cirugiaRepository->guardarAuxiliares($cirugia->id,$auxiliares); }
		}
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
		$cirugia = Cirugia::find($id);

		if(empty($cirugia)){
			Flash::error('No se encontro');
			return redirect(route('cirugias.index'));
		}

		$cirugia->delete();

		Flash::message('Borrado.');

		return redirect(route('cirugias.index'));
	}

	public function busquedaAvanzadaNueva(Request $request){
		$input = $request->all();
		$busqueda = $input['busqueda'];

		$cirugias = $this->cirugiaRepository->busquedaNueva($busqueda);

		if ($cirugias->isEmpty()) {
			$cirugias = $this->cirugiaRepository->all();
			foreach ($cirugias as $cirugia){
				$id_cirugia = $cirugia->id;
				$cirugia->materiales = $this->cirugiaRepository->materiales($id_cirugia);
				$cirugia->auxiliares = $this->cirugiaRepository->auxiliares($id_cirugia);
			}
			Flash::error('No se encontro resultado');
			return view('cirugias.index')
					->with('cirugias', $cirugias);
		}else{
			foreach ($cirugias as $cirugia){
				$id_cirugia = $cirugia->id;
				$cirugia->materiales = $this->cirugiaRepository->materiales($id_cirugia);
				$cirugia->auxiliares = $this->cirugiaRepository->auxiliares($id_cirugia);
			}

			return view('cirugias.index')
					->with('cirugias', $cirugias);
		}
	}

	public function existeCirugiaGuardada($id_servicio){
		return Cirugia::where('id_servicio',$id_servicio)->first();;
	}

}
