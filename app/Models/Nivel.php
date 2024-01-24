<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;


class Nivel extends Model
{
    // use HasFactory;
    protected $table = 'nivel';
    protected $fillable=[
        'descripcion',
        'abreviatura',
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
