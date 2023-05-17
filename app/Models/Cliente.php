<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'rut',
        'nombre',
        'apellido',
        'direccion',
        'telefono',
        'limite_credito',
        'credito_usado',
        'descuento',
    ];

    protected $guarded = [];
}
