<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratoPdfController extends Controller
{
    public function generar($id)
    {
        $contrato = Contrato::with([
            'cliente',
            'terreno',
            'cuotas' => function ($query) {
                $query->orderBy('numero_cuota');
            },
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'contratos.pdf',
            [
                'contrato' => $contrato,
            ]
        );

        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream(
            'Contrato-' . $contrato->folio . '.pdf'
        );
    }
}