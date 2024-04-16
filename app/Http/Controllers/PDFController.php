<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PDFController extends Controller
{
    // public function generatePDF()
    // {
    //     // Load view data
    //     $data = [
    //         'content' => 'Hello, this is a PDF document.'
    //     ];

    //     // Generate PDF
    //     $pdf = PDF::loadView('pdf.stamp', $data);

    //     // Add stamp
    //     $pdf->getDomPDF()->getCanvas()->page_text(50, 50, 'Confidential', null, 24, array(255,0,0));

    //     // Output PDF
    //     return $pdf->stream('example.pdf');
    // }
    public function generatePDF(Request $request)
    {
        // Generate PDF
        $pdf = PDF::loadView('pdf.stamp');

        // Add stamp
        $pdf->getDomPDF()->getCanvas()->page_text(50, 50, 'Confidential', null, 24, array(255,0,0));

        // Output PDF
        return $pdf->stream('example.pdf');
    }
}
