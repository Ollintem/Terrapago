<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
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

    // Campos para formalizar contrato
    public $cliente_id;
    public $terreno_id;
    public $precio_total = 0;
    public $enganche = 0;
    public $saldo_inicial = 0;
    public $numero_cuotas = 12;
    public $frecuencia_pago = 'MENSUAL';
    public $fecha_inicio;

    protected function rules()
    {
        return [
            'cliente_id'      => 'required|exists:clientes,id',
            'terreno_id'      => 'required|exists:terrenos,id',
            'precio_total'    => 'required|numeric|min:1',
            'enganche'        => 'required|numeric|min:0|lte:precio_total',
            'numero_cuotas'   => 'required|integer|min:1|max:360',
            'frecuencia_pago' => 'required|in:SEMANAL,QUINCENAL,MENSUAL,ANUAL',
            'fecha_inicio'    => 'required|date',
        ];
    }

    public function mount()
    {
        $this->fecha_inicio = now()->format('Y-m-d');
    }

    public function updatedTerrenoId($value)
    {
        if ($value) {
            $terreno = Terreno::find($value);
            if ($terreno) {
                $this->precio_total = (float) $terreno->precio;
                $this->recalcularSaldo();
            }
        }
    }

    public function updatedEnganche()
    {
        $this->recalcularSaldo();
    }

    public function updatedPrecioTotal()
    {
        $this->recalcularSaldo();
    }

    public function recalcularSaldo()
    {
        $pTotal = (float) ($this->precio_total ?: 0);
        $eng = (float) ($this->enganche ?: 0);
        $this->saldo_inicial = max(0, $pTotal - $eng);
    }

    public function render()
    {
        $contratos = Contrato::with(['cliente', 'terreno'])
            ->when($this->search, function ($query) {
                $query->where('folio', 'like', '%' . $this->search . '%')
                    ->orWhereHas('cliente', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%')
                          ->orWhere('apellido_paterno', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('terreno', function ($q) {
                        $q->where('manzana', 'like', '%' . $this->search . '%')
                          ->orWhere('lote', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        // Solo clientes activos y terrenos disponibles (evitar doble venta)
        $clientesDisponibles = Cliente::where('estado', true)->orderBy('nombre')->get();
        $terrenosDisponibles = Terreno::where('estado', 'DISPONIBLE')->orderBy('manzana')->orderBy('lote')->get();

        return view('livewire.admin.contrato-management', [
            'contratos'           => $contratos,
            'clientesDisponibles' => $clientesDisponibles,
            'terrenosDisponibles' => $terrenosDisponibles,
        ])->layout('layouts.app');
    }

    public function abrirModalCrear()
    {
        $this->reset(['cliente_id', 'terreno_id', 'precio_total', 'enganche', 'saldo_inicial']);
        $this->numero_cuotas = 12;
        $this->frecuencia_pago = 'MENSUAL';
        $this->fecha_inicio = now()->format('Y-m-d');
        $this->resetValidation();
        $this->modalCrearAbierto = true;
    }

    public function cancelarContrato($id)
    {
        DB::transaction(function () use ($id) {
            $contrato = Contrato::with('terreno')->findOrFail($id);

            if ($contrato->estado === 'CANCELADO') {
                session()->flash('error', 'Este contrato ya se encuentra cancelado.');
                return;
            }

            // 1. Marcar contrato como cancelado
            $contrato->update(['estado' => 'CANCELADO']);

            // 2. Liberar el terreno nuevamente a DISPONIBLE
            if ($contrato->terreno) {
                $contrato->terreno->update(['estado' => 'DISPONIBLE']);
            }

            // 3. Eliminar las cuotas pendientes para no dejar deudas huérfanas
            $contrato->cuotas()->where('estado', 'PENDIENTE')->delete();

            session()->flash('mensaje', "El contrato {$contrato->folio} fue cancelado y el terreno quedó DISPONIBLE.");
        });
    }

    public function liquidarContrato($id)
    {
        DB::transaction(function () use ($id) {
            $contrato = Contrato::with('cuotas')->findOrFail($id);

            if ($contrato->estado !== 'ACTIVO') {
                session()->flash('error', "Solo se pueden liquidar contratos en estado ACTIVO.");
                return;
            }

            // 1. Actualizar todas las cuotas pendientes a PAGADA
            foreach ($contrato->cuotas()->where('estado', 'PENDIENTE')->get() as $cuota) {
                $cuota->update([
                    'estado'         => 'PAGADA',
                    'monto_pagado'   => $cuota->monto,
                    'abono_capital'  => $cuota->monto,
                    'saldo_restante' => 0.00,
                ]);
            }

            // 2. Cambiar estado del contrato y saldar la deuda
            $contrato->update([
                'saldo_actual' => 0.00,
                'estado'       => 'LIQUIDADO',
            ]);

            session()->flash('mensaje', "El contrato {$contrato->folio} ha sido LIQUIDADO con éxito.");
        });

        // Si el modal de estado de cuenta está abierto con ese contrato, refrescarlo
        if ($this->contratoSeleccionado && $this->contratoSeleccionado->id === $id) {
            $this->contratoSeleccionado = Contrato::with(['cliente', 'terreno', 'cuotas'])->find($id);
        }
    }

    public function cerrarModalCrear()
    {
        $this->modalCrearAbierto = false;
    }

    public function verEstadoCuenta($id)
    {
        $this->contratoSeleccionado = Contrato::with(['cliente', 'terreno', 'cuotas'])->findOrFail($id);
        $this->modalDetalleAbierto = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalleAbierto = false;
        $this->contratoSeleccionado = null;
    }

    public function guardarContrato()
    {
        $this->validate();

        $this->recalcularSaldo();

        DB::transaction(function () {
            // 1. Doble validación de disponibilidad del lote
            $terreno = Terreno::where('id', $this->terreno_id)->lockForUpdate()->first();
            if ($terreno->estado !== 'DISPONIBLE') {
                throw new \Exception("El terreno ya no se encuentra disponible.");
            }

            // 2. Generar Folio consecutivo (Ej. CT-2026-0001)
            $ultimoId = Contrato::max('id') ?? 0;
            $folio = 'CT-' . now()->format('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

            // 3. Crear Contrato
            $contrato = Contrato::create([
                'folio'           => $folio,
                'cliente_id'      => $this->cliente_id,
                'terreno_id'      => $this->terreno_id,
                'precio_total'    => $this->precio_total,
                'enganche'        => $this->enganche,
                'saldo_inicial'   => $this->saldo_inicial,
                'saldo_actual'    => $this->saldo_inicial,
                'numero_cuotas'   => $this->numero_cuotas,
                'frecuencia_pago' => $this->frecuencia_pago,
                'fecha_inicio'    => $this->fecha_inicio,
                'estado'          => 'ACTIVO',
            ]);

            // 4. Actualizar lote a VENDIDO
            $terreno->update(['estado' => 'VENDIDO']);

            // 5. Generar tabla de amortización (Cuotas)
            $montoPorCuota = round($this->saldo_inicial / $this->numero_cuotas, 2);
            $saldoCorriente = $this->saldo_inicial;
            $fechaBase = Carbon::parse($this->fecha_inicio);

            for ($i = 1; $i <= $this->numero_cuotas; $i++) {
                // Calcular fecha según frecuencia
                $fechaVencimiento = match ($this->frecuencia_pago) {
                    'SEMANAL'   => (clone $fechaBase)->addWeeks($i),
                    'QUINCENAL' => (clone $fechaBase)->addDays($i * 15),
                    'MENSUAL'   => (clone $fechaBase)->addMonthsNoOverflow($i),
                    'ANUAL'     => (clone $fechaBase)->addYears($i),
                };

                // Ajuste de redondeo en la última cuota
                if ($i === $this->numero_cuotas) {
                    $montoCuotaActual = $saldoCorriente;
                    $saldoNuevo = 0.00;
                } else {
                    $montoCuotaActual = $montoPorCuota;
                    $saldoNuevo = max(0, round($saldoCorriente - $montoPorCuota, 2));
                }

                Cuota::create([
                    'contrato_id'       => $contrato->id,
                    'numero_cuota'      => $i,
                    'fecha_vencimiento' => $fechaVencimiento->format('Y-m-d'),
                    'monto'             => $montoCuotaActual,
                    'monto_pagado'      => 0.00,
                    'saldo_anterior'    => $saldoCorriente,
                    'abono_capital'     => 0.00,
                    'saldo_restante'    => $saldoNuevo,
                    'estado'            => 'PENDIENTE',
                ]);

                $saldoCorriente = $saldoNuevo;
            }
        });

        $this->cerrarModalCrear();
        session()->flash('mensaje', 'Contrato formalizado y tabla de amortización creada con éxito.');
    }
}