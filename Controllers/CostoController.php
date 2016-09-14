<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateCostosRequest;
use App\Models\Costos;
use Illuminate\Http\Request;
use App\Libraries\Repositories\CostosRepository;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;

class CostoController extends AppBaseController{

	private $costoRepository;

	function __construct(CostosRepository $costoRepo){
		$this->costoRepository = $costoRepo;
		$this->middleware('auth');

		/*$this->beforeFilter('ver_pacientes', array('only' => 'index') );
		$this->beforeFilter('crear_pacientes', array('only' => 'create') );
		$this->beforeFilter('crear_pacientes', array('only' => 'store') );
		$this->beforeFilter('editar_pacientes', array('only' => 'edit') );
		$this->beforeFilter('editar_pacientes', array('only' => 'update') );
		$this->beforeFilter('eliminar_pacientes', array('only' => 'delete') );*/
	}

	public function index(Request $request){
		$input = $request->all();
		$result = $this->costoRepository->search($input);
		$costos = $result[0];
		$attributes = $result[1];
		return view('costos.index')
				->with('costos', $costos)
				->with('attributes', $attributes);
	}

	public function create(){
		return view('costos.create');
	}

	public function store(CreateCostosRequest $request){
		$input = $request->all();
		$this->costoRepository->store($input);

		Flash::message('Guardado.');
		return redirect(route('costos.index'));
	}

	public function show($id){
		//
	}

	public function edit($id){
		$costo = $this->costoRepository->findCostosById($id);

		$costo->costo2 = $costo->costo;

		if(empty($costo)){
			Flash::error('No se encontro');
			return redirect(route('costos.index'));
		}

		return view('costos.edit')
				->with('costos', $costo);
	}

	public function update($id, CreateCostosRequest $request){
		$input = $request->all();
		$costo = $this->costoRepository->findCostosById($id);
		if(empty($costo)){
			Flash::error('No se encontro');
			return redirect(route('costos.index'));
		}
		$this->costoRepository->update($costo, $input);

		Flash::message('Actualizado.');
		return redirect(route('costos.index'));
	}

	public function destroy($id){
		$costo = $this->costoRepository->findCostosById($id);
		if(empty($costo)){
			Flash::error('No se encontro');
			return redirect(route('costos.index'));
		}
		$costo->delete();
		Flash::message('Borrado');
		return redirect(route('costos.index'));
	}

	public function cargarCostos(){
		return $this->costoRepository->all();

	}

}
