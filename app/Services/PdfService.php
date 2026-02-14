<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Services\GstCalculationService;

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

        // Calculate GST breakdown
        $gstBreakdown = $this->calculateGstBreakdown($items, $business, $customer);
        $data['gst_breakdown'] = $gstBreakdown;

        if ($isPreview) {
            $data = array_merge($data, [
                'document_number' => 'QUO-2526-0001',
                'document_date' => now()->format('d M Y'),
                'valid_until' => now()->addDays(30)->format('d M Y'),
                'subtotal' => 10000,
                'tax_amount' => 1800,
                'total_amount' => 11800,
            ]);
            // For preview, create a mock quotation object
            $data['quotation'] = (object) [
                'number' => $data['document_number'],
                'created_at' => \Carbon\Carbon::parse($data['document_date']),
                'valid_until' => \Carbon\Carbon::parse($data['valid_until']),
                'subtotal' => $data['subtotal'],
                'discount_amount' => $data['discount_amount'] ?? 0,
                'tax_amount' => $data['tax_amount'],
                'total' => $data['total_amount'],
            ];
            return PDF::loadView('pdfs.document', $data);
        } else {
            $data = array_merge($data, $documentData);

            if ($documentType === 'quotation') {
                $data['quotation'] = (object) [
                    'number' => $documentData['document_number'],
                    'created_at' => \Carbon\Carbon::parse($documentData['document_date']),
                    'valid_until' => \Carbon\Carbon::parse($documentData['valid_until']),
                    'subtotal' => $documentData['subtotal'],
                    'discount_amount' => $documentData['discount_amount'] ?? 0,
                    'tax_amount' => $documentData['tax_amount'],
                    'total' => $documentData['total_amount'],
                    'status' => $documentData['status'] ?? 'draft',
                ];
                return PDF::loadView('pdfs.quotation', $data);
            } elseif ($documentType === 'invoice') {
                $data['invoice'] = (object) [
                    'invoice_number' => $documentData['document_number'],
                    'issue_date' => \Carbon\Carbon::parse($documentData['document_date']),
                    'due_date' => isset($documentData['due_date']) ? \Carbon\Carbon::parse($documentData['due_date']) : null,
                    'subtotal' => $documentData['subtotal'],
                    'tax_amount' => $documentData['tax_amount'],
                    'total_amount' => $documentData['total_amount'],
                    'status' => $documentData['status'] ?? 'draft',
                ];
                return PDF::loadView('pdfs.invoice', $data);
            } else {
                return PDF::loadView('pdfs.document', $data);
            }
        }
    }

    private function calculateGstBreakdown($items, $business, $customer)
    {
        $totalCgst = 0;
        $totalSgst = 0;
        $totalIgst = 0;
        $gstType = 'unknown';

        $businessState = $business->business_state ?? $business->state;
        $customerState = $customer->state ?? null;

        foreach ($items as $item) {
            $taxableAmount = ($item->quantity * $item->unit_price) - ($item->discount_amount ?? 0);
            $gstRate = $item->tax_rate ?? 0;
            
            $gst = GstCalculationService::calculateGst($taxableAmount, $gstRate, $businessState, $customerState);
            
            $totalCgst += $gst['cgst_amount'];
            $totalSgst += $gst['sgst_amount'];
            $totalIgst += $gst['igst_amount'];
            $gstType = $gst['type'];
        }

        return [
            'type' => $gstType,
            'cgst' => $totalCgst,
            'sgst' => $totalSgst,
            'igst' => $totalIgst,
            'total' => $totalCgst + $totalSgst + $totalIgst
        ];
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