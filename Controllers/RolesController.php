<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Requests\CreateRolesRequest;
use App\Libraries\Repositories\RoleRepository;
use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use Mitul\Controller\AppBaseController;
use Zizaco\Entrust;
class RolesController extends AppBaseController {

    public function __construct(RoleRepository $rolesRepo){
        $this->rolesRepository = $rolesRepo;
        $this->middleware('auth');
        $this->beforeFilter('ver_roles', array('only' => 'index') );
        $this->beforeFilter('crear_roles', array('only' => 'create') );
        $this->beforeFilter('crear_roles', array('only' => 'store') );
        $this->beforeFilter('editar_roles', array('only' => 'edit') );
        $this->beforeFilter('editar_roles', array('only' => 'update') );
        $this->beforeFilter('eliminar_roles', array('only' => 'delete') );
    }

    public function index(){

        /*$roles = Role::all();*/
        $roles = Role::paginate(15);
        $permisos = Permission::all();

        return view('roles.index')
        ->with('roles',$roles)
        ->with('permisos',$permisos);
    }

    public function create(){
        $permisos = DB::table('permissions')
            ->select('modulo', 'id', 'display_name', 'name')
            ->orderBy('display_name', 'asc')
            ->get();

        $modulos = DB::table('permissions')
            ->select('modulo', 'id', 'display_name')
            ->groupBy('modulo')
            ->get();

        return view('roles.create')
            ->with('modulos',$modulos)
            ->with('todos',$permisos);
    }

    public function store(CreateRolesRequest $request){
        $input = $request->all();

        Role::create([
            'name'  => $input['name'],
            'display_name'  => $input['display_name'],
            'description'  => $input['description']
        ]);

        Flash::message('Guardado.');

        return redirect(route('roles.index'));
    }

    public function show($id){
        //
    }

    public function edit($id){
        $role = $this->rolesRepository->findRolesById($id);

        if(empty($role)){
            Flash::error('Role not found');
            return redirect(route('roles.index'));
        }

        $permisosSel = DB::table('permissions')
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->select('modulo', 'permissions.id', 'display_name', 'name')
            ->where('permission_role.role_id', $id)
            /*->orderBy(DB::raw('FIELD(modulo,
				"usuarios",
				"roles"
				)'))*/
            ->orderBy('display_name', 'asc')
            ->get();

        $permisos = DB::table('permissions')
            ->select('modulo', 'id', 'display_name', 'name')
            /*->orderBy(DB::raw('FIELD(modulo,
				"usuarios",
				"roles"
				)'))*/
            ->orderBy('display_name', 'asc')
            ->get();

        $modulos = DB::table('permissions')
            ->select('modulo', 'id', 'display_name')
            ->groupBy('modulo')
            /*->orderBy(DB::raw('FIELD(modulo,
				"usuarios",
				"roles"
				)'))*/
            ->get();

        return view('roles.edit')
            ->with('role', $role)
            ->with('permisos',$permisosSel)
            ->with('todos',$permisos)
            ->with('modulos',$modulos);
    }

    public function update($id,CreateRolesRequest $request){
        $roles = $this->rolesRepository->findRolesById($id);

        if(empty($roles)){
            Flash::error('rol no encontrado');
            return redirect(route('roles.index'));
        }

        $roles = $this->rolesRepository->update($roles, $request->all());

        Flash::message('Eliminado.');

        return redirect(route('roles.index'));
    }

    public function destroy($id){
        $roles = $this->rolesRepository->findRolesById($id);
        $usuarioAsignado = $this->rolesRepository->usuarioAsignado($id);

        if(empty($roles)){
            Flash::error('No se encontro.');
            return redirect(route('roles.index'));
        }

        if($usuarioAsignado == null){
            $roles->delete();
            Flash::message('Borrado.');
            return redirect(route('roles.index'));
        }else{
            return $mensaje = 'error';
        }

    }

    function agregarRol(Request $request){
        $input = $request->all();
        $permisos = $input["permisos_id"];
        $name = $input["name"];
        $display_name = $input["display_name"];
        $id_rol = $this->rolesRepository->store($input);

        foreach($permisos as $p){
            $this->rolesRepository->guardarPermisoRol($id_rol->id, $p);
        }

        Flash::message('Actualizado.');
        return redirect(route('roles.index'));
    }

    function actualizarRol(Request $request){
        $input = $request->all();
        $permisos = $input["permisos_id"];
        $name = $input["name"];
        $display_name = $input["display_name"];
        $id_rol = $input['id'];

        $this->rolesRepository->eliminarPermisosRol($id_rol);
        $this->rolesRepository->actualizarRol($id_rol,$input);

        foreach($permisos as $p){
            $this->rolesRepository->guardarPermisoRol($id_rol, $p);
        }

        Flash::message('Actualizado.');
        return redirect(route('roles.index'));
    }

    public function showAll(){
        return $roles = $this->rolesRepository->all();
    }

}