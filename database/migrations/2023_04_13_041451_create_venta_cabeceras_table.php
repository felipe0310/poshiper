<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('venta_cabeceras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('almacen_id')->constrained('almacenes');
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('docalmacen_id')->constrained('doc_almacenes');
            $table->foreignId('caja_id')->constrained('cajas');
            $table->string('serie');
            $table->string('nro_comprobante');
            $table->string('descripcion');
            $table->float('pago_efectivo', 8, 2);
            $table->float('pago_tarjeta', 8, 2);
            $table->float('pago_transferencia', 8, 2);
            $table->float('pago_credito', 8, 2);
            $table->float('subtotal', 8, 2);
            $table->string('iva');
            $table->float('delivery', 8, 2);
            $table->float('total_venta', 8, 2);
            $table->string('tipo_pago');
            $table->integer('estado');
            $table->time('fecha_venta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_cabeceras');
    }
};
