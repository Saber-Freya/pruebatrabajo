<?php namespace App\Http\Controllers\API;
use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Estudio;
use Illuminate\Http\Request;
use App\Libraries\Repositories\EstudioRepository;
use Response;
use Schema;
class EstudioAPIController extends AppBaseController{

	private $EstudioRepository;

	function __construct(EstudioRepository $estudioRepo){
		$this->estudioRepository = $estudioRepo;
	}

	public function index(Request $request){
	    $input = $request->all();

		$result = $this->estudioRepository->search($input);

		$estudios = $result[0];

		return Response::json(ResponseManager::makeResult($estudios->toArray(), "Estudios Guardados."));
	}

	public function search($input){
        $query = Estudio::query();

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
		if(sizeof(Estudio::$rules) > 0)
            $this->validateRequest($request, Estudio::$rules);

        $input = $request->all();

		$estudio = $this->estudioRepository->store($input);

		return Response::json(ResponseManager::makeResult($estudio->toArray(), "Estudio guardado"));
	}

	public function show($id){
		$estudio = $this->estudioRepository->findEstudioById($id);

		if(empty($estudio))
			$this->throwRecordNotFoundException("Estudio no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($estudio->toArray(), "Estudio cargado."));
	}

	public function edit($id){
		//
	}

	public function update($id, Request $request){
		$estudio = $this->estudioRepository->findEstudioById($id);

		if(empty($estudio))
			$this->throwRecordNotFoundException("Estudio no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$estudio = $this->estudioRepository->update($estudio, $input);

		return Response::json(ResponseManager::makeResult($estudio->toArray(), "Estudio actualizado."));
	}

	public function destroy($id){
		$estudio = $this->estudioRepository->findEstudioById($id);

		if(empty($estudio))
			$this->throwRecordNotFoundException("Estudio no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$estudio->delete();

		return Response::json(ResponseManager::makeResult($id, "Estudio eliminado"));
	}
}
