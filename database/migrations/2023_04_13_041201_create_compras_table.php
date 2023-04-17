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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('docalmacen_id')->constrained('doc_almacenes');
            $table->string('num_documento');
            $table->string('serie');
            $table->float('subtotal', 8, 2);
            $table->float('total', 8, 2);
            $table->string('iva');
            $table->float('total_compra', 8, 2);
            $table->string('tipo_pago');
            $table->integer('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
