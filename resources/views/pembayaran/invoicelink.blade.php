<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran | Reang Net</title>
    <link rel="stylesheet" href="{{ asset('template') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('template') }}/css/atlantis.min.css">
    <link rel="icon" href="{{ asset('img/reanglogo.png') }}" type="image/x-icon" />
    <style>
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 2px solid #ddd;
        }

        .invoice-header img {
            max-height: 70px;
        }

        .invoice-title {
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        .invoice-body {
            padding: 20px;
        }

        .table {
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-top: 2px solid #ddd;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="card shadow">
            <div class="invoice-header">
                <div>
                    <h4><strong>Reang Net</strong></h4>
                    <p>Jl. MT Haryono, Sindang, Kec. Sindang, Kabupaten Indramayu, Jawa Barat 45222</p>
                </div>
                <div>
                    <img src="{{ asset('img/reanglogo.png') }}" alt="Logo">
                </div>
            </div>
            <div class="invoice-title">
                <h2><strong>Invoice Pembayaran</strong></h2>
            </div>
            <div class="invoice-body">
                <p><strong>Nama Pelanggan:</strong> {{ $pembayaran->pelanggan->nama }}</p>
                <p><strong>Periode:</strong> {{ $pembayaran->periode }}</p>
                <p><strong>Tanggal Pembayaran:</strong>
                    {{ \Carbon\Carbon::parse($pembayaran->tgl_pembayaran)->format('d F Y') }}</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Paket Internet</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $pembayaran->pelanggan->package->nama }}</td>
                                <td>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p><strong>Status:</strong>
                    <span class="badge badge-success">{{ $pembayaran->status }}</span>
                </p>
            </div>
            {{-- <div class="footer">
                <p>&copy; {{ date('Y') }} Company Name. All rights reserved.</p>
            </div> --}}
        </div>
    </div>
</body>

</html>
