<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Libraries\Repositories\ClienteRepository;
use Response;
use Schema;

class ClienteAPIController extends AppBaseController
{

	/** @var  ClienteRepository */
	private $clienteRepository;

	function __construct(ClienteRepository $clienteRepo)
	{
		$this->clienteRepository = $clienteRepo;
	}

	/**
	 * Display a listing of the Cliente.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $input = $request->all();

		$result = $this->clienteRepository->search($input);

		$clientes = $result[0];

		return Response::json(ResponseManager::makeResult($clientes->toArray(), "Clientes retrieved successfully."));
	}

	public function search($input)
    {
        $query = Cliente::query();

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
	 * Show the form for creating a new Cliente.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created Cliente in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(Cliente::$rules) > 0)
            $this->validateRequest($request, Cliente::$rules);

        $input = $request->all();

		$cliente = $this->clienteRepository->store($input);

		return Response::json(ResponseManager::makeResult($cliente->toArray(), "Cliente guardado"));
	}

	/**
	 * Display the specified Cliente.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$cliente = $this->clienteRepository->findClienteById($id);

		if(empty($cliente))
			$this->throwRecordNotFoundException("Cliente no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		return Response::json(ResponseManager::makeResult($cliente->toArray(), "Cliente cargado."));
	}

	/**
	 * Show the form for editing the specified Cliente.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified Cliente in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$cliente = $this->clienteRepository->findClienteById($id);

		if(empty($cliente))
			$this->throwRecordNotFoundException("Cliente no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$cliente = $this->clienteRepository->update($cliente, $input);

		return Response::json(ResponseManager::makeResult($cliente->toArray(), "Cliente actualizado."));
	}

	/**
	 * Remove the specified Cliente from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$cliente = $this->clienteRepository->findClienteById($id);

		if(empty($cliente))
			$this->throwRecordNotFoundException("Cliente no encontrado", ERROR_CODE_RECORD_NOT_FOUND);

		$cliente->delete();

		return Response::json(ResponseManager::makeResult($id, "Cliente eliminado"));
	}
}
