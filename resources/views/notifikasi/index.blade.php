@extends('layouts.layouts', ['menu' => 'keloladata', 'submenu' => 'notifikasi'])

@section('title', 'Data Notifikasi')

@section('content')



    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-primary-gradient">
                <div class="page-inner py-5">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="text-white pb-2 fw-bold">@yield('title')</h2>
                            <h5 class="text-white op-7 mb-2">Total Notifikasi : {{ $notifikasi->count() }} </h5>
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
                                <!-- <h4 class="card-title">Add Row</h4> -->
                                <button class="btn btn-primary btn-round ml-auto" data-toggle="modal"
                                    data-target="#addRowModal">
                                    <i class="fa fa-plus"></i>
                                    Tambah Notifikasi
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Modal Add-->
                            <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header no-bd">
                                            <h5 class="modal-title">
                                                <span class="fw-mediumbold">
                                                    New</span>
                                                <span class="fw-light">
                                                    Notifikasi
                                                </span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p class="small">Create a new row using this form, make sure you fill them all</p> -->
                                            <form action="{{ route('notifikasi.add') }}" method="POST">
                                                @csrf
                                                <div class="row">

                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Kategori</label>
                                                            <select name="kategori" id="" class="form-control" >
                                                                <option value="">Pilih</option>
                                                                <option value="pelanggan_baru">Pelanggan Baru</option>
                                                                <option value="pembayaran_berhasil">Pembayaran Berhasil
                                                                </option>
                                                                <option value="isolir">Isolir</option>
                                                                <option value="peringatan_jatuh_tempo">Peringatan Jatuh
                                                                    Tempo</option>
                                                                <option value="internet_aktif">Internet Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Pesan</label>
                                                            <textarea name="template_pesan" class="form-control" cols="30" rows="10"></textarea>
                                                        </div>
                                                    </div>

                                                </div>
                                        </div>
                                        <div class="modal-footer no-bd">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


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
                                            <form id="editNotifikasiForm" method="POST">
                                                @csrf

                                                <input type="hidden" name="id" id="editNotifikasiId">
                                                <div class="row">

                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Kategori</label>
                                                            <select name="kategori" id="editNotifikasiKategori" class="form-control">
                                                                <option value="pelanggan_baru">Pelanggan Baru</option>
                                                                <option value="pembayaran_berhasil">Pembayaran Berhasil
                                                                </option>
                                                                <option value="isolir">Isolir</option>
                                                                <option value="peringatan_jatuh_tempo">Peringatan Jatuh
                                                                    Tempo</option>
                                                                <option value="internet_aktif">Internet Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Pesan</label>
                                                            <textarea name="template_pesan" id="editNotifikasiTemplate" class="form-control" cols="30" rows="10"></textarea>
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

                        </div>



                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Pesan</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Pesan</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($notifikasi as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $item->kategori ?? '' }} </td>
                                            <td>{{ $item->template_pesan ?? '' }} </td>
                                            <td>
                                                <div class="form-button-action">

                                                    <a href="javascript:void(0)"
                                                        class="btn btn-link btn-primary btn-lg btn-edit-router"
                                                        data-toggle="tooltip"
                                                        data-original-title="Edit" data-id="{{ $item->id }}"
                                                        data-kategori="{{ $item->kategori }}"
                                                        data-template_pesan="{{ $item->template_pesan }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>


                                                    <a href="{{ route('notifikasi.delete', $item->id) }}" type="button"
                                                        data-toggle="tooltip" class="btn btn-link btn-danger"
                                                        data-original-title="Hapus"
                                                        onclick="return confirm('Apakah anda yakin menghapus notif {{ $item->name }} ?')">
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
        document.addEventListener('DOMContentLoaded', function() {
            $('.btn-edit-router').on('click', function() {
                const id = $(this).data('id');
                const kategori = $(this).data('kategori');
                const template_pesan = $(this).data('template_pesan');

                const actionUrl = "{{ route('notifikasi.update', ':id') }}".replace(':id', id);

                // Set data ke modal
                $('#editNotifikasiId').val(id);
                $('#editNotifikasiKategori').val(kategori);
                $('#editNotifikasiTemplate').val(template_pesan);

                // Set action form
                $('#editNotifikasiForm').attr('action', actionUrl);

                // Buka modal
                $('#editRowModal').modal('show');
            });
        });
    </script>

@endsection
