@extends('layouts.layouts', ['menu' => 'transaksi', 'submenu' => 'pembayaran'])

@section('title', 'Data Pembayaran')

@section('content')
    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-primary-gradient">
                <div class="page-inner py-5">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="text-white pb-2 fw-bold">@yield('title')</h2>
                            <h5 class="text-white op-7 mb-2">Total Pembayaran : {{ $pembayaran->count() }} </h5>
                        </div>
                        <div class="ml-md-auto py-2 py-md-0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-inner mt--5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">

                            </div>
                        </div>
                        <div class="card-body">



                            <!-- Modal Edit -->
                            <div class="modal fade" id="editRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header no-bd">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold">Edit</span>
                                                <span class="fw-light">Router</span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editRouterForm" method="POST">
                                                @csrf

                                                <input type="hidden" name="id" id="editRouterId">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Nama Router</label>
                                                            <input name="name" type="text" id="editRouterName"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>IP Address</label>
                                                            <input name="ip" type="text" id="editRouterIp"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Username</label>
                                                            <input name="username" type="text" id="editRouterUsername"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Password</label>
                                                            <input name="password" type="text" id="editRouterPassword"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer no-bd">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <form action="{{ route('pembayaran.filter') }}" method="GET"
                                    class="d-flex gap-2 align-items-end">
                                    <div class="col-auto">
                                        <label for="status" class="form-label small mb-1">Status</label>
                                        <select id="status" name="status" class="form-control form-control-sm"
                                            style="width: 150px;">
                                            <option value="">Semua Status</option>
                                            <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>
                                                Lunas
                                            </option>
                                            <option value="Belum Lunas"
                                                {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <label for="periode" class="form-label small mb-1">Periode</label>
                                        <input type="text" id="periode" name="periode"
                                            class="form-control form-control-sm" style="width: 150px;" required
                                            value="{{ request('periode') }}">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-filter me-1"></i>Filter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>




                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Alamat</th>
                                        <th>Periode</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Paket</th>
                                        <th>Jumlah</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Alamat</th>
                                        <th>Periode</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Paket</th>
                                        <th>Jumlah</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($pembayaran as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->pelanggan->nama ?? '' }}</td>
                                            <td>{{ $item->pelanggan->alamat ?? '' }}</td>
                                            <td>{{ $item->periode ?? 'Tidak ada periode' }}</td>
                                            <td>{{ $item->tgl_pembayaran ? \Carbon\Carbon::parse($item->tgl_pembayaran)->format('d M Y') : 'Belum ada tanggal' }}
                                            </td>
                                            <td>{{ $item->pelanggan->package->nama ?? 'Tidak ada paket' }}</td>
                                            <td>{{ $item->jumlah ? number_format($item->jumlah, 0, ',', '.') : '0' }}</td>
                                            <td>{{ $item->metode ?? '' }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->status == 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                                                    {{ $item->status ?? 'Tidak ada status' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center gap-2" style="max-width: 150px;">

                                                    @if ($item->bukti)
                                                        <a href="{{ asset($item->bukti) }}"
                                                            class="btn btn-sm btn-light download-btn"
                                                            download="{{ basename($item->bukti) }}"
                                                            onclick="downloadFile(this.href, '{{ basename($item->bukti) }}'); return false;">
                                                            <i class="fas fa-download me-1"></i>
                                                            <span class="d-none d-sm-inline">Download</span>
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">No file</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-button-action">

                                                    @if ($item->status == 'Lunas')
                                                        <a href="{{ route('pembayaran.invoice', $item->id) }}"
                                                            class="btn btn-link btn-warning btn-lg" data-toggle="tooltip"
                                                            data-original-title="Print Invoice" target="_blank">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    @endif
                                                    {{-- <a href="javascript:void(0)"
                                                        class="btn btn-link btn-primary btn-lg btn-edit-router"
                                                        data-original-title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a> --}}

                                                    <a href="{{ route('pembayaran.delete', $item->id) }}"
                                                        class="btn btn-link btn-danger btn-lg" data-toggle="tooltip"
                                                        data-original-title="Hapus"
                                                        onclick="return confirm('Apakah anda yakin menghapus Pembayaran {{ $item->pelanggan->nama ?? '' }} Periode {{ $item->periode ?? '' }} ?')">
                                                        <i class="fa fa-times"></i>
                                                    </a>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
        var datepicker = $('#periode').datepicker({
            format: "MM yyyy",
            minViewMode: 1,
            autoclose: true,
            todayHighlight: true,
            startDate: new Date() // Set tanggal mulai ke hari ini
        });

        // Set nilai default
        // datepicker.datepicker('setDate', new Date());
    </script>

    @if (session('print_pembayaran_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Buka halaman print
                var printWindow = window.open('{{ route('pembayaran.invoice', session('print_pembayaran_id')) }}');

                // Otomatis cetak
                printWindow.onload = function() {
                    printWindow.print();
                    // Opsional: Tutup window setelah print
                    // printWindow.close();
                };
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.btn-edit-router').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const ip = $(this).data('ip');
                const username = $(this).data('username');
                const password = $(this).data('password');
                const actionUrl = "{{ route('router.update', ':id') }}".replace(':id', id);

                // Set data ke modal
                $('#editRouterId').val(id);
                $('#editRouterName').val(name);
                $('#editRouterIp').val(ip);
                $('#editRouterUsername').val(username);
                $('#editRouterPassword').val(password);

                // Set action form
                $('#editRouterForm').attr('action', actionUrl);

                // Buka modal
                $('#editRowModal').modal('show');
            });
        });
    </script>

@endsection
