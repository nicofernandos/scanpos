@extends('layouts.admintemplate')
@section('content')
    <!-- page content -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <!-- Page-header start -->
                <div class="page-header">
                    <div class="page-block">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="page-header-title">
                                    <h5 class="m-b-10">Edit Pengguna</h5>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">

                            <div class="page-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Data Pengguna</h5>
                                            </div>
                                            <div class="card-block">
                                                <form action="{{ url('penggunaeditsimpan/' . $pengguna->id) }}"
                                                    method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Nama</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="name"
                                                                value="{{ old('name', $pengguna->name) }}"
                                                                placeholder="Nama">
                                                            @error('name')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Username</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="username"
                                                                value="{{ old('username', $pengguna->username) }}"
                                                                placeholder="Username">
                                                            @error('username')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Email</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="email"
                                                                value="{{ old('email', $pengguna->email) }}"
                                                                placeholder="Email">
                                                            @error('email')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="password"
                                                                placeholder="Password">
                                                            <span style="color: red">* Kosongkan jika password tidak
                                                                diubah</span>
                                                            @error('password')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Role</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" name="role">
                                                                <option value="" disabled selected>Pilih Role</option>
                                                                <option value="Admin"
                                                                    {{ old('role', $pengguna->role) == 'Admin' ? 'selected' : '' }}>
                                                                    Admin</option>
                                                                <option value="Kasir"
                                                                    {{ old('role', $pengguna->role) == 'Kasir' ? 'selected' : '' }}>
                                                                    Kasir</option>
                                                            </select>
                                                            @error('role')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <button type="submit" required
                                                        class="btn btn-primary float-right pull-right"
                                                        name="tambah">Simpan</button>
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
        </div>
    </div>
@endsection
