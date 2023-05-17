<?php

namespace App\Imports;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductosImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Busca la categoria, si no existe la crea
        $categoria = Categoria::firstOrCreate(['nombre' => $row['categoria']]);

        // Busca el proveedor, si no existe lo crea
        $proveedor = Proveedor::firstOrCreate(['nombre' => $row['proveedor']]);

        // Busca un producto existente por su Codigo o crea uno nuevo
        $producto = Producto::firstOrNew(['codigo_barras' => $row['codigo'], 'categoria_id' => $categoria->id, 'proveedor_id' => $proveedor->id]);

        // Actualiza los atributos del producto

        $producto->descripcion = $row['descripcion'];
        $producto->codigo_barras = $row['codigo'];
        $producto->precio_compra = $row['precio_costo'];
        $producto->precio_venta = $row['precio_venta'];
        $producto->precio_mayoreo = $row['precio_mayoreo'];
        $producto->precio_oferta = $row['precio_oferta'];
        $producto->cantidad_caja = $row['cantidad_bulto'];
        // Agrega los campos que necesites

        // Guarda el producto y lo retorna
        $producto->save();

        return $producto;

    }

    public function rules(): array
    {
        return
        [
            '*.codigo' => ['required', 'numeric'],
            '*.categoria' => ['required', 'string', 'exists:categorias,nombre'],
            '*.proveedor' => ['required', 'string', 'exists:proveedores,nombre'],
            '*.descripcion' => ['required', 'string', 'max:50'],
            '*.precio_costo' => ['required', 'numeric'],
            '*.precio_venta' => ['required', 'numeric'],
            '*.precio_mayoreo' => ['required', 'numeric'],
            '*.precio_oferta' => ['required', 'numeric'],
            '*.cantidad_bulto' => ['required', 'numeric'],
        ];
    }
}
