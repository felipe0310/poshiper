<?php

namespace App\Models;

use App\Models\Deposito;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


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
    ];

    public function categorias()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id'); // pertenece a una categoria
    }

    protected $guarded = [];
}
