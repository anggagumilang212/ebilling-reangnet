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
                                <!-- <h4 class="card-title">Add Row</h4> -->
                                {{-- <button class="btn btn-primary btn-round ml-auto" data-toggle="modal"
                                    data-target="#addRowModal">
                                    <i class="fa fa-plus"></i>
                                    Add Pelanggan
                                </button> --}}

                                <a href="{{ route('pelanggan.create') }}" class="btn btn-primary btn-round ml-auto">
                                    <i class="fa fa-plus"></i>
                                    Tambah Pelanggan
                                </a>
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
                                                    Pelanggan
                                                </span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p class="small">Create a new row using this form, make sure you fill them all</p> -->
                                            <form action="{{ route('pelanggan.add') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Jenis Pelanggan</label>
                                                            <select name="jenis" id="jenis" class="form-control">
                                                                <option value="" selected disabled>Pilih</option>
                                                                <option value="baru">Baru</option>
                                                                <option value="lama">Lama</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Nama Pelanggan</label>
                                                            <input name="nama" type="text" id="user"
                                                                class="form-control" placeholder="Nama" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Tanggal Daftar</label>
                                                            <input name="tgl_daftar" type="date" id="user"
                                                                class="form-control" placeholder="Tanggal Daftar" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>No Hp/WA</label>
                                                            <input name="no_hp" type="text" id="no_hp"
                                                                class="form-control" placeholder="No Hp/WA" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Foto KTP</label>
                                                            <input name="ktp" type="file" id="ktp"
                                                                class="form-control" placeholder="No Hp/WA" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Alamat</label>
                                                            <textarea name="alamat" id="" class="form-control"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Router</label>
                                                            <select name="router_id" id="router_id" class="form-control"
                                                                placeholder="Profile">
                                                                <option>Pilih</option>
                                                                @foreach ($routers as $data)
                                                                    <option value="{{ $data['id'] }}">{{ $data['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- Metode koneksi -->
                                                    <div class="col-md-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Metode Koneksi</label>
                                                            <select name="metode" id="metode" class="form-control"
                                                                placeholder="metode">
                                                                <option>Pilih</option>
                                                                <option value="buat_baru">Buat Baru</option>
                                                                <option value="sinkronisasi">Sinkronisasi</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Metode sinkronisasi pppoe -->
                                                    <div class="col-md-12 sync-section" style="display: none;">
                                                        <div class="form-group form-group-default">
                                                            <label>Secret</label>
                                                            <select id="secret_id" class="form-control select2-container"
                                                                style="width: 100%">
                                                                <option></option>
                                                            </select>
                                                        </div>
                                                        <!-- Hidden fields for sync data -->
                                                        <input type="hidden" id="sync_username" name="sync_username">
                                                        <input type="hidden" id="sync_password" name="sync_password">
                                                        <input type="hidden" id="sync_service" name="sync_service">
                                                        <input type="hidden" id="sync_profile" name="sync_profile">
                                                        <input type="hidden" id="sync_local_address"
                                                            name="sync_local_address">
                                                        <input type="hidden" id="sync_remote_address"
                                                            name="sync_remote_address">
                                                    </div>

                                                    <!-- Metode membuat pppoe -->
                                                    <div class="new-pppoe-section" style="display: none;">
                                                        <!-- Username -->
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Username</label>
                                                                <input name="user" type="text" class="form-control"
                                                                    placeholder="Username">
                                                            </div>
                                                        </div>

                                                        <!-- Password -->
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Password</label>
                                                                <input name="password" type="text"
                                                                    class="form-control" placeholder="Password">
                                                            </div>
                                                        </div>

                                                        <!-- Service -->
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Service</label>
                                                                <select name="service" id="service"
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Pilih</option>
                                                                    <option value="any">ANY</option>
                                                                    <option value="async">ASYNC</option>
                                                                    <option value="pppoe">PPPoE</option>
                                                                    <option value="pptp">PPTP</option>
                                                                    <option value="sstp">SSTP</option>
                                                                    <option value="l2tp">L2TP</option>
                                                                    <option value="ovpn">OVPN</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Profile -->
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Profile</label>
                                                                <select name="profile" id="profile"
                                                                    class="form-control">
                                                                    <option disabled selected value="">Pilih</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Local & Remote Address dalam satu row -->
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Local Address</label>
                                                                        <input name="localaddress" id="localaddress"
                                                                            type="text" class="form-control"
                                                                            placeholder="Local Address">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Remote Address</label>
                                                                        <input name="remoteaddress" id="remoteaddress"
                                                                            class="form-control"
                                                                            placeholder="Remote Address">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Comment -->
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Comment</label>
                                                                <input name="comment" id="comment" type="text"
                                                                    class="form-control" placeholder="Comment">
                                                            </div>
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
                        </div>



                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No HP</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Ktp</th>
                                        <th>Paket</th>
                                        <th>Status</th>
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
                                        <th>Ktp</th>
                                        <th>Paket</th>
                                        <th>Status</th>
                                        <th>Alamat</th>
                                        <th>Pembayaran Terakhir</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($pelanggan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $item->nama ?? '' }} </td>
                                            <td>{{ $item->no_hp ?? '' }} </td>
                                            <td>{{ Carbon\Carbon::parse($item->tgl_daftar)->format('d F Y') }} </td>
                                            <td class="text-center">
                                                {{-- <img src="{{ $item->ktp ?? '' }}" alt=""
                                                    style="width: 50px"> --}}
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
