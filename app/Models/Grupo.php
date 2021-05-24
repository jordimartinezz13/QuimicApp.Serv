<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{

    protected $fillable = [
        'nombre'
    ];
    use HasFactory;
}
