<?php

namespace App\Repositories;

use App\Interfaces\GrupoRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Grupo;
use App\Models\Departamento;
use App\Models\GruposDepartamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class GrupoRepository extends BaseRepository {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   * 
   * @param Model $model
   */
  public function __construct(Grupo $grupo)
  {
      $this->model = $grupo;
  }

  /**
   * Listar todas las Grupos de un departamento
   */
  public function allGrupos(){
      $departamento = Auth::user()->personal->departamento_id;
      return Grupo::whereDepartamentoId($departamento)->with('departamentos')->get();
  }

  /**
   * Registrar grupo de un Departamento
   * @param Array $departamentos 
   * @param Array $data 
   */
   public function registrarGrupo($data, $departamentos){   

      try {
         DB::beginTransaction();
         $grupo = Grupo::create($data);

         foreach ($departamentos as $dpto) {
               GruposDepartamento::create([
               'grupo_id'        => $grupo->id,
               'departamento_id' => $dpto,
            ]);
         }

         DB::commit();
         return $grupo;
      } catch (\Throwable $th) {
         DB::rollBack();
         throw new Exception('Hubo un error al intentar Crear el Grupo.');
      }
   }

  /**
   * Obtener un grupo de un Departamento
   * @param Integer $id 
   */
   public function obtenerGrupo($id){  
      try {
         $grupo = Grupo::with('departamentos')->find($id);
         if(!$grupo) {
            throw new Exception('El grupo con id '.$id.' no existe.',422);
         }         
         return $grupo;
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode());
      }
   }

  /**
   * Obtener un grupo de un Departamento
   * @param Array $dptos 
   */
   public function validarDepartamentos($dptos){  
      foreach ($dptos as $departamento_id) {
         $dpto = Departamento::find($departamento_id);
         if(!$dpto){
            throw new Exception('El departamento con id '.$departamento_id.' no existe.');
         }
     }
   }

   /**
   * Actualizar grupo de un Departamento
   * @param Array $departamentos 
   * @param Array $data 
   */
   public function actualizarGrupo($data, $departamentos, $id){  

      $grupo = Grupo::find($id);
   
      try {
         DB::beginTransaction();
         
         foreach ($data as $campo => $value) {
            if(!empty($value)){
               $grupo->update([$campo => $value]);
            }
         }
         //Se elimina todos los departamentos integrantes del Grupo
         GruposDepartamento::where('grupo_id', $id)->delete();
         //Se Reasigna todos los departamentos a actualizar
         foreach ($departamentos as $dpto) {
               GruposDepartamento::create([
               'grupo_id'        => $grupo->id,
               'departamento_id' => $dpto,
            ]);
         }

         DB::commit();
         return $grupo;
      } catch (\Throwable $th) {
         DB::rollBack();
         throw new Exception('Hubo un error al intentar Actualizar el Grupo.');
      }
   }

   /**
   * Actualizar grupo de un Departamento
   * @param Array $departamentos 
   * @param Array $data 
   */
   public function eliminarGrupo($id){  

      $grupo = Grupo::find($id);
      if(!$grupo) {
         throw new Exception('El grupo con id '.$id.' no existe.');
      } 
   
      try {
         DB::beginTransaction();
         //Se elimina todos los departamentos integrantes del Grupo
         GruposDepartamento::where('grupo_id', $id)->delete();
         //Se elimina el Grupo
         Grupo::whereId($id)->delete();

         DB::commit();
      } catch (\Throwable $th) {
         DB::rollBack();
         throw new Exception('Hubo un error al intentar Eliminar el Grupo.');
      }
   }

   /**
   * Eliminar Departamento de un Grupo
   * @param Integer $id_grupo 
   * @param Integer $id_dpto 
   */
   public function eliminarDepartamentoGrupo($id_grupo,$id_dpto){  

      $grupo = Grupo::find($id_grupo);
      if(!$grupo) {
         throw new Exception('El grupo con id '.$id_dpto.' no existe.');
      } 
   
      try {
         $grupo->departamentos()->detach($id_dpto);
      } catch (\Throwable $th) {
         throw new Exception('Hubo un error al intentar Eliminar el departamento del Grupo.');
      }
   }

  /**
   * Agregar Departamento de un Grupo
   * @param Integer $id_grupo 
   * @param Integer $id_dpto 
   */
   public function agregarDepartamentoGrupo($id_grupo,$id_dpto){  

      $grupo = Grupo::find($id_grupo);
      if(!$grupo) {
         throw new Exception('El grupo con id '.$id_dpto.' no existe.');
      } 
   
      try {
         $grupo->departamentos()->attach($id_dpto);
      } catch (\Throwable $th) {
         throw new Exception('Hubo un error al intentar Registrar el departamento del Grupo.');
      }
   }
        
}