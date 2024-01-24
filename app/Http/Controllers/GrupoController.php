<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\GrupoRepository;
use App\Http\Requests\GrupoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Grupo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;


class GrupoController extends AppBaseController
{
    private $repository;

    public function __construct(GrupoRepository $grupoRepository)
    {
        $this->repository = $grupoRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        try {
            $grupos = $this->repository->allGrupos();
            $message = 'Lista de Grupos';
            return $this->sendResponse(['grupos' => $grupos], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GrupoRequest $request)
    {
        $data = [
            'nombre'            =>  $request->nombre,
            'descripcion'       =>  $request->descripcion,
            'departamento_id'   =>  Auth::user()->personal->departamento_id
        ];
        $departamentos = explode(',',trim($request->departamentos));
        try {
            $this->repository->validarDepartamentos($departamentos);
            $grupo = $this->repository->registrarGrupo($data, $departamentos);
            return $this->sendResponse(
                $grupo,
                'Grupo Registrado exitosamente.'
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
            $grupo = $this->repository->obtenerGrupo($id);
            return $this->sendResponse(
                $grupo,
                'Grupo Obtenido.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0 
                    ? $th->getMessage() 
                    : 'Hubo un error al intentar Actualizar el Departamento'
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $grupo = Grupo::find($id);
   
        if(!$grupo) {
            throw new Exception('El grupo con id '.$id.' no existe.');
        } 
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                Rule::unique('grupos')->where(function ($query) {
                    return $query->where('departamento_id', Auth::user()->personal->departamento_id);
                })->ignore($grupo)
            ],
            'departamentos'     => 'required|string',
        ]);
        if ($validator->fails()) {            
            return $this->sendError($validator->errors(),422);
        }  

        $ITEMS_UPDATE = $request->only('nombre','descripcion');
        $departamentos = explode(',',trim($request->departamentos));
        try {
            $this->repository->validarDepartamentos($departamentos);
            $grupo = $this->repository->actualizarGrupo($ITEMS_UPDATE,$departamentos, $id);
            return $this->sendResponse(
                $grupo->load('departamentos'),
                'Grupo Actualizado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->eliminarGrupo($id);
            return $this->sendSuccess(
                'Grupo Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyDepartamento(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'departamento_id'     => 'required|exists:departamentos,id',
        ]);
        if ($validator->fails()) {            
            return $this->sendError($validator->errors(),422);
        }  

        try {
            $this->repository->eliminarDepartamentoGrupo($id, $request->departamento_id);
            return $this->sendSuccess(
                'Departamento Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    /**
     * Add the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addDepartamento(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'departamento_id'     => 'required|exists:departamentos,id',
        ]);
        if ($validator->fails()) {            
            return $this->sendError($validator->errors(),422);
        }  

        try {
            $this->repository->agregarDepartamentoGrupo($id, $request->departamento_id);
            return $this->sendSuccess(
                'Departamento Registrado en el grupo Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
