<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'almacen_id',
        'producto_id',
        'usuario_id',
        'stock',
        'stock_minimo',
    ];

    public function almacenes()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id'); // pertenece a un almacen
    }

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'producto_id'); // un inventario tiene muchos productos
    }

    //public function usuarios()
    //{
    //    return $this->hasMany(Users::class); // un producto tiene muchos depositos
    //}

    protected $guarded = [];
}
