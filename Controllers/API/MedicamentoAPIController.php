<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use App\Libraries\Repositories\MedicamentoRepository;
use Response;
use Schema;

class MedicamentoAPIController extends AppBaseController{

	private $medicamentoRepository;

	function __construct(MedicamentoRepository $medicamentoRepo){
		$this->medicamentoRepository = $medicamentoRepo;
	}

	public function index(Request $request){
	    $input = $request->all();

		$result = $this->medicamentoRepository->search($input);

		$medicamentos = $result[0];

		return Response::json(ResponseManager::makeResult($medicamentos->toArray(), "Medicamentos retrieved successfully."));
	}

	public function search($input){
        $query = Medicamento::query();

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
		if(sizeof(Medicamento::$rules) > 0)
            $this->validateRequest($request, Medicamento::$rules);

        $input = $request->all();

		$medicamento = $this->medicamentoRepository->store($input);

		return Response::json(ResponseManager::makeResult($medicamento->toArray(), "Medicamento guardado"));
	}

	public function show($id){
		$medicamento = $this->medicamentoRepository->findMedicamentoById($id);

		if(empty($medicamento))
			$this->throwRecordNotFoundException("Medicamento no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($medicamento->toArray(), "Medicamento cargado."));
	}

	public function edit($id){
		//
	}

	public function update($id, Request $request){
		$medicamento = $this->medicamentoRepository->findMedicamentoById($id);

		if(empty($medicamento))
			$this->throwRecordNotFoundException("Medicamento no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$medicamento = $this->medicamentoRepository->update($medicamento, $input);

		return Response::json(ResponseManager::makeResult($medicamento->toArray(), "Medicamento actualizado."));
	}

	public function destroy($id){
		$medicamento = $this->medicamentoRepository->findMedicamentoById($id);

		if(empty($medicamento))
			$this->throwRecordNotFoundException("Medicamento no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$medicamento->delete();

		return Response::json(ResponseManager::makeResult($id, "Medicamento eliminado"));
	}
}
