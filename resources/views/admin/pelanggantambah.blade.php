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
                                    <h5 class="m-b-10">Tambah Pelanggan</h5>
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
                                                <h5>Data Pelanggan</h5>
                                            </div>
                                            <div class="card-block">
                                                <form action="{{ url('pelanggantambahsimpan') }}" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Nama</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="namapelanggan"
                                                                placeholder="Nama">
                                                            @error('namapelanggan')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">No HP</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="nohp"
                                                                placeholder="nohp">
                                                            @error('nohp')
                                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Alamat</label>
                                                        <div class="col-sm-10">
                                                            <textarea class="form-control" name="alamat" placeholder="Alamat" rows="4"></textarea>
                                                            @error('alamat')
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
