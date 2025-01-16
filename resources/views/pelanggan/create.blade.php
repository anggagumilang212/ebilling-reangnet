@extends('layouts.layouts', ['menu' => 'keloladata', 'submenu' => 'pelanggan'])

@section('title', 'Tambah Pelanggan')

@section('content')
    {{-- style input titik koordinat --}}
    <style>
        .input-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            width: 100%;
            margin-bottom: 10px;
        }

        .input-group .form-control {
            flex: 1;
            min-width: 220px;
        }

        .input-group .btn {
            padding: 6px 12px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        @media (max-width: 576px) {
            .input-group {
                flex-direction: column;
                align-items: stretch;
            }

            .input-group .btn {
                width: 100%;
            }
        }
    </style>
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
                            <div class="card-body">
                                <form action="{{ route('pelanggan.add') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Jenis Pelanggan</label>
                                                <select name="jenis" id="jenis" class="form-control" required>
                                                    <option value="" selected disabled>Pilih</option>
                                                    <option value="baru">Baru</option>
                                                    <option value="lama">Lama</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Nama Pelanggan</label>
                                                <input name="nama" type="text" id="user" class="form-control"
                                                    placeholder="Nama" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Tanggal Daftar</label>
                                                <input name="tgl_daftar" type="date" id="tgl_daftar" class="form-control"
                                                    placeholder="Tanggal Daftar" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>No Hp/WA</label>
                                                <input name="no_hp" type="text" id="no_hp" class="form-control"
                                                    placeholder="08xxxxxxxxx" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Foto KTP</label>
                                                <input name="ktp" type="file" id="ktp" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="input-group col-md-6">
                                            <input type="text" id="titik_koordinat" name="titik_koordinat"
                                                class="form-control" placeholder="Titik Lokasi" />
                                            <button type="button" class="btn btn-primary btn-sm" id="btnFindLocation">
                                                <i class="fa fa-map-marker"></i> Koordinat
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" id="btnViewMap">
                                                Lihat Map
                                            </button>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group form-group-default">
                                                <label>Paket Internet</label>
                                                <select name="package_id" class="form-control">
                                                    <option>Pilih</option>
                                                    @foreach ($packages as $package)
                                                        <option value="{{ $package->id }}">{{ $package->nama }} - Rp
                                                            {{ number_format($package->harga, 0, ',', '.') }} -
                                                            {{ $package->kecepatan }} | {{ $package->router->name }} -
                                                            {{ $package->profile }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Tanggal Jatuh Tempo</label>
                                                <select name="tanggal_jatuh_tempo" class="form-control">
                                                    <option>Pilih</option>
                                                    @for ($i = 1; $i <= 31; $i++)
                                                        <option value="{{ $i }}">
                                                            TANGGAL {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Alamat</label>
                                                <textarea name="alamat" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Router</label>
                                                <select name="router_id" id="router_id" class="form-control">
                                                    <option>Pilih</option>
                                                    @foreach ($routers as $data)
                                                        <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label>Metode Koneksi</label>
                                                <select name="metode" id="metode" class="form-control" required>
                                                    <option>Pilih</option>
                                                    <option value="buat_baru">Buat Baru</option>
                                                    <option value="sinkronisasi">Sinkronisasi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 sync-section" style="display: none;">
                                            <div class="form-group form-group-default">
                                                <label>Secret</label>
                                                <select id="secret_id" class="form-control select2-container"
                                                    style="width: 100%" required>
                                                    <option></option>
                                                </select>
                                            </div>
                                            <input type="hidden" id="sync_username" name="sync_username">
                                            <input type="hidden" id="sync_password" name="sync_password">
                                            <input type="hidden" id="sync_service" name="sync_service">
                                            <input type="hidden" id="sync_profile" name="sync_profile">
                                            <input type="hidden" id="sync_local_address" name="sync_local_address">
                                            <input type="hidden" id="sync_remote_address" name="sync_remote_address">
                                        </div>
                                        <div class="new-pppoe-section" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Username</label>
                                                        <input name="user" type="text" class="form-control"
                                                            placeholder="Username" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Password</label>
                                                        <input name="password" type="text" class="form-control"
                                                            placeholder="Password" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Service</label>
                                                        <select name="service" id="service" class="form-control">
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
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Profile</label>
                                                        <select name="profile" id="profile" class="form-control">
                                                            <option disabled selected value="">Pilih</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Local Address</label>
                                                        <input name="localaddress" id="localaddress" type="text"
                                                            class="form-control" placeholder="Local Address">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group form-group-default">
                                                        <label>Remote Address</label>
                                                        <input name="remoteaddress" id="remoteaddress"
                                                            class="form-control" placeholder="Remote Address">
                                                    </div>
                                                </div>
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
    </div>
    </div>
    {{-- titik koordinat --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const findLocationButton = document.getElementById('btnFindLocation');
            const viewMapButton = document.getElementById('btnViewMap');
            const koordinatInput = document.getElementById('titik_koordinat');

            // Fungsi untuk mendapatkan lokasi
            findLocationButton.addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const latitude = position.coords.latitude;
                            const longitude = position.coords.longitude;
                            koordinatInput.value = `${latitude}, ${longitude}`;
                        },
                        function(error) {
                            alert('Gagal mendapatkan lokasi: ' + error.message);
                        }
                    );
                } else {
                    alert('Geolocation tidak didukung oleh browser Anda.');
                }
            });

            // Fungsi untuk melihat lokasi di peta
            viewMapButton.addEventListener('click', function() {
                const koordinat = koordinatInput.value;
                if (koordinat) {
                    const [latitude, longitude] = koordinat.split(',').map(parseFloat);
                    if (!isNaN(latitude) && !isNaN(longitude)) {
                        const mapUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;
                        window.open(mapUrl, '_blank');
                    } else {
                        alert('Koordinat tidak valid.');
                    }
                } else {
                    alert('Isi atau dapatkan koordinat terlebih dahulu.');
                }
            });
        });
    </script>


    {{-- dinamic form section --}}
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

    {{-- get profile with router id --}}
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


