<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CarpetaRepository;
use App\Http\Requests\CarpetaRequest;
use Illuminate\Support\Facades\Auth;

class CarpetaController extends AppBaseController
{
    private $repository;

    public function __construct(CarpetaRepository $carpetaRepository)
    {
        $this->repository = $carpetaRepository;
    }

     /**
     * Listar Carpetas de un Departamento.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $carpetas = $this->repository->allCarpetas();
            $message = 'Lista de Carpetas';
            return $this->sendResponse(['carpetas' => $carpetas], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

     /**
     * Registrar carpeta de un Departamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarpetaRequest $request)
    {
        $data = [
            'nombre'            =>  $request->nombre,
            'departamento_id'   => Auth::user()->personal->departamento_id
        ];
        try {
            $carpeta = $this->repository->registrar($data);
            return $this->sendResponse(
                $carpeta,
                'Carpeta Registrada exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError('Ocurrio un error al intentar registrar la carpeta');
        }
    }

    /**
     * Actualizar carpeta de un Departamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CarpetaRequest $request, $id)
    {
        $data = [
            'nombre'            =>  $request->nombre,
            'departamento_id'   => Auth::user()->personal->departamento_id
        ];

        try {      
            $this->repository->actualizar($data, $id);
            return $this->sendSuccess('Carpeta Actualizada exitosamente.');
        } catch (\Throwable $th) {
            return $this->sendError('Ocurrio un error al intentar actualizar la carpeta del departamento');
        }
    }

    /**
     * Actualizar carpeta de un Departamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {      
            $this->repository->delete($id);
            return $this->sendSuccess('Carpeta Eliminada exitosamente.');
        } catch (\Throwable $th) {
            return $this->sendError('Ocurrio un error al intentar eliminar la carpeta');
        }
    }

}
