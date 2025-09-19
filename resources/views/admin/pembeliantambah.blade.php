@extends('layouts.admintemplate')
@section('title','Tambah Pembelian')
@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Tambah Pembelian</h5>
      </div>
      <div class="card-body">
        <form action="{{ url('bookingtambahsimpan') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Pilih Sales --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Sales</label>
            <div class="col-sm-10">
              <select name="idpelanggan" class="form-control" required>
                <option value="">-- Pilih Sales --</option>
                <option value="toyeb">Toyeb - 081291092121</option>
                <option value="budi">Budi - 081234567890</option>
                <option value="sinta">Sinta - 082233445566</option>
              </select>
            </div>
          </div>

          {{-- Pilih Barang --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Barang</label>
            <div class="col-sm-10">
              <select name="idkamar" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                <option value="Bola Junior">Bola Junior - Rp. 200.000</option>
                <option value="Bola Senior">Bola Senior - Rp. 350.000</option>
                <option value="Jersey Anak">Jersey Anak - Rp. 150.000</option>
              </select>
            </div>
          </div>

          {{-- No Invoice --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">No. Invoice</label>
            <div class="col-sm-10">
              <input type="text" name="noinvoice" class="form-control" value="INV{{ date('YmdHis') }}" readonly>
            </div>
          </div>

          {{-- Tanggal Barang Masuk --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Barang Masuk</label>
            <div class="col-sm-10">
              <input type="date" name="tanggalbooking" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
          </div>

          {{-- Jumlah Barang --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Jumlah Barang</label>
            <div class="col-sm-10">
              <input type="number" name="jumlahorang" class="form-control" min="1" required>
            </div>
          </div>

          {{-- No HP Sales --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">No. HP Sales</label>
            <div class="col-sm-10">
              <input type="text" name="nohp" class="form-control" required>
            </div>
          </div>

          {{-- Foto Barang --}}
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Foto Barang</label>
            <div class="col-sm-10">
              <input type="file" name="fotoidentitas" class="form-control">
            </div>
          </div>

          <hr>

          {{-- Barang Tambahan --}}
          <h5 class="mb-3">Barang Tambahan</h5>
          <div id="layanan-wrapper">
            <div class="row mb-3 layanan-item">
              <div class="col-sm-6">
                <select name="layanan[0][idlayanantambahan]" class="form-control">
                  <option value="">-- Pilih Barang --</option>
                  <option value="01">Bola Ladies - Rp. 180.000</option>
                  <option value="02">Kaos Latihan - Rp. 120.000</option>
                  <option value="03">Sepatu Futsal - Rp. 400.000</option>
                </select>
              </div>
              <div class="col-sm-4">
                <input type="number" name="layanan[0][jumlah]" class="form-control" placeholder="Jumlah">
              </div>
              <div class="col-sm-2">
                <button type="button" class="btn btn-success add-layanan">+</button>
              </div>
            </div>
          </div>

          <hr>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ url('booking') }}" class="btn btn-secondary">Batal</a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  let layananIndex = 1;
  document.addEventListener('click', function(e) {
    if(e.target.classList.contains('add-layanan')) {
      e.preventDefault();
      let wrapper = document.getElementById('layanan-wrapper');
      let newRow = document.createElement('div');
      newRow.classList.add('row','mb-3','layanan-item');
      newRow.innerHTML = `
        <div class="col-sm-6">
          <select name="layanan[${layananIndex}][idlayanantambahan]" class="form-control">
            <option value="">-- Pilih Barang --</option>
            <option value="01">Bola Ladies - Rp. 180.000</option>
            <option value="02">Kaos Latihan - Rp. 120.000</option>
            <option value="03">Sepatu Futsal - Rp. 400.000</option>
          </select>
        </div>
        <div class="col-sm-4">
          <input type="number" name="layanan[${layananIndex}][jumlah]" class="form-control" placeholder="Jumlah">
        </div>
        <div class="col-sm-2">
          <button type="button" class="btn btn-danger remove-layanan">-</button>
        </div>
      `;
      wrapper.appendChild(newRow);
      layananIndex++;
    }

    if(e.target.classList.contains('remove-layanan')) {
      e.preventDefault();
      e.target.closest('.layanan-item').remove();
    }
  });
</script>
@endsection
