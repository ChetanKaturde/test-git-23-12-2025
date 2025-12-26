<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Barcodes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 40px 20px 20px 20px; /* Increased top margin */
            font-family: Arial, sans-serif;
            font-size: 16px; /* Increased base font size */
        }

        .container {
            width: 100%;
            margin: auto;
        }

        .text-center {
            text-align: center;
        }

        .mb-5 {
            margin-bottom: 3rem;
        }

        .mt-2 {
            margin-top: 0.75rem;
        }

        .mt-4 {
            margin-top: 2rem;
        }

        h2 {
            font-size: 24px; /* Larger heading */
        }

        p, code {
            font-size: 18px;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                margin: 40px 20px 20px 20px; /* Ensure top margin remains in print */
            }

            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">

       @foreach($analyses as $analysis)
    <div class="text-center mb-5 page-break">
        <h2>{{ $analysis->product_name ?? 'Product' }}</h2>
        <p>Category: {{ $analysis->product_category ?? 'N/A' }}</p>
        <p>SKU: {{ $analysis->sku_id ?? 'N/A' }}</p>
        <p>Quantity: {{ $analysis->quantity ?? 'N/A' }}</p>

        <svg class="barcode" data-code="{{ $analysis->barcode }}"></svg>

        <div class="mt-2">
            <code>{{ $analysis->barcode ?? 'N/A' }}</code>
        </div>
    </div>
@endforeach


    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.barcode').forEach(function (el) {
                const code = el.dataset.code;
                if (code) {
                    JsBarcode(el, code, {
                        format: "CODE128",
                        width: 2,
                        height: 60,
                        displayValue: false,
                        margin: 10
                    });
                }
            });

            window.print();
            window.onafterprint = function () {
                window.close();
            };
        });
    </script>
</body>
</html>
