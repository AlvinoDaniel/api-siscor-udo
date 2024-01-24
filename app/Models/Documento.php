<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departamento;
use App\Models\DocumentosDepartamento;
use App\Models\DocumentosTemporal;
use App\Models\Anexo;

class Documento extends Model
{
    // use HasFactory;

    const NAME = 'Documento';
    const ESTATUS_ENVIADO = 'enviado';
    const ESTATUS_ENVIADO_ALL = 'enviado_all';
    const ESTATUS_BORRADOR = 'borrador';
    const ESTATUS_POR_CORREGIR = 'por_corregir';

    protected $fillable=[
        'asunto',
        'nro_documento',
        'contenido',
        'tipo_documento',
        'estatus',
        'fecha_enviado',
        'departamento_id',
        'copias',
        'user_id',
    ];

    protected $with = ['propietario', 'anexos'];

    public function propietario()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function anexos()
    {
        return $this->hasMany(Anexo::class);
    }

    public function destino()
    {
        return $this->hasOne(documentosDepartamento::class);
    }

    public function enviados()
    {
        return $this->belongsToMany(Departamento::class, 'documentos_departamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps()->wherePivot('copia', false);
    }

    public function dptoCopias()
    {
        return $this->belongsToMany(Departamento::class, 'documentos_departamentos')->withPivot('leido', 'copia', 'fecha_leido')->withTimestamps()->wherePivot('copia', true);
    }

    public function temporal()
    {
        return $this->hasOne(documentosTemporal::class);

    }

}
