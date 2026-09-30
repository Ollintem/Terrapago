<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'monto' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'metodo_pago' => [
                'required',
                'in:EFECTIVO,TRANSFERENCIA,DEPOSITO,TARJETA',
            ],

            'fecha_pago' => [
                'required',
                'date',
            ],

            'referencia' => [
                'nullable',
                'string',
                'max:100',
            ],

            'observaciones' => [
                'nullable',
                'string',
            ],

            'modo_pago' => [
                'required',
                'in:NORMAL,ADELANTO',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'monto.required' => 'El monto del pago es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.gt' => 'El monto debe ser mayor a 0.',

            'metodo_pago.required' => 'Selecciona un método de pago.',
            'metodo_pago.in' => 'El método de pago seleccionado no es válido.',

            'fecha_pago.required' => 'La fecha del pago es obligatoria.',
            'fecha_pago.date' => 'La fecha del pago no es válida.',

            'referencia.max' => 'La referencia no puede superar los 100 caracteres.',

            'modo_pago.required' => 'Selecciona el tipo de pago.',
            'modo_pago.in' => 'El tipo de pago seleccionado no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'monto' => 'monto',
            'metodo_pago' => 'método de pago',
            'fecha_pago' => 'fecha de pago',
            'referencia' => 'referencia',
            'observaciones' => 'observaciones',
            'modo_pago' => 'tipo de pago',
        ];
    }
}