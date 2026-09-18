<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'telefono',
        'email',
        'direccion',
        'curp',
        'rfc',
        'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'estado'           => 'boolean',
    ];

    // Relación con el usuario/asesor asignado
    public function asesor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'cliente_id');
    }

    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}