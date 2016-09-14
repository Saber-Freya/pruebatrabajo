<?php namespace App\Http\Controllers;

use App\Libraries\Repositories\ClienteRepository;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller {

	public function __construct(ClienteRepository $clienteRepo){
		$this->clienteRepository = $clienteRepo;
		$this->middleware('auth');
	}

	public function index(){
		$emails = [];

		$listaClientes = Cliente::
		select('*',
				DB::raw('CONCAT(nombre," ",apellido) as paciente'))
				->lists('paciente','id');

		$estados = $this->clienteRepository->getEstados();

		return view('welcome')
				->with('listaClientes', $listaClientes)
				->with('emails',$emails)
				->with('estados',$estados);
	}

}
