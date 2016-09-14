<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Hospital;
use Illuminate\Http\Request;
use App\Libraries\Repositories\HospitalRepository;
use Response;
use Schema;

class HospitalAPIController extends AppBaseController{

	private $hospitalRepository;

	function __construct(HospitalRepository $hospitalRepo){
		$this->hospitalRepository = $hospitalRepo;
	}

	public function index(Request $request){
	    $input = $request->all();

		$result = $this->hospitalRepository->search($input);

		$hospitals = $result[0];

		return Response::json(ResponseManager::makeResult($hospitals->toArray(), "Hospitals retrieved successfully."));
	}

	public function search($input){
        $query = Hospital::query();

        $columns = Schema::getColumnListing('$TABLE_NAME$');
        $attributes = array();

        foreach($columns as $attribute)
        {
            if(isset($input[$attribute]))
            {
                $query->where($attribute, $input[$attribute]);
            }
        }

        return $query->get();
    }

	public function create(){
		//
	}

	public function store(Request $request){
		if(sizeof(Hospital::$rules) > 0)
            $this->validateRequest($request, Hospital::$rules);

        $input = $request->all();

		$hospital = $this->hospitalRepository->store($input);

		return Response::json(ResponseManager::makeResult($hospital->toArray(), "Hospital guardado"));
	}

	public function show($id){
		$hospital = $this->hospitalRepository->findHospitalById($id);

		if(empty($hospital))
			$this->throwRecordNotFoundException("Hospital no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($hospital->toArray(), "Hospital cargado."));
	}

	public function edit($id){
		//
	}

	public function update($id, Request $request){
		$hospital = $this->hospitalRepository->findHospitalById($id);

		if(empty($hospital))
			$this->throwRecordNotFoundException("Hospital no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$hospital = $this->hospitalRepository->update($hospital, $input);

		return Response::json(ResponseManager::makeResult($hospital->toArray(), "Hospital actualizado."));
	}

	public function destroy($id){
		$hospital = $this->hospitalRepository->findHospitalById($id);

		if(empty($hospital))
			$this->throwRecordNotFoundException("Hospital no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$hospital->delete();

		return Response::json(ResponseManager::makeResult($id, "Hospital eliminado"));
	}
}
