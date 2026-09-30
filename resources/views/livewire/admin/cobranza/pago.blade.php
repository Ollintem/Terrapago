<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">

```
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Recibo {{ $operacionFolio ?? '' }} - TerraPago
</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 30px;
        background: #f1f5f9;
        font-family: Arial, Helvetica, sans-serif;
        color: #1e293b;
    }

    .contenedor {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
    }

    .acciones {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 20px;
    }

    .boton {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
    }

    .boton-imprimir {
        background: #0f172a;
        color: white;
    }

    .boton-regresar {
        background: white;
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .recibo {
        background: white;
        border: 1px solid #dbe3ea;
        border-radius: 14px;
        overflow: hidden;
    }

    .encabezado {
        padding: 30px;
        border-bottom: 3px solid #059669;
    }

    .empresa {
        font-size: 28px;
        font-weight: bold;
        color: #0f172a;
        margin: 0;
    }

    .subtitulo {
        margin-top: 5px;
        color: #64748b;
        font-size: 14px;
    }

    .titulo-recibo {
        margin-top: 25px;
        font-size: 22px;
        font-weight: bold;
        color: #047857;
    }

    .folio {
        margin-top: 6px;
        font-size: 13px;
        color: #64748b;
    }

    .contenido {
        padding: 30px;
    }

    .seccion {
        margin-bottom: 28px;
    }

    .seccion-titulo {
        margin: 0 0 12px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
        color: #0f172a;
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .grid {
        width: 100%;
        display: table;
        table-layout: fixed;
    }

    .columna {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-right: 20px;
    }

    .dato {
        margin-bottom: 12px;
    }

    .etiqueta {
        display: block;
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 3px;
    }

    .valor {
        font-size: 14px;
        color: #1e293b;
    }

    .monto-principal {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 10px;
        padding: 18px;
        text-align: center;
        margin-bottom: 25px;
    }

    .monto-label {
        font-size: 12px;
        color: #047857;
        text-transform: uppercase;
        font-weight: bold;
    }

    .monto {
        margin-top: 5px;
        font-size: 30px;
        font-weight: bold;
        color: #065f46;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        text-transform: uppercase;
        text-align: left;
        padding: 10px;
        border-bottom: 1px solid #cbd5e1;
    }

    td {
        padding: 10px;
        font-size: 12px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }

    .total {
        text-align: right;
        font-size: 16px;
        font-weight: bold;
        color: #047857;
    }

    .saldo {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 15px;
    }

    .saldo-row {
        display: table;
        width: 100%;
        margin-bottom: 8px;
    }

    .saldo-label,
    .saldo-valor {
        display: table-cell;
        width: 50%;
    }

    .saldo-valor {
        text-align: right;
        font-weight: bold;
    }

    .pie {
        padding: 20px 30px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        text-align: center;
        color: #64748b;
        font-size: 11px;
    }

    .estado {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-size: 11px;
        font-weight: bold;
    }

    @media print {
        body {
            padding: 0;
            background: white;
        }

        .acciones {
            display: none !important;
        }

        .contenedor {
            max-width: none;
        }

        .recibo {
            border: none;
            border-radius: 0;
        }

        .encabezado {
            border-radius: 0;
        }
    }
</style>
```

</head>

<body>

<div class="contenedor">

