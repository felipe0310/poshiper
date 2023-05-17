<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocAlmacen extends Model
{
    use HasFactory;

    protected $table = 'doc_almacenes';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'almacen_id',
        'documento',
        'serie',
        'cantidad',
    ];

    public function productos()
    {
        return $this->hasMany(Almacen::class); //una categoria tiene muchos productos
    }

    protected $guarded = [];
}
