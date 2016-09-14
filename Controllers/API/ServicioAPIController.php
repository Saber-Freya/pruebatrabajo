<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ServicioRepository;
use Response;
use Schema;

class ServicioAPIController extends AppBaseController
{

	/** @var  ServicioRepository */
	private $servicioRepository;

	function __construct(ServicioRepository $servicioRepo)
	{
		$this->servicioRepository = $servicioRepo;
	}

	/**
	 * Display a listing of the Servicio.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $input = $request->all();

		$result = $this->servicioRepository->search($input);

		$servicios = $result[0];

		return Response::json(ResponseManager::makeResult($servicios->toArray(), "Servicios retrieved successfully."));
	}

	public function search($input)
    {
        $query = Servicio::query();

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
	 * Show the form for creating a new Servicio.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created Servicio in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(Servicio::$rules) > 0)
            $this->validateRequest($request, Servicio::$rules);

        $input = $request->all();

		$servicio = $this->servicioRepository->store($input);

		return Response::json(ResponseManager::makeResult($servicio->toArray(), "Servicio guardado"));
	}

	/**
	 * Display the specified Servicio.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$servicio = $this->servicioRepository->findServicioById($id);

		if(empty($servicio))
			$this->throwRecordNotFoundException("Servicio no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($servicio->toArray(), "Servicio cargado."));
	}

	/**
	 * Show the form for editing the specified Servicio.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified Servicio in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$servicio = $this->servicioRepository->findServicioById($id);

		if(empty($servicio))
			$this->throwRecordNotFoundException("Servicio no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$servicio = $this->servicioRepository->update($servicio, $input);

		return Response::json(ResponseManager::makeResult($servicio->toArray(), "Servicio actualizado."));
	}

	/**
	 * Remove the specified Servicio from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$servicio = $this->servicioRepository->findServicioById($id);

		if(empty($servicio))
			$this->throwRecordNotFoundException("Servicio no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$servicio->delete();

		return Response::json(ResponseManager::makeResult($id, "Servicio eliminado"));
	}
}
