<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'proveedor_id',
        'documento',
        'num_documento',
        'subtotal',
        'iva',
        'total_compra',
        'tipo_pago'
    ];    
    
    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function proveedor()
    {
        return $this->hasMany(Proveedor::class, 'proveedor_id');
    }

    protected $guarded = [];
}
