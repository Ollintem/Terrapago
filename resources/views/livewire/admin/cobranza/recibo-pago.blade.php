<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recibo {{ $operacionFolio }} - TerraPago</title>

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #f1f5f9;
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
        }

        body {
            padding: 30px 15px;
        }

        /* =========================================================
           BOTONES
           ========================================================= */

        .acciones {
            width: 100%;
            max-width: 420px;
            margin: 0 auto 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .boton {
            border: none;
            border-radius: 8px;
            padding: 11px 16px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: background 0.2s ease;
        }

        .boton-imprimir {
            background: #059669;
            color: #ffffff;
        }

        .boton-imprimir:hover {
            background: #047857;
        }

        /* =========================================================
           TICKET
           ========================================================= */

        .ticket {
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            padding: 8mm 5mm;
            background: #ffffff;
            box-shadow:
                0 4px 20px rgba(15, 23, 42, 0.12);
        }

        /* =========================================================
           ENCABEZADO
           ========================================================= */

        .encabezado {
            text-align: center;
        }

        .logo {
            width: 48px;
            height: 48px;
            margin: 0 auto 7px auto;
            border-radius: 12px;
            background: #0f766e;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 900;
        }

        .empresa {
            margin: 0;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #0f766e;
        }

        .subtitulo {
            margin: 3px 0 0 0;
            font-size: 10px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* =========================================================
           SEPARADORES
           ========================================================= */

        .separador {
            border-top: 1px dashed #64748b;
            margin: 10px 0;
        }

        /* =========================================================
           TÍTULO
           ========================================================= */

        .titulo-recibo {
            margin: 8px 0;
            text-align: center;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 1px;
        }

        .folio {
            text-align: center;
            font-size: 10px;
            color: #334155;
            word-break: break-word;
        }

        /* =========================================================
           SECCIONES
           ========================================================= */

        .seccion-titulo {
            margin: 10px 0 6px 0;
            font-size: 10px;
            font-weight: 900;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .fila {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin: 4px 0;
            font-size: 10px;
            line-height: 1.35;
        }

        .etiqueta {
            min-width: 38%;
            color: #475569;
            font-weight: 700;
        }

        .valor {
            color: #111827;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }

        /* =========================================================
           TOTAL
           ========================================================= */

        .total-contenedor {
            margin: 12px 0;
            padding: 10px 0;
            border-top: 2px solid #111827;
            border-bottom: 2px solid #111827;
            text-align: center;
        }

        .total-label {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .total {
            margin-top: 4px;
            font-size: 25px;
            font-weight: 900;
            color: #0f766e;
        }

        /* =========================================================
           SALDOS
           ========================================================= */

        .saldo-tabla {
            margin-top: 8px;
        }

        .saldo-fila {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            font-size: 10px;
        }

        .saldo-fila span:first-child {
            color: #475569;
            font-weight: 700;
        }

        .saldo-fila span:last-child {
            font-weight: 800;
        }

        .saldo-final {
            margin-top: 4px;
            padding-top: 7px;
            border-top: 1px dashed #64748b;
            font-size: 12px;
        }

        .saldo-final span:first-child {
            color: #0f766e;
        }

        .saldo-final span:last-child {
            color: #0f766e;
            font-size: 14px;
        }

        /* =========================================================
           DETALLE DE PAGOS
           ========================================================= */

        .detalle-pagos {
            margin-top: 8px;
        }

        .pago-item {
            padding: 7px 0;
            border-bottom: 1px dashed #cbd5e1;
        }

        .pago-item:last-child {
            border-bottom: none;
        }

        .pago-cabecera {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            font-size: 10px;
            font-weight: 800;
        }

        .pago-secundario {
            margin-top: 3px;
            font-size: 9px;
            color: #64748b;
        }

        /* =========================================================
           OBSERVACIONES
           ========================================================= */

        .observaciones {
            margin-top: 10px;
            padding: 7px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font-size: 9px;
            line-height: 1.4;
        }

        .observaciones-titulo {
            margin-bottom: 3px;
            font-weight: 900;
        }

        /* =========================================================
           PIE
           ========================================================= */

        .pie {
            margin-top: 15px;
            text-align: center;
        }

        .gracias {
            font-size: 11px;
            font-weight: 900;
        }

        .mensaje-pie {
            margin-top: 5px;
            font-size: 9px;
            line-height: 1.4;
            color: #64748b;
        }

        .folio-pie {
            margin-top: 8px;
            font-size: 8px;
            color: #94a3b8;
            word-break: break-word;
        }

        /* =========================================================
           IMPRESIÓN TÉRMICA 80 MM
           ========================================================= */

        @media print {

            @page {
                size: 80mm auto;
                margin: 0;
            }

            html,
            body {
                width: 80mm;
                min-width: 80mm;
                max-width: 80mm;
                margin: 0;
                padding: 0;
                background: #ffffff;
            }

            body {
                padding: 0;
            }

            .acciones {
                display: none !important;
            }

            .ticket {
                width: 80mm;
                max-width: 80mm;
                margin: 0;
                padding: 5mm 4mm;
                box-shadow: none;
            }

            .logo,
            .empresa,
            .total,
            .seccion-titulo,
            .saldo-final span:first-child,
            .saldo-final span:last-child {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

    </style>

</head>

<body>

    {{-- =========================================================
         BOTÓN DE IMPRIMIR
         ========================================================= --}}

    <div class="acciones">

        <button
            type="button"
            class="boton boton-imprimir"
            onclick="window.print()"
        >
            🖨 Imprimir ticket
        </button>

    </div>


    {{-- =========================================================
         TICKET
         ========================================================= --}}

    <div class="ticket">

        {{-- ENCABEZADO --}}

        <div class="encabezado">

            <div class="logo">
                TP
            </div>

            <h1 class="empresa">
                TerraPago
            </h1>

            <div class="subtitulo">
                Sistema de gestión de pagos
            </div>

        </div>


        <div class="separador"></div>


        {{-- TÍTULO DEL RECIBO --}}

        <div class="titulo-recibo">
            RECIBO DE PAGO
        </div>

        <div class="folio">

            Folio de operación:

            <strong>
                {{ $operacionFolio }}
            </strong>

        </div>


        <div class="separador"></div>


        {{-- INFORMACIÓN DE OPERACIÓN --}}

        @php

            $fechaOperacion = $primerPago->fecha_pago
                ?? $primerPago->fecha
                ?? $primerPago->created_at
                ?? now();

            $fechaFormateada = \Carbon\Carbon::parse(
                $fechaOperacion
            )->format('d/m/Y H:i');

        @endphp


        <div class="seccion-titulo">
            Información de la operación
        </div>


        <div class="fila">

            <span class="etiqueta">
                Fecha:
            </span>

            <span class="valor">
                {{ $fechaFormateada }}
            </span>

        </div>


        <div class="fila">

            <span class="etiqueta">
                Tipo:
            </span>

            <span class="valor">
                {{ $tipoPago }}
            </span>

        </div>


        <div class="fila">

            <span class="etiqueta">
                Concepto:
            </span>

            <span class="valor">
                {{ $concepto }}
            </span>

        </div>


        {{-- CLIENTE --}}

        <div class="separador"></div>

        <div class="seccion-titulo">
            Datos del cliente
        </div>


        @php

            $nombreCliente = trim(
                ($cliente?->nombre ?? '') . ' ' .
                ($cliente?->apellidos ?? '')
            );

        @endphp


        <div class="fila">

            <span class="etiqueta">
                Cliente:
            </span>

            <span class="valor">
                {{ $nombreCliente ?: 'No disponible' }}
            </span>

        </div>


        @if ($cliente?->telefono)

            <div class="fila">

                <span class="etiqueta">
                    Teléfono:
                </span>

                <span class="valor">
                    {{ $cliente->telefono }}
                </span>

            </div>

        @endif


        @if ($cliente?->email)

            <div class="fila">

                <span class="etiqueta">
                    Correo:
                </span>

                <span class="valor">
                    {{ $cliente->email }}
                </span>

            </div>

        @endif


        {{-- CONTRATO Y TERRENO --}}

        <div class="separador"></div>

        <div class="seccion-titulo">
            Datos del contrato
        </div>


        <div class="fila">

            <span class="etiqueta">
                Contrato:
            </span>

            <span class="valor">
                {{ $contrato?->folio ?? 'No disponible' }}
            </span>

        </div>


        @if ($terreno)

            <div class="fila">

                <span class="etiqueta">
                    Terreno:
                </span>

                <span class="valor">

                    @if (!empty($terreno->manzana))
                        Mza. {{ $terreno->manzana }}
                    @endif

                    @if (!empty($terreno->lote))
                        · Lote {{ $terreno->lote }}
                    @endif

                </span>

            </div>

        @endif


        {{-- DETALLE DE PAGOS --}}

        <div class="separador"></div>

        <div class="seccion-titulo">
            Detalle del pago
        </div>


        <div class="detalle-pagos">

            @foreach ($pagos as $pago)

                @php

                    $metodo = $pago->metodo_pago
                        ?? $pago->metodo
                        ?? 'No especificado';

                    $numeroCuota = $pago->cuota?->numero_cuota;

                    $referenciaPago = $pago->referencia
                        ?? null;

                @endphp


                <div class="pago-item">

                    <div class="pago-cabecera">

                        <span>

                            @if ($numeroCuota)

                                Cuota #{{ $numeroCuota }}

                            @else

                                Pago

                            @endif

                        </span>


                        <span>

                            ${{ number_format((float) $pago->monto, 2) }}

                        </span>

                    </div>


                    <div class="pago-secundario">

                        Método: {{ $metodo }}

                    </div>


                    @if ($referenciaPago)

                        <div class="pago-secundario">

                            Referencia: {{ $referenciaPago }}

                        </div>

                    @endif

                </div>

            @endforeach

        </div>


        {{-- TOTAL --}}

        <div class="total-contenedor">

            <div class="total-label">
                Total pagado
            </div>

            <div class="total">
                ${{ number_format((float) $totalPagado, 2) }}
            </div>

        </div>


        {{-- RESUMEN DE SALDO --}}

        <div class="seccion-titulo">
            Resumen de saldo
        </div>


        <div class="saldo-tabla">

            <div class="saldo-fila">

                <span>
                    Saldo anterior
                </span>

                <span>
                    ${{ number_format((float) $saldoAnterior, 2) }}
                </span>

            </div>


            <div class="saldo-fila">

                <span>
                    Pago realizado
                </span>

                <span>
                    - ${{ number_format((float) $totalPagado, 2) }}
                </span>

            </div>


            <div class="saldo-fila saldo-final">

                <span>
                    Saldo restante
                </span>

                <span>
                    ${{ number_format((float) $saldoDespues, 2) }}
                </span>

            </div>

        </div>


        {{-- REFERENCIA --}}

        @if ($primerPago->referencia)

            <div class="separador"></div>

            <div class="fila">

                <span class="etiqueta">
                    Referencia:
                </span>

                <span class="valor">
                    {{ $primerPago->referencia }}
                </span>

            </div>

        @endif


        {{-- OBSERVACIONES --}}

        @if ($primerPago->observaciones)

            <div class="observaciones">

                <div class="observaciones-titulo">
                    Observaciones
                </div>

                <div>
                    {{ $primerPago->observaciones }}
                </div>

            </div>

        @endif


        {{-- PIE DEL RECIBO --}}

        <div class="separador"></div>

        <div class="pie">

            <div class="gracias">
                ¡Gracias por su pago!
            </div>

            <div class="mensaje-pie">

                Conserve este recibo como comprobante
                de su operación.

            </div>

            <div class="folio-pie">

                Folio: {{ $operacionFolio }}

            </div>

        </div>

    </div>

</body>

</html>