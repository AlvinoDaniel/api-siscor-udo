<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;

class Grupo extends Model
{
    // use HasFactory;

    const NAME = 'Grupo';

    protected $fillable=[
        'nombre',
        'descripcion',
        'departamento_id',
    ];

    public function departamentos() {
        return $this->belongsToMany(Departamento::class, 'grupos_departamentos');
    }
}
