<?php

namespace App\Repositories;

use App\Interfaces\ConfiguracionRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Personal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;


class ConfiguracionRepository {

   /**
   * Guardar Firma en Carpeta Storage/Firma
   */
  public function storeFirma($file){

    try {
        $personal = Personal::find(Auth::user()->personal_id);

        if($personal->firma !== null){
            Storage::disk('firmas')->delete($personal->firma);
        }

        $fileName = str_replace(' ','',$personal->nombres).'_'.str_replace(' ','',$personal->apellidos).'_'.$personal->cedula_identidad.'.'.$file->getClientOriginalExtension();

        $personal->update(['firma' => $fileName]);
        Storage::disk('firmas')->putFileAs('/',$file,$fileName);
    } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
    }


  }
}
