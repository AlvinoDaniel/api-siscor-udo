<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documento;
use App\Models\Carpeta;

class CarpertasDocumento extends Model
{
    // use HasFactory;


    protected $fillable=[
        'carpeta_id',
        'departamento_id',
    ];

    public function carpeta() {
        return $this->belongsTo(Carpeta::class);
    }

    public function documento() {
        return $this->belongsTo(Documento::class);
    }

}
