<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompuestoQuimico extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'compuestos_quimicos';

    use HasFactory;

    protected $fillable = [
        'nombre', 'formula', 'descripcion', 'tipo', 'estructura'
    ];
}
