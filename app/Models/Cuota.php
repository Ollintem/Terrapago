<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;

    protected $table = 'cuotas';

    protected $fillable = [
        'contrato_id',
        'numero_cuota',
        'fecha_vencimiento',
        'monto',
        'monto_pagado',
        'saldo_anterior',
        'abono_capital',
        'saldo_restante',
        'estado',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'monto'             => 'decimal:2',
        'monto_pagado'      => 'decimal:2',
        'saldo_anterior'    => 'decimal:2',
        'abono_capital'     => 'decimal:2',
        'saldo_restante'    => 'decimal:2',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
}