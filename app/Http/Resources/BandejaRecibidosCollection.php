<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BandejaRecibidosCollection extends ResourceCollection
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
                'id'              => $item->id,
                'asunto'          => $item->asunto,
                'tipo_documento'  => $item->tipo_documento,
                'estatus'         => $item->estatus,
                'fecha_enviado'   => $item->fecha_enviado,
                'leido'           => $item->pivot->leido,
                'copia'           => $item->pivot->copia,
                'propietario'     => $item->propietario,
                'anexos'          => count($item->anexos),

            ];
        });
    }
}
