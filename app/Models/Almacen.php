<?php

namespace App\Models;

use App\Models\Empresa;
use App\Models\DocAlmacen;
use App\Models\Inventario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Almacen extends Model
{
    use HasFactory;
    protected $table = 'almacenes';
    protected $primaryKey = 'id';    

    protected $fillable =
    [
        'empresa_id',
        'descripcion',
        'ubicacion',
        'entrada',
        'salida'
    ];

    public function empresas()
    {
        return $this->belongsTo(Empresa::class); // pertenece a una empresa
    }

    public function docalmacen()
    {
        return $this->belongsTo(DocAlmacen::class); // pertenece a una empresa
    }
    
    public function inventarios()
    {
        return $this->hasMany(DocAlmacen::class); // pertenece a una empresa
    } 

    protected $guarded = [];
}
