<?php

namespace App\Models;

use App\Models\User;
use App\Models\Almacen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table = 'movimiento_cajas';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'usuario_id ',
        'almacen_id ',
        'tipo',
        'descripcion',
        'monto'
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
