<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Cirugia;
use Illuminate\Http\Request;
use App\Libraries\Repositories\CirugiaRepository;
use Response;
use Schema;

class CirugiaAPIController extends AppBaseController
{

	/** @var  CirugiaRepository */
	private $cirugiaRepository;

	function __construct(CirugiaRepository $cirugiaRepo)
	{
		$this->cirugiaRepository = $cirugiaRepo;
	}

	/**
	 * Display a listing of the Cirugia.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $input = $request->all();

		$result = $this->cirugiaRepository->search($input);

		$cirugias = $result[0];

		return Response::json(ResponseManager::makeResult($cirugias->toArray(), "Cirugias retrieved successfully."));
	}

	public function search($input)
    {
        $query = Cirugia::query();

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
	 * Show the form for creating a new Cirugia.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created Cirugia in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(Cirugia::$rules) > 0)
            $this->validateRequest($request, Cirugia::$rules);

        $input = $request->all();

		$cirugia = $this->cirugiaRepository->store($input);

		return Response::json(ResponseManager::makeResult($cirugia->toArray(), "Cirugia guardado"));
	}

	/**
	 * Display the specified Cirugia.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$cirugia = $this->cirugiaRepository->findCirugiaById($id);

		if(empty($cirugia))
			$this->throwRecordNotFoundException("Cirugia no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($cirugia->toArray(), "Cirugia cargado."));
	}

	/**
	 * Show the form for editing the specified Cirugia.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified Cirugia in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$cirugia = $this->cirugiaRepository->findCirugiaById($id);

		if(empty($cirugia))
			$this->throwRecordNotFoundException("Cirugia no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$cirugia = $this->cirugiaRepository->update($cirugia, $input);

		return Response::json(ResponseManager::makeResult($cirugia->toArray(), "Cirugia actualizado."));
	}

	/**
	 * Remove the specified Cirugia from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$cirugia = $this->cirugiaRepository->findCirugiaById($id);

		if(empty($cirugia))
			$this->throwRecordNotFoundException("Cirugia no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$cirugia->delete();

		return Response::json(ResponseManager::makeResult($id, "Cirugia eliminado"));
	}
}
