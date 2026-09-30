<?php

namespace App\Livewire\Admin;

use App\Models\Contrato;
use App\Models\Cuota;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CobranzaManagement extends Component
{
    public $search = '';
    public $contratosEncontrados = [];
    public $contratoSeleccionado = null;

    public $monto = '';
    public $metodo_pago = 'EFECTIVO';
    public $fecha_pago;
    public $referencia = '';
    public $observaciones = '';
    public $modo_pago = 'NORMAL';

    public $modalCobroAbierto = false;
    public $modalDetalleAbierto = false;
    public $ultimaOperacionFolio = null;

    public function mount()
    {
        $this->fecha_pago = now()->format('Y-m-d\TH:i');

        /*
         * Si regresamos desde el ticket mediante:
         *
         * /caja?operacion=PG-XXXXXXXX
         *
         * recuperamos automáticamente la operación
         * y el contrato que acabamos de cobrar.
         */
        $operacionFolio = request()->query('operacion');

        if ($operacionFolio) {
            $this->restaurarOperacion($operacionFolio);
        }
    }

    public function render()
    {
        return view('livewire.admin.cobranza.index')
            ->layout('layouts.app');
    }

    /**
     * Restaura la información de una operación anterior
     * cuando regresamos desde el ticket.
     */
    private function restaurarOperacion(string $operacionFolio): void
    {
        $pago = Pago::query()
            ->where('operacion_folio', $operacionFolio)
            ->latest('id')
            ->first();

        /*
         * Si el folio no existe, simplemente dejamos
         * Cobranza en su estado normal.
         */
        if (!$pago) {
            return;
        }

        /*
         * Actualizamos nuevamente los estados de las cuotas
         * para mostrar la información actualizada.
         */
        $this->actualizarEstadosCuotas(
            $pago->contrato_id
        );

        /*
         * Recuperamos el contrato completo con sus relaciones.
         */
        $this->contratoSeleccionado = Contrato::with([
            'cliente',
            'terreno',
            'cuotas',
        ])->find($pago->contrato_id);

        if (!$this->contratoSeleccionado) {
            return;
        }

        /*
         * Limpiamos la búsqueda porque ya tenemos
         * directamente el contrato seleccionado.
         */
        $this->contratosEncontrados = [];
        $this->search = '';

        /*
         * El modal de cobro permanece cerrado.
         */
        $this->modalCobroAbierto = false;

        /*
         * Recuperamos el folio de la operación para que
         * vuelvan a aparecer los botones:
         *
         * - Ver recibo
         * - Generar recibo PDF
         */
        $this->ultimaOperacionFolio = $operacionFolio;

        /*
         * Mostramos nuevamente el mensaje de operación
         * para conservar la misma interfaz después de regresar.
         */
        session()->flash(
            'success',
            'Operación registrada correctamente. Operación: ' .
            $operacionFolio
        );
    }

    public function updatedSearch()
    {
        $busqueda = trim($this->search);

        if ($busqueda === '') {
            $this->contratosEncontrados = [];
            return;
        }

        $this->contratosEncontrados = Contrato::with([
            'cliente',
            'terreno',
            'cuotas',
        ])
            ->where(function ($query) use ($busqueda) {

                $query->where(
                    'folio',
                    'like',
                    "%{$busqueda}%"
                )

                ->orWhereHas('cliente', function ($cliente) use ($busqueda) {

                    $cliente->where(
                        'nombre',
                        'like',
                        "%{$busqueda}%"
                    )

                    ->orWhere(
                        'email',
                        'like',
                        "%{$busqueda}%"
                    )

                    ->orWhere(
                        'telefono',
                        'like',
                        "%{$busqueda}%"
                    );
                })

                ->orWhereHas('terreno', function ($terreno) use ($busqueda) {

                    $terreno->where(
                        'manzana',
                        'like',
                        "%{$busqueda}%"
                    )

                    ->orWhere(
                        'lote',
                        'like',
                        "%{$busqueda}%"
                    );
                });
            })
            ->orderBy('folio')
            ->limit(20)
            ->get();
    }

    public function seleccionarContrato($contratoId)
    {
        $this->actualizarEstadosCuotas($contratoId);

        $this->contratoSeleccionado = Contrato::with([
            'cliente',
            'terreno',
            'cuotas',
        ])->findOrFail($contratoId);

        $this->contratosEncontrados = [];
        $this->search = '';
    }

    /**
     * Actualiza automáticamente el estado y saldo
     * de las cuotas de un contrato.
     *
     * Reglas:
     *
     * - Si la cuota tiene saldo 0 -> PAGADA.
     * - Si tiene saldo y ya venció -> VENCIDA.
     * - Si tiene saldo y todavía no vence -> PENDIENTE.
     */
    private function actualizarEstadosCuotas($contratoId)
    {
        $cuotas = Cuota::where(
            'contrato_id',
            $contratoId
        )->get();

        $hoy = now()->startOfDay();

        foreach ($cuotas as $cuota) {

            $monto = round(
                (float) $cuota->monto,
                2
            );

            $montoPagado = round(
                (float) $cuota->monto_pagado,
                2
            );

            $saldo = round(
                max(
                    0,
                    $monto - $montoPagado
                ),
                2
            );

            if ($saldo <= 0) {

                $estado = 'PAGADA';

            } elseif (
                $cuota->fecha_vencimiento &&
                $cuota->fecha_vencimiento->lt($hoy)
            ) {

                $estado = 'VENCIDA';

            } else {

                $estado = 'PENDIENTE';
            }

            $saldoActual = round(
                (float) $cuota->saldo_restante,
                2
            );

            if (
                $cuota->estado !== $estado ||
                $saldoActual !== $saldo
            ) {

                $cuota->update([
                    'estado' => $estado,
                    'saldo_restante' => $saldo,
                ]);
            }
        }
    }

    public function abrirCobro()
    {
        if (!$this->contratoSeleccionado) {

            session()->flash(
                'error',
                'Primero selecciona un contrato.'
            );

            return;
        }

        $this->actualizarEstadosCuotas(
            $this->contratoSeleccionado->id
        );

        $this->contratoSeleccionado = Contrato::with([
            'cliente',
            'terreno',
            'cuotas',
        ])->findOrFail(
            $this->contratoSeleccionado->id
        );

        if ((float) $this->contratoSeleccionado->saldo_actual <= 0) {

            if ($this->contratoSeleccionado->estado !== 'LIQUIDADO') {

                $this->contratoSeleccionado->update([
                    'estado' => 'LIQUIDADO',
                ]);

                $this->contratoSeleccionado->refresh();
            }

            session()->flash(
                'error',
                'El contrato seleccionado ya se encuentra liquidado.'
            );

            return;
        }

        $this->resetValidation();

        $this->monto = '';
        $this->metodo_pago = 'EFECTIVO';
        $this->fecha_pago = now()->format('Y-m-d\TH:i');
        $this->referencia = '';
        $this->observaciones = '';
        $this->modo_pago = 'NORMAL';

        $this->modalCobroAbierto = true;
    }

    public function cerrarCobro()
    {
        $this->modalCobroAbierto = false;

        $this->resetValidation();
    }

    public function registrarCobro()
    {
        $this->resetValidation();

        if (
            $this->monto === null ||
            $this->monto === '' ||
            !is_numeric($this->monto)
        ) {

            $this->addError(
                'monto',
                'El monto del pago es obligatorio y debe ser numérico.'
            );

            return;
        }

        $montoIngresado = round(
            (float) $this->monto,
            2
        );

        if ($montoIngresado <= 0) {

            $this->addError(
                'monto',
                'El monto debe ser mayor a 0.'
            );

            return;
        }

        $metodosPermitidos = [
            'EFECTIVO',
            'TRANSFERENCIA',
            'DEPOSITO',
            'TARJETA',
        ];

        if (
            !in_array(
                $this->metodo_pago,
                $metodosPermitidos,
                true
            )
        ) {

            $this->addError(
                'metodo_pago',
                'Selecciona un método de pago válido.'
            );

            return;
        }

        if (
            $this->fecha_pago === null ||
            trim((string) $this->fecha_pago) === ''
        ) {

            $this->addError(
                'fecha_pago',
                'La fecha del pago es obligatoria.'
            );

            return;
        }

        if (
            !in_array(
                $this->modo_pago,
                ['NORMAL', 'ADELANTO'],
                true
            )
        ) {

            $this->addError(
                'modo_pago',
                'Selecciona el tipo de pago.'
            );

            return;
        }

        if (
            $this->referencia !== null &&
            strlen((string) $this->referencia) > 100
        ) {

            $this->addError(
                'referencia',
                'La referencia no puede superar los 100 caracteres.'
            );

            return;
        }

        if (!$this->contratoSeleccionado) {

            session()->flash(
                'error',
                'No hay un contrato seleccionado.'
            );

            return;
        }

        try {

            DB::beginTransaction();

            $contrato = Contrato::query()
                ->whereKey(
                    $this->contratoSeleccionado->id
                )
                ->lockForUpdate()
                ->firstOrFail();

            $cuotasContrato = Cuota::query()
                ->where(
                    'contrato_id',
                    $contrato->id
                )
                ->lockForUpdate()
                ->get();

            $hoy = now()->startOfDay();

            foreach ($cuotasContrato as $cuota) {

                $saldoCuota = round(
                    max(
                        0,
                        (float) $cuota->monto -
                        (float) $cuota->monto_pagado
                    ),
                    2
                );

                if ($saldoCuota <= 0) {

                    $estado = 'PAGADA';

                } elseif (
                    $cuota->fecha_vencimiento &&
                    $cuota->fecha_vencimiento->lt($hoy)
                ) {

                    $estado = 'VENCIDA';

                } else {

                    $estado = 'PENDIENTE';
                }

                $saldoActual = round(
                    (float) $cuota->saldo_restante,
                    2
                );

                if (
                    $cuota->estado !== $estado ||
                    $saldoActual !== $saldoCuota
                ) {

                    $cuota->update([
                        'estado' => $estado,
                        'saldo_restante' => $saldoCuota,
                    ]);
                }
            }

            $contrato->refresh();

            $saldoContrato = round(
                (float) $contrato->saldo_actual,
                2
            );

            if ($contrato->estado === 'LIQUIDADO') {

                DB::rollBack();

                $this->addError(
                    'monto',
                    'El contrato ya se encuentra liquidado.'
                );

                return;
            }

            if ($saldoContrato <= 0) {

                $contrato->saldo_actual = 0;
                $contrato->estado = 'LIQUIDADO';
                $contrato->save();

                DB::commit();

                $this->addError(
                    'monto',
                    'El contrato ya no tiene saldo pendiente.'
                );

                return;
            }

            if ($montoIngresado > $saldoContrato) {

                DB::rollBack();

                $this->addError(
                    'monto',
                    'El monto ingresado ($' .
                    number_format(
                        $montoIngresado,
                        2
                    ) .
                    ') es mayor al saldo pendiente del contrato ($' .
                    number_format(
                        $saldoContrato,
                        2
                    ) .
                    ').'
                );

                return;
            }

            if ($this->modo_pago === 'NORMAL') {

                $this->registrarPagoNormal(
                    $contrato,
                    $montoIngresado
                );

                return;
            }

            $this->registrarAdelanto(
                $contrato,
                $montoIngresado
            );

        } catch (\Throwable $e) {

            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            report($e);

            $this->addError(
                'monto',
                'ERROR AL REGISTRAR EL COBRO: ' .
                $e->getMessage()
            );

            session()->flash(
                'error',
                'No fue posible registrar el cobro. Revisa el mensaje de error.'
            );
        }
    }

    private function registrarPagoNormal(
        Contrato $contrato,
        float $montoIngresado
    ): void {

        $cuota = Cuota::query()
            ->where(
                'contrato_id',
                $contrato->id
            )
            ->whereIn(
                'estado',
                [
                    'PENDIENTE',
                    'VENCIDA',
                ]
            )
            ->whereDate(
                'fecha_vencimiento',
                '<=',
                now()->toDateString()
            )
            ->orderBy(
                'numero_cuota'
            )
            ->lockForUpdate()
            ->first();

        if (!$cuota) {

            DB::rollBack();

            $this->addError(
                'monto',
                'No existe una cuota vencida o con fecha de pago actual.'
            );

            return;
        }

        $saldoCuota = round(
            (float) $cuota->monto -
            (float) $cuota->monto_pagado,
            2
        );

        if ($saldoCuota <= 0) {

            DB::rollBack();

            $this->addError(
                'monto',
                'La cuota seleccionada ya está pagada.'
            );

            return;
        }

        if ($montoIngresado > $saldoCuota) {

            DB::rollBack();

            $this->addError(
                'monto',
                'Para "Pagar cuota" no puedes ingresar más de $' .
                number_format(
                    $saldoCuota,
                    2
                ) .
                ', que es el saldo pendiente de esta cuota. ' .
                'Si deseas pagar más cuotas, selecciona "Adelantar pago".'
            );

            return;
        }

        $operacionFolio = $this->generarOperacionFolio();

        $this->aplicarPagoACuota(
            $contrato,
            $cuota,
            $montoIngresado,
            $operacionFolio,
            1
        );

        $nuevoSaldoContrato = round(
            max(
                0,
                (float) $contrato->saldo_actual -
                $montoIngresado
            ),
            2
        );

        $contrato->saldo_actual = $nuevoSaldoContrato;

        if ($nuevoSaldoContrato <= 0) {

            $contrato->saldo_actual = 0;
            $contrato->estado = 'LIQUIDADO';
        }

        $contrato->save();

        DB::commit();

        $this->ultimaOperacionFolio = $operacionFolio;

        $this->finalizarCobro(
            $contrato->id,
            $nuevoSaldoContrato <= 0,
            'El pago de la cuota se registró correctamente.',
            $operacionFolio
        );
    }

    private function registrarAdelanto(
        Contrato $contrato,
        float $montoIngresado
    ): void {

        $cuotas = Cuota::query()
            ->where(
                'contrato_id',
                $contrato->id
            )
            ->whereIn(
                'estado',
                [
                    'PENDIENTE',
                    'VENCIDA',
                ]
            )
            ->orderBy(
                'numero_cuota'
            )
            ->lockForUpdate()
            ->get();

        if ($cuotas->isEmpty()) {

            DB::rollBack();

            $this->addError(
                'monto',
                'No existen cuotas pendientes disponibles para realizar el adelanto.'
            );

            return;
        }

        $operacionFolio = $this->generarOperacionFolio();

        $montoRestante = $montoIngresado;

        $numeroDetalle = 1;

        foreach ($cuotas as $cuota) {

            if ($montoRestante <= 0.009) {
                break;
            }

            $saldoCuota = round(
                (float) $cuota->monto -
                (float) $cuota->monto_pagado,
                2
            );

            if ($saldoCuota <= 0) {
                continue;
            }

            $aplicado = round(
                min(
                    $saldoCuota,
                    $montoRestante
                ),
                2
            );

            $this->aplicarPagoACuota(
                $contrato,
                $cuota,
                $aplicado,
                $operacionFolio,
                $numeroDetalle
            );

            $montoRestante = round(
                $montoRestante -
                $aplicado,
                2
            );

            $numeroDetalle++;
        }

        if ($montoRestante > 0.009) {

            DB::rollBack();

            $this->addError(
                'monto',
                'No fue posible aplicar todo el monto. Quedaron $' .
                number_format(
                    $montoRestante,
                    2
                ) .
                ' sin aplicar.'
            );

            return;
        }

        $nuevoSaldoContrato = round(
            max(
                0,
                (float) $contrato->saldo_actual -
                $montoIngresado
            ),
            2
        );

        $contrato->saldo_actual = $nuevoSaldoContrato;

        if ($nuevoSaldoContrato <= 0) {

            $contrato->saldo_actual = 0;
            $contrato->estado = 'LIQUIDADO';
        }

        $contrato->save();

        DB::commit();

        $this->ultimaOperacionFolio = $operacionFolio;

        $this->finalizarCobro(
            $contrato->id,
            $nuevoSaldoContrato <= 0,
            'El adelanto se registró correctamente.',
            $operacionFolio
        );
    }

    private function aplicarPagoACuota(
        Contrato $contrato,
        Cuota $cuota,
        float $monto,
        string $operacionFolio,
        int $numeroDetalle
    ): void {

        $nuevoMontoPagado = round(
            (float) $cuota->monto_pagado +
            $monto,
            2
        );

        $nuevoSaldoCuota = round(
            max(
                0,
                (float) $cuota->monto -
                $nuevoMontoPagado
            ),
            2
        );

        if ($nuevoSaldoCuota <= 0) {

            $nuevoEstado = 'PAGADA';

        } elseif (
            $cuota->fecha_vencimiento &&
            $cuota->fecha_vencimiento->lt(
                now()->startOfDay()
            )
        ) {

            $nuevoEstado = 'VENCIDA';

        } else {

            $nuevoEstado = 'PENDIENTE';
        }

        $cuota->update([
            'monto_pagado' => $nuevoMontoPagado,
            'saldo_restante' => $nuevoSaldoCuota,
            'estado' => $nuevoEstado,
        ]);

        $folioDetalle =
            $operacionFolio .
            '-' .
            str_pad(
                $numeroDetalle,
                2,
                '0',
                STR_PAD_LEFT
            );

        Pago::create([
            'contrato_id' => $contrato->id,
            'cuota_id' => $cuota->id,
            'usuario_id' => auth()->id(),
            'operacion_folio' => $operacionFolio,
            'folio' => $folioDetalle,
            'fecha_pago' => $this->fecha_pago,
            'monto' => $monto,
            'metodo_pago' => $this->metodo_pago,
            'referencia' => $this->referencia !== ''
                ? $this->referencia
                : null,
            'observaciones' => $this->observaciones !== ''
                ? $this->observaciones
                : null,
        ]);
    }

    private function generarOperacionFolio(): string
    {
        do {

            $operacionFolio =
                'PG-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(4)
                        ),
                        0,
                        8
                    )
                );

        } while (
            Pago::where(
                'operacion_folio',
                $operacionFolio
            )->exists()
        );

        return $operacionFolio;
    }

    private function finalizarCobro(
        int $contratoId,
        bool $liquidado,
        string $mensaje,
        string $operacionFolio
    ): void {

        $this->actualizarEstadosCuotas(
            $contratoId
        );

        $this->contratoSeleccionado =
            Contrato::with([
                'cliente',
                'terreno',
                'cuotas',
            ])->find($contratoId);

        $this->modalCobroAbierto = false;

        $this->monto = '';
        $this->metodo_pago = 'EFECTIVO';
        $this->fecha_pago = now()->format('Y-m-d\TH:i');
        $this->referencia = '';
        $this->observaciones = '';
        $this->modo_pago = 'NORMAL';

        /*
         * Notificar a los demás componentes Livewire
         * que se registró un pago.
         */
        $this->dispatch(
            'pago-registrado',
            contratoId: $contratoId,
            operacionFolio: $operacionFolio
        );

        if ($liquidado) {

            session()->flash(
                'success',
                $mensaje .
                ' El contrato ha quedado LIQUIDADO. Operación: ' .
                $operacionFolio
            );

        } else {

            session()->flash(
                'success',
                $mensaje .
                ' Operación: ' .
                $operacionFolio
            );
        }
    }
}