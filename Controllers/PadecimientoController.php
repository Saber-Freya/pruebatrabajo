<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreatePadecimientoRequest;
use App\Libraries\Repositories\PadecimientoRepository;
use App\Models\Padecimiento;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class PadecimientoController extends Controller {

	private $padecimientoRepository;

	function __construct(PadecimientoRepository $padecimientoRepo){
		$this->padecimientoRepository = $padecimientoRepo;
		$this->middleware('auth');
	}

	public function index(Request $request){
		$input = $request->all();
		$result = $this->padecimientoRepository->search($input);
		$padecimientos = $result[0];
		$attributes = $result[1];
		return view('padecimientos.index')
				->with('padecimientos', $padecimientos)
				->with('attributes', $attributes);
	}

	public function create(){
		return view('padecimientos.create');
	}

	public function store(CreatePadecimientoRequest $request){
		$input = $request->all();

		$padecimiento = $this->padecimientoRepository->store($input);

		Flash::message('Guardado.');

		return redirect(route('padecimientos.index'));
	}

	public function show($id){
		//
	}

	public function edit($id){
		$padecimiento = $this->padecimientoRepository->findPadecimientoById($id);
		if(empty($padecimiento)){
			Flash::error('No se encontro.');
			return redirect(route('padecimientos.index'));
		}
		return view('padecimientos.edit')
				->with('padecimiento', $padecimiento);
	}

	public function update($id, CreatePadecimientoRequest $request){
		$input = $request->all();

		$padecimiento = $this->padecimientoRepository->findPadecimientoById($id);
		if(empty($padecimiento)){
			Flash::error('No se encontro.');
			return redirect(route('padecimientos.index'));
		}

		$padecimiento = $this->padecimientoRepository->update($padecimiento, $input);

		Flash::message('Actualizado.');
		return redirect(route('padecimientos.index'));
	}

	public function destroy($id){
		$padecimiento = Padecimiento::find($id);

		if(empty($padecimiento)){
			Flash::error('No se encontro');
			return redirect(route('padecimientos.index'));
		}

		$padecimiento->delete();

		Flash::message('Borrado.');

		return redirect(route('padecimientos.index'));
	}

}
