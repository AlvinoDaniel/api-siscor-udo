<?php

namespace App\Repositories;

use App\Interfaces\CarpetaRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Carpeta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CarpetaRepository extends BaseRepository {

        /**
         * @var Model
         */
        protected $model;

        /**
         * Base Repository Construct
         * 
         * @param Model $model
         */
        public function __construct(Carpeta $carpeta)
        {
            $this->model = $carpeta;
        }

        /**
         * Listar todas las carpetas de un departamento
         */
        public function allCarpetas(){
            $departamento = Auth::user()->personal->departamento_id;
            return Carpeta::whereDepartamentoId($departamento)->get();
        }
        
}