<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documento;

class DocumentosTemporal extends Model
{
    // use HasFactory;

    protected $table = 'documentos_temporal';

    protected $fillable=[
        'documento_id',
        'departamentos_destino',
        'departamentos_copias',
        'tieneCopia',
        'leido',
    ];

    public function documento() {
        return $this->belongsTo(Documento::class);
    }
}
