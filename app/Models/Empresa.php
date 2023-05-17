<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'rut',
        'razon_social',
        'direccion',
        'email',
        'iva',
    ];

    public function almacenes()
    {
        return $this->hasMany(Almacen::class); //una empresa tiene muchos almacenes
    }

    protected $guarded = [];
}
