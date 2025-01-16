@extends('layouts.layouts', ['menu' => 'keloladata', 'submenu' => 'pelanggan'])

@section('title', 'Data Pelanggan')

@section('content')
    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-primary-gradient">
                <div class="page-inner py-5">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="text-white pb-2 fw-bold">@yield('title')</h2>
                            <h5 class="text-white op-7 mb-2">Total Pelanggan : {{ $pelanggan->count() }}</h5>
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
                                <a href="{{ route('pelanggan.create') }}" class="btn btn-primary btn-round ml-auto">
                                    <i class="fa fa-plus"></i>
                                    Tambah Pelanggan
                                </a>

                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pelanggan.send-reminder') }}" method="POST" id="reminderForm">
                                @csrf
                                <div class="row">

                                    <div class="form-group">
                                        <label>Pilih Tanggal Jatuh Tempo</label>
                                        <select name="tanggal_jatuh_tempo" class="form-control form-control-sm" required>
                                            <option value="">Pilih</option>
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}">TANGGAL {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-sm  d-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15px"
                                                height="15px" fill="currentColor">
                                                <path
                                                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                                            </svg> Kirim Pesan Peringatan
                                        </button>
                                    </div>


                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-sm  d-block" data-toggle="modal"
                                            data-target="#importExcelModal">
                                            <i class="fa fa-upload me-2"></i> Import Excel
                                        </button>
                                    </div>

                                    <div class="form-group">
                                        <label for="statusFilter">Status Internet:</label>
                                        <select id="statusFilter" class="form-control form-control-sm">
                                            <option value="">Semua</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Isolir">Isolir</option>
                                        </select>
                                    </div>


                                </div>
                            </form>
                        </div>

                        <!-- Modal Import excel-->
                        <div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header no-bd">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold">
                                                Import</span>
                                            <span class="fw-light">
                                                Excel
                                            </span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('pelanggan.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>File</label>
                                                        <input name="file" type="file" id="file"
                                                            class="form-control" placeholder="file" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer no-bd">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table id="server-side" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>No HP</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Ktp</th>
                                    <th>Paket</th>
                                    <th>Status Internet</th>
                                    <th>Alamat</th>
                                    <th>Pembayaran Terakhir</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>No HP</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Ktp</th>
                                    <th>Paket</th>
                                    <th>Status Internet</th>
                                    <th>Alamat</th>
                                    <th>Pembayaran Terakhir</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                <script>
                                    $(document).ready(function() {
                                        const table = $('#server-side').DataTable({
                                            processing: true,
                                            serverSide: true,
                                            ajax: {
                                                url: "{{ route('pelanggan.data') }}",
                                                data: function(d) {
                                                    d.status = $('#statusFilter').val(); // Kirim status yang dipilih ke server
                                                },
                                            },
                                            columns: [{
                                                    data: 'DT_RowIndex',
                                                    name: 'DT_RowIndex',
                                                    orderable: false,
                                                    searchable: false
                                                },
                                                {
                                                    data: 'nama',
                                                    name: 'nama'
                                                },
                                                {
                                                    data: 'no_hp',
                                                    name: 'no_hp'
                                                },
                                                {
                                                    data: 'tgl_daftar',
                                                    name: 'tgl_daftar'
                                                },
                                                {
                                                    data: 'tanggal_jatuh_tempo',
                                                    name: 'tanggal_jatuh_tempo'
                                                },
                                                {
                                                    data: 'ktp',
                                                    name: 'ktp',
                                                    orderable: false,
                                                    searchable: false
                                                },
                                                {
                                                    data: 'package.nama',
                                                    name: 'package.nama'
                                                },
                                                {
                                                    data: 'status',
                                                    name: 'status'
                                                },
                                                {
                                                    data: 'alamat',
                                                    name: 'alamat'
                                                },
                                                {
                                                    data: 'pembayaran_terakhir',
                                                    name: 'pembayaran_terakhir'
                                                },
                                                {
                                                    data: 'action',
                                                    name: 'action',
                                                    orderable: false,
                                                    searchable: false
                                                },
                                            ],
                                        });

                                        // Event listener untuk filter
                                        $('#statusFilter').on('change', function() {
                                            table.ajax.reload(); // Refresh tabel saat filter diubah
                                        });

                                        // Re-inisialisasi tooltip
                                        table.on('draw.dt', function() {
                                            $('[data-toggle="tooltip"]').tooltip();
                                        });
                                    });
                                </script>

                                {{-- @foreach ($pelanggan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $item->nama ?? '' }} </td>
                                        <td>{{ $item->no_hp ?? '' }} </td>
                                        <td>{{ Carbon\Carbon::parse($item->tgl_daftar)->format('d F Y') }} </td>
                                        <td>Tanggal {{ $item->tanggal_jatuh_tempo ?? '' }} </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center gap-2" style="max-width: 150px;">
                                                @if ($item->ktp)
                                                    <a href="{{ asset($item->ktp) }}"
                                                        class="btn btn-sm btn-light download-btn"
                                                        download="{{ basename($item->ktp) }}"
                                                        onclick="downloadFile(this.href, '{{ basename($item->ktp) }}'); return false;">
                                                        <i class="fas fa-download me-1"></i>
                                                        <span class="d-none d-sm-inline">Download</span>
                                                    </a>
                                                @else
                                                    <span class="text-muted small">No file</span>
                                                @endif
                                            </div>

                                        </td>
                                        <td>{{ $item->package->nama ?? '' }} </td>
                                        <td>{{ $item->status ?? '' }} </td>
                                        <td>{{ $item->alamat ?? '' }} </td>
                                        <td>
                                            {{ optional($item->pembayaran()->latest()->first())->periode ?? 'Belum ada pembayaran' }}
                                        </td>

                                        <td>
                                            <div class="form-button-action d-flex flex-wrap gap-1"
                                                style="min-width: 200px;">
                                                <button class="btn btn-link btn-danger btn-isolir btn-md p-1"
                                                    data-toggle="tooltip" data-original-title="Isolir"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fa fa-lock"></i>
                                                </button>

                                                <button class="btn btn-link btn-success btn-bukaisolir btn-md p-1"
                                                    data-toggle="tooltip" data-original-title="Buka Isolir"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fa fa-unlock"></i>
                                                </button>

                                                <a href="{{ route('pembayaran.create', $item->id) }}"
                                                    class="btn btn-link btn-primary btn-md p-1" data-toggle="tooltip"
                                                    data-original-title="Pembayaran">
                                                    <i class="fa fa-money-bill"></i>
                                                </a>

                                                <a href="{{ route('pelanggan.edit', $item->id) }}"
                                                    class="btn btn-link btn-primary btn-md p-1" data-toggle="tooltip"
                                                    data-original-title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a href="{{ route('pelanggan.delete', $item->id) }}"
                                                    class="btn btn-link btn-danger btn-md p-1" data-toggle="tooltip"
                                                    data-original-title="Hapus"
                                                    onclick="return confirm('Apakah anda yakin menghapus router {{ $item->nama }} ?')">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach --}}
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
            const metodeSelect = document.getElementById('metode');
            const syncSection = document.querySelector('.sync-section');
            const newPppoeSection = document.querySelector('.new-pppoe-section');

            // Fungsi untuk menyembunyikan semua bagian dan mereset input
            function resetSections() {
                // Sembunyikan semua bagian
                [syncSection, newPppoeSection].forEach(section => {
                    section.style.display = 'none';
                    // Reset dan aktifkan semua input di dalamnya
                    section.querySelectorAll('input, select').forEach(input => {
                        input.value = '';
                        input.disabled = false;
                    });
                });
            }

            // Event listener untuk dropdown metode
            metodeSelect.addEventListener('change', function() {
                resetSections(); // Reset semua bagian sebelum menampilkan yang dipilih

                const selectedMethod = this.value;

                if (selectedMethod === 'sinkronisasi') {
                    syncSection.style.display = 'block'; // Tampilkan bagian sinkronisasi
                    newPppoeSection.querySelectorAll('input, select').forEach(input => input.disabled =
                        true);
                } else if (selectedMethod === 'buat_baru') {
                    newPppoeSection.style.display = 'block'; // Tampilkan bagian buat baru
                    syncSection.querySelectorAll('input, select').forEach(input => input.disabled = true);
                }
            });

            // Event listener untuk secret selection (sinkronisasi)
            const secretSelect = document.getElementById('secret_id');
            if (secretSelect) {
                secretSelect.addEventListener('change', function() {
                    const selectedSecret = this.value;

                    if (selectedSecret) {
                        fetch(`/get-secret-details/${selectedSecret}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }

                                return response.json();
                            })
                            .then(data => {
                                if (data.error) {
                                    console.error('Error:', data.error);
                                    return;
                                }

                                // Isi field tersembunyi dengan detail secret
                                document.getElementById('sync_username').value = data.name || '';
                                document.getElementById('sync_password').value = data.password || '';
                                document.getElementById('sync_service').value = data.service || '';
                                document.getElementById('sync_profile').value = data.profile || '';
                                document.getElementById('sync_local_address').value = data
                                    .local_address || '';
                                document.getElementById('sync_remote_address').value = data
                                    .remote_address || '';
                            })
                            .catch(error => console.error('Error fetching secret details:', error));
                    }
                });
            }

        });
    </script>

    <script>
        document.getElementById('router_id').addEventListener('change', function() {
            let routerId = this.value;

            // Ensure a router is selected
            if (routerId) {
                fetch(`/getProfiles/${routerId}`) // Correct URL based on routing setup
                    .then(response => response.json())
                    .then(data => {
                        let profileSelect = document.getElementById('profile');
                        profileSelect.innerHTML =
                            '<option disabled selected value="">Pilih</option>'; // Clear previous options

                        // Check if profiles are returned
                        if (data.profiles && data.profiles.length > 0) {
                            // Populate the profile dropdown with new options
                            data.profiles.forEach(profile => {
                                let option = document.createElement('option');
                                option.value = profile[
                                    'name']; // Use the appropriate field from profile data
                                option.textContent = profile[
                                    'name']; // Assuming 'name' is a valid field
                                profileSelect.appendChild(option);
                            });
                        } else {
                            // Handle the case when no profiles are available
                            let option = document.createElement('option');
                            option.disabled = true;
                            option.textContent = 'No profiles available';
                            profileSelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching profiles:', error);
                    });
            }
        });
    </script>


    <script>
        $(document).on('click', '.btn-isolir', function() {
            var id = $(this).data('id');

            if (confirm('Apakah Anda yakin ingin mengisolir pelanggan ini?')) {
                $.ajax({
                    url: '/isolir/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response
                            .success) { // Menggunakan response.success alih-alih response.status
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Gagal mengisolir pelanggan: ' + (response.message ||
                                'Terjadi kesalahan'));
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert('Gagal mengisolir pelanggan: ' + errorMessage);
                    }
                });
            }
        });

        $(document).on('click', '.btn-bukaisolir', function() {
            var id = $(this).data('id');

            if (confirm('Apakah Anda yakin ingin buka isolir pelanggan ini?')) {
                $.ajax({
                    url: '/bukaisolir/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response
                            .success) { // Menggunakan response.success alih-alih response.status
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Gagal buka isolir pelanggan: ' + (response.message ||
                                'Terjadi kesalahan'));
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert('Gagal buka isolir pelanggan: ' + errorMessage);
                    }
                });
            }
        });
    </script>





@endsection


@push('scripts')
    {{-- Tambahkan di head --}}

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS (setelah jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('#secret_id').select2({
                placeholder: "Pilih Secret",
                allowClear: true,
                ajax: {
                    url: function() {
                        const routerId = $('#router_id').val();
                        if (!routerId) {
                            return null; // Prevent the request if routerId is not selected
                        }
                        return `/getSecrets/${routerId}`;
                    },
                    dataType: 'json',
                    delay: 250, // Add delay to improve performance
                    processResults: function(data) {
                        return {
                            results: data.secrets.map(secret => ({
                                id: secret
                                    .name, // Adjust the key based on your data structure
                                text: secret
                                    .name // Adjust the key based on your data structure
                            }))
                        };
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching secrets:', errorThrown);
                    }
                },
                // dropdownParent: $('.form-group-default')
            });

            // Reinitialize Select2 when router_id changes
            $('#router_id').on('change', function() {
                $('#secret_id').val(null).trigger('change');
            });
        });
    </script>
@endpush
