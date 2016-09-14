<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateHorariosRequest;
use Illuminate\Http\Request;
use App\Libraries\Repositories\HorariosRepository;
use Illuminate\Support\Facades\DB;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;

class HorariosController extends AppBaseController{
	private $horariosRepository;

	function __construct(HorariosRepository $horariosRepo)	{
		$this->horariosRepository = $horariosRepo;
		$this->middleware('auth');

		$this->beforeFilter('ver_horarios', array('only' => 'index') );
		$this->beforeFilter('crear_horarios', array('only' => 'create') );
		$this->beforeFilter('crear_horarios', array('only' => 'store') );
		$this->beforeFilter('editar_horarios', array('only' => 'edit') );
		$this->beforeFilter('editar_horarios', array('only' => 'update') );
		$this->beforeFilter('eliminar_horarios', array('only' => 'delete') );
	}

	public function index(Request $request){
		$horarios = $this->horariosRepository->HorariosPaginado();
		return view('horarios.index')
				->with('horarios', $horarios);
	}
	
	public function create(){
		return view('horarios.create');
	}
	
	public function store(CreateHorariosRequest $request)	{
		$input = $request->all();
		$this->horariosRepository->store($input);
		Flash::message('Guardado.');
		return redirect(route('horarios.index'));
	}

	public function show($id){
		$horarios = $this->horariosRepository->findHorariosById($id);

		if(empty($horarios)){
			Flash::error('producto no encontrado');
			return redirect(route('horarios.index'));
		}
		return view('horarios.show')->with('horarios', $horarios);
	}
	
	public function edit($id){

		$horario = $this->horariosRepository->findHorariosById($id);

		if(empty($horario)){
			Flash::error('No encontrado.');
			return redirect(route('horarios.index'));
		}

		return view('horarios.edit')
				->with('horarios', $horario);
	}

	public function update($id, CreateHorariosRequest $request)	{
		$input = $request->all();

		$horarios = $this->horariosRepository->findHorariosById($id);

		if(empty($horarios)){
			Flash::error('No se encontro');
			return redirect(route('horarios.index'));
		}

		$horarios = $this->horariosRepository->update($id, $input);


		Flash::message('Actualizado.');

		return redirect(route('horarios.index'));
	}

	public function destroy($id)	{
		$horarios = $this->horariosRepository->findHorariosById($id);

		if(empty($horarios)){
			Flash::error('No encontrado.');
			return redirect(route('horarios.index'));
		}

		$horarios->delete();

		Flash::message('Borrado.');
		return redirect(route('horarios.index'));
	}

	public function todos()	{
		$horarios = $this->horariosRepository->all();
		return $horarios;
	}

	public function horarioCita($no_dia,$tipo)	{
		$horarios = $this->horariosRepository->horarioCita($no_dia,$tipo);
		return $horarios;
	}

	public function inhabiles(){
		//dia de la semana inhabiles
		$inhabiles = [];

		for ($i = 0; $i <= 6; $i++) {

		$horario = DB::table('horarios')
				->where('dia', $i)
				->where('deleted_at', null)
				->get();
			if (!count($horario)) {
				array_push($inhabiles, $i);
			}else{
				//si despues se necesita enviar la informacion
				/*dd('con contenido');*/
			}
		}
		/*dd($inhabiles);*/
		return $inhabiles;
	}

}
