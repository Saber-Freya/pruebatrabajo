<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Auxiliar;
use Illuminate\Http\Request;
use App\Libraries\Repositories\AuxiliarRepository;
use Response;
use Schema;

class AuxiliarAPIController extends AppBaseController
{

	/** @var  AuxiliarRepository */
	private $auxiliarRepository;

	function __construct(AuxiliarRepository $auxiliarRepo)
	{
		$this->auxiliarRepository = $auxiliarRepo;
	}

	/**
	 * Display a listing of the Auxiliar.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request){
	    $input = $request->all();
		$result = $this->auxiliarRepository->search($input);
		$auxiliars = $result[0];

		return Response::json(ResponseManager::makeResult($auxiliars->toArray(), "Auxiliars retrieved successfully."));
	}

	public function search($input)
    {
        $query = Auxiliar::query();

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

	/**
	 * Show the form for creating a new Auxiliar.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created Auxiliar in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(Auxiliar::$rules) > 0)
            $this->validateRequest($request, Auxiliar::$rules);

        $input = $request->all();

		$auxiliar = $this->auxiliarRepository->store($input);

		return Response::json(ResponseManager::makeResult($auxiliar->toArray(), "Auxiliar guardado"));
	}

	/**
	 * Display the specified Auxiliar.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$auxiliar = $this->auxiliarRepository->findAuxiliarById($id);

		if(empty($auxiliar))
			$this->throwRecordNotFoundException("Auxiliar no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($auxiliar->toArray(), "Auxiliar cargado."));
	}

	/**
	 * Show the form for editing the specified Auxiliar.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified Auxiliar in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$auxiliar = $this->auxiliarRepository->findAuxiliarById($id);

		if(empty($auxiliar))
			$this->throwRecordNotFoundException("Auxiliar no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$auxiliar = $this->auxiliarRepository->update($auxiliar, $input);

		return Response::json(ResponseManager::makeResult($auxiliar->toArray(), "Auxiliar actualizado."));
	}

	/**
	 * Remove the specified Auxiliar from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$auxiliar = $this->auxiliarRepository->findAuxiliarById($id);

		if(empty($auxiliar))
			$this->throwRecordNotFoundException("Auxiliar no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$auxiliar->delete();

		return Response::json(ResponseManager::makeResult($id, "Auxiliar eliminado"));
	}
}
