<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documento;
use App\Models\Personal;
use App\Models\Carpeta;
use App\Models\Plantilla;
use App\Models\Grupo;
use App\User;
use App\Models\Nucleo;

class Departamento extends Model
{
    // use HasFactory;

    const NAME = 'Departamento';

    protected $fillable=[
        'nombre',
        'siglas',
        'codigo',
        'correo',
        'cod_nucleo',
    ];

    protected $with = ['jefe', 'nucleo'];

    public function documentos() {
        return $this->hasMany(Documento::class, 'departamento_id');
    }

    public function carpetas() {
        return $this->belongsTo(Carpeta::class);
    }

    public function plantillas() {
        return $this->belongsTo(Plantilla::class);
    }

    public function recibidos()
    {
        return $this->belongsToMany(Documento::class, 'documentos_departamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps();
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupos_departamentos');
    }

    public function personal() {
        return $this->hasMany(Personal::class);
    }

    public function jefe() {
        return $this->hasOne(Personal::class)->where('jefe', 1);
    }

    public function nucleo() {
        return $this->hasOne(Nucleo::class, 'codigo_concatenado', 'cod_nucleo');
    }
}
