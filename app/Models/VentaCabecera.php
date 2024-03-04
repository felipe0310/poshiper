<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaCabecera extends Model
{
    use HasFactory;

    protected $table = 'venta_cabeceras';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'cliente_id','almacen_id','usuario_id','docalmacen_id','caja_id','serie','nro_comprobante','descripcion','pago_efectivo','pago_tarjeta','pago_transferencia','pago_credito','subtotal','iva','delivery','total_venta','tipo_pago','estado','fecha_venta'
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class); 
    }

    public function almacenes()
    {
        return $this->hasMany(Almacen::class); 
    }

    public function usuarios()
    {
        return $this->hasMany(User::class); 
    }

    public function docAlmacenes()
    {
        return $this->hasMany(DocAlmacen::class); 
    }

    public function cajas()
    {
        return $this->hasMany(Caja::class);
    }

    protected $guarded = [];
}
