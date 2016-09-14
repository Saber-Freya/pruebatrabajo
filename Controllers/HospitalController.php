<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateHospitalRequest;
use App\Libraries\Repositories\HospitalRepository;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
class HospitalController extends Controller {

	private $hospitalRepository;

	function __construct(HospitalRepository $hospitalRepo){
		$this->hospitalRepository = $hospitalRepo;
		$this->middleware('auth');
	}

	public function index(Request $request){
		$input = $request->all();
		$result = $this->hospitalRepository->search($input);
		$hospitals = $result[0];
		$attributes = $result[1];
		return view('hospitals.index')
				->with('hospitals', $hospitals)
				->with('attributes', $attributes);
	}

	public function create(){
		return view('hospitals.create');
	}

	public function store(CreateHospitalRequest $request){
		$input = $request->all();

		if ($input['principal'] == 1){
			//actualizar principal
			$this->hospitalRepository->actualizarP();
		}

		$hospital = $this->hospitalRepository->store($input);

		Flash::message('Guardado.');

		return redirect(route('hospitals.index'));
	}

	public function show($id){
		//
	}

	public function edit($id){
		$hospital = $this->hospitalRepository->findHospitalById($id);
		if(empty($hospital)){
			Flash::error('No se encontro.');
			return redirect(route('hospital.index'));
		}
		return view('hospitals.edit')
				->with('hospital', $hospital);
	}

	public function update($id, CreateHospitalRequest $request){
		$input = $request->all();

		$hospital = $this->hospitalRepository->findHospitalById($id);
		if(empty($hospital)){
			Flash::error('No se encontro.');
			return redirect(route('hospitals.index'));
		}

		if ($input['principal'] == 1){
			//actualizar principal
			$this->hospitalRepository->actualizarP();
		}

		$hospital = $this->hospitalRepository->update($hospital, $input);

		Flash::message('Actualizado.');
		return redirect(route('hospitals.index'));
	}

	public function destroy($id){
		$hospital = Hospital::find($id);

		if(empty($hospital)){
			Flash::error('No se encontro');
			return redirect(route('hospitals.index'));
		}

		$hospital->delete();

		Flash::message('Borrado.');

		return redirect(route('hospitals.index'));
	}

}
