<?php

namespace App\Models;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deposito extends Model
{
    use HasFactory;
    protected $table = 'depositos';
    protected $primaryKey = 'id';
   
    protected $fillable =
    [
        'producto_id',
        'stock'
    ];

    public function productos()
    {
        return $this->belongsTo(Producto::class); //un deposito tiene un productos
    }

    protected $guarded = [];

}