```
{{-- ========================================================= --}}
{{-- BOTONES --}}
{{-- ========================================================= --}}

<div class="acciones">

    <a
        href="{{ url()->previous() }}"
        class="boton boton-regresar"
    >
        Regresar
    </a>

    <button
        type="button"
        onclick="window.print()"
        class="boton boton-imprimir"
    >
        Imprimir recibo
    </button>

</div>


{{-- ========================================================= --}}
{{-- RECIBO --}}
{{-- ========================================================= --}}

<div class="recibo">

    {{-- ENCABEZADO --}}
    <div class="encabezado">

        <h1 class="empresa">
            TerraPago
        </h1>

        <p class="subtitulo">
            Sistema de gestión financiera y cobranza
        </p>

        <div class="titulo-recibo">
            RECIBO DE PAGO
        </div>

        <div class="folio">
            Folio de operación:
            <strong>
                {{ $operacionFolio ?? '—' }}
            </strong>
        </div>

    </div>


    <div class="contenido">

        {{-- ================================================= --}}
        {{-- MONTO --}}
        {{-- ================================================= --}}

        <div class="monto-principal">

            <div class="monto-label">
                Total recibido
            </div>

            <div class="monto">
                ${{ number_format((float) ($totalPagado ?? 0), 2) }}
            </div>

        </div>


        {{-- ================================================= --}}
        {{-- INFORMACIÓN DE LA OPERACIÓN --}}
        {{-- ================================================= --}}

        <div class="seccion">

            <h2 class="seccion-titulo">
                Información de la operación
            </h2>

            <div class="grid">

                <div class="columna">

                    <div class="dato">

                        <span class="etiqueta">
                            Tipo de pago
                        </span>

                        <span class="valor">
                            {{ $tipoPago ?? 'Pago de cuota' }}
                        </span>

                    </div>


                    <div class="dato">

                        <span class="etiqueta">
                            Concepto
                        </span>

                        <span class="valor">
                            {{ $concepto ?? 'Pago de cuota' }}
                        </span>

                    </div>

                </div>


                <div class="columna">

                    <div class="dato">

                        <span class="etiqueta">
                            Fecha de operación
                        </span>

                        <span class="valor">

                            @if(isset($primerPago->fecha))
                                {{ \Carbon\Carbon::parse($primerPago->fecha)->format('d/m/Y H:i') }}
                            @elseif(isset($primerPago->created_at))
                                {{ $primerPago->created_at->format('d/m/Y H:i') }}
                            @else
                                —
                            @endif

                        </span>

                    </div>


                    <div class="dato">

                        <span class="etiqueta">
                            Estado
                        </span>

                        <span class="estado">
                            PAGADO
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- DATOS DEL CLIENTE --}}
        {{-- ================================================= --}}

        <div class="seccion">

            <h2 class="seccion-titulo">
                Datos del cliente
            </h2>

            <div class="grid">

                <div class="columna">

                    <div class="dato">

                        <span class="etiqueta">
                            Cliente
                        </span>

                        <span class="valor">

                            @if($cliente)
                                {{ $cliente->nombre_completo
                                    ?? trim(
                                        ($cliente->nombre ?? '') . ' ' .
                                        ($cliente->apellido_paterno ?? '') . ' ' .
                                        ($cliente->apellido_materno ?? '')
                                    )
                                }}
                            @else
                                Sin información
                            @endif

                        </span>

                    </div>


                    <div class="dato">

                        <span class="etiqueta">
                            Teléfono
                        </span>

                        <span class="valor">
                            {{ $cliente->telefono ?? '—' }}
                        </span>

                    </div>

                </div>


                <div class="columna">

                    <div class="dato">

                        <span class="etiqueta">
                            Correo electrónico
                        </span>

                        <span class="valor">
                            {{ $cliente->email ?? '—' }}
                        </span>

                    </div>


                    <div class="dato">

                        <span class="etiqueta">
                            Contrato
                        </span>

                        <span class="valor">
                            {{ $contrato->folio ?? '—' }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- TERRENO --}}
        {{-- ================================================= --}}

        <div class="seccion">

            <h2 class="seccion-titulo">
                Datos del terreno
            </h2>

            <div class="grid">

                <div class="columna">

                    <div class="dato">

                        <span class="etiqueta">
                            Manzana
                        </span>

                        <span class="valor">
                            {{ $terreno->manzana ?? '—' }}
                        </span>

                    </div>

                </div>


                <div class="columna">

                    <div class="dato">

                        <span class="etiqueta">
                            Lote
                        </span>

                        <span class="valor">
                            {{ $terreno->lote ?? '—' }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- PAGOS APLICADOS --}}
        {{-- ================================================= --}}

        <div class="seccion">

            <h2 class="seccion-titulo">
                Detalle del pago
            </h2>

            <table>

                <thead>

                    <tr>

                        <th>
                            Concepto
                        </th>

                        <th>
                            Método
                        </th>

                        <th>
                            Referencia
                        </th>

                        <th>
                            Monto
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($pagos as $pago)

                        <tr>

                            <td>

                                @if($pago->cuota)
                                    Cuota #{{ $pago->cuota->numero_cuota }}
                                @else
                                    {{ $concepto ?? 'Pago' }}
                                @endif

                            </td>


                            <td>
                                {{ $pago->metodo_pago ?? $pago->metodo ?? '—' }}
                            </td>


                            <td>
                                {{ $pago->referencia ?? '—' }}
                            </td>


                            <td>
                                ${{ number_format((float) $pago->monto, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4">
                                No hay pagos asociados.
                            </td>

                        </tr>

                    @endforelse


                    <tr>

                        <td
                            colspan="3"
                            class="total"
                        >
                            TOTAL
                        </td>

                        <td class="total">
                            ${{ number_format((float) ($totalPagado ?? 0), 2) }}
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- ================================================= --}}
        {{-- SALDOS --}}
        {{-- ================================================= --}}

        <div class="seccion">

            <h2 class="seccion-titulo">
                Resumen del saldo
            </h2>

            <div class="saldo">

                <div class="saldo-row">

                    <span class="saldo-label">
                        Saldo antes de la operación
                    </span>

                    <span class="saldo-valor">
                        ${{ number_format((float) ($saldoAnterior ?? 0), 2) }}
                    </span>

                </div>


                <div class="saldo-row">

                    <span class="saldo-label">
                        Pago realizado
                    </span>

                    <span class="saldo-valor">
                        ${{ number_format((float) ($totalPagado ?? 0), 2) }}
                    </span>

                </div>


                <div class="saldo-row">

                    <span class="saldo-label">
                        Saldo después de la operación
                    </span>

                    <span class="saldo-valor">
                        ${{ number_format((float) ($saldoDespues ?? 0), 2) }}
                    </span>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- OBSERVACIONES --}}
        {{-- ================================================= --}}

        @if($pagos->contains(function ($pago) {
            return !empty($pago->observaciones);
        }))

            <div class="seccion">

                <h2 class="seccion-titulo">
                    Observaciones
                </h2>

                @foreach($pagos as $pago)

                    @if(!empty($pago->observaciones))

                        <p class="valor">
                            {{ $pago->observaciones }}
                        </p>

                    @endif

                @endforeach

            </div>

        @endif

    </div>


    {{-- ================================================= --}}
    {{-- PIE --}}
    {{-- ================================================= --}}

    <div class="pie">

        <strong>TerraPago</strong>

        <br>

        Comprobante generado por el sistema.

        <br>

        Folio:
        {{ $operacionFolio ?? '—' }}

    </div>

</div>
```

</div>

</body>
</html>
