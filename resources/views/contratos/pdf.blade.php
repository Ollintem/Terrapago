<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <title>Contrato {{ $contrato->folio }}</title>

    <style>
        @page {
            margin: 35px 40px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            color: #1f2937;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .header {
            width: 100%;
            border-bottom: 3px solid #0f766e;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0f766e;
            letter-spacing: 1px;
        }

        .subtitle {
            color: #64748b;
            font-size: 9px;
            margin-top: 3px;
        }

        .document-title {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }

        .folio {
            text-align: right;
            margin-top: 5px;
            color: #475569;
            font-size: 9px;
        }

        .section {
            margin-top: 15px;
            margin-bottom: 12px;
        }

        .section-title {
            background: #0f766e;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 7px 9px;
            margin-bottom: 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 7px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .label {
            width: 24%;
            background: #f8fafc;
            color: #475569;
            font-weight: bold;
        }

        .value {
            color: #1e293b;
        }

        .financial-table {
            width: 100%;
            border-collapse: collapse;
        }

        .financial-table td {
            width: 25%;
            padding: 8px;
            border: 1px solid #e2e8f0;
        }

        .financial-label {
            display: block;
            font-size: 8px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .financial-value {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
        }

        .amortization-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 8.5px;
        }

        .amortization-table th {
            background: #0f766e;
            color: white;
            padding: 7px 5px;
            border: 1px solid #0f766e;
            text-align: center;
            font-weight: bold;
        }

        .amortization-table td {
            padding: 6px 5px;
            border: 1px solid #cbd5e1;
            text-align: center;
        }

        .amortization-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .money {
            text-align: right !important;
            white-space: nowrap;
        }

        .conditions {
            font-size: 8.5px;
            line-height: 1.5;
            color: #475569;
            text-align: justify;
        }

        .conditions p {
            margin: 0 0 6px 0;
        }

        .signatures {
            width: 100%;
            border-collapse: collapse;
            margin-top: 55px;
        }

        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 25px;
        }

        .signature-line {
            border-top: 1px solid #334155;
            padding-top: 7px;
            margin-top: 35px;
            font-size: 9px;
            color: #334155;
        }

        .footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5px;
            color: #94a3b8;
        }

        .page-number:after {
            content: counter(page);
        }

        .small-note {
            font-size: 8px;
            color: #64748b;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    {{-- ENCABEZADO --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 55%;">
                    <div class="logo">TerraPago</div>

                    <div class="subtitle">
                        Gestión financiera y seguimiento de pagos
                    </div>
                </td>

                <td style="width: 45%;">
                    <div class="document-title">
                        CONTRATO DE COMPRA
                    </div>

                    <div class="folio">
                        Folio: <strong>{{ $contrato->folio }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>


    {{-- INFORMACIÓN DEL CONTRATO --}}
    <div class="section">

        <div class="section-title">
            Información del contrato
        </div>

        <table class="info-table">
            <tr>
                <td class="label">
                    Folio
                </td>

                <td class="value">
                    {{ $contrato->folio }}
                </td>

                <td class="label">
                    Fecha de inicio
                </td>

                <td class="value">
                    {{ optional($contrato->fecha_inicio)->format('d/m/Y') }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    Frecuencia de pago
                </td>

                <td class="value">
                    {{ $contrato->frecuencia_pago }}
                </td>

                <td class="label">
                    Número de cuotas
                </td>

                <td class="value">
                    {{ $contrato->numero_cuotas }}
                </td>
            </tr>
        </table>

    </div>


    {{-- INFORMACIÓN DEL CLIENTE --}}
    <div class="section">

        <div class="section-title">
            Información del cliente
        </div>

        <table class="info-table">

            <tr>
                <td class="label">
                    Nombre completo
                </td>

                <td class="value" colspan="3">
                    {{ $contrato->cliente->nombre ?? '' }}
                    {{ $contrato->cliente->apellidos ?? '' }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    Teléfono
                </td>

                <td class="value">
                    {{ $contrato->cliente->telefono ?? 'No registrado' }}
                </td>

                <td class="label">
                    Correo electrónico
                </td>

                <td class="value">
                    {{ $contrato->cliente->email ?? 'No registrado' }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    CURP
                </td>

                <td class="value">
                    {{ $contrato->cliente->curp ?? 'No registrado' }}
                </td>

                <td class="label">
                    RFC
                </td>

                <td class="value">
                    {{ $contrato->cliente->rfc ?? 'No registrado' }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    Dirección
                </td>

                <td class="value" colspan="3">
                    {{ $contrato->cliente->direccion ?? 'No registrada' }}
                </td>
            </tr>

        </table>

    </div>


    {{-- INFORMACIÓN DEL TERRENO --}}
    <div class="section">

        <div class="section-title">
            Información del terreno
        </div>

        <table class="info-table">

            <tr>
                <td class="label">
                    Manzana
                </td>

                <td class="value">
                    {{ $contrato->terreno->manzana ?? 'N/A' }}
                </td>

                <td class="label">
                    Lote
                </td>

                <td class="value">
                    {{ $contrato->terreno->lote ?? 'N/A' }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    Superficie
                </td>

                <td class="value">
                    {{ $contrato->terreno->superficie ?? 'N/A' }} m²
                </td>

                <td class="label">
                    Ubicación
                </td>

                <td class="value">
                    {{ $contrato->terreno->ubicacion ?? 'N/A' }}
                </td>
            </tr>

        </table>

    </div>


    {{-- CONDICIONES FINANCIERAS --}}
    <div class="section">

        <div class="section-title">
            Condiciones financieras
        </div>

        <table class="financial-table">

            <tr>

                <td>
                    <span class="financial-label">
                        Precio total
                    </span>

                    <span class="financial-value">
                        ${{ number_format($contrato->precio_total, 2) }}
                    </span>
                </td>

                <td>
                    <span class="financial-label">
                        Enganche
                    </span>

                    <span class="financial-value">
                        ${{ number_format($contrato->enganche, 2) }}
                    </span>
                </td>

                <td>
                    <span class="financial-label">
                        Saldo inicial
                    </span>

                    <span class="financial-value">
                        ${{ number_format($contrato->saldo_inicial, 2) }}
                    </span>
                </td>

                <td>
                    <span class="financial-label">
                        Saldo actual
                    </span>

                    <span class="financial-value">
                        ${{ number_format($contrato->saldo_actual, 2) }}
                    </span>
                </td>

            </tr>

        </table>

    </div>


    {{-- TABLA DE AMORTIZACIÓN --}}
    <div class="section">

        <div class="section-title">
            Tabla de amortización
        </div>

        <table class="amortization-table">

            <thead>
                <tr>
                    <th style="width: 10%;">
                        Cuota
                    </th>

                    <th style="width: 20%;">
                        Vencimiento
                    </th>

                    <th style="width: 22%;">
                        Monto
                    </th>

                    <th style="width: 24%;">
                        Saldo anterior
                    </th>

                    <th style="width: 24%;">
                        Saldo restante
                    </th>
                </tr>
            </thead>

            <tbody>

                @foreach($contrato->cuotas as $cuota)

                    <tr>

                        <td>
                            {{ $cuota->numero_cuota }}
                        </td>

                        <td>
                            {{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}
                        </td>

                        <td class="money">
                            ${{ number_format($cuota->monto, 2) }}
                        </td>

                        <td class="money">
                            ${{ number_format($cuota->saldo_anterior, 2) }}
                        </td>

                        <td class="money">
                            ${{ number_format($cuota->saldo_restante, 2) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div class="small-note">
            La tabla representa el calendario de pagos establecido al momento
            de formalizar el contrato.
        </div>

    </div>


    {{-- CONDICIONES --}}
    <div class="section">

        <div class="section-title">
            Condiciones
        </div>

        <div class="conditions">

            <p>
                El presente documento representa el acuerdo de compra y
                financiamiento celebrado entre el cliente y la administración
                correspondiente al terreno identificado en este contrato.
            </p>

            <p>
                El cliente se compromete a cubrir los pagos establecidos en la
                tabla de amortización de acuerdo con la frecuencia y fechas
                indicadas.
            </p>

            <p>
                Los pagos realizados posteriormente a la formalización del
                contrato serán registrados en el sistema TerraPago y podrán
                generar un comprobante correspondiente.
            </p>

            <p>
                Cualquier modificación, cancelación o liquidación del contrato
                deberá quedar registrada en el sistema de acuerdo con las
                condiciones administrativas correspondientes.
            </p>

        </div>

    </div>


    {{-- FIRMAS --}}
    <table class="signatures">

        <tr>

            <td>

                <div class="signature-line">
                    Firma del cliente
                </div>

                <div style="margin-top: 5px;">
                    {{ $contrato->cliente->nombre ?? '' }}
                    {{ $contrato->cliente->apellidos ?? '' }}
                </div>

            </td>

            <td>

                <div class="signature-line">
                    Firma de la administración
                </div>

                <div style="margin-top: 5px;">
                    TerraPago
                </div>

            </td>

        </tr>

    </table>


    {{-- PIE DE PÁGINA --}}
    <div class="footer">

        TerraPago · Contrato {{ $contrato->folio }}

        &nbsp; | &nbsp;

        Página <span class="page-number"></span>

    </div>

</body>
</html>