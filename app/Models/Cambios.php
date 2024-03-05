<?php

namespace App\Models;

use App\Models\User;
use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cambios extends Model
{
    use HasFactory;

    protected $table = 'cambios';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'usuario_id ',
        'almacen_id ',
        'producto_ingresa_id',
        'cantidad_ingresa',
        'producto_sale_id',
        'cantidad_sale',
    ];

    public function almacenes()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'producto_ingresa_id');
    }


}