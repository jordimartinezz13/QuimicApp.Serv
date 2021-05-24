<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profesores';

    use HasFactory;

    protected $fillable = [
        'nombre', 'apellidos', 'email', 'es_admin'
    ];
}
