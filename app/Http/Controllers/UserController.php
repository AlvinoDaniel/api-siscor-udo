<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\User;
use App\Models\Nivel;
use App\Repositories\UserRepository;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Storage;
use Artisan;
use Exception;


class UserController extends AppBaseController
{
    private $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth:api');
        $this->repository = $userRepository;
    }

    /**
     * Listar Usuarios
     *
     * [Se retorna la lista de los usuarios registrados.]
     *
     *
    */
    public function index(){
        try {
            $usuarios = $this->repository->all(['personal']);
            $message = 'Lista de Usuarios';
            return $this->sendResponse(['usuarios' => $usuarios], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Crear usuario
     *
     * [Se asigna el usuario a algún vendedor existente en el sistema.]
     *
     * @bodyParam  email email required Correo de usuario. Example: jose@gmail.com
     * @bodyParam  username required pseudonimo del usuario.
     * @bodyParam  password string required Contraseña de usuario.
     * @bodyParam  name string required Nombre del usuario.
     * @bodyParam  apellido string required Nombre del usuario.
     * @bodyParam  rol array required Nombre del rol que se desea asignar. Example: ["administrador","estandar"]
     *
     *
     *
    */

    public function store(UserRequest $request){
        $data = $request->all();
        $isJefe = in_array('jefe', $request->rol);
        try {
            if($isJefe){
                $hasJefe = $this->repository->verificarJefatura($data['departamento_id']);
                if(!$hasJefe){
                    return $this->sendError("Ya existe un usuario Jefe en el Departamento.");
                }
            }
            $user = $this->repository->registrarUsuario($data);
            return $this->sendResponse(
                $user,
                'Usuario Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = $this->repository->findById($id);
            $user->load(['personal', 'roles']);
            return $this->sendResponse(
                $user,
                'Usuario Obtenido'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getMessage()
                // $th->getCode() > 0
                //     ? $th->getMessage()
                //     : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }

    /**
     * Actualizar usuario
     *
     * [Se actualiza la infomacion de un usuario.]
     *
     * @bodyParam  email email Correo de usuario. Example: jose@gmail.com
     * @bodyParam  username pseudonimo del usuario.
     * @bodyParam  name string Nombre del usuario.
     * @bodyParam  apellido string Nombre del usuario.
     * @bodyParam  rol array Nombre del rol que se desea asignar. Example: ["administrador","estandar"]
     *
     *
    */

    public function update(UserUpdateRequest $request,$id){

        $data = $request->all();
        $data['hasFile'] = $request->hasFile('firma');
        $roles = $request->rol;
        $isJefe = in_array('jefe', $roles);
        try {
            $user = $this->repository->actualizarUsuario($data, $id);
            // if($isJefe && !$user->hasRole('jefe')){
            //     $hasJefe = $this->repository->verificarJefatura($data['departamento_id']);
            //     if(!$hasJefe){
            //         return $this->sendError("No se puede actualizar el rol. Ya existe un usuario Jefe en el Departamento.");
            //     }
            // }

                return $this->sendResponse(
                    $user,
                    'Usuario Actualzado exitosamente.'
                );
            } catch (\Throwable $th) {
                return $this->sendError(
                    $th->getMessage()
                    // $th->getCode() > 0
                    //     ? $th->getMessage()
                    //     : 'Hubo un error al intentar Actualizar el Usuario'
                );
            }

    }

    /**
     * Actualizar contraseña de usuario
     *
     * [Se actualiza la contrseña de un usuario.]
     *
     * @bodyParam  newpassword
     * @bodyParam  repassword
     *
     *
    */

    public function update_password(Request $request,$id){
        $tipo_accion = 'Actualizar Contraseña';

        try {
            $user = User::find($id);

            if(!$user){
                return $this->sendError('El rol que desea asignar al usuario no existe.');
            }

            $validator = Validator::make($request->all(), [
                'newpassword' => 'required|min:5',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 422);
            }

            $user->update(['password' => $request->newpassword]);
            return $this->sendSuccess('Contraseña Actualizada exitosamente.');
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
             $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar actualizar la contraseña del usuario');
        }
    }

     /**
     * Eliminar usuario
     *
     * [Se Elemina un usuario.]
     *
     *
     *
    */

    public function delete($id){
        try {
            $user = $this->repository->findById($id);
            $user->syncRoles([]);
            $this->repository->delete($id);
            return $this->sendSuccess(
                'Usuario Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Eliminar el Usuario'
            );
        }
    }

    public function roles(){
        $tipo_accion = 'Listar Roles';
        try {
            $all_roles = Role::select('id','name')->get();
            $message = 'Lista de Roles.';
            return $this->sendResponse(['roles' => $all_roles], $message);
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar obtener el listado de roles');
        }
    }

    public function nivel(){

        try {
            $niveles = Nivel::all();
            $message = 'Lista de Niveles.';
            return $this->sendResponse(['niveles' => $niveles], $message);
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar obtener el listado de niveles');
        }
    }

    public function backupDownload(){
        try {
            Artisan::call('backup:run');
            $files = array_reverse(Storage::disk('backup')->files('cultores'));
            return Storage::disk('backup')->download($files[0]);
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
               $th->getCode(),
               $msg_error,
               $tipo_accion,
               'error'
            );
            return $this->sendError('Ocurrio un error al intentar crear el Respaldo');
        }
    }
}

