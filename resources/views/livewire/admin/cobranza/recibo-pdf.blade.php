```blade
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>Recibo de Pago - TerraPago</title>

    <style>

        @page {
            size: letter portrait;
            margin: 20px 25px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #243447;
            font-size: 9px;
            line-height: 1.3;
        }

        /*
        |--------------------------------------------------------------------------
        | PÁGINA
        |--------------------------------------------------------------------------
        */

        .pagina {
            width: 100%;
        }

        /*
        |--------------------------------------------------------------------------
        | ENCABEZADO
        |--------------------------------------------------------------------------
        */

        .encabezado {
            background: #f6f1e7;
            border: 1px solid #d9e4df;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 12px;
        }

        .encabezado table {
            width: 100%;
            border-collapse: collapse;
        }

        .encabezado td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .marca {
            width: 60%;
        }

        .logo {
            display: inline-block;
            width: 38px;
            height: 38px;
            line-height: 38px;
            text-align: center;
            border-radius: 50%;
            background: #0f766e;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            margin-right: 8px;
        }

        .empresa {
            display: inline-block;
            vertical-align: middle;
            color: #123047;
            font-size: 19px;
            font-weight: bold;
        }

        .empresa-verde {
            color: #0f766e;
        }

        .lema {
            color: #64748b;
            font-size: 7px;
            margin-top: 2px;
            font-weight: normal;
        }

        .titulo {
            text-align: right;
        }

        .titulo-principal {
            color: #123047;
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .folio {
            color: #64748b;
            font-size: 8px;
            margin-top: 4px;
        }

        .folio strong {
            color: #0f766e;
        }

        /*
        |--------------------------------------------------------------------------
        | SECCIONES
        |--------------------------------------------------------------------------
        */

        .seccion {
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .seccion-titulo {
            background: #123047;
            color: #ffffff;
            padding: 6px 9px;
            border-radius: 6px 6px 0 0;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .seccion-contenido {
            border: 1px solid #d9e4df;
            border-top: none;
            border-radius: 0 0 6px 6px;
        }

        /*
        |--------------------------------------------------------------------------
        | INFORMACIÓN DEL PAGO
        |--------------------------------------------------------------------------
        */

        .tabla-info {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla-info td {
            border-bottom: 1px solid #e5ebe8;
            padding: 7px 9px;
            vertical-align: middle;
        }

        .tabla-info tr:last-child td {
            border-bottom: none;
        }

        .label {
            width: 15%;
            background: #f8faf9;
            color: #64748b;
            font-weight: bold;
        }

        .dato {
            color: #243447;
        }

        .dato-verde {
            color: #0f766e;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | TARJETAS DEL CLIENTE
        |--------------------------------------------------------------------------
        */

        .tarjetas {
            width: 100%;
            border-collapse: separate;
            border-spacing: 7px;
        }

        .tarjetas td {
            width: 33.33%;
            border: 1px solid #d9e4df;
            background: #fafcfb;
            border-radius: 5px;
            padding: 8px 9px;
            vertical-align: middle;
        }

        .tarjeta-label {
            color: #64748b;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .tarjeta-valor {
            color: #123047;
            font-size: 9px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTRATO
        |--------------------------------------------------------------------------
        */

        .contrato {
            width: 100%;
            border-collapse: collapse;
        }

        .contrato td {
            border-right: 1px solid #dfe7e4;
            padding: 8px 9px;
            vertical-align: middle;
        }

        .contrato td:last-child {
            border-right: none;
        }

        .contrato .campo {
            width: 22%;
            background: #f8faf9;
        }

        /*
        |--------------------------------------------------------------------------
        | DETALLE
        |--------------------------------------------------------------------------
        */

        .detalle {
            width: 100%;
            border-collapse: collapse;
        }

        .detalle th {
            background: #eef5f2;
            color: #123047;
            border: 1px solid #d9e4df;
            padding: 7px 6px;
            font-size: 8px;
            text-align: center;
        }

        .detalle td {
            border: 1px solid #d9e4df;
            padding: 8px 6px;
            font-size: 9px;
            text-align: center;
        }

        .detalle td:nth-child(3),
        .detalle td:nth-child(4),
        .detalle td:nth-child(5) {
            text-align: right;
        }

        .numero-cuota {
            color: #0f766e;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | PARTE FINAL
        |--------------------------------------------------------------------------
        */

        .final {
            width: 100%;
            border-collapse: collapse;
            margin-top: 11px;
            page-break-inside: avoid;
        }

        .final > tbody > tr > td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .mensaje {
            width: 57%;
            padding-right: 10px !important;
        }

        .mensaje-box {
            border: 1px solid #b9d9d0;
            background: #f1faf7;
            border-radius: 7px;
            padding: 12px;
            min-height: 82px;
        }

        .mensaje-titulo {
            color: #0f766e;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .mensaje-texto {
            color: #64748b;
            font-size: 8px;
            line-height: 1.5;
        }

        /*
        |--------------------------------------------------------------------------
        | RESUMEN
        |--------------------------------------------------------------------------
        */

        .resumen {
            width: 43%;
        }

        .resumen table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d9e4df;
        }

        .resumen td {
            border-bottom: 1px solid #e5ebe8;
            padding: 7px 8px;
        }

        .resumen tr:last-child td {
            border-bottom: none;
        }

        .resumen .importe {
            text-align: right;
            font-weight: bold;
            color: #123047;
        }

        .resumen .saldo td {
            background: #0f766e;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
        }

        .resumen .saldo .importe {
            color: #ffffff;
        }

        /*
        |--------------------------------------------------------------------------
        | CONFIRMACIÓN
        |--------------------------------------------------------------------------
        */

        .confirmacion {
            margin-top: 10px;
            background: #123047;
            color: #ffffff;
            border-radius: 7px;
            padding: 9px;
            text-align: center;
            page-break-inside: avoid;
        }

        .confirmacion-principal {
            font-size: 9px;
            font-weight: bold;
        }

        .confirmacion-secundaria {
            margin-top: 3px;
            font-size: 7px;
            color: #dbeafe;
        }

        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .footer {
            margin-top: 9px;
            padding-top: 6px;
            border-top: 1px solid #d9e4df;
            text-align: center;
            color: #94a3b8;
            font-size: 7px;
        }

        .footer strong {
            color: #0f766e;
        }

        /*
        |--------------------------------------------------------------------------
        | EVITAR SALTOS
        |--------------------------------------------------------------------------
        */

        .seccion,
        .encabezado,
        .final,
        .confirmacion,
        .footer {
            page-break-before: avoid;
            page-break-after: avoid;
        }

    </style>

</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | DATOS DEL PAGO
    |--------------------------------------------------------------------------
    */

    $fechaPago = $primerPago?->created_at
        ? $primerPago->created_at->format('d/m/Y H:i')
        : date('d/m/Y H:i');

    $nombreCliente = $cliente?->nombre ?? 'Sin nombre';

    if (!empty($cliente?->apellidos)) {
        $nombreCliente .= ' ' . $cliente->apellidos;
    }

    $correoCliente = $cliente?->email ?? 'Sin correo';

    $telefonoCliente = $cliente?->telefono ?? 'Sin teléfono';

    $folioContrato = $contrato?->folio ?? 'Sin folio';

    $estado = $estadoContrato ?? 'ACTIVO';


    /*
    |--------------------------------------------------------------------------
    | TERRENO
    |--------------------------------------------------------------------------
    */

    $manzana = $terreno?->manzana ?? '';

    $lote = $terreno?->lote ?? '';

    if ($manzana !== '' && $lote !== '') {

        $nombreTerreno =
            'Manzana ' . $manzana .
            ' - Lote ' . $lote;

    } elseif ($manzana !== '') {

        $nombreTerreno =
            'Manzana ' . $manzana;

    } elseif ($lote !== '') {

        $nombreTerreno =
            'Lote ' . $lote;

    } else {

        $nombreTerreno =
            'Sin terreno';
    }

@endphp


<div class="pagina">


    {{-- =========================================================
         ENCABEZADO
    ========================================================== --}}

    <div class="encabezado">

        <table>

            <tr>

                <td class="marca">

                    <span class="logo">
                        TP
                    </span>

                    <span class="empresa">

                        Terra<span class="empresa-verde">Pago</span>

                        <div class="lema">
                            Gestión financiera de pagos y amortizaciones
                        </div>

                    </span>

                </td>


                <td class="titulo">

                    <div class="titulo-principal">
                        RECIBO DE PAGO
                    </div>

                    <div class="folio">

                        Folio:

                        <strong>
                            {{ $operacionFolio }}
                        </strong>

                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =========================================================
         INFORMACIÓN DEL PAGO
    ========================================================== --}}

    <div class="seccion">

        <div class="seccion-titulo">
            Información del pago
        </div>

        <div class="seccion-contenido">

            <table class="tabla-info">

                <tr>

                    <td class="label">
                        Fecha
                    </td>

                    <td class="dato">
                        {{ $fechaPago }}
                    </td>

                    <td class="label">
                        Tipo
                    </td>

                    <td class="dato">
                        {{ $tipoPago }}
                    </td>

                </tr>

                <tr>

                    <td class="label">
                        Concepto
                    </td>

                    <td class="dato">
                        {{ $concepto }}
                    </td>

                    <td class="label">
                        Método
                    </td>

                    <td class="dato-verde">
                        {{ $metodoPago }}
                    </td>

                </tr>

                <tr>

                    <td class="label">
                        Referencia
                    </td>

                    <td colspan="3" class="dato">
                        {{ $referencia }}
                    </td>

                </tr>

            </table>

        </div>

    </div>


    {{-- =========================================================
         CLIENTE
    ========================================================== --}}

    <div class="seccion">

        <div class="seccion-titulo">
            Información del cliente
        </div>

        <div class="seccion-contenido">

            <table class="tarjetas">

                <tr>

                    <td>

                        <div class="tarjeta-label">
                            Cliente
                        </div>

                        <div class="tarjeta-valor">
                            {{ $nombreCliente }}
                        </div>

                    </td>


                    <td>

                        <div class="tarjeta-label">
                            Correo electrónico
                        </div>

                        <div class="tarjeta-valor">
                            {{ $correoCliente }}
                        </div>

                    </td>


                    <td>

                        <div class="tarjeta-label">
                            Teléfono
                        </div>

                        <div class="tarjeta-valor">
                            {{ $telefonoCliente }}
                        </div>

                    </td>

                </tr>

            </table>

        </div>

    </div>


    {{-- =========================================================
         CONTRATO
    ========================================================== --}}

    <div class="seccion">

        <div class="seccion-titulo">
            Información del contrato
        </div>

        <div class="seccion-contenido">

            <table class="contrato">

                <tr>

                    <td class="campo">

                        <div class="tarjeta-label">
                            Folio
                        </div>

                        <div class="tarjeta-valor">
                            {{ $folioContrato }}
                        </div>

                    </td>


                    <td class="campo">

                        <div class="tarjeta-label">
                            Estado
                        </div>

                        <div class="tarjeta-valor">
                            {{ $estado }}
                        </div>

                    </td>


                    <td>

                        <div class="tarjeta-label">
                            Terreno
                        </div>

                        <div class="tarjeta-valor">
                            {{ $nombreTerreno }}
                        </div>

                    </td>

                </tr>

            </table>

        </div>

    </div>


    {{-- =========================================================
         DETALLE DE OPERACIÓN
    ========================================================== --}}

    <div class="seccion">

        <div class="seccion-titulo">
            Detalle de la operación
        </div>

        <div class="seccion-contenido">

            <table class="detalle">

                <thead>

                    <tr>

                        <th width="15%">
                            Cuota
                        </th>

                        <th width="22%">
                            Vencimiento
                        </th>

                        <th width="21%">
                            Monto
                        </th>

                        <th width="21%">
                            Abono
                        </th>

                        <th width="21%">
                            Saldo
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($pagos as $pago)

                        @php

                            $cuota = $pago->cuota;

                            $numeroCuota =
                                $cuota?->numero_cuota
                                ?? 'N/D';

                            $vencimiento =
                                $cuota?->fecha_vencimiento
                                ?? $cuota?->vencimiento
                                ?? null;

                            $montoCuota =
                                $cuota?->monto
                                ?? $cuota?->monto_cuota
                                ?? $pago->monto
                                ?? 0;

                            $abono =
                                $pago->monto
                                ?? 0;

                            $saldoCuota =
                                $pago->saldo
                                ?? $cuota?->saldo
                                ?? $cuota?->saldo_pendiente
                                ?? 0;

                        @endphp


                        <tr>

                            <td class="numero-cuota">
                                #{{ $numeroCuota }}
                            </td>

                            <td>

                                @if ($vencimiento)

                                    {{ \Carbon\Carbon::parse($vencimiento)->format('d/m/Y') }}

                                @else

                                    N/D

                                @endif

                            </td>

                            <td>
                                ${{ number_format((float) $montoCuota, 2) }}
                            </td>

                            <td>
                                ${{ number_format((float) $abono, 2) }}
                            </td>

                            <td>
                                ${{ number_format((float) $saldoCuota, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         RESUMEN
    ========================================================== --}}

    <table class="final">

        <tr>

            <td class="mensaje">

                <div class="mensaje-box">

                    <div class="mensaje-titulo">
                        ✓ Pago registrado correctamente
                    </div>

                    <div class="mensaje-texto">

                        ¡Gracias por tu pago!

                        <br><br>

                        Conserva este recibo como comprobante
                        de la operación.

                    </div>

                </div>

            </td>


            <td class="resumen">

                <table>

                    <tr>

                        <td>
                            Saldo anterior
                        </td>

                        <td class="importe">
                            ${{ number_format((float) $saldoAnterior, 2) }}
                        </td>

                    </tr>


                    <tr>

                        <td>
                            Pago realizado
                        </td>

                        <td class="importe">
                            ${{ number_format((float) $totalPagado, 2) }}
                        </td>

                    </tr>


                    <tr class="saldo">

                        <td>
                            Saldo pendiente
                        </td>

                        <td class="importe">
                            ${{ number_format((float) $saldoDespues, 2) }}
                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>


    {{-- =========================================================
         CONFIRMACIÓN FINAL
    ========================================================== --}}

    <div class="confirmacion">

        <div class="confirmacion-principal">
            PAGO REGISTRADO CORRECTAMENTE
        </div>

        <div class="confirmacion-secundaria">
            Este documento es un comprobante de la operación realizada en TerraPago.
        </div>

    </div>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <div class="footer">

        <strong>TerraPago</strong>
        · Sistema de gestión financiera
        &nbsp;|&nbsp;
        Recibo generado automáticamente

    </div>


</div>

</body>

</html>

