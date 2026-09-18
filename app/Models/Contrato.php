<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'folio',
        'cliente_id',
        'terreno_id',
        'precio_total',
        'enganche',
        'saldo_inicial',
        'saldo_actual',
        'numero_cuotas',
        'frecuencia_pago',
        'fecha_inicio',
        'estado',
    ];

    protected $casts = [
        'precio_total'  => 'decimal:2',
        'enganche'      => 'decimal:2',
        'saldo_inicial' => 'decimal:2',
        'saldo_actual'  => 'decimal:2',
        'fecha_inicio'  => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function terreno()
    {
        return $this->belongsTo(Terreno::class, 'terreno_id');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class, 'contrato_id')->orderBy('numero_cuota', 'asc');
    }
}