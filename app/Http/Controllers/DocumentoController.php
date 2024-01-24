<?php

namespace App\Http\Controllers;

use Response;
use Carbon\Carbon;
use App\Models\Anexo;
use App\Models\Documento;
use Illuminate\Http\Request;
use App\Traits\DepartamentoTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AnexoRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentosDepartamento;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DocumentoRequest;
use App\Repositories\DocumentoRepository;
use App\Http\Controllers\AppBaseController;

use Exception;

class DocumentoController extends AppBaseController
{
    use DepartamentoTrait;

    private $repository;

    public function __construct(DocumentoRepository $documentoRepository)
    {
        $this->repository = $documentoRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentoRequest $request)
    {
        $estatus = [
            'enviar'        => Documento::ESTATUS_ENVIADO,
            'enviar_all'    => Documento::ESTATUS_ENVIADO_ALL,
        ];
        $data = [
            'asunto'            =>  $request->asunto,
            'contenido'         =>  $request->contenido,
            'tipo_documento'    =>  $request->tipo_documento,
            'estatus'           =>  $estatus[$request->estatus],
            'departamento_id'   =>  Auth::user()->personal->departamento_id,
            'fecha_enviado'     =>  Carbon::now(),
            'copia'             =>  $request->copias
        ];
        $hasCopia = $request->copias;
        $departamentos_destino = explode(',',trim($request->departamentos_destino));
        $departamentos_copias = $hasCopia ? explode(',',trim($request->departamentos_copias)) : [];
        $dataCopia = [
            'copia'         => $request->copias,
            'departamentos' => $departamentos_copias
        ];
        try {
            $this->validarDepartamentos($departamentos_destino);
            if($request->copias){
                $this->validarDepartamentos($departamentos_copias);
            }
            $documento = $this->repository->crearDocumento($data, $departamentos_destino, $dataCopia);
            if($request->hasFile('anexos')) {
                $this->repository->attachAnexos($request->file('anexos'), $documento->id);
            }
            return $this->sendResponse(
                $documento,
                'Documento enviado exitosamente'
            );
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
    public function storeTemporal(DocumentoRequest $request)
    {
        $data = [
            'asunto'            =>  $request->asunto,
            'nro_documento'     =>  0,
            'contenido'         =>  $request->contenido,
            'tipo_documento'    =>  $request->tipo_documento,
            'departamento_id'   =>  Auth::user()->personal->departamento_id,
        ];
        $data['estatus'] = $request->estatus === 'corregir' ? Documento::ESTATUS_POR_CORREGIR : Documento::ESTATUS_BORRADOR;

        $hasCopia = $request->copias;
        $departamentos_destino = explode(',',trim($request->departamentos_destino));
        $departamentos_copias = $hasCopia ? explode(',',trim($request->departamentos_copias)) : [];
        $dataTemporal = [
            'departamentos_destino' => $request->departamentos_destino,
            'departamentos_copias'  => $request->departamentos_copias,
            'tieneCopia'            => $hasCopia,
        ];
        try {
            $this->validarDepartamentos($departamentos_destino);
            if($request->copias){
                $this->validarDepartamentos($departamentos_copias);
            }
            $documento = $this->repository->crearTemporalDocumento($data, $dataTemporal);
            if($request->hasFile('anexos')) {
                $this->repository->attachAnexos($request->file('anexos'), $documento->id);
            }
            return $this->sendResponse(
                $documento,
                'Documento guardado exitosamente'
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
    public function show(Request $request, $id)
    {
        $relaciones = null;
        if (isset($request->estatus)) {
            switch ($request->estatus) {
                case 'temporal':
                    $relaciones = ['temporal'];
                    break;
                case 'enviado':
                    $relaciones = ['enviados', 'dptoCopias'];
                    break;

                default:
                    $relaciones = [];
                    break;
            }
        }
        try {
            $documento = $this->repository->obtenerDocumento($id, $relaciones);

            if($request->estatus === 'temporal'){
                $this->repository->leidoDocumentoTemporal($id);
            }

            if($documento->departamento_id !== Auth::user()->personal->departamento_id){
                $this->repository->leidoDocumento($id);
            }

            return $this->sendResponse(
                $documento,
                $request->estatus
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentoRequest $request, $id)
    {
        $status = [
            'enviar' => Documento::ESTATUS_ENVIADO,
            'enviar_all' => Documento::ESTATUS_ENVIADO_ALL,
            'borrador' => Documento::ESTATUS_BORRADOR,
            'corregir' => Documento::ESTATUS_POR_CORREGIR,
        ];
        $data = [
            'asunto'            =>  $request->asunto,
            'contenido'         =>  $request->contenido,
            'tipo_documento'    =>  $request->tipo_documento,
            'estatus'           =>  $status[$request->estatus],
            'departamento_id'   =>  Auth::user()->personal->departamento_id,
        ];
        $hasCopia = $request->copias;
        $departamentos_destino = explode(',',trim($request->departamentos_destino));
        $departamentos_copias = $hasCopia ? explode(',',trim($request->departamentos_copias)) : [];
        $dataTemporal = [
            'departamentos_destino' => $request->departamentos_destino,
            'departamentos_copias'  => $request->departamentos_copias,
            'tieneCopia'            => $hasCopia,
        ];
        try {
            $this->validarDepartamentos($departamentos_destino);
            if($hasCopia){
                $this->validarDepartamentos($departamentos_copias);
            }
            $documento = $this->repository->updateTemporalDocumento($data, $dataTemporal, $id);
            if($request->hasFile('anexos')) {
                $this->repository->attachAnexos($request->file('anexos'), $documento->id);
            }
            $mensaje = $request->estatus === Documento::ESTATUS_ENVIADO ? 'Documento enviado exitosamente' : 'Documento guardado exitosamente';
            return $this->sendResponse(
                $documento,
                $mensaje
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
        //
    }

    /**
     * Remove Anexo
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addAnexo(AnexoRequest $request)
    {
        try {
            if($request->hasFile('anexos')) {
                $anexos =  $this->repository->attachAnexos($request->file('anexos'), $request->documento_id);
                return $this->sendResponse(
                    $anexos,
                    'Anexo creado Exitosamente.'
                );
            }
            else {
                throw new Exception('Los anexos deben ser de tipo Archivo',422);
            }
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0 ? $th->getMessage() : 'Hubo un error al intentar Agregar el Anexo.',
                $th->getCode()
            );
        }
    }
    /**
     * Remove Anexo
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAnexo($id)
    {
        try {
            $this->repository->deleteAnexo($id);
            return $this->sendSuccess(
                'Anexo Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Elminar el Anexo.',
                $th->getCode()
            );
        }
    }

    /**
     * Remove Anexo
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyDocument($id)
    {
        try {
            $data = $this->repository->eliminarDocumento($id);
            return $this->sendSuccess(
                'Documento Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage()
                // $th->getCode() > 0
                //     ? $th->getMessage()
                //     : 'Hubo un error al intentar Elminar el Anexo.',
                // $th->getCode()
            );
        }
    }

    public function downloadAnexo($id) {
        try {
            $anexo = Anexo::find($id);
            if(!$anexo) {
                throw new Exception('El anexo con id '.$id.' no existe.',422);
            }

            return Storage::disk('anexos')->download($anexo->urlAnexo);

        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Descargar el Archivo.',
                $th->getCode()
            );
        }
    }

    function getNames($data, $estatus){

        if($estatus === 'enviado_all'){
            return 'COMUNIDAD UNIVERSITARIA';
        }

        $nombres = [];
        foreach ($data as $value) {
            array_push($nombres, $value->nombre);
        }

        return implode(', ',$nombres);
    }

    public function genareteDocument($id){
        // try {
        //     $relaciones = ['enviados', 'dptoCopias'];
        //     $documento = $this->repository->obtenerDocumento($id, $relaciones);
        //     $hasCopias = count($documento->dptoCopias) > 0;
        //     $copiasNombres = [];
        //     if($hasCopias){
        //         foreach ($documento->dptoCopias as $value) {
        //            array_push($copiasNombres, $value->nombre);
        //         }
        //     }
        //     $pdf = \PDF::loadView('pdf.documento', [
        //         'dptoPropietario'   => $documento->propietario->nombre,
        //         'dptoSiglas'        => $documento->propietario->siglas,
        //         'fechaEnviado'      => Carbon::create($documento->fecha_enviado)->format('d \d\e F \d\e Y'),
        //         'dptoCopias'        => implode(', ',$copiasNombres),
        //         'hasCopias'         => $hasCopias ,
        //         'contenido'         => $documento->contenido,
        //         'isCircular'        => $documento->tipo_documento === 'circular',
        //         'isOficio'          => $documento->tipo_documento === 'oficio',
        //         'nucleo'            => $documento->propietario->nucleo->nombre,
        //         'nucleoDireccion'   => $documento->propietario->nucleo->direccion,
        //         'propietarioJefe'   => $documento->propietario->jefe->nombres_apellidos,
        //         'propietarioCargo'  => $documento->propietario->jefe->descripcion_cargo,
        //         'baseUrlFirma'      => $documento->propietario->jefe->baseUrlFirma,
        //         'destino'           => $documento->tipo_documento === 'circular' ? $this->getNames($documento->enviados, $documento->estatus) : $documento->enviados[0]->jefe,

        //     ]);
        //     return $pdf->download('Documento_Recibido.pdf');
        // } catch (\Throwable $th) {
        //     return $this->sendError(
        //         $th->getMessage()
        //         // $th->getCode() > 0
        //         //     ? $th->getMessage()
        //         //     : 'Hubo un error al intentar Obtener el documento'
        //     );
        // }
    }

}
