<?php

namespace App\Http\Controllers;

use App\Models\tblGIN;
use App\Models\tblGRN;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentPreviewController extends Controller
{
    public function grnPreview($payload)
    {

        $parsedPayload = base64_decode($payload);
        if (empty($parsedPayload)) {
            return view('error.documentnotfound');
        }

        $grn = tblGRN::with(['actor', 'order', 'warehouse.fees', 'commodity'])->where('grnidno', $parsedPayload)->first();

        if (empty($grn)) {
            return view('error.documentnotfound');
        }

        info(json_encode($grn));
        $pdf = Pdf::loadView('documents.grn', [
            'grn' => $grn
        ]);
        return $pdf->stream();
    }


    public function ginPreview($payload)
    {

        $parsedPayload = base64_decode($payload);
        if (empty($parsedPayload)) {
            return view('error.documentnotfound');
        }

        $gin = tblGIN::with(['actor', 'order', 'warehouse.fees', 'commodity'])->where('ginidno', "2024-TV6MU")->first();
        if (empty($gin)) {
            return view('error.documentnotfound');
        }

        $pdf = Pdf::loadView('documents.gin', [
            'gin' => $gin
        ]);
        return $pdf->stream();
    }
}
