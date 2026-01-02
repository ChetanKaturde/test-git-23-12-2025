<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PdfService
{
    public function generateDocumentPdf($business, $items = [], $customer = null, $isPreview = false, $documentType = 'quotation', $documentData = [])
    {
        $data = [
            'business' => $business,
            'items' => $items,
            'customer' => $customer,
            'is_preview' => $isPreview,
            'document_type' => $documentType,
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
        } else {
            $data = array_merge($data, $documentData);
        }

        return PDF::loadView('pdfs.document', $data);
    }

    public function generateReceiptPdf($payment, $invoice, $business, $customer)
    {
        $data = [
            'payment' => $payment,
            'invoice' => $invoice,
            'business' => $business,
            'customer' => $customer,
        ];

        return PDF::loadView('pdfs.receipt', $data);
    }
}