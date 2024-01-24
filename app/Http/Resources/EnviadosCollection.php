<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EnviadosCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id'                => $item->id,
                'nombre'            => $item->nombre,
                'jefe'              => $item->jefe->nombres_apellidos ?? '',
                'siglas'            => $item->siglas,
                'leido'             => $item->pivot->leido,
                'copia'             => $item->pivot->copia,
                'fecha_leido'       => $item->pivot->fecha_leido,
            ];
        });
    }
}
