<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return Producto::query()
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id')
            ->select('codigo_barras',
                'descripcion',
                'precio_compra',
                'precio_venta',
                'precio_mayoreo',
                'precio_oferta',
                'categorias.nombre as categoria_id',
                'proveedores.nombre as proveedor_id',
                'cantidad_caja');

    }

    public function headings(): array
    {
        return [
            'Codigo',
            'Descripcion',
            'Precio Costo',
            'Precio Venta',
            'Precio Mayoreo',
            'Precio Oferta',
            'Categoria',
            'Proveedor',
            'Cantidad Bulto',
            // Agrega más encabezados si necesitas
        ];
    }

    public function map($producto): array
    {
        return [
            (string) $producto->codigo_barras,
            $producto->descripcion,
            (string) $producto->precio_compra,
            (string) $producto->precio_venta,
            (string) $producto->precio_mayoreo,
            (string) $producto->precio_oferta,
            $producto->categoria_id,
            $producto->proveedor_id,
            (string) $producto->cantidad_caja,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
