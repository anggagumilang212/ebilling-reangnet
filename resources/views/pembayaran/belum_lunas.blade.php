@extends('layouts.layouts', ['menu' => 'transaksi', 'submenu' => 'belumlunas'])

@section('title', 'Data Pelanggan Belum Lunas')

@section('content')
    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-primary-gradient">
                <div class="page-inner py-5">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="text-white pb-2 fw-bold">@yield('title') Periode : {{ $currentPeriode }}</h2>
                            <h5 class="text-white op-7 mb-2">Total Pelanggan : {{ $pelangganBelumLunas->count() }}</h5>
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
                                    @foreach ($pelangganBelumLunas as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $item->nama ?? '' }} </td>
                                            <td>{{ $item->no_hp ?? '' }} </td>
                                            <td>{{ $item->tgl_daftar ?? '' }} </td>
                                            <td class="text-center">
                                                <img src="{{ $item->ktp ?? '' }}" alt=""
                                                    style="width: 50px">
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
                                                    <a href="{{ route('pembayaran.create', $item->id) }}"
                                                        class="btn btn-link btn-primary btn-md p-1" data-toggle="tooltip"
                                                        data-original-title="Pembayaran">
                                                        <i class="fa fa-money-bill"></i>
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
