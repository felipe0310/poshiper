<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'descripcion',
        'codigo_barras',
        'precio_compra',
        'precio_venta',
        'precio_mayoreo',
        'precio_oferta',
        'categoria_id',
        'proveedor_id',
        'cantidad_caja',
    ];

    public function categorias()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id'); // pertenece a una categoria
    }

    public function detallesCompra()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function inventarios()
    {
        return $this->hasMany(Categoria::class, 'id'); // pertenece a una categoria
    }

    protected $guarded = [];
}
