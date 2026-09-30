<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Terreno;
use App\Models\Cuota;

class ContratoManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $modalCrearAbierto = false;
    public $modalDetalleAbierto = false;

    public $contratoSeleccionado;

    public $cliente_id;
    public $terreno_id;
    public $precio_total = 0;
    public $enganche = 0;
    public $saldo_inicial = 0;
    public $numero_cuotas = 12;
    public $frecuencia_pago = 'MENSUAL';
    public $fecha_inicio;

    /*
    |--------------------------------------------------------------------------
    | Confirmación de contrato creado
    |--------------------------------------------------------------------------
    */

    public $contratoCreadoId = null;
    public $mostrarContratoCreado = false;


    /*
    |--------------------------------------------------------------------------
    | Inicialización
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->fecha_inicio = now()->format('Y-m-d');
    }


    /*
    |--------------------------------------------------------------------------
    | Reglas de validación
    |--------------------------------------------------------------------------
    */

    protected function rules()
    {
        return [

            'cliente_id' => [
                'required',
                'exists:clientes,id',
            ],

            'terreno_id' => [
                'required',
                'exists:terrenos,id',
            ],

            'precio_total' => [
                'required',
                'numeric',
                'min:1',
            ],

            'enganche' => [
                'required',
                'numeric',
                'min:0',
                'lte:precio_total',
            ],

            'numero_cuotas' => [
                'required',
                'integer',
                'min:1',
                'max:360',
            ],

            'frecuencia_pago' => [
                'required',
                'in:SEMANAL,QUINCENAL,MENSUAL,ANUAL',
            ],

            'fecha_inicio' => [
                'required',
                'date',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Mensajes personalizados en español
    |--------------------------------------------------------------------------
    */

    protected function messages()
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Cliente
            |--------------------------------------------------------------------------
            */

            'cliente_id.required' =>
                'Debes seleccionar un cliente.',

            'cliente_id.exists' =>
                'El cliente seleccionado no es válido.',


            /*
            |--------------------------------------------------------------------------
            | Terreno
            |--------------------------------------------------------------------------
            */

            'terreno_id.required' =>
                'Debes seleccionar un terreno.',

            'terreno_id.exists' =>
                'El terreno seleccionado no es válido.',


            /*
            |--------------------------------------------------------------------------
            | Precio total
            |--------------------------------------------------------------------------
            */

            'precio_total.required' =>
                'El precio acordado es obligatorio.',

            'precio_total.numeric' =>
                'El precio acordado debe ser un número.',

            'precio_total.min' =>
                'El precio acordado debe ser mayor a $0.00.',


            /*
            |--------------------------------------------------------------------------
            | Enganche
            |--------------------------------------------------------------------------
            */

            'enganche.required' =>
                'El enganche inicial es obligatorio.',

            'enganche.numeric' =>
                'El enganche debe ser un número.',

            'enganche.min' =>
                'El enganche no puede ser menor a $0.00.',

            'enganche.lte' =>
                'El enganche no puede ser mayor que el precio acordado.',


            /*
            |--------------------------------------------------------------------------
            | Número de cuotas
            |--------------------------------------------------------------------------
            */

            'numero_cuotas.required' =>
                'El plazo de cuotas es obligatorio.',

            'numero_cuotas.integer' =>
                'El plazo de cuotas debe ser un número entero.',

            'numero_cuotas.min' =>
                'El plazo debe ser de al menos 1 cuota.',

            'numero_cuotas.max' =>
                'El plazo no puede superar las 360 cuotas.',


            /*
            |--------------------------------------------------------------------------
            | Frecuencia
            |--------------------------------------------------------------------------
            */

            'frecuencia_pago.required' =>
                'Debes seleccionar una frecuencia de pago.',

            'frecuencia_pago.in' =>
                'La frecuencia de pago seleccionada no es válida.',


            /*
            |--------------------------------------------------------------------------
            | Fecha
            |--------------------------------------------------------------------------
            */

            'fecha_inicio.required' =>
                'La fecha de inicio es obligatoria.',

            'fecha_inicio.date' =>
                'La fecha de inicio no es válida.',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar después de registrar un pago
    |--------------------------------------------------------------------------
    */

    #[On('pago-registrado')]
    public function actualizarDespuesDePago()
    {
        $this->actualizarEstadosCuotas();

        if ($this->contratoSeleccionado) {

            $this->contratoSeleccionado->refresh();

            $this->contratoSeleccionado->load([
                'cliente',
                'terreno',
                'cuotas',
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Cuando cambia el terreno
    |--------------------------------------------------------------------------
    */

    public function updatedTerrenoId($value)
    {
        if (!$value) {
            return;
        }

        $terreno = Terreno::find($value);

        if ($terreno) {

            $this->precio_total = $terreno->precio ?? 0;

            $this->recalcularSaldo();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Cuando cambia el enganche
    |--------------------------------------------------------------------------
    */

    public function updatedEnganche()
    {
        $this->recalcularSaldo();
    }


    /*
    |--------------------------------------------------------------------------
    | Cuando cambia el precio
    |--------------------------------------------------------------------------
    */

    public function updatedPrecioTotal()
    {
        $this->recalcularSaldo();
    }


    /*
    |--------------------------------------------------------------------------
    | Recalcular saldo
    |--------------------------------------------------------------------------
    */

    public function recalcularSaldo()
    {
        $precio = (float) ($this->precio_total ?? 0);
        $enganche = (float) ($this->enganche ?? 0);

        $this->saldo_inicial = max(
            0,
            $precio - $enganche
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar estados de cuotas
    |--------------------------------------------------------------------------
    */

    public function actualizarEstadosCuotas()
    {
        $hoy = Carbon::today();

        $cuotas = Cuota::whereHas('contrato', function ($query) {
            $query->where('estado', 'ACTIVO');
        })
        ->where('estado', 'PENDIENTE')
        ->whereDate('fecha_vencimiento', '<', $hoy)
        ->get();

        foreach ($cuotas as $cuota) {

            $cuota->estado = 'VENCIDA';

            $cuota->save();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Reiniciar paginación al buscar
    |--------------------------------------------------------------------------
    */

    public function updatingSearch()
    {
        $this->resetPage();
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $this->actualizarEstadosCuotas();

        $contratos = Contrato::with([
            'cliente',
            'terreno',
        ])
        ->when($this->search, function ($query) {

            $search = '%' . $this->search . '%';

            $query->where(function ($q) use ($search) {

                $q->where('folio', 'like', $search)

                    ->orWhereHas('cliente', function ($cliente) use ($search) {

                        $cliente->where('nombre', 'like', $search)
                            ->orWhere('apellidos', 'like', $search)
                            ->orWhere('email', 'like', $search);

                    })

                    ->orWhereHas('terreno', function ($terreno) use ($search) {

                        $terreno->where('lote', 'like', $search)
                            ->orWhere('manzana', 'like', $search);

                    });
            });

        })
        ->latest()
        ->paginate(10);

        $clientesDisponibles = Cliente::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $terrenosDisponibles = Terreno::where(
            'estado',
            'DISPONIBLE'
        )
        ->orderBy('manzana')
        ->orderBy('lote')
        ->get();

        return view(
            'livewire.admin.contratos.index',
            [
                'contratos' => $contratos,
                'clientesDisponibles' => $clientesDisponibles,
                'terrenosDisponibles' => $terrenosDisponibles,
            ]
        )->layout('layouts.app');
    }


    /*
    |--------------------------------------------------------------------------
    | Abrir modal crear
    |--------------------------------------------------------------------------
    */

    public function abrirModalCrear()
    {
        $this->resetValidation();

        $this->cliente_id = null;
        $this->terreno_id = null;

        $this->precio_total = 0;
        $this->enganche = 0;
        $this->saldo_inicial = 0;

        $this->numero_cuotas = 12;
        $this->frecuencia_pago = 'MENSUAL';

        $this->fecha_inicio = now()->format('Y-m-d');

        $this->mostrarContratoCreado = false;
        $this->contratoCreadoId = null;

        $this->modalCrearAbierto = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Cancelar contrato
    |--------------------------------------------------------------------------
    */

    public function cancelarContrato($id)
    {
        try {

            DB::transaction(function () use ($id) {

                $contrato = Contrato::lockForUpdate()
                    ->findOrFail($id);

                if ($contrato->estado !== 'ACTIVO') {

                    throw new \Exception(
                        'El contrato ya no se encuentra activo.'
                    );
                }

                $contrato->update([
                    'estado' => 'CANCELADO',
                ]);

                if ($contrato->terreno_id) {

                    Terreno::where(
                        'id',
                        $contrato->terreno_id
                    )->update([
                        'estado' => 'DISPONIBLE',
                    ]);
                }

                Cuota::where(
                    'contrato_id',
                    $contrato->id
                )
                ->whereIn('estado', [
                    'PENDIENTE',
                    'VENCIDA',
                ])
                ->delete();
            });

            session()->flash(
                'mensaje',
                'El contrato fue cancelado correctamente.'
            );

        } catch (\Throwable $e) {

            session()->flash(
                'error',
                $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Liquidar contrato
    |--------------------------------------------------------------------------
    */

    public function liquidarContrato($id)
    {
        try {

            DB::transaction(function () use ($id) {

                $contrato = Contrato::lockForUpdate()
                    ->findOrFail($id);

                if ($contrato->estado !== 'ACTIVO') {

                    throw new \Exception(
                        'El contrato ya no se encuentra activo.'
                    );
                }

                $cuotas = Cuota::where(
                    'contrato_id',
                    $contrato->id
                )
                ->whereIn('estado', [
                    'PENDIENTE',
                    'VENCIDA',
                ])
                ->get();

                foreach ($cuotas as $cuota) {

                    $cuota->monto_pagado = $cuota->monto;
                    $cuota->abono_capital = $cuota->monto;
                    $cuota->saldo_restante = 0;
                    $cuota->estado = 'PAGADA';

                    $cuota->save();
                }

                $contrato->saldo_actual = 0;
                $contrato->estado = 'LIQUIDADO';

                $contrato->save();
            });

            session()->flash(
                'mensaje',
                'El contrato fue liquidado correctamente.'
            );

        } catch (\Throwable $e) {

            session()->flash(
                'error',
                $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Cerrar modal crear
    |--------------------------------------------------------------------------
    */

    public function cerrarModalCrear()
    {
        $this->modalCrearAbierto = false;

        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | Ver estado de cuenta
    |--------------------------------------------------------------------------
    */

    public function verEstadoCuenta($id)
    {
        $this->contratoSeleccionado = Contrato::with([
            'cliente',
            'terreno',
            'cuotas',
        ])->findOrFail($id);

        $this->actualizarEstadosCuotas();

        $this->contratoSeleccionado->refresh();

        $this->contratoSeleccionado->load([
            'cliente',
            'terreno',
            'cuotas',
        ]);

        $this->modalDetalleAbierto = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Cerrar estado de cuenta
    |--------------------------------------------------------------------------
    */

    public function cerrarModalDetalle()
    {
        $this->modalDetalleAbierto = false;

        $this->contratoSeleccionado = null;
    }


    /*
    |--------------------------------------------------------------------------
    | Guardar contrato
    |--------------------------------------------------------------------------
    */

    public function guardarContrato()
    {
        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $this->validate(
            $this->rules(),
            $this->messages()
        );


        /*
        |--------------------------------------------------------------------------
        | Recalcular saldo antes de guardar
        |--------------------------------------------------------------------------
        */

        $this->recalcularSaldo();


        /*
        |--------------------------------------------------------------------------
        | Validación adicional del enganche
        |--------------------------------------------------------------------------
        */

        if ((float) $this->enganche > (float) $this->precio_total) {

            $this->addError(
                'enganche',
                'El enganche no puede ser mayor que el precio acordado.'
            );

            return;
        }


        try {

            $contratoCreado = DB::transaction(function () {

                /*
                |--------------------------------------------------------------------------
                | Bloquear terreno
                |--------------------------------------------------------------------------
                */

                $terreno = Terreno::lockForUpdate()
                    ->findOrFail($this->terreno_id);


                /*
                |--------------------------------------------------------------------------
                | Verificar disponibilidad
                |--------------------------------------------------------------------------
                */

                if ($terreno->estado !== 'DISPONIBLE') {

                    throw new \Exception(
                        'El terreno seleccionado ya no está disponible.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Generar folio
                |--------------------------------------------------------------------------
                */

                $anio = now()->format('Y');

                $ultimoContrato = Contrato::where(
                    'folio',
                    'like',
                    'CT-' . $anio . '-%'
                )
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

                if ($ultimoContrato) {

                    $numero = (int) substr(
                        $ultimoContrato->folio,
                        -4
                    );

                    $numero++;

                } else {

                    $numero = 1;
                }

                $folio = 'CT-' . $anio . '-' .
                    str_pad(
                        $numero,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );


                /*
                |--------------------------------------------------------------------------
                | Calcular saldo
                |--------------------------------------------------------------------------
                */

                $precio = round(
                    (float) $this->precio_total,
                    2
                );

                $enganche = round(
                    (float) $this->enganche,
                    2
                );

                $saldo = round(
                    $precio - $enganche,
                    2
                );


                /*
                |--------------------------------------------------------------------------
                | Crear contrato
                |--------------------------------------------------------------------------
                */

                $contrato = Contrato::create([

                    'folio' => $folio,

                    'cliente_id' => $this->cliente_id,

                    'terreno_id' => $this->terreno_id,

                    'precio_total' => $precio,

                    'enganche' => $enganche,

                    'saldo_inicial' => $saldo,

                    'saldo_actual' => $saldo,

                    'numero_cuotas' => $this->numero_cuotas,

                    'frecuencia_pago' => $this->frecuencia_pago,

                    'fecha_inicio' => $this->fecha_inicio,

                    'estado' => 'ACTIVO',
                ]);


                /*
                |--------------------------------------------------------------------------
                | Cambiar terreno a vendido
                |--------------------------------------------------------------------------
                */

                $terreno->estado = 'VENDIDO';

                $terreno->save();


                /*
                |--------------------------------------------------------------------------
                | Generar amortización
                |--------------------------------------------------------------------------
                */

                $numeroCuotas = (int) $this->numero_cuotas;

                $montoBase = $numeroCuotas > 0
                    ? round(
                        $saldo / $numeroCuotas,
                        2
                    )
                    : 0;

                $saldoAnterior = $saldo;

                $fechaBase = Carbon::parse(
                    $this->fecha_inicio
                );


                for ($i = 1; $i <= $numeroCuotas; $i++) {

                    /*
                    |--------------------------------------------------------------------------
                    | Calcular fecha de vencimiento
                    |--------------------------------------------------------------------------
                    */

                    $fechaVencimiento = $fechaBase->copy();

                    switch ($this->frecuencia_pago) {

                        case 'SEMANAL':

                            $fechaVencimiento->addWeeks($i);

                            break;

                        case 'QUINCENAL':

                            $fechaVencimiento->addDays(
                                15 * $i
                            );

                            break;

                        case 'MENSUAL':

                            $fechaVencimiento->addMonths($i);

                            break;

                        case 'ANUAL':

                            $fechaVencimiento->addYears($i);

                            break;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Última cuota
                    |--------------------------------------------------------------------------
                    */

                    if ($i === $numeroCuotas) {

                        $montoCuota = round(
                            $saldoAnterior,
                            2
                        );

                    } else {

                        $montoCuota = $montoBase;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Saldo restante
                    |--------------------------------------------------------------------------
                    */

                    $saldoRestante = round(
                        $saldoAnterior - $montoCuota,
                        2
                    );


                    if ($saldoRestante < 0) {
                        $saldoRestante = 0;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Crear cuota
                    |--------------------------------------------------------------------------
                    */

                    Cuota::create([

                        'contrato_id' => $contrato->id,

                        'numero_cuota' => $i,

                        'fecha_vencimiento' => $fechaVencimiento,

                        'monto' => $montoCuota,

                        'monto_pagado' => 0,

                        'saldo_anterior' => $saldoAnterior,

                        'abono_capital' => 0,

                        'saldo_restante' => $saldoRestante,

                        'estado' => 'PENDIENTE',
                    ]);


                    $saldoAnterior = $saldoRestante;
                }


                return $contrato;
            });


            /*
            |--------------------------------------------------------------------------
            | Mostrar confirmación
            |--------------------------------------------------------------------------
            */

            $this->contratoCreadoId = $contratoCreado->id;

            $this->mostrarContratoCreado = true;


            /*
            |--------------------------------------------------------------------------
            | Cerrar formulario
            |--------------------------------------------------------------------------
            */

            $this->cerrarModalCrear();


            /*
            |--------------------------------------------------------------------------
            | Mensaje
            |--------------------------------------------------------------------------
            */

            session()->flash(
                'mensaje',
                'El contrato fue formalizado correctamente y se generó su tabla de amortización.'
            );

        } catch (\Throwable $e) {

            session()->flash(
                'error',
                $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Cerrar confirmación de contrato creado
    |--------------------------------------------------------------------------
    */

    public function cerrarContratoCreado()
    {
        $this->mostrarContratoCreado = false;

        $this->contratoCreadoId = null;
    }
}