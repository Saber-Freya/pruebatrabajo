<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\productos;
use Illuminate\Http\Request;
use App\Libraries\Repositories\productosRepository;
use Response;
use Schema;

class productosAPIController extends AppBaseController
{

	/** @var  productosRepository */
	private $productosRepository;

	function __construct(productosRepository $productosRepo)
	{
		$this->productosRepository = $productosRepo;
	}

	/**
	 * Display a listing of the productos.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $input = $request->all();

		$result = $this->productosRepository->search($input);

		$productos = $result[0];

		return Response::json(ResponseManager::makeResult($productos->toArray(), "productos retrieved successfully."));
	}

	public function search($input)
    {
        $query = productos::query();

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
	 * Show the form for creating a new productos.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created productos in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(productos::$rules) > 0)
            $this->validateRequest($request, productos::$rules);

        $input = $request->all();

		$productos = $this->productosRepository->store($input);

		return Response::json(ResponseManager::makeResult($productos->toArray(), "productos guardado"));
	}

	/**
	 * Display the specified productos.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$productos = $this->productosRepository->findproductosById($id);

		if(empty($productos))
			$this->throwRecordNotFoundException("productos no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($productos->toArray(), "productos cargado."));
	}

	/**
	 * Show the form for editing the specified productos.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified productos in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$productos = $this->productosRepository->findproductosById($id);

		if(empty($productos))
			$this->throwRecordNotFoundException("productos no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$productos = $this->productosRepository->update($productos, $input);

		return Response::json(ResponseManager::makeResult($productos->toArray(), "productos actualizado."));
	}

	/**
	 * Remove the specified productos from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$productos = $this->productosRepository->findproductosById($id);

		if(empty($productos))
			$this->throwRecordNotFoundException("productos no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$productos->delete();

		return Response::json(ResponseManager::makeResult($id, "productos eliminado"));
	}
}
