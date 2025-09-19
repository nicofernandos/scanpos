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
                                    <h5 class="m-b-10">Ubah Profil</h5>
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
                                                <h5>Data Profil</h5>
                                            </div>
                                            @if (Session::has('success'))
                                                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                    <strong>Sukses!</strong> {{ Session::get('success') }}.
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div> 
                                            @endif
                                            <div class="card-block p-5">
                                                <form action="{{ url('profileupdate') }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group row mx-1 my-2">
                                                        <label class="col-sm-2 col-form-label mt-1 mb-2">Nama</label>
                                                        <div class="col-sm-10">
                                                            <input value="{{ $profile->name }}" type="text" required
                                                                class="form-control" name="name" placeholder="Nama">
                                                            @error('name')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label mt-1 mb-2">Email</label>
                                                        <div class="col-sm-10">
                                                            <input value="{{ $profile->email }}" type="text" required
                                                                class="form-control" name="email" placeholder="Email">
                                                            @error('email')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label mt-1 mb-2">Username</label>
                                                        <div class="col-sm-10">
                                                            <input value="{{ $profile->username }}" type="text" required
                                                                class="form-control" name="username" placeholder="Username">
                                                            @error('username')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label mt-1 mb-2">Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control"name="password"
                                                                placeholder="Password">
                                                            <span style="color: red">* Kosongkan jika tidak ingin
                                                                merubah password</span>
                                                        </div>
                                                    </div>
                                                    <button type="submit" required
                                                        class="btn btn-primary float-right pull-right" name="ubah"
                                                        onclick="return confirm('Apakah Anda Yakin Ingin Merubah Data Ini')">Simpan</button>
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
    <!-- /page content -->
@endsection