@endsection

{{-- select 2 get secret --}}
@push('scripts')
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS (setelah jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function initializeSecretSelect() {
                $('#secret_id').select2({
                    placeholder: "Pilih Secret",
                    allowClear: true,
                    ajax: {
                        url: function() {
                            const routerId = $('#router_id').val();
                            if (!routerId) {
                                return '';
                            }
                            return `/getSecrets/${routerId}`;
                        },
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term || '', // Kirim parameter pencarian
                                page: params.page || 1
                            };
                        },
                        processResults: function(data) {
                            if (data && data.secrets) {
                                return {
                                    results: data.secrets.map(secret => ({
                                        id: secret['.id'], // Gunakan .id dari MikroTik
                                        text: secret.name,
                                        // Simpan data tambahan yang mungkin diperlukan
                                        service: secret.service,
                                        profile: secret.profile,
                                        password: secret.password,
                                        local_address: secret['local-address'],
                                        remote_address: secret['remote-address']
                                    }))
                                };
                            }
                            return {
                                results: []
                            };
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error fetching secrets:', textStatus, errorThrown);
                        },
                        cache: true
                    },
                    minimumInputLength: 0,
                    templateResult: formatSecret,
                    templateSelection: formatSecret
                });
            }

            function formatSecret(secret) {
                if (!secret.id) return secret.text;
                // return $(`<span>${secret.text} ${secret.profile ? `(${secret.profile})` : ''}</span>`);
                return $(`<span>${secret.text}</span>`);
            }

            // Inisialisasi awal
            initializeSecretSelect();

            // Reset dan reinisialisasi saat router berubah
            $('#router_id').on('change', function() {
                $('#secret_id').empty().trigger('change');
                initializeSecretSelect();
            });

            // Handler untuk perubahan secret
            $('#secret_id').on('change', function(e) {
                const data = $(this).select2('data')[0];
                if (data) {
                    // Isi field dengan data yang dipilih
                    $('#sync_username').val(data.text || '');
                    $('#sync_password').val(data.password || '');
                    $('#sync_service').val(data.service || '');
                    $('#sync_profile').val(data.profile || '');
                    $('#sync_local_address').val(data.local_address || '');
                    $('#sync_remote_address').val(data.remote_address || '');
                }
            });
        });
    </script>
@endpush
