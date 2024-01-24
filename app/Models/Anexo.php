<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Documento;

class Anexo extends Model
{
    // use HasFactory;

    protected $fillable=[
        'nombre',
        'urlAnexo',
        'documento_id',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

}
