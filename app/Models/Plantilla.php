<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;

class Plantilla extends Model
{
    // use HasFactory;

    const NAME = 'Plantilla';

    protected $fillable=[
        'asunto',
        'contenido',
        'departamento_id',
    ];

    public function propietario()
    {
        return $this->hasOne(Departamento::class);
    }

}
