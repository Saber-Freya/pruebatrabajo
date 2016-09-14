<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateproductosRequest;
use App\Models\productos;
use Illuminate\Http\Request;
use App\Libraries\Repositories\productosRepository;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;

class productosController extends AppBaseController{
	/** @var  productosRepository */
	private $productosRepository;

	function __construct(productosRepository $productosRepo)	{
		$this->productosRepository = $productosRepo;
		$this->middleware('auth');
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
		$result = $this->productosRepository->productosPaginado();

		return view('productos.index')
				->with('productos', $result);
	}
	public function fields(Request $request)
	{
		$input = $request->all();
		$result = $this->productosRepository->search($input);


		return view('productos.fields');
	}

	/**
	 * Show the form for creating a new productos.
	 *
	 * @return Response
	 */
	public function create(){

		return view('productos.create');
	}

	public function store(CreateproductosRequest $request){
		$input = $request->all();
		$id = $this->productosRepository->store($input);

		Flash::message('Guardado.');
		return redirect(route('productos.index'));
	}

	public function show($id){
		$productos = $this->productosRepository->findproductosById($id);

		if(empty($productos)){
			Flash::error('No encontrado');
			return redirect(route('productos.index'));
		}

		return view('productos.show')->with('productos', $productos);
	}

	/**
	 * Show the form for editing the specified productos.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		/*
         *  $input = $request->all();
             $id_prod = $input['id'];
             $productos = $this->productosRepository->findproductosById($id_prod);
             $materiales = $this->productosRepository->findMaterialesOfProdById($id_prod);
        */
		$productos = $this->productosRepository->findproductosById($id);

		$productos->precio_unitario2 = $productos->precio_unitario;

		if(empty($productos))
		{
			Flash::error('productos no encontrado');
			return redirect(route('productos.index'));
		}

		return view('productos.edit')
				->with('productos', $productos);
	}

	/**
	 * Update the specified productos in storage.
	 *
	 * @param  int    $id
	 * @param CreateproductosRequest $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$productos = $this->productosRepository->findproductosById($id);

		if(empty($productos))
		{
			Flash::error('No se encontro');
			return redirect(route('productos.index'));
		}

		$productos = $this->productosRepository->update($id, $request->all());


		Flash::message('Actualizado.');

		return redirect(route('productos.index'));
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
		{
			Flash::error('producto no encontrado');
			return redirect(route('productos.index'));
		}

		$productos->delete();

		Flash::message('Eliminado Correctamente');
		return redirect(route('productos.index'));
	}

	public function verificarCodigo(Request $test)
	{
		$test = $test->all();
		return $res = $this->productosRepository->verificarCodigo($test['codigo']);
	}
	public function verificarExistencia(Request $test)
	{
		$test = $test->all();
		return $res = $this->materialesRepository->verificarExistencia($test['codigo']);
	}

	public function getProductosZeros()
	{
		return $res = $this->productosRepository->getProductosZeros();
	}
	public function buscar(Request $request)
	{
		$input = $request->all();
		$productos = $this->productosRepository->buscar($input);
		//return $productos;
		return view('productos.index')
				->with('productos', $productos);
	}
	public function getVentasProductosByID(Request $request)
	{
		$input = $request->all();
		return $producto = $this->productosRepository->getVentasProductosByID($input['id']);
	}
	public function reporte(Request $request)
	{
		$input = $request->all();
		$array = [];
		$array['inicio'] = $input['inicio'];
		$inicio = $this->productosRepository->getFecha($input['inicio']);
		$final = $this->productosRepository->getFecha($input['final']);

		$vista = view('productos.reporte')
				->with($array)
				->with('fecha', $input['fecha'])
				->with('inicio', $input['inicio'])
				->with('final', $input['final'])
				->with('Tinicio', $inicio)
				->with('Tfinal', $final);

		$pdf = \App::make('dompdf.wrapper');
		$pdf->loadHTML($vista);
		$pdf->save(storage_path()."/pdf/reporte/prods".$input['nombre'].".pdf");
		return $pdf->stream("mats".$input['nombre'].".pdf");
	}
	public function buscarReporte($num)
	{
		$name = storage_path()."/pdf/reporte/prods".$num.".pdf";
		return Response::make(file_get_contents($name),200,[
				'Content-Type' => 'application/pdf',
				'Content-Disposition' => 'inline; '.$name,
		]);

	}
	public function buscarProducto(Request $request){

		$input = $request->all();
		$busqueda = $input['busqueda'];

		$busquedas = $this->productosRepository->busqueda($busqueda);

		if (empty($busquedas)) {
			/*Flash::error('No se encuentra cliente con ese nombre.');*/
			return view('productos.index');
		}else{
			return view('productos.index')
					->with('productos', $busquedas);
		}
	}
}
