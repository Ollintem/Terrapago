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
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            font-size: 10px;
            line-height: 1.45;
        }

        .header {
            width: 100%;
            border-bottom: 3px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            color: #047857;
        }

        .logo span {
            color: #0f172a;
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
            color: #64748b;
            font-size: 9px;
            margin-top: 3px;
        }

        .section {
            margin-top: 16px;
            margin-bottom: 8px;
            padding: 7px 9px;
            background: #ecfdf5;
            border-left: 4px solid #059669;
            font-size: 11px;
            font-weight: bold;
            color: #065f46;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .info-table td {
            width: 50%;
            padding: 5px 7px;
            border: 1px solid #e2e8f0;
        }

        .label {
            display: block;
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .value {
            color: #0f172a;
            font-size: 10px;
            font-weight: bold;
        }

        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .financial-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: center;
        }

        .financial-label {
            display: block;
            font-size: 8px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .financial-value {
            display: block;
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }

        .table-amortizacion {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8px;
        }

        .table-amortizacion th {
            background: #0f172a;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-size: 7.5px;
        }

        .table-amortizacion td {
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
            text-align: center;
        }

        .table-amortizacion tr:nth-child(even) {
            background: #f8fafc;
        }

        .estado {
            font-weight: bold;
            font-size: 7px;
        }

        .estado-pendiente {
            color: #b45309;
        }

        .estado-pagada {
            color: #047857;
        }

        .estado-vencida {
            color: #dc2626;
        }

        .conditions {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            font-size: 8.5px;
            text-align: justify;
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
            padding-top: 6px;
            font-size: 9px;
        }

        .signature-name {
            font-weight: bold;
            color: #0f172a;
        }

        .signature-description {
            color: #64748b;
            font-size: 8px;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7px;
            color: #94a3b8;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>

    {{-- =========================================================
         ENCABEZADO
    ========================================================== --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="logo">
                        Terra<span>Pago</span>
                    </div>

                    <div class="subtitle">
                        Gestión financiera y control de pagos
                    </div>
                </td>

                <td>
                    <div class="document-title">
                        CONTRATO DE VENTA
                    </div>

                    <div class="folio">
                        Folio: {{ $contrato->folio }}
                    </div>
                </td>
            </tr>
        </table>
    </div>


    {{-- =========================================================
         INFORMACIÓN DEL CONTRATO
    ========================================================== --}}
    <div class="section">
        Información del contrato
    </div>

    <table class="info-table">
        <tr>
            <td>
                <span class="label">Folio del contrato</span>
                <span class="value">
                    {{ $contrato->folio }}
                </span>
            </td>

            <td>
                <span class="label">Fecha de inicio</span>
                <span class="value">
                    {{ $contrato->fecha_inicio?->format('d/m/Y') ?? '—' }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Frecuencia de pago</span>
                <span class="value">
                    {{ $contrato->frecuencia_pago }}
                </span>
            </td>

            <td>
                <span class="label">Número de cuotas</span>
                <span class="value">
                    {{ $contrato->numero_cuotas }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Estado del contrato</span>
                <span class="value">
                    {{ $contrato->estado }}
                </span>
            </td>

            <td>
                <span class="label">Documento generado</span>
                <span class="value">
                    {{ now()->format('d/m/Y H:i') }}
                </span>
            </td>
        </tr>
    </table>


    {{-- =========================================================
         CLIENTE
    ========================================================== --}}
    <div class="section">
        Datos del cliente
    </div>

    <table class="info-table">
        <tr>
            <td>
                <span class="label">Nombre completo</span>

                <span class="value">
                    {{ $contrato->cliente->nombre ?? '' }}
                    {{ $contrato->cliente->apellidos ?? '' }}
                </span>
            </td>

            <td>
                <span class="label">Teléfono</span>

                <span class="value">
                    {{ $contrato->cliente->telefono ?? '—' }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Correo electrónico</span>

                <span class="value">
                    {{ $contrato->cliente->email ?? '—' }}
                </span>
            </td>

            <td>
                <span class="label">RFC / CURP</span>

                <span class="value">
                    {{ $contrato->cliente->rfc ?? $contrato->cliente->curp ?? '—' }}
                </span>
            </td>
        </tr>
    </table>


    {{-- =========================================================
         TERRENO
    ========================================================== --}}
    <div class="section">
        Datos del terreno
    </div>

    <table class="info-table">
        <tr>
            <td>
                <span class="label">Manzana</span>

                <span class="value">
                    {{ $contrato->terreno->manzana ?? '—' }}
                </span>
            </td>

            <td>
                <span class="label">Lote</span>

                <span class="value">
                    {{ $contrato->terreno->lote ?? '—' }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Superficie</span>

                <span class="value">
                    {{ $contrato->terreno->superficie ?? '—' }} m²
                </span>
            </td>

            <td>
                <span class="label">Estado del terreno</span>

                <span class="value">
                    {{ $contrato->terreno->estado ?? '—' }}
                </span>
            </td>
        </tr>
    </table>


    {{-- =========================================================
         INFORMACIÓN FINANCIERA
    ========================================================== --}}
    <div class="section">
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
                    Saldo financiado
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


    {{-- =========================================================
         TABLA DE AMORTIZACIÓN
    ========================================================== --}}
    <div class="section">
        Tabla de amortización
    </div>

    <table class="table-amortizacion">
        <thead>
            <tr>
                <th>
                    Cuota
                </th>

                <th>
                    Vencimiento
                </th>

                <th>
                    Monto
                </th>

                <th>
                    Pagado
                </th>

                <th>
                    Saldo anterior
                </th>

                <th>
                    Abono capital
                </th>

                <th>
                    Saldo restante
                </th>

                <th>
                    Estado
                </th>
            </tr>
        </thead>

        <tbody>

            @forelse($contrato->cuotas as $cuota)

                <tr>
                    <td>
                        {{ $cuota->numero_cuota }}
                    </td>

                    <td>
                        {{ $cuota->fecha_vencimiento?->format('d/m/Y') ?? '—' }}
                    </td>

                    <td>
                        ${{ number_format($cuota->monto, 2) }}
                    </td>

                    <td>
                        ${{ number_format($cuota->monto_pagado, 2) }}
                    </td>

                    <td>
                        ${{ number_format($cuota->saldo_anterior, 2) }}
                    </td>

                    <td>
                        ${{ number_format($cuota->abono_capital, 2) }}
                    </td>

                    <td>
                        ${{ number_format($cuota->saldo_restante, 2) }}
                    </td>

                    <td>

                        @if($cuota->estado === 'PAGADA')

                            <span class="estado estado-pagada">
                                PAGADA
                            </span>

                        @elseif($cuota->estado === 'VENCIDA')

                            <span class="estado estado-vencida">
                                VENCIDA
                            </span>

                        @else

                            <span class="estado estado-pendiente">
                                PENDIENTE
                            </span>

                        @endif

                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="8">
                        No hay cuotas registradas.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>


    {{-- =========================================================
         CONDICIONES
    ========================================================== --}}
    <div class="conditions">

        <strong>Condiciones del contrato:</strong>

        El presente documento contiene la información financiera
        correspondiente al contrato {{ $contrato->folio }} y su
        respectiva tabla de amortización.

        El cliente se compromete a realizar los pagos establecidos
        de acuerdo con la frecuencia y fechas de vencimiento
        indicadas en la tabla anterior.

        Los pagos realizados serán registrados en el sistema
        TerraPago y aplicados al saldo correspondiente del contrato.

        La información contenida en este documento corresponde a
        los datos registrados en el sistema al momento de su
        generación.

    </div>


    {{-- =========================================================
         FIRMAS
    ========================================================== --}}
    <table class="signatures">
        <tr>

            <td>
                <div class="signature-line">

                    <div class="signature-name">
                        {{ $contrato->cliente->nombre ?? '' }}
                        {{ $contrato->cliente->apellidos ?? '' }}
                    </div>

                    <div class="signature-description">
                        Firma del cliente
                    </div>

                </div>
            </td>

            <td>
                <div class="signature-line">

                    <div class="signature-name">
                        TerraPago
                    </div>

                    <div class="signature-description">
                        Representante / Administración
                    </div>

                </div>
            </td>

        </tr>
    </table>


    {{-- =========================================================
         PIE DE PÁGINA
    ========================================================== --}}
    <div class="footer">

        TerraPago · Documento generado automáticamente ·

        Página <span class="page-number"></span>

    </div>

</body>
</html>