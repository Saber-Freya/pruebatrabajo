<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Horarios;
use Illuminate\Http\Request;
use App\Libraries\Repositories\HorariosRepository;
use Response;
use Schema;

class HorariosAPIController extends AppBaseController{

	private $horariosRepository;

	function __construct(HorariosRepository $horariosRepo){
		$this->horariosRepository = $horariosRepo;
	}

	public function index(Request $request)	{
	    $input = $request->all();

		$result = $this->horariosRepository->search($input);

		$horarios = $result[0];

		return Response::json(ResponseManager::makeResult($horarios->toArray(), "horarios retrieved successfully."));
	}

	public function search($input)
    {
        $query = Horarios::query();

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

	public function store(Request $request)	{
		if(sizeof(Horarios::$rules) > 0)
            $this->validateRequest($request, horarios::$rules);

        $input = $request->all();

		$horarios = $this->horariosRepository->store($input);

		return Response::json(ResponseManager::makeResult($horarios->toArray(), "horarios guardado"));
	}


	public function show($id)	{
		$horarios = $this->horariosRepository->findhorariosById($id);

		if(empty($horarios))
			$this->throwRecordNotFoundException("horarios no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($horarios->toArray(), "horarios cargado."));
	}


	public function edit($id){
		//
	}

	public function update($id, Request $request){
		$horarios = $this->horariosRepository->findhorariosById($id);

		if(empty($horarios))
			$this->throwRecordNotFoundException("horarios no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$horarios = $this->horariosRepository->update($horarios, $input);

		return Response::json(ResponseManager::makeResult($horarios->toArray(), "horarios actualizado."));
	}

	public function destroy($id){
		$horarios = $this->horariosRepository->findhorariosById($id);

		if(empty($horarios))
			$this->throwRecordNotFoundException("horarios no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$horarios->delete();

		return Response::json(ResponseManager::makeResult($id, "horarios eliminado"));
	}
}
