<?php

namespace App\Models;

use App\Models\Deposito;
use App\Models\Categoria;
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
        return $this->belongsTo(Categoria::class); // pertenece a una categoria
    }

    public function depositos()
    {
        return $this->hasMany(Deposito::class); // un producto tiene muchos depositos
    }

    protected $guarded = [];
}
