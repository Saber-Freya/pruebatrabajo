<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateMedicamentoRequest;
use App\Libraries\Repositories\MedicamentoRepository;
use App\Libraries\Repositories\PadecimientoRepository;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class MedicamentoController extends Controller {

	private $medicamentoRepository;

	function __construct(MedicamentoRepository $medicamentoRepo, PadecimientoRepository $padecimientoRepo){
		$this->medicamentoRepository = $medicamentoRepo;
		$this->padecimientoRepository = $padecimientoRepo;
		$this->middleware('auth');

		$this->beforeFilter('ver_medicamentos', array('only' => 'index') );
		$this->beforeFilter('crear_medicamentos', array('only' => 'create') );
		$this->beforeFilter('crear_medicamentos', array('only' => 'store') );
		$this->beforeFilter('editar_medicamentos', array('only' => 'edit') );
		$this->beforeFilter('editar_medicamentos', array('only' => 'update') );
		$this->beforeFilter('eliminar_medicamentos', array('only' => 'delete') );
	}

	public function index(Request $request){
		$input = $request->all();
		$result = $this->medicamentoRepository->search($input);
		$medicamentos = $result[0];
		$attributes = $result[1];
		return view('medicamentos.index')
				->with('medicamentos', $medicamentos)
				->with('attributes', $attributes);
	}

	public function create(){
		$padecimientosM = $this->padecimientoRepository->all();
		return view('medicamentos.create')
				->with("padecimientosM", $padecimientosM);
	}

	public function store(CreateMedicamentoRequest $request){
		$input = $request->all();
		/*dd($input);*/
		$medicamento = $this->medicamentoRepository->store($input);

		if ($input['padecimientos'] != 'vacio'){
			$this->medicamentoRepository->storeControl($medicamento->id,$input['padecimientos']);
		}

		Flash::message('Guardado.');
		return redirect(route('medicamentos.index'));
	}

	public function show($id){
		//
	}

	public function edit($id){

		$medicamento = $this->medicamentoRepository->findMedicamentoById($id);
		$padecimientosM = $this->padecimientoRepository->all();
		$padecimientosME = $this->padecimientoRepository->padecimientoE($id);
		/*dd($padecimientosME);*/

		if(empty($medicamento)){
			Flash::error('No se encontro.');
			return redirect(route('medicamento.index'));
		}
		return view('medicamentos.edit')
				->with("padecimientosM", $padecimientosM)
				->with("padecimientosME", $padecimientosME)
				->with('medicamento', $medicamento);
	}

	public function update($id, CreateMedicamentoRequest $request){
		$input = $request->all();
		/*dd($input);*/
		$medicamento = $this->medicamentoRepository->findMedicamentoById($id);

		if(empty($medicamento)){
			Flash::error('No se encontro.');
			return redirect(route('medicamentos.index'));
		}
		
		$medicamento = $this->medicamentoRepository->update($medicamento, $input);

		//borrar padecimientos
		$this->medicamentoRepository->borrarControl($id);

		if($input['padecimientos'] != "vacio") {
			//guardar nuevos padecimientos
			$this->medicamentoRepository->storeControl($id, $input['padecimientos']);
		}

		Flash::message('Actualizado.');
		return redirect(route('medicamentos.index'));
	}

	public function destroy($id){
		$medicamento = Medicamento::find($id);

		if(empty($medicamento)){
			Flash::error('No se encontro');
			return redirect(route('medicamentos.index'));
		}

		$medicamento->delete();
		//borrar control de padecimientos - medicamento
		$this->medicamentoRepository->borrarControl($id);

		Flash::message('Borrado.');

		return redirect(route('medicamentos.index'));
	}

}
