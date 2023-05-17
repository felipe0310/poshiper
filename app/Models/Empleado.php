<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'nombres',
        'apellidos',
        'direccion',
        'telefono',
        'email',
        'rut',

    ];

    protected $guarded = [];
}
