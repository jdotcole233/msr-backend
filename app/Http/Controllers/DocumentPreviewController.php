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

        $fees = collect($grn->warehouse->fees)->firstWhere('commodityType', $grn->commodity->commodityName);

        info("fees " . json_encode($fees));

        if (empty($grn)) {
            return view('error.documentnotfound');
        }

        info(json_encode($grn));
        $pdf = Pdf::loadView('documents.grn', [
            'grn' => $grn,
            'grn_id' => $parsedPayload,
            'fees' => $fees
        ]);
        return $pdf->stream();
    }


    public function ginPreview($payload)
    {

        $parsedPayload = base64_decode($payload);
        if (empty($parsedPayload)) {
            return view('error.documentnotfound');
        }


        // "2024-TV6MU"
        $gin = tblGIN::with(['actor', 'order', 'warehouse.fees', 'commodity'])->where('ginidno', $parsedPayload)->first();
        if (empty($gin)) {
            return view('error.documentnotfound');
        }

        info("GIN " . json_encode($gin->order->grn));

        $pdf = Pdf::loadView('documents.gin', [
            'gin' => $gin
        ]);
        return $pdf->stream();
    }
}
