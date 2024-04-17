<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfWatermarker\Watermark\Pdf;
use Spatie\PdfWatermarker\Watermarker;
use Illuminate\Support\Facades\Storage;

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
        $pdf = Pdf::loadView('pdf.stamp');

        // Add stamp
        $pdf->getDomPDF()->getCanvas()->page_text(50, 50, 'Confidential', null, 24, array(255,0,0));

        // Output PDF
        return $pdf->stream('example.pdf');
    }

        public function download($id)
    {
        $pdf = Pdf::find($id);
        if (!$pdf) {
            return redirect()->back()->with('error', 'PDF not found.');
        }

        $filePath = storage_path('app/' . $pdf->file_path);

        return response()->download($filePath);
    }
        public function upload()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $user = auth()->user();

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('pdfs', $fileName);

        // Add watermark
        $watermarkText = $user->name;
        $watermarker = (new Watermarker())
            ->addWatermarkText($watermarkText)
            ->savePath(storage_path('app/' . $filePath));

        return redirect()->back()->with('success', 'PDF file uploaded successfully.');
    }
}
