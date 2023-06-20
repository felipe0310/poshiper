<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compras';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'compra_id',
        'producto_id',
        'cantidad',
        'total_compra'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id'); // pertenece a una categoria
    }
    
    protected $guarded = [];
}
