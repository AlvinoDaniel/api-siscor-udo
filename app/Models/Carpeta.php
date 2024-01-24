<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;

class Carpeta extends Model
{
    // use HasFactory;

    const NAME = 'Carpeta';

    protected $fillable=[
        'nombre',
        'departamento_id',
    ];

    public function creador()
    {
        return $this->hasOne(Departamento::class);
    }


}
