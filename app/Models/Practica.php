<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practica extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_profesor', 'id_compuesto_en_muestra', 'fecha_inicio', 'fecha_fin', 'enunciado'
    ];
}
