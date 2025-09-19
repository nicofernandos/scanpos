@extends('layouts.admintemplate')
@section('content')
    <!-- page content -->
    <div class="pcoded-content">
        <!-- Page-header start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Daftar Pengguna</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="card">
                            <div class="card-header">
                                <h5>Daftar Pengguna</h5>
                                <div class="card-header-right">
                                    <ul class="list-unstyled card-option">
                                        <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                        <li><i class="fa fa-window-maximize full-card"></i></li>
                                        <li><i class="fa fa-minus minimize-card"></i></li>
                                        <li><i class="fa fa-refresh reload-card"></i></li>
                                        <li><i class="fa fa-trash close-card"></i></li>
                                    </ul>
                                </div>
                                @if (Session::has('success'))
                                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                        <strong>Sukses!</strong> {{ Session::get('success') }}.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="card-block table-border-style">
                                <div class="table-responsive">
                                    <table class="table" id="daftarproduk">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengguna as $key => $value)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->username }}</td>
                                                    <td>{{ $value->email }}</td>
                                                    <td>{{ $value->role }}</td>
                                                    <td>
                                                        <a href="{{ url('penggunaedit/' . $value->id) }}"
                                                            class="btn btn-success m-1">Edit</a>
                                                        <form action="{{ url('penggunahapus/' . $value->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-danger m-1" type="submit"
                                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                                                        </form>
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
        @endsection
