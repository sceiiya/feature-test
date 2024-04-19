<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use FilippoToso\PdfWatermarker\Support\Pdf;
use FilippoToso\PdfWatermarker\PdfWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;
use FilippoToso\PdfWatermarker\Watermarks\ImageWatermark;
use FilippoToso\PdfWatermarker\Facades\TextWatermarker;

class PDFController extends Controller
{

    public function ping(Request $req)
    {
        return response($req);
    }

    public function stamp(Request $request)
    {


        return TextWatermarker::input('input.pdf')
            ->output('laravel-text.pdf')
            ->position(Position::BOTTOM_CENTER, -50, -10)
            ->asBackground()
            ->pageRange(3, 4)
            ->text('Copyrighted')
            ->angle(25)
            ->font('arial.ttf')
            ->size('25')
            ->color('#CC00007F')
            ->resolution(300) // 300 dpi
            ->save()
            ->download();

    }
    
    public function uploadView (Request $request)
    {
        return view('pdf.stamp');
    }
    
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('pdfs', $fileName);

        return response()->json(['filePath' => $filePath]);
    }

    public function retrieve(Request $request)
    {
        $filePath = $request->input('filePath');
    
        // Retrieve the contents of the PDF file
        $pdfContents = Storage::get($filePath);
    
        // Now you can do whatever you want with the PDF contents
        // For example, you can return the contents as a response to download the file
        return response($pdfContents, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="example.pdf"'
        ]);
    }
    
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


    //     public function upload()
    // {
    //     return view('upload');
    // }

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
