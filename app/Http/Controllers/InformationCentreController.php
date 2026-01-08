<?php

namespace App\Http\Controllers;

use App\Models\InformationCentre;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InformationCentreController extends Controller
{
    public function download($id)
    {
        $document = InformationCentre::findOrFail($id);
        
        // Generate PDF dynamically using DomPDF
        $pdf = Pdf::loadHTML($this->generatePdfContent($document));
        
        // Download using filename
        return $pdf->download($document->filename);
    }
    
    /**
     * Generate PDF content
     */
    private function generatePdfContent($document)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 40px;
                    line-height: 1.6;
                }
                h1 {
                    color: #333;
                    border-bottom: 2px solid #007bff;
                    padding-bottom: 10px;
                }
                .info {
                    margin-top: 30px;
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                }
            </style>
        </head>
        <body>
            <h1>{$document->subtitle}</h1>
            
            <h2>Document Content</h2>
            <p>This is a student document.</p>
        </body>
        </html>
        HTML;
    }
}
