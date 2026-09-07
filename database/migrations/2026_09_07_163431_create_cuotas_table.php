<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->cascadeOnDelete();
            $table->integer('numero_cuota');
            $table->date('fecha_vencimiento');
            $table->decimal('monto', 12, 2);
            $table->decimal('monto_pagado', 12, 2)->default(0.00);
            $table->decimal('saldo_anterior', 12, 2);
            $table->decimal('abono_capital', 12, 2)->default(0.00);
            $table->decimal('saldo_restante', 12, 2);
            $table->enum('estado', ['PENDIENTE', 'PAGADA', 'VENCIDA'])->default('PENDIENTE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};