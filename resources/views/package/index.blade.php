@extends('layouts.layouts', ['menu' => 'keloladata', 'submenu' => 'paket'])

@section('title', 'Data Paket')

@section('content')



    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-primary-gradient">
                <div class="page-inner py-5">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="text-white pb-2 fw-bold">@yield('title')</h2>
                            <h5 class="text-white op-7 mb-2">Total Paket : {{ $packages->count() }} </h5>
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
                                    Tambah Paket
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
                                                    Tambah</span>
                                                <span class="fw-light">
                                                    Paket
                                                </span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p class="small">Create a new row using this form, make sure you fill them all</p> -->
                                            <form action="{{ route('package.add') }}" method="POST">
                                                @csrf
                                                <div class="row">

                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Nama Paket</label>
                                                            <input name="nama" type="text" id="user"
                                                                class="form-control" placeholder="Nama Paket" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Kecepatan</label>
                                                            <input name="kecepatan" type="text" id="user"
                                                                class="form-control" placeholder="Kecepatan" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Tarif / Bulan</label>
                                                            <input name="harga" type="text" id="harga"
                                                                class="form-control" placeholder="Tarif / Bulan" required>
                                                        </div>
                                                    </div>


                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Router</label>
                                                            <select name="router_id" id="router_id" class="form-control">
                                                                <option>Pilih</option>
                                                                @foreach ($routers as $data)
                                                                    <option value="{{ $data['id'] }}">{{ $data['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Profile</label>
                                                            <select name="profile" id="profile" class="form-control">
                                                                <option disabled selected value="">Pilih</option>
                                                            </select>
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
                                                <span class="fw-light">Paket</span>
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
                                                            <label>Nama Paket</label>
                                                            <input name="nama" type="text" id="editRouterName"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Kecepatan</label>
                                                            <input name="kecepatan" type="text"
                                                                id="editRouterKecepatan" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Tarif / Bulan</label>
                                                            <input name="harga" type="text" id="editRouterHarga"
                                                                class="form-control harga-input" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Router</label>
                                                            <select name="router_id" id="editRouterSelect"
                                                                class="form-control">
                                                                <option disabled selected value="">Pilih</option>
                                                                @foreach ($routers as $data)
                                                                    <option value="{{ $data->id }}">{{ $data->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Profile</label>
                                                            <select name="profile" id="editProfileSelect"
                                                                class="form-control">
                                                                <option disabled selected value="">Pilih</option>
                                                            </select>
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
                                            <th>Kecepatan</th>
                                            <th>Tarif / Bulan</th>
                                            <th>Router</th>
                                            <th>Profile</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kecepatan</th>
                                            <th>Tarif / Bulan</th>
                                            <th>Router</th>
                                            <th>Profile</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($packages as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }} </td>
                                                <td>{{ $item->nama ?? '' }} </td>
                                                <td>{{ $item->kecepatan ?? '' }} </td>
                                                <td>{{ $item->harga ?? '' }} </td>
                                                <td>{{ $item->router->name ?? '' }} </td>
                                                <td>{{ $item->profile ?? '' }} </td>

                                                <td>
                                                    <div class="form-button-action">

                                                        <a href="javascript:void(0)"
                                                            class="btn btn-link btn-primary btn-lg btn-edit-router"
                                                            data-id="{{ $item->id }}"
                                                            data-nama="{{ $item->nama }}"
                                                            data-kecepatan="{{ $item->kecepatan }}"
                                                            data-harga="{{ $item->harga }}"
                                                            data-router="{{ $item->router_id }}"
                                                            data-profile="{{ $item->profile }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>


                                                        <a href="{{ route('package.delete', $item->id) }}" type="button"
                                                            data-toggle="tooltip" class="btn btn-link btn-danger"
                                                            data-original-title="Remove"
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
{{-- format harga di modal add --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk memformat angka ke format currency
            function formatCurrency(input) {
                // Hapus semua karakter selain angka
                let value = input.value.replace(/\D/g, '');

                // Format angka dengan menambahkan titik
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Update nilai input
                input.value = value;
            }

            // Event listener untuk input harga
            const hargaInput = document.getElementById('harga');
            hargaInput.addEventListener('input', function() {
                formatCurrency(this);
            });

            // Format initial value jika ada
            if (hargaInput.value) {
                formatCurrency(hargaInput);
            }
        });
    </script>

    {{-- format harga di modal edit --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, '');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        input.value = value;
    }

    // Format untuk semua input harga
    document.querySelectorAll('.harga-input').forEach(input => {
        input.addEventListener('input', function() {
            formatCurrency(this);
        });

        // Format initial value
        if (input.value) {
            formatCurrency(input);
        }
    });
});
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function untuk mengambil dan memperbarui profile berdasarkan router
            function updateProfiles(routerId, selectedProfile = '') {
                fetch(`/getProfiles/${routerId}`)
                    .then(response => response.json())
                    .then(data => {
                        let profileSelect = $('#editProfileSelect');
                        profileSelect.empty();
                        profileSelect.append('<option disabled value="">Pilih</option>');

                        data.profiles.forEach(item => {
                            let selected = item.name === selectedProfile ? 'selected' : '';
                            profileSelect.append(
                                `<option value="${item.name}" ${selected}>${item.name}</option>`);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Event listener untuk perubahan pada dropdown router
            $('#editRouterSelect').on('change', function() {
                const selectedRouterId = $(this).val();
                if (selectedRouterId) {
                    updateProfiles(selectedRouterId);
                }
            });

            // Event listener untuk tombol edit
            $('.btn-edit-router').on('click', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const kecepatan = $(this).data('kecepatan');
                const harga = $(this).data('harga');
                const routerId = $(this).data('router');
                const profile = $(this).data('profile');
                const actionUrl = "{{ route('package.update', ':id') }}".replace(':id', id);

                // Set data ke modal
                $('#editRouterId').val(id);
                $('#editRouterName').val(nama);
                $('#editRouterKecepatan').val(kecepatan);
                $('#editRouterHarga').val(harga);
                $('#editRouterSelect').val(routerId);

                // Ambil profile untuk router yang dipilih
                updateProfiles(routerId, profile);

                // Set action form
                $('#editRouterForm').attr('action', actionUrl);

                // Buka modal
                $('#editRowModal').modal('show');
            });
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
