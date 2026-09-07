<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->string('folio', 30)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('terreno_id')->constrained('terrenos')->restrictOnDelete();
            $table->decimal('precio_total', 12, 2);
            $table->decimal('enganche', 12, 2);
            $table->decimal('saldo_inicial', 12, 2);
            $table->decimal('saldo_actual', 12, 2);
            $table->integer('numero_cuotas');
            $table->enum('frecuencia_pago', ['SEMANAL', 'QUINCENAL', 'MENSUAL', 'ANUAL']);
            $table->date('fecha_inicio');
            $table->enum('estado', ['ACTIVO', 'LIQUIDADO', 'CANCELADO'])->default('ACTIVO');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};