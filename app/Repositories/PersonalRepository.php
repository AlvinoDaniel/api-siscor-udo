<?php

namespace App\Repositories;

use App\Interfaces\PersonalRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Personal;
use App\Models\Departamento;
use App\Models\PersonalMigracion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class PersonalRepository extends BaseRepository {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(Personal $personal)
  {
      $this->model = $personal;
  }

  /**
   * Listar todas las departamentos de un departamento
   */
  public function alldepartamentos(){
      $departamento = Auth::user()->personal->departamento_id;
      return Departamento::whereDepartamentoId($departamento)->with('departamentos')->get();
  }

  public function searchPersonal($cedula) {
    $search = PersonalMigracion::where('cedula_identidad', 'like', '%'. $cedula. '%')->get();

    return $search;
  }

}
