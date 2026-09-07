<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terrenos', function (Blueprint $table) {
            $table->id();
            $table->string('manzana', 20);
            $table->string('lote', 20);
            $table->decimal('superficie', 10, 2);
            $table->string('medidas', 50);
            $table->string('ubicacion', 150)->nullable();
            $table->decimal('precio', 12, 2);
            $table->enum('estado', ['DISPONIBLE', 'APARTADO', 'VENDIDO'])->default('DISPONIBLE');
            $table->timestamps();

            $table->unique(['manzana', 'lote']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terrenos');
    }
};