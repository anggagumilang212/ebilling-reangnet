<!DOCTYPE html>
<html>

<head>
    <title>Sales Report</title>
    <style>
        /* Reset default margins and set base font size */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            /* Diperkecil untuk hemat ruang */
            line-height: 1.2;
            /* Diperapat */
            color: #333;
            background: #fff;
        }

        /* A4 paper styling */
        @page {
            size: A4;
            margin: 5mm;
            /* Margin kertas diperkecil */
        }

        .container {
            width: 210mm;
            margin: 0 auto;
            padding: 10mm;
            /* Diperkecil padding */
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-info {
            margin-bottom: 10px;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8px;
            /* Diperkecil ukuran font tabel */
        }

        th,
        td {
            padding: 3px 5px;
            /* Diperkecil padding */
            border: 0.5px solid #ddd;
            /* Diperkecil border */
            text-align: left;
            white-space: nowrap;
            /* Mencegah line break */
            overflow: hidden;
            /* Sembunyikan overflow */
            text-overflow: ellipsis;
            /* Tambahkan elipsis jika terlalu panjang */
            max-width: 200px;
            /* Batasi lebar maksimal */
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .product-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: nowrap;
        }

        .product-list li {
            font-size: 8px;
            margin-right: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .totals {
            margin-top: 10px;
            text-align: right;
            font-size: 9px;
        }

        .badge {
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 7px;
        }

        @media print {
            body {
                zoom: 0.8;
                /* Perkecil ukuran keseluruhan dokumen */
            }

            .container {
                width: 100%;
                margin: 0;
                padding: 5mm;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('messages.sales_report') }}</h1>
        </div>

        <div class="report-info">
            <p><strong>{{ __('messages.period') }}:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d M, Y') }}
                -
                {{ \Carbon\Carbon::parse($end_date)->format('d M, Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.productname') }}</th>
                    <th>{{ __('messages.branches') }}</th>
                    <th>{{ __('messages.customer') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.totalamount') }}</th>
                    <th>{{ __('messages.paidamount') }}</th>
                    <th>{{ __('messages.dueamount') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</td>
                        <td>
                            <ul class="product-list">
                                @foreach ($sale->saleDetails as $detail)
                                    <li>{{ $detail->product->product_name ?? '' }} (x{{ $detail->quantity ?? '' }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $sale->branch->name }}</td>
                        <td>{{ $sale->customer_name }}</td>
                        <td>
                            <span
                                class="badge badge-{{ $sale->status == 'Pending' ? 'info' : ($sale->status == 'Shipped' ? 'primary' : 'success') }}">
                                {{ $sale->status }}
                            </span>
                        </td>
                        <td>{{ format_currency($sale->total_amount) }}</td>
                        <td>{{ format_currency($sale->paid_amount) }}</td>
                        <td>{{ format_currency($sale->due_amount) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">No Data Available!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="totals">
            <p><strong>Jumlah:</strong> {{ format_currency($totalSales) }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            loading_is = false;
            window.close();

        }
    </script>
</body>

</html>
