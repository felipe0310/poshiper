<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $table = 'venta_detalles';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'ventacabecera_id','producto_id','cantidad','total_venta'
    ];

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

    protected $guarded = [];
}
