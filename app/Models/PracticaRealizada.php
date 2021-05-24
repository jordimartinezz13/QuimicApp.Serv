<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticaRealizada extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'practicas_realizadas';

    use HasFactory;
    protected $fillable = [
        'id_practica', 'id_grupo', 'respuesta_alumno', 'nota', 'comentario_alumno',
        'comentario_profesor', 'fichero', 'puede_proceder'
    ];
}
