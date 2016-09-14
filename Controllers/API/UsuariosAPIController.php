<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use Mitul\Controller\AppBaseController;
use Mitul\Generator\Utils\ResponseManager;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use App\Libraries\Repositories\UsuariosRepository;
use Response;

class UsuariosAPIController extends AppBaseController
{

	/** @var  UsuariosRepository */
	private $usuariosRepository;

	function __construct(UsuariosRepository $usuariosRepo)
	{
		$this->usuariosRepository = $usuariosRepo;
	}

	/**
	 * Display a listing of the Usuarios.
	 *
	 * @return Response
	 */
	public function index()
	{
		$usuarios = $this->usuariosRepository->all();

		return Response::json(ResponseManager::makeResult($usuarios->toArray(), "Usuarios retrieved successfully."));
	}

	/**
	 * Show the form for creating a new Usuarios.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created Usuarios in storage.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(Usuarios::$rules) > 0)
            $this->validateRequest($request, Usuarios::$rules);
        $input = $request->all();
		$usuarios = $this->usuariosRepository->store($input);
		return Response::json(ResponseManager::makeResult($usuarios->toArray(), "Usuarios saved successfully."));
	}

	/**
	 * Display the specified Usuarios.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$usuarios = $this->usuariosRepository->findUsuariosById($id);
		if(empty($usuarios))
			$this->throwRecordNotFoundException("Usuarios not found", ERROR_CODE_RECORD_NOT_FOUND);
		return Response::json(ResponseManager::makeResult($usuarios->toArray(), "Usuarios retrieved successfully."));
	}

	/**
	 * Show the form for editing the specified Usuarios.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}
	/**
	 * Update the specified Usuarios in storage.
	 *
	 * @param  int    $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$usuarios = $this->usuariosRepository->findUsuariosById($id);

		if(empty($usuarios))
			$this->throwRecordNotFoundException("Usuarios not found", ERROR_CODE_RECORD_NOT_FOUND);

		$input = $request->all();

		$usuarios = $this->usuariosRepository->update($usuarios, $input);

		return Response::json(ResponseManager::makeResult($usuarios->toArray(), "Usuarios updated successfully."));
	}

	/**
	 * Remove the specified Usuarios from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$usuarios = $this->usuariosRepository->findUsuariosById($id);

		if(empty($usuarios))
			$this->throwRecordNotFoundException("Usuarios not found", ERROR_CODE_RECORD_NOT_FOUND);

		$usuarios->delete();

		return Response::json(ResponseManager::makeResult($id, "Usuarios deleted successfully."));
	}

}
