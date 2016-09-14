<?php namespace App\Http\Controllers;

use App\Libraries\Repositories\ClienteRepository;
use App\Models\Cliente;
use App\Models\Servicio;
use App\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {

	public function __construct(ClienteRepository $clienteRepo){
		$this->clienteRepository = $clienteRepo;
		$this->middleware('auth');
	}

	public function index(){
		$emails = [];
		$listaClientes = Cliente::
		select('*',DB::raw('CONCAT(nombre," ",apellido) as paciente'))
				->lists('paciente','id');
		$estados = $this->clienteRepository->getEstados();

		$t_clientes = Cliente::select()->count();
		$usuarios = User::select()->count();
		$t_servicios = Servicio::select()->count();
		return view('home')
				->with('t_clientes', $t_clientes)
				->with('t_servicios', $t_servicios)
				->with('listaClientes', $listaClientes)
				->with('emails',$emails)
				->with('fechaante',"")
				->with('estados',$estados)
				->with('usuarios', $usuarios);
	}

}
