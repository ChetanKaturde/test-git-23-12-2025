<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transactions Report</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #222;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #1a3d7c;
        }

        .report-title {
            font-size: 26px;
            font-weight: bold;
            color: #1a3d7c;
            margin-bottom: 15px;
        }

        .report-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f0f4f8;
            border-radius: 5px;
        }

        .meta-item {
            font-size: 13px;
            color: #444;
        }

        .meta-label {
            font-weight: bold;
            color: #000;
        }

        .summary-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f7f9fc;
            border-radius: 5px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a3d7c;
            margin-bottom: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .summary-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .summary-value {
            font-size: 22px;
            font-weight: bold;
            color: #1a3d7c;
            display: block;
        }

        .summary-label {
            font-size: 12px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-container {
            margin-top: 25px;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background: #1a3d7c;
            color: white;
        }

        th {
            padding: 15px 10px;
            font-size: 14px;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255,255,255,0.2);
        }

        th:last-child {
            border-right: none;
        }

        tbody tr {
            border-bottom: 1px solid #e0e6f1;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        td {
            padding: 12px 10px;
            font-size: 13px;
            border-right: 1px solid #e0e6f1;
            vertical-align: middle;
            color: #222;
        }

        td:last-child {
            border-right: none;
        }

        .transaction-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 18px; /* Extra larger for Transaction ID */
            color: #1a3d7c;
        }

        .material-name {
            font-weight: 600;
            color: #000;
        }

        .transaction-type {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .type-in {
            background-color: #d4edda;
            color: #155724;
        }

        .type-out {
            background-color: #f8d7da;
            color: #721c24;
        }

        .type-transfer {
            background-color: #fff3cd;
            color: #856404;
        }

        .quantity, .weight {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        .currency {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .date {
            font-family: 'Courier New', monospace;
            color: #666;
        }

        .footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #e0e6f1;
            text-align: center;
            font-size: 12px;
            color: #555;
        }

        .no-data {
            text-align: center;
            padding: 50px;
            color: #666;
            font-style: italic;
            font-size: 16px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .table-container {
                page-break-inside: avoid;
            }
            thead {
                display: table-header-group;
            }
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="report-title">Transactions Report</div>
    </div>

    <div class="report-meta">
        <div class="meta-item">
            <span class="meta-label">Generated:</span> {{ date('F j, Y \a\t g:i A') }}
        </div>
        <div class="meta-item">
            <span class="meta-label">Report ID:</span> RPT-{{ date('Ymd-His') }}
        </div>
        <div class="meta-item">
            <span class="meta-label">Total Records:</span> {{ count($transactions) }}
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Material</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Weight (kg)</th>
                    <th>Unit Price</th>
                    <th>Total Value</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="transaction-id">{{ $transaction->transaction_id }}</td>
                        <td class="material-name">{{ $transaction->batch->material->name ?? 'N/A' }}</td>
                        <td>
                            <span class="transaction-type type-{{ $transaction->type }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td class="quantity">{{ number_format($transaction->quantity) }}</td>
                        <td class="weight">{{ number_format($transaction->weight, 2) }}</td>
                        <td class="currency">
                            @if($transaction->unit_price)
                                ${{ number_format($transaction->unit_price, 2) }}
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td class="currency">
                            @if($transaction->total_value)
                                ${{ number_format($transaction->total_value, 2) }}
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td class="date">{{ $transaction->transaction_date->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="no-data">
                            No transactions found for the selected criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
  <!-- Summary after table -->
<div style="margin-top: 30px; text-align: right; font-size: 17px; color: #1a3d7c;">
    <p><strong>Total Weight:</strong> {{ number_format($transactions->sum('weight'), 2) }} kg</p>
    <p><strong>Total Value:</strong> ${{ number_format($transactions->sum('total_value'), 2) }}</p>
</div>

    <div class="footer">
        <p>This report was generated automatically by the Transaction Management System.</p>
        <p>For any queries, please contact the system administrator.</p>
    </div>
</body>
</html>
