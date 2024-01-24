<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PersonalRepository;
use App\Http\Requests\PersonalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Personal;
use Exception;

class PersonalController extends AppBaseController
{
    private $repository;

    public function __construct(PersonalRepository $personalRepository)
    {
        $this->repository = $personalRepository;
    }

    /**
     * Listar todo el Personal.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $personal = $this->repository->all(['departamento']);
            $message = 'Lista de Trabajadores';
            return $this->sendResponse(['personal' => $personal], $message);
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
    public function store(PersonalRequest $request)
    {
        $data = $request->all();
        try {
            $personal = $this->repository->registrar($data);
            return $this->sendResponse(
                $personal,
                'Personal Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError('Hubo un error al intentar Registrar el Personal');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PersonalRequest $request, $id)
    {
        $data = $request->all();
        try {
            $personal = $this->repository->actualizar($data, $id);
            return $this->sendResponse(
                $personal,
                'Personal Actualzado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Actualizar el Personal'
            );
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
            $this->repository->delete($id);
            return $this->sendSuccess(
                'Personal Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Actualizar el Departamento'
            );
        }
    }

    public function search(Request $request) {
        $cedula = $request->cedula;

        try {
            $result = $this->repository->searchPersonal($cedula);
            return $this->sendResponse(
                $result,
                'Resultado de la Busqueda.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
