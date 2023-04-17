<?php

namespace App\Models;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categorias';
    protected $primaryKey = 'id';
   
    protected $fillable =
    [
        'nombre'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class); //una categoria tiene muchos productos
    }

    protected $guarded = [];

    
}
