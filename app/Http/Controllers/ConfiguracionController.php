<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ConfiguracionRepository;
use App\Http\Requests\FirmaRequest;

class ConfiguracionController extends AppBaseController
{
    private $repository;

    public function __construct(ConfiguracionRepository $configuracionRepository)
    {
        $this->repository = $configuracionRepository;
    }

    /**
     * GUARDAR FIRMA DEL JEFE DE DEPARTAMENTO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createFirma(FirmaRequest $request)
    {
        $file = $request->file('firma');

        try {
            $this->repository->storeFirma($file);
            $message = 'Firma actualizada corectamente.';
            return $this->sendSuccess($message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
            // 'Hubo un error al intentar Actualizar la firma del Jefe'
        }
    }
}
