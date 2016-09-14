<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateEstudioRequest;
use App\Models\Estudio;
use App\Models\niveles;
use Illuminate\Http\Request;
use App\Libraries\Repositories\EstudioRepository;
use Illuminate\Support\Facades\DB;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;
class EstudioController extends AppBaseController{

	private $estudioRepository;

	function __construct(EstudioRepository $estudioRepo){
		$this->estudioRepository = $estudioRepo;
		$this->middleware('auth');
	}

	public function index(Request $request){
		$input = $request->all();
		$result = $this->estudioRepository->search($input);
		$estudios = $result[0];
		$attributes = $result[1];
		return view('estudios.index')
				->with('estudios', $estudios)
				->with('attributes', $attributes);
	}

	public function create(){
		return view('estudios.create');
	}

	public function store(CreateEstudioRequest $request){
        $input = $request->all();
		$estudio = $this->estudioRepository->store($input);
		Flash::message('Guardado');
		return redirect(route('estudios.index'));
	}

	public function show($id){
		$estudio = $this->estudioRepository->findEstudioById($id);
		if(empty($estudio)){
			Flash::error('No se encontro.');
			return redirect(route('estudios.index'));
		}
		return view('estudios.show')->with('estudio', $estudio);
	}

	public function edit($id){
		$estudio = $this->estudioRepository->findEstudioById($id);
		if(empty($estudio)){
			Flash::error('No se encontro.');
			return redirect(route('estudios.index'));
		}
		return view('estudios.edit')
				->with('estudio', $estudio);
	}

	public function update($id, CreateEstudioRequest $request){
		$estudio = $this->estudioRepository->findEstudioById($id);
		if(empty($estudio)){
			Flash::error('No se encontro.');
			return redirect(route('estudios.index'));
		}
		$estudio = $this->estudioRepository->update($estudio, $request->all());
		Flash::message('Actualizado.');
		return redirect(route('estudios.index'));
	}

	public function destroy($id){
		$estudio = $this->estudioRepository->findEstudioById($id);
		if(empty($estudio)){
			Flash::error('No encontrado');
			return redirect(route('estudios.index'));
		}
		$estudio->delete();
		Flash::message('Borrado.');
		return redirect(route('estudios.index'));
	}

}
