<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserAuthCollection extends ResourceCollection
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
                'fullName'          => $item->personal->nombres_apellidos,
                'email'             => $item->email,
                'usuario'           => $item->usuario,
                'status'            => $item->status,
                'departamento_id'   => $item->personal->departamento_id,
                'rol_id'            => $item->roles[0]->id,
                'rol'               => $item->roles[0]->name,
            ];
        });
    }
}
