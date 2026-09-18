<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente') ?? $this->id;

        return [
            'nombre'           => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'telefono'         => 'required|string|max:20',
            'email'            => 'nullable|email|max:150|unique:clientes,email,' . $clienteId,
            'direccion'        => 'required|string',
            'curp'             => 'required|string|max:20',
            'rfc'              => 'required|string|max:20',
            'estado'           => 'boolean',
        ];
    }
}