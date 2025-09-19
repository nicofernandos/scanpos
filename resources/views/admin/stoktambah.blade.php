@extends('layouts.admintemplate')
@section('title','Tambah Stok')
@section('content')

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Tambah Stok</h5>
      </div>
      <div class="card-body">
        <form action="{{ url('tambahlayanansimpan') }}" method="POST">
          @csrf
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nama Barang</label>
            <div class="col-sm-10">
              <input type="text" name="" class="form-control" placeholder="Contoh: Beras Enak" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Stok</label>
            <div class="col-sm-10">
              <input type="number" name="" class="form-control" placeholder="Contoh: 20000" required>
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ url('stok') }}" class="btn btn-secondary">Batal</a>
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