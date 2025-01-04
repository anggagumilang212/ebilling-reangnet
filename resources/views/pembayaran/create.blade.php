@extends('layouts.layouts', ['menu' => 'keloladata', 'submenu' => 'pembayaran'])

@section('title', 'Tambah Pembayaran')

@section('content')

    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-primary-gradient">
                <div class="page-inner py-5">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="text-white pb-2 fw-bold">@yield('title')</h2>
                            {{-- <h5 class="text-white op-7 mb-2">Total Pelanggan : {{ $pelanggan->count() }}</h5> --}}
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
                            <form action="{{ route('pembayaran.add') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">

                                    <div class="row">
                                        <input type="hidden" name="id_pelanggan" value="{{ $pelanggan->id }}">
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label for="nama">Nama Pelanggan</label>
                                                <input type="text" class="form-control" id="nama"
                                                    value="{{ $pelanggan->nama }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label for="tgl_pembayaran">Tanggal Pembayaran</label>
                                                <input type="date" class="form-control" name="tgl_pembayaran" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label for="jumlah">Jumlah</label>
                                                <input type="number" class="form-control" name="jumlah"
                                                    value="{{ $pelanggan->package->harga }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label for="periode">Periode Bulan</label>
                                                <input type="text" id="periode" name="periode" class="form-control"
                                                    required>
                                            </div>
                                        </div>



                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label for="metode">Metode Pembayaran</label>
                                                <select name="metode" class="form-control" required>
                                                    <option value="">Pilih</option>
                                                    <option value="transfer">Transfer</option>
                                                    <option value="cash">Cash / Tunai</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label for="bukti">Upload Bukti Pembayaran</label>
                                                <input type="file" class="form-control" name="bukti">
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-danger">Batal</a>
                                </div>
                            </form>
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
        datepicker.datepicker('setDate', new Date());
    </script>

@endsection
