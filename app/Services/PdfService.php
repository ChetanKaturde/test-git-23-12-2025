<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PdfService
{
    public function generateDocumentPdf($business, $items = [], $customer = null, $isPreview = false, $documentType = 'quotation', $documentData = [], $document = null)
    {
        $data = [
            'business' => $business,
            'items' => $items,
            'customer' => $customer,
            'is_preview' => $isPreview,
            'document_type' => $documentType,
            'document' => $document,
        ];

        if ($isPreview) {
            $data = array_merge($data, [
                'document_number' => 'QUO-2526-0001',
                'document_date' => now()->format('d M Y'),
                'valid_until' => now()->addDays(30)->format('d M Y'),
                'subtotal' => 10000,
                'tax_amount' => 1800,
                'total_amount' => 11800,
            ]);
            // Create a mock document object for preview
            $data['document'] = (object) [
                'pdf_options' => ['show_list_price' => true, 'show_discount' => true, 'show_hsn' => true, 'show_tax_breakdown' => true],
                'subtotal' => 10000,
                'tax_amount' => 1800,
            ];
        } else {
            $data = array_merge($data, $documentData);
        }

        return PDF::loadView('pdfs.document', $data);
    }
}