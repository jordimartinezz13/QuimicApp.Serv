<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condicion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'condiciones';

    use HasFactory;

    protected $fillable = [
        'longitud_columna', 'diametro_interior_columna', 'tamano_particula', 'temperatura', 'velocidad_flujo',
        'eluyente', 'detector_uv'
    ];
}
