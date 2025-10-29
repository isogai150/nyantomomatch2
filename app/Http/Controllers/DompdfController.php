<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pair;
use Barryvdh\DomPDF\Facade\Pdf;

class DompdfController extends Controller
{
public function downloadContract(Pair $pair)
{
    $pair->load(['post', 'userA', 'userB']);

    $pdf = Pdf::loadView('pdf.contract', [
        'pair' => $pair,
    ]);

    $fileName = 'contract_' . $pair->id . '.pdf';

    return $pdf->download($fileName);
}

}
