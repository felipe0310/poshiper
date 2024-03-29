<?php

namespace App\Models;

use App\Models\Almacen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'cajas';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'usuario_id ',
        'almacen_id ',
        'fecha_apertura',
        'fecha_cierre',
        'monto_apertura',
        'monto_ingreso',
        'monto_egreso',
        'monto_cierre',
        'estado',
    ];

    public function almacenes()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id'); 
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'usuario_id'); 
    }


}
