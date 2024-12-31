@extends('layouts.layouts', ['menu' => 'keloladata', 'submenu' => 'router'])

@section('title', 'Data Router')

@section('content')



    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-primary-gradient">
                <div class="page-inner py-5">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="text-white pb-2 fw-bold">@yield('title')</h2>
                            <h5 class="text-white op-7 mb-2">Total Router : {{ $router->count() }} </h5>
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
                                    Add Router
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
                                                    Router
                                                </span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p class="small">Create a new row using this form, make sure you fill them all</p> -->
                                            <form action="{{ route('router.add') }}" method="POST">
                                                @csrf
                                                <div class="row">

                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Nama Router</label>
                                                            <input name="name" type="text" id="user"
                                                                class="form-control" placeholder="Nama" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>IP Address</label>
                                                            <input name="ip" type="text" id="user"
                                                                class="form-control" placeholder="Ip Address" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Username</label>
                                                            <input name="username" type="text" id="username"
                                                                class="form-control" placeholder="Username" required>
                                                        </div>
                                                    </div>


                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default">
                                                            <label>Password</label>
                                                            <input name="password" type="text" id="password"
                                                                class="form-control" placeholder="Password" required>
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

                        </div>



                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Ip Address</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Ip Address</th>
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($router as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $item->name ?? '' }} </td>
                                            <td>{{ $item->ip ?? '' }} </td>
                                            <td>{{ $item->username ?? '' }} </td>
                                            <td>{{ $item->password ?? '' }} </td>

                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('router.check', $item->id) }}"
                                                        class="btn btn-link btn-info" data-toggle="tooltip"
                                                        data-original-title="Cek Koneksi">
                                                        <i class="fa fa-wifi"></i>
                                                    </a>
                                                    <a href="javascript:void(0)"
                                                    class="btn btn-link btn-primary btn-lg btn-edit-router"
                                                    data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}"
                                                    data-ip="{{ $item->ip }}"
                                                    data-username="{{ $item->username }}"
                                                    data-password="{{ $item->password }}">
                                                     <i class="fa fa-edit"></i>
                                                 </a>


                                                    <a href="{{ route('router.delete', $item->id) }}" type="button"
                                                        data-toggle="tooltip" class="btn btn-link btn-danger"
                                                        data-original-title="Remove"
                                                        onclick="return confirm('Apakah anda yakin menghapus router {{ $item->name }} ?')">
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
        document.addEventListener('DOMContentLoaded', function () {
            $('.btn-edit-router').on('click', function () {
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
