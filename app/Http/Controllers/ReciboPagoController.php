<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Barryvdh\DomPDF\Facade\Pdf;

class ReciboPagoController extends Controller
{
    /**
     * Obtiene toda la información necesaria
     * para mostrar o generar el recibo de una operación.
     */
    private function obtenerDatosOperacion(string $operacionFolio): array
    {
        $pagos = Pago::with([
            'contrato.cliente',
            'contrato.terreno',
            'cuota',
            'usuario',
        ])
            ->where('operacion_folio', $operacionFolio)
            ->orderBy('id')
            ->get();

        if ($pagos->isEmpty()) {
            abort(
                404,
                'No se encontró la operación de pago solicitada.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAGO PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $primerPago = $pagos->first();

        $contrato = $primerPago->contrato;

        $cliente = $contrato?->cliente;

        $terreno = $contrato?->terreno;


        /*
        |--------------------------------------------------------------------------
        | TOTAL PAGADO
        |--------------------------------------------------------------------------
        */

        $totalPagado = round(
            (float) $pagos->sum('monto'),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | SALDO ACTUAL DEL CONTRATO
        |--------------------------------------------------------------------------
        */

        $saldoDespues = round(
            (float) ($contrato?->saldo_actual ?? 0),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | SALDO ANTERIOR
        |--------------------------------------------------------------------------
        */

        $saldoAnterior = round(
            $saldoDespues + $totalPagado,
            2
        );


        /*
        |--------------------------------------------------------------------------
        | MODO DE PAGO
        |--------------------------------------------------------------------------
        */

        $modoPago = $primerPago->modo_pago
            ?? 'NORMAL';


        /*
        |--------------------------------------------------------------------------
        | TIPO DE PAGO
        |--------------------------------------------------------------------------
        */

        $tipoPago = $modoPago === 'ADELANTO'
            ? 'Adelanto de pago'
            : 'Pago de cuota';


        /*
        |--------------------------------------------------------------------------
        | CONCEPTO
        |--------------------------------------------------------------------------
        */

        if ($modoPago === 'ADELANTO') {

            $cuotasAfectadas = $pagos
                ->map(function ($pago) {
                    return $pago->cuota?->numero_cuota;
                })
                ->filter()
                ->unique()
                ->values();

            if ($cuotasAfectadas->count() > 1) {

                $concepto =
                    'Adelanto de cuotas #' .
                    $cuotasAfectadas->first() .
                    ' a #' .
                    $cuotasAfectadas->last();

            } elseif ($cuotasAfectadas->count() === 1) {

                $concepto =
                    'Adelanto de cuota #' .
                    $cuotasAfectadas->first();

            } else {

                $concepto =
                    'Adelanto de pago';
            }

        } else {

            $numeroCuota =
                $primerPago->cuota?->numero_cuota;

            $concepto = $numeroCuota
                ? 'Pago de cuota #' . $numeroCuota
                : 'Pago de cuota';
        }


        /*
        |--------------------------------------------------------------------------
        | ESTADO DEL CONTRATO
        |--------------------------------------------------------------------------
        */

        $estadoContrato =
            $contrato?->estado ?? 'ACTIVO';


        /*
        |--------------------------------------------------------------------------
        | MÉTODO DE PAGO
        |--------------------------------------------------------------------------
        |
        | Se revisan diferentes nombres de campo para evitar
        | romper el código si el modelo utiliza alguno de ellos.
        |
        */

        $metodoPago =
            $primerPago->metodo_pago
            ?? $primerPago->metodo
            ?? $primerPago->forma_pago
            ?? $modoPago
            ?? 'No especificado';


        /*
        |--------------------------------------------------------------------------
        | REFERENCIA
        |--------------------------------------------------------------------------
        */

        $referencia =
            $primerPago->referencia
            ?: 'Sin referencia';


        /*
        |--------------------------------------------------------------------------
        | DATOS QUE SE ENVÍAN A LAS VISTAS
        |--------------------------------------------------------------------------
        */

        return [

            'pagos' => $pagos,

            'primerPago' => $primerPago,

            'contrato' => $contrato,

            'cliente' => $cliente,

            'terreno' => $terreno,

            'totalPagado' => $totalPagado,

            'saldoAnterior' => $saldoAnterior,

            'saldoDespues' => $saldoDespues,

            'operacionFolio' => $operacionFolio,

            'modoPago' => $modoPago,

            'tipoPago' => $tipoPago,

            'concepto' => $concepto,

            'estadoContrato' => $estadoContrato,

            'metodoPago' => $metodoPago,

            'referencia' => $referencia,
        ];
    }


    /**
     * =========================================================================
     * MUESTRA EL RECIBO NORMAL EN EL NAVEGADOR
     * =========================================================================
     */
    public function ver(string $operacionFolio)
    {
        $datos = $this->obtenerDatosOperacion(
            $operacionFolio
        );

        return view(
            'livewire.admin.cobranza.recibo-pago',
            $datos
        );
    }


    /**
     * =========================================================================
     * GENERA EL RECIBO EN PDF
     * =========================================================================
     */
    public function generar(string $operacionFolio)
    {
        /*
        |--------------------------------------------------------------------------
        | OBTENER DATOS
        |--------------------------------------------------------------------------
        */

        $datos = $this->obtenerDatosOperacion(
            $operacionFolio
        );


        /*
        |--------------------------------------------------------------------------
        | CARGAR LA VISTA EXCLUSIVA DEL PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'livewire.admin.cobranza.recibo-pdf',
            $datos
        );


        /*
        |--------------------------------------------------------------------------
        | CONFIGURACIÓN DE LA HOJA
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper(
            'letter',
            'portrait'
        );


        /*
        |--------------------------------------------------------------------------
        | OPCIONES DE DOMPDF
        |--------------------------------------------------------------------------
        */

        $pdf->setOption([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
        ]);


        /*
        |--------------------------------------------------------------------------
        | MOSTRAR PDF EN EL NAVEGADOR
        |--------------------------------------------------------------------------
        */

        return $pdf->stream(
            'recibo-' . $operacionFolio . '.pdf'
        );
    }
}

