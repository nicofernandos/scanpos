@extends('layouts.admintemplate')
@section('title','Data Penjualan')
@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Tambah Penjualan</h5>
      </div>
      <div class="card-body">
        <form action="{{ url('tambahpelanggansimpan') }}" method="POST">
          @csrf
          

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Nama Pelanggan</label>
            <div class="col-sm-10">
              <input type="text" name="nohp" class="form-control" placeholder="Contoh: 08123456789" required>
            </div>
          </div>


          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">No. HP</label>
            <div class="col-sm-10">
              <input type="text" name="nohp" class="form-control" placeholder="Contoh: 08123456789" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
              <input type="text" name="nohp" class="form-control" placeholder="Contoh: SugengPpe" required>
            </div>
          </div>



          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
              <textarea name="alamat" class="form-control" placeholder="Tulis alamat lengkap pelanggan..." rows="3" required></textarea>
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Simpan</button>
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