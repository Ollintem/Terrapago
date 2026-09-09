<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $fillable = ['nombre', 'clave'];

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'modulo_id');
    }
}