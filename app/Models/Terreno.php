<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terreno extends Model
{
    use HasFactory;

    protected $table = 'terrenos';

    protected $fillable = [
        'manzana',
        'lote',
        'superficie',
        'medidas',
        'ubicacion',
        'precio',
        'estado',
    ];

    protected $casts = [
        'precio'     => 'decimal:2',
        'superficie' => 'decimal:2',
    ];

    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'terreno_id');
    }

    public function getUbicacionCompletaAttribute()
    {
        return "Mz. {$this->manzana} - Lt. {$this->lote}";
    }
}