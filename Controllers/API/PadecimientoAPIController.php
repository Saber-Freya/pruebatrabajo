<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Padecimiento;
use Illuminate\Http\Request;
use App\Libraries\Repositories\PadecimientoRepository;
use Response;
use Schema;

class PadecimientoAPIController extends AppBaseController
{

	/** @var  PadecimientoRepository */
	private $padecimientoRepository;

	function __construct(PadecimientoRepository $padecimientoRepo)
	{
		$this->padecimientoRepository = $padecimientoRepo;
	}

	/**
	 * Display a listing of the Padecimiento.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $input = $request->all();

		$result = $this->padecimientoRepository->search($input);

		$padecimientos = $result[0];

		return Response::json(ResponseManager::makeResult($padecimientos->toArray(), "Padecimientos retrieved successfully."));
	}

	public function search($input)
    {
        $query = Padecimiento::query();

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
	 * Show the form for creating a new Padecimiento.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created Padecimiento in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(Padecimiento::$rules) > 0)
            $this->validateRequest($request, Padecimiento::$rules);

        $input = $request->all();

		$padecimiento = $this->padecimientoRepository->store($input);

		return Response::json(ResponseManager::makeResult($padecimiento->toArray(), "Padecimiento guardado"));
	}

	/**
	 * Display the specified Padecimiento.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$padecimiento = $this->padecimientoRepository->findPadecimientoById($id);

		if(empty($padecimiento))
			$this->throwRecordNotFoundException("Padecimiento no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($padecimiento->toArray(), "Padecimiento cargado."));
	}

	/**
	 * Show the form for editing the specified Padecimiento.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified Padecimiento in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$padecimiento = $this->padecimientoRepository->findPadecimientoById($id);

		if(empty($padecimiento))
			$this->throwRecordNotFoundException("Padecimiento no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$padecimiento = $this->padecimientoRepository->update($padecimiento, $input);

		return Response::json(ResponseManager::makeResult($padecimiento->toArray(), "Padecimiento actualizado."));
	}

	/**
	 * Remove the specified Padecimiento from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$padecimiento = $this->padecimientoRepository->findPadecimientoById($id);

		if(empty($padecimiento))
			$this->throwRecordNotFoundException("Padecimiento no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$padecimiento->delete();

		return Response::json(ResponseManager::makeResult($id, "Padecimiento eliminado"));
	}
}
