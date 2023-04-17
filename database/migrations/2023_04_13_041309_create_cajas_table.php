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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('almacen_id')->constrained('almacenes');
            $table->time('fecha_apertura');
            $table->time('fecha_cierre');
            $table->float('monto_apertura', 8, 2);
            $table->float('monto_ingreso', 8, 2);
            $table->float('monto_egreso', 8, 2);
            $table->float('monto_cierre', 8, 2);
            $table->integer('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
