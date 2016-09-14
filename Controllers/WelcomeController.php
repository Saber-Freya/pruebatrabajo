<?php namespace App\Http\Controllers;

use App\Libraries\Repositories\ClienteRepository;
use App\Libraries\Repositories\MedicamentoRepository;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller {

	public function __construct(ClienteRepository $clienteRepo,MedicamentoRepository $medicamentoRepo){
		$this->clienteRepository = $clienteRepo;
		$this->medicamentoRepository = $medicamentoRepo;
		$this->middleware('auth');
	}

	public function index(){
		$emails = [];

		$listaClientes = Cliente::
		select('*',
				DB::raw('CONCAT(nombre," ",apellido) as paciente'))
				->lists('paciente','id');

		$estados = $this->clienteRepository->getEstados();
		$medicamentos = $this->medicamentoRepository->all();

		return view('welcome')
				->with('listaClientes', $listaClientes)
				->with('emails',$emails)
				->with('medicamentos', $medicamentos)
				->with('estados',$estados);
	}

}
