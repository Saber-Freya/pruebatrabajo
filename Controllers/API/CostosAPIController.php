<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Costos;
use Illuminate\Http\Request;
use App\Libraries\Repositories\CostosRepository;
use Response;
use Schema;

class CostosAPIController extends AppBaseController
{

	/** @var  CostosRepository */
	private $costosRepository;

	function __construct(CostosRepository $costosRepo)
	{
		$this->costosRepository = $costosRepo;
	}

	/**
	 * Display a listing of the Costos.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $input = $request->all();

		$result = $this->costosRepository->search($input);

		$costos = $result[0];

		return Response::json(ResponseManager::makeResult($costos->toArray(), "Costos retrieved successfully."));
	}

	public function search($input)
    {
        $query = Costos::query();

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
	 * Show the form for creating a new Costos.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created Costos in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(Costos::$rules) > 0)
            $this->validateRequest($request, Costos::$rules);

        $input = $request->all();

		$costos = $this->costosRepository->store($input);

		return Response::json(ResponseManager::makeResult($costos->toArray(), "Costos guardado"));
	}

	/**
	 * Display the specified Costos.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$costos = $this->costosRepository->findCostosById($id);

		if(empty($costos))
			$this->throwRecordNotFoundException("Costos no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($costos->toArray(), "Costos cargado."));
	}

	/**
	 * Show the form for editing the specified Costos.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified Costos in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$costos = $this->costosRepository->findCostosById($id);

		if(empty($costos))
			$this->throwRecordNotFoundException("Costos no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$costos = $this->costosRepository->update($costos, $input);

		return Response::json(ResponseManager::makeResult($costos->toArray(), "Costos actualizado."));
	}

	/**
	 * Remove the specified Costos from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$costos = $this->costosRepository->findCostosById($id);

		if(empty($costos))
			$this->throwRecordNotFoundException("Costos no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$costos->delete();

		return Response::json(ResponseManager::makeResult($id, "Costos eliminado"));
	}
}
