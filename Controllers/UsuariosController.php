<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateUsuariosRequest;
use App\Libraries\Repositories\UsuariosRepository;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Mitul\Controller\AppBaseController;
use Response;
use Flash;
/*use yajra\Datatables\Datatables;*/
use Zizaco\Entrust;
class UsuariosController extends AppBaseController{

	private $usuariosRepository;

	function __construct(UsuariosRepository $usuariosRepo){
		$this->usuariosRepository = $usuariosRepo;
		$this->middleware('auth');

        $this->beforeFilter('ver_usuarios', array('only' => 'index') );
        $this->beforeFilter('crear_usuarios', array('only' => 'create') );
        $this->beforeFilter('crear_usuarios', array('only' => 'store') );
        $this->beforeFilter('editar_usuarios', array('only' => 'edit') );
        $this->beforeFilter('editar_usuarios', array('only' => 'update') );
        $this->beforeFilter('eliminar_usuarios', array('only' => 'delete') );
	}

    public function index(Request $request){
        $usuarios = $this->usuariosRepository->all();
        return view('usuarios.index')
            ->with('usuarios', $usuarios);
    }

    public function create(){
        $lista_roles = Role::where('id','!=',1)->lists('display_name','id');
        $roles = DB::table('roles')->select()->get();
        $listaEstados = DB::table('estados')->orderBy('title')->lists('title', 'id');

        return view('usuarios.create')
            ->with('roles', $roles)
            ->with('lista_roles', $lista_roles)
            ->with('listaEstados', $listaEstados);
    }

    public function store(CreateUsuariosRequest $request){
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $usuario = $this->usuariosRepository->store($input);
        $this->usuariosRepository->storeRoleUser($input,$usuario->id);

        Flash::message('Guardado.');
        return redirect(route('usuarios.index'));
    }

    public function show($id){
        $usuarios = $this->usuariosRepository->findUsuariosById($id);

        if(empty($usuarios)){
            Flash::error('No se encontro.');
            return redirect(route('usuarios.index'));
        }

        return view('usuarios.show')->with('usuarios', $usuarios);
    }

    public function postUserData(Request $requests){
        $input = $requests->all();
        $usuario = User::find($input['id']);
        return $usuario;
    }

    public function edit($id){
        $lista_roles = Role::where('id','!=',1)->lists('display_name','id');
        $usuarios = $this->usuariosRepository->findUsuarios($id);
        $listaEstados = DB::table('estados')->orderBy('title')->lists('title', 'id');

        if(empty($usuarios)){
            Flash::error('No se encontro.');
            return redirect(route('usuarios.index'));
        }

        return view('usuarios.edit')
            ->with('usuarios', $usuarios)
            ->with('lista_roles', $lista_roles)
            ->with('listaEstados', $listaEstados);
    }

    public function update($id,CreateUsuariosRequest $request){
        $usuarios = $this->usuariosRepository->findUsuariosById($id);

        if(empty($usuarios)){
            Flash::error('No se encontro.');
            return redirect(route('usuarios.index'));
        }

        $usuarios = $this->usuariosRepository->update($usuarios, $request->all());
        Flash::message('Actualizado.');
        return redirect(route('usuarios.index'));
    }

    public function destroy($id){
        $usuarios = $this->usuariosRepository->findUsuariosById($id);

        if($id == '1'){
            Flash::error('No se puede borrar el Usuario Maestro.');
        }elseif (empty($usuarios)){
            Flash::error('No se encontro.');
            return redirect(route('usuarios.index'));
        }else{
            $usuarios->delete();
            Flash::message('Borrado.');
            return redirect(route('usuarios.index'));
        }
    }

    public function ciudades($estado){
        $ciudades = DB::table('ciudades')->leftjoin('estados','estados.id','=','ciudades.estado_id')
            ->select('ciudades.*','ciudades.title as ciudad','estados.title as estado')
            ->where('estado_id',$estado)->orderBy('title')->get();
        return $ciudades;
    }

}
