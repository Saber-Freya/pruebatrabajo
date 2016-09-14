<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateproveedoresRequest;
use App\Libraries\Repositories\EstudioRepository;
use App\Models\proveedores;
use Illuminate\Http\Request;
use App\Libraries\Repositories\proveedoresRepository;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;
class proveedoresController extends AppBaseController{

	private $proveedoresRepository;

	function __construct(proveedoresRepository $proveedoresRepo, EstudioRepository $estudioRepo){
		$this->proveedoresRepository = $proveedoresRepo;
		$this->estudioRepository = $estudioRepo;
		$this->middleware('auth');
	}

	public function index(Request $request){
		$input = $request->all();
		$result = $this->proveedoresRepository->search($input);
		$proveedores = $result[0];
		$attributes = $result[1];

		return view('proveedores.index')
				->with('proveedores', $proveedores)
				->with('attributes', $attributes);
	}

	public function create(){
		$estudios = $this->estudioRepository->all();
		return view('proveedores.create')
				->with("estudios", $estudios);
	}

	public function store(CreateproveedoresRequest $request){
        $input = $request->all();
		$proveedor = $this->proveedoresRepository->store($input);

		if ($input['estudios'] != 'vacio'){
			$this->proveedoresRepository->storeControl($proveedor->id,$input['estudios']);
		}

		Flash::message('Guardado.');
		return redirect(route('proveedores.index'));
	}

	public function show($id){
		$proveedores = $this->proveedoresRepository->findproveedoresById($id);
		if(empty($proveedores)){
			Flash::error('No se encontro.');
			return redirect(route('proveedores.index'));
		}
		return view('proveedores.show')->with('proveedores', $proveedores);
	}

	public function edit($id){

		$proveedor = $this->proveedoresRepository->findproveedoresById($id);
		$estudios = $this->estudioRepository->all();
		$estudiosE = $this->estudioRepository->estudioE($id);

		if(empty($proveedor)){
			Flash::error('No se encontro.');
			return redirect(route('proveedores.index'));
		}
		return view('proveedores.edit')
				->with('estudios', $estudios)
				->with('estudiosE', $estudiosE)
				->with('proveedor', $proveedor);
	}

	public function update($id, CreateproveedoresRequest $request){
		$input = $request->all();
		$proveedor = $this->proveedoresRepository->findproveedoresById($id);

		if(empty($proveedor)){
			Flash::error('No se encontro.');
			return redirect(route('proveedores.index'));
		}

		$proveedores = $this->proveedoresRepository->update($proveedor, $input);

		//borrar estudios
		$this->proveedoresRepository->borrarControl($id);

		if ($input['estudios'] != 'vacio') {
			//guardar nuevos estudios
			$this->proveedoresRepository->storeControl($id, $input['estudios']);
		}

		Flash::message('Actualizado.');
		return redirect(route('proveedores.index'));
	}

	public function destroy($id){
		$proveedor = $this->proveedoresRepository->findproveedoresById($id);
		$consultaAsignada = $this->proveedoresRepository->consultaAsignada($id);

		if(empty($proveedor)){
			Flash::error('No se encontro.');
			return redirect(route('proveedores.index'));
		}

		if($consultaAsignada == null){
			$proveedor->delete();
			//borrar control estudios - proveedor
			$this->proveedoresRepository->borrarControl($id);
			Flash::message('Borrado.');
			return redirect(route('proveedores.index'));

		}else{
			return $mensaje = 'error';
		}
	}

	public function buscarProveedoresByEstudio($estudio){
		$proveedores = $this->proveedoresRepository->buscarProveedoresByEstudio($estudio);
		return $proveedores;
	}

}
