<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'historiales';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'producto_id','usuario_id','almacen_id','motivo','stock','tipo','estado','stock_antiguo','stock_nuevo','fecha_registro'
    ];

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function almacenes()
    {
        return $this->hasMany(Almacen::class);
    }

    protected $guarded = [];
}
