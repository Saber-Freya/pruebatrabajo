<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Permission;
use App\Role;
use App\RolesPermission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Mitul\Controller\AppBaseController;
use Illuminate\Support\Facades\Input;

class PermisosController extends AppBaseController  {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	protected $permisos;

	public function  __construct(){
		$permisos = Permission::all();
		$this->permisos = $permisos;
	}

	public function index(){
		return \Response::json($this->getPermisos(Input::get('id')));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(){
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
		//
	}

	private function getPermisos($id_rol){
		$permisos = array();
		$permisos['todosPermisos'] = $this->getTodosPermisos();
		$permisos['permisosAsignados'] = $this->getPermisosAsignados($id_rol);
		return $permisos;

	}
	private function getTodosPermisos(){
		$permisosDeRol = RolesPermission::leftJoin('permissions','permissions.id','=','permission_role.permission_id')
				->select('*') ->orderBy('modulo','desc')->get();
		/*$permisosDeRol = RolesPermission::all();*/
		$todos = array();
		foreach($permisosDeRol as $p){
			foreach ($this->permisos as $key => $value){
				if($value->id == $p->permission_id){
					$todos[] = $value;
				}
			}
		}
		return $todos;
	}

	private function getPermisosAsignados($id_rol){
		$permisosDeRol = RolesPermission::leftJoin('permissions','permissions.id','=','permission_role.permission_id')
				->where('role_id', '=', $id_rol)->orderBy('modulo','desc')->get();
		/*$permisosDeRol = RolesPermission::where('role_id', '=', $id_rol)->get();*/
		$asignados = array();
		foreach($permisosDeRol as $p){
			foreach ($this->permisos as $key => $value){
				if($value->id == $p->permission_id){
					$asignados[] = $value;
				}
			}
		}
		return $asignados;
	}

	public function asignar(){
		$rol = Role::find(Input::get('role_id'));
		$rol->attachPermission(Input::get('permission_id'));
		return \Response::json('ok');
	}

	public function desasignar(){
		$rol = Role::find(Input::get('role_id'));
		$rolPermisos = RolesPermission::where('role_id', '=', Input::get('role_id'))
				->where('permission_id', '=', Input::get('permission_id'))->get()->first();

		if(empty($rolPermisos->id)){
			return \Response::json('no existe');
		}else {
			$desasignar = RolesPermission::destroy($rolPermisos->id);
			return \Response::json('ok');
		}
	}

}
