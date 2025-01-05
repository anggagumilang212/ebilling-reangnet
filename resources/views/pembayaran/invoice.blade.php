<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Print Struk</title>
    <style>
        @page {
            size: 58mm 297mm;
            margin: 0;

        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        body {
            width: 58mm;
            margin: 0;
            padding: 0;
            font-family: 'Courier New', monospace;
            font-size: 9px;
            line-height: 1.2;
            display: block;
            /* Mengubah ke block dari flex */
            color: #000;
            /* Hitam pekat */
            font-weight: 550;
            /* Tebalkan teks */

        }

        .receipt-wrapper {
            width: 58mm;
            margin: 0;
            padding: 0;
            text-align: center;
            /* Mengatur alignment center untuk wrapper */

        }

        .receipt {
            width: 48mm;
            display: inline-block;
            /* Mengubah ke inline-block */
            text-align: left;
            /* Mengembalikan alignment teks ke kiri */
            margin: 0 5mm;
            /* Margin kiri-kanan otomatis untuk centering */
            padding: 2mm 0;
            color: #000;
            /* Hitam pekat */
            font-weight: 550;
            /* Tebalkan teks */
        }

        .header {
            text-align: center;
            margin-bottom: 3mm;
            color: #000;
            /* Hitam pekat */
            font-weight: 550;
            /* Tebalkan teks */
        }

        .shop-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 1mm;
            text-align: center;
            color: #000;
            /* Hitam pekat */
            font-weight: 550;
            /* Tebalkan teks */
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 2mm 0;
            width: 100%;
        }

        .transaction-info {
            margin-bottom: 2mm;
        }

        .items {
            width: 100%;
        }

        .item {
            margin-bottom: 1mm;
        }

        .item-name {
            margin-bottom: 0.5mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            padding-left: 2mm;
        }

        .summary {
            margin-top: 2mm;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5mm;
        }

        .footer {
            margin-top: 3mm;
            text-align: center;
            font-size: 8px;
        }

        @media print {

            html,
            body {
                width: 58mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .receipt-wrapper {
                width: 58mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .receipt {
                width: 48mm !important;
                margin: 0 5mm !important;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-wrapper">
        <div class="receipt">
            <div class="header">
                <div class="shop-name">REANG NET</div>
                <div>Jl. MT Haryono, Sindang, Kec. Sindang, Kabupaten Indramayu, Jawa Barat 45222</div>
                <div>087828496000</div>
            </div>

            <div class="divider"></div>

            <div class="transaction-info">
                <div style="display: flex; justify-content: space-between;">
                    <div>Tanggal: {{ \Carbon\Carbon::parse($pembayaran->tgl_pembayaran)->format('d M, Y') }}
                        {{ \Carbon\Carbon::parse($pembayaran->created_at)->format('H:i') }}</div>
                </div>
                <div class="divider"></div>
                <div style="display: flex; justify-content: space-between;">
                    <div>Pelanggan: {{ $pembayaran->pelanggan->nama ?? '' }}</div>
                </div>
                <div>Alamat: {{ $pembayaran->pelanggan->alamat ?? '' }}</div>
            </div>

            <div class="divider"></div>

            <div class="items">
                <div class="item">
                    <div class="item-name">Pembayaran Wifi {{ $pembayaran->periode }}</div>
                    <div class="item-detail">
                        <div>Paket: {{ $pembayaran->pelanggan->package->nama ?? '' }}</div>

                    </div>
                    <div class="item-detail">

                        <div>Harga: Rp{{ number_format($pembayaran->pelanggan->package->harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="summary">
                <div class="summary-item">
                    <div>Total Pembayaran</div>
                    <div>Rp {{ number_format($pembayaran->pelanggan->package->harga, 0, ',', '.') }}</div>
                </div>
                <div class="summary-item">
                    <div>Metode Pembayaran</div>
                    <div>{{ $pembayaran->metode }}</div>
                </div>
                <div class="summary-item">
                    <div>Status</div>
                    <div>{{ $pembayaran->status }}</div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="footer">
                <p>Terimakasih telah membayar wifi Reang Net</p>
                <p>Untuk informasi lebih lanjut kunjungi:</p>
                <p>reang.net</p>
            </div>
        </div>
    </div>
    {{-- <script>
        window.print();
    </script> --}}
</body>

</html>
