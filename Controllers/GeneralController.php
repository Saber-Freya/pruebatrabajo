<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Libraries\Repositories\GeneralRepository;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;

class GeneralController extends AppBaseController
{

	private $GeneralRepository;

	function __construct(GeneralRepository $generalRepo)
	{
		$this->GeneralRepository = $generalRepo;
		$this->middleware('auth');
	}

	public function getCiudadesByEdoId(Request $request){
		$input = $request->all();
		return $this->GeneralRepository->getCiudadesByEdoId($input['id']);
	}

}
