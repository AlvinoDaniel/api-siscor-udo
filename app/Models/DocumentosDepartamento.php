<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Documento;
use App\Models\Departamento;

class DocumentosDepartamento extends Pivot
{
    // use HasFactory;
    protected $table = 'documentos_departamentos';
    protected $fillable=[
        'documento_id',
        'departamento_id',
        'leido',
        'copia',
        'fecha_leido',
    ];

    public function documento() {
        return $this->belongsTo(Documento::class);
    }

    public function departamento() {
        return $this->belongsTo(Departamento::class);
    }

}
