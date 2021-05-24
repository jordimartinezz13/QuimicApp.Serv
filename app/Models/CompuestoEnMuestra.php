<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompuestoEnMuestra extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'compuestos_en_muestras';

    use HasFactory;
    protected $fillable = [
        'nombre', 'id_compuesto', 'id_condiciones', 'id_muestra', 'cantidad',
        'minutos', 'altura'
    ];
}
