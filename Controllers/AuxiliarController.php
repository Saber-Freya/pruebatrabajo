<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateAuxiliarRequest;
use App\Models\Auxiliar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Repositories\AuxiliarRepository;
use Illuminate\Support\Facades\DB;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;

class AuxiliarController extends Controller {

	private $auxiliarRepository;

	function __construct(AuxiliarRepository $auxiliarRepo){
		$this->auxiliarRepository = $auxiliarRepo;
		$this->middleware('auth');

		/*$this->beforeFilter('ver_pacientes', array('only' => 'index') );
		$this->beforeFilter('crear_pacientes', array('only' => 'create') );
		$this->beforeFilter('crear_pacientes', array('only' => 'store') );
		$this->beforeFilter('editar_pacientes', array('only' => 'edit') );
		$this->beforeFilter('editar_pacientes', array('only' => 'update') );
		$this->beforeFilter('eliminar_pacientes', array('only' => 'delete') );*/
	}

	public function index(Request $request){
		$input = $request->all();
		$result = $this->auxiliarRepository->search($input);
		$auxiliars = $result[0];
		$attributes = $result[1];
		return view('auxiliars.index')
				->with('auxiliars', $auxiliars)
				->with('attributes', $attributes);
	}

	public function create(){
		$emails = [];
		return view('auxiliars.create')
				->with('emails', $emails);
	}

	public function store(CreateAuxiliarRequest $request){
		$input = $request->all();

		if($input['coloniaA'] != "" && $input['calleA'] != "" && $input['no_extA'] != ""){
			$input['domicilio'] = "Col. ".$input['coloniaA'].", Calle ".$input['calleA'].", No. Exterior ".$input['no_extA'].", No. Interno ".$input['no_intA'];
		}else{
			$input['domicilio'] = 'Faltan datos';
		}

		$auxiliar = $this->auxiliarRepository->store($input);
		$id_auxiliar = $auxiliar['id'];
		if (!empty($input["e"])) {
			$this->auxiliarRepository->multiEmails($input["e"], $id_auxiliar);
		}
		Flash::message('Guardado');
		return redirect(route('auxiliars.index'));
	}

	public function show($id){
		$auxiliar = $this->auxiliarRepository->findAuxiliarById($id);
		if(empty($auxiliar)){
			Flash::error('No se encontro');
			return redirect(route('auxiliars.index'));
		}
		return view('auxiliars.show')->with('auxiliar', $auxiliar);
	}

	public function edit($id){
		$auxiliar = $this->auxiliarRepository->findAuxiliarById($id);
		$emails = $this->auxiliarRepository->buscarEmails($id);
		if(empty($auxiliar)){
			Flash::error('No se encontro');
			return redirect(route('auxiliares.index'));
		}
		return view('auxiliars.edit')
				->with('auxiliar', $auxiliar)
				->with('emails', $emails);
	}

	public function update($id, CreateAuxiliarRequest $request){
		$input = $request->all();

		if($input['coloniaA'] != "" && $input['calleA'] != "" && $input['no_extA'] != ""){
			$input['domicilio'] = "Col. ".$input['coloniaA'].", Calle ".$input['calleA'].", No. Exterior ".$input['no_extA'].", No. Interno ".$input['no_intA'];
		}else{
			$input['domicilio'] = 'Faltan datos';
		}

		if(!empty($input["e"])) {
			$this->auxiliarRepository->multiEmailsEdit($input["e"], $id);
		}else {
			$emails = $this->auxiliarRepository->buscarEmails($id);
			foreach($emails as $email){
				DB::table('correos')->where('id',$email->id)->delete();
			}
		}
		$auxiliar = $this->auxiliarRepository->findAuxiliarById($id);
		if(empty($auxiliar)){
			Flash::error('No se encontro');
			return redirect(route('auxiliars.index'));
		}
		$auxiliar = $this->auxiliarRepository->update($auxiliar, $input);
		Flash::message('Actualizado.');
		return redirect(route('auxiliars.index'));
	}

	public function destroy($id){
		$auxiliar = Auxiliar::find($id);

		if(empty($auxiliar))
		{
			Flash::error('No se encontro');
			return redirect(route('auxiliars.index'));
		}

		$auxiliar->delete();

		Flash::message('Borrado.');

		return redirect(route('auxiliars.index'));
	}

	public function pagar($id_cirugia,$id_auxiliar){
		$hoy = Carbon::now();
		$hoy = $hoy->format('Y-m-d');
		DB::table('cirugia_auxiliares')
				->where('id_cirugia', $id_cirugia)
				->where('id_auxiliar', $id_auxiliar)
				->update(['estatus'=>'1','fecha_pago'=>$hoy]);

		Flash::message('Pagado.');

		return redirect(route('cirugias.index'));
	}

	public function guardarModificaciones(Request $request){

		$input = $request->all();
		$pago = $input['pago'];
		$comentarios = $input['comentarios'];
		$id_cirugia = $input['id_cirugia'];
		$id_auxiliar = $input['id_auxiliar'];

		if ($pago == ''){
			DB::table('cirugia_auxiliares')
					->where('id_cirugia', $id_cirugia)
					->where('id_auxiliar', $id_auxiliar)
					->update(['comentarios'=>$comentarios]);
		}else{
			DB::table('cirugia_auxiliares')
					->where('id_cirugia', $id_cirugia)
					->where('id_auxiliar', $id_auxiliar)
					->update(['pago'=>$pago,'comentarios'=>$comentarios]);
		}

		/*Flash::message('Pagado.');

		return redirect(route('cirugias.index'));*/
	}

	public function buscarAuxiliar(Request $request){

		$input = $request->all();
		$busqueda = $input['busqueda'];

		$busquedas = $this->auxiliarRepository->busqueda($busqueda);

		if (empty($busquedas)) {
			/*Flash::error('No se encuentra cliente con ese nombre.');*/
			return view('auxiliars.index');
		}else{
			return view('auxiliars.index')
					->with('auxiliars', $busquedas);
		}
	}

}
