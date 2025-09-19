@extends('layouts.admintemplate')
@section('title','Edit Pelanggan')
@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Edit Pelanggan</h5>
      </div>
      <div class="card-body">
        <form action="{{ url('pelangganeditsimpan/'.$pelanggan->idpelanggan) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
            <div class="col-sm-10">
              <input type="text" name="namapelanggan" class="form-control" 
                     value="{{ $pelanggan->namapelanggan }}" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">No. HP</label>
            <div class="col-sm-10">
              <input type="text" name="nohp" class="form-control" 
                     value="{{ $pelanggan->nohp }}" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
              <textarea name="alamat" class="form-control" rows="3" required>{{ $pelanggan->alamat }}</textarea>
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Update</button>
              <a href="{{ url('pelanggan') }}" class="btn btn-secondary">Batal</a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
@endsection
