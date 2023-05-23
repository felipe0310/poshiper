<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'nombre',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    protected $guarded = [];
}
