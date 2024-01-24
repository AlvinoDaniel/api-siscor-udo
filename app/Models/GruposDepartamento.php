<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;
use App\Models\Grupo;

class GruposDepartamento extends Model
{
    // use HasFactory;

    protected $fillable=[
        'grupo_id',
        'departamento_id',
    ];

    public function grupo() {
        return $this->belongsTo(Grupo::class);
    }

    public function departamento() {
        return $this->belongsTo(Departamento::class);
    }
    
}
