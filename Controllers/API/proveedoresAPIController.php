<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\proveedores;
use Illuminate\Http\Request;
use App\Libraries\Repositories\proveedoresRepository;
use Response;
use Schema;

class proveedoresAPIController extends AppBaseController{

	/** @var  proveedoresRepository */
	private $proveedoresRepository;

	function __construct(proveedoresRepository $proveedoresRepo)
	{
		$this->proveedoresRepository = $proveedoresRepo;
	}

	/**
	 * Display a listing of the proveedores.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $input = $request->all();

		$result = $this->proveedoresRepository->search($input);

		$proveedores = $result[0];

		return Response::json(ResponseManager::makeResult($proveedores->toArray(), "proveedores retrieved successfully."));
	}

	public function search($input)
    {
        $query = proveedores::query();

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
	 * Show the form for creating a new proveedores.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created proveedores in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(proveedores::$rules) > 0)
            $this->validateRequest($request, proveedores::$rules);

        $input = $request->all();

		$proveedores = $this->proveedoresRepository->store($input);

		return Response::json(ResponseManager::makeResult($proveedores->toArray(), "proveedores guardado"));
	}

	/**
	 * Display the specified proveedores.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$proveedores = $this->proveedoresRepository->findproveedoresById($id);

		if(empty($proveedores))
			$this->throwRecordNotFoundException("proveedores no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($proveedores->toArray(), "proveedores cargado."));
	}

	/**
	 * Show the form for editing the specified proveedores.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified proveedores in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$proveedores = $this->proveedoresRepository->findproveedoresById($id);

		if(empty($proveedores))
			$this->throwRecordNotFoundException("proveedores no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$proveedores = $this->proveedoresRepository->update($proveedores, $input);

		return Response::json(ResponseManager::makeResult($proveedores->toArray(), "proveedores actualizado."));
	}

	/**
	 * Remove the specified proveedores from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$proveedores = $this->proveedoresRepository->findproveedoresById($id);

		if(empty($proveedores))
			$this->throwRecordNotFoundException("proveedores no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$proveedores->delete();

		return Response::json(ResponseManager::makeResult($id, "proveedores eliminado"));
	}
}
