@extends('layouts.admintemplate')
@section('title','Detail Penjualan')

@section('content')
<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">
          <i class="bx bx-receipt me-2"></i>Detail Penjualan
        </h5>
        <div>
          <a href="{{ url('penjualan') }}" class="btn btn-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i>Kembali
          </a>
          <a href="{{ url('penjualan/cetak/'.$penjualan->id) }}" class="btn btn-primary btn-sm" target="_blank">
            <i class="bx bx-printer me-1"></i>Cetak
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Informasi Invoice -->
          <div class="col-md-6 mb-3">
            <div class="bg-light p-3 rounded">
              <h6 class="fw-bold text-primary mb-3">
                <i class="bx bx-file-blank me-2"></i>Informasi Invoice
              </h6>
              <div class="row mb-2">
                <div class="col-sm-4">
                  <span class="fw-medium">No. Invoice:</span>
                </div>
                <div class="col-sm-8">
                  <span class="badge bg-primary">{{ $penjualan->nofp }}</span>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-4">
                  <span class="fw-medium">Tanggal:</span>
                </div>
                <div class="col-sm-8">
                  <span>{{ date('d/m/Y', strtotime($penjualan->tglfp)) }}</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Detail Barang -->
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="bx bx-list-ul me-2"></i>Detail Barang
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead class="table-white">
              <tr>
                <th class="text-center" width="50">No</th>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @php $no = 1; @endphp
              @forelse($penjualan->penjualanDetails as $detail)
              <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>
                  <span class="fw-medium">{{ $detail->idbar ?? '-' }}</span>
                </td>
                <td>
                  <div>
                    <span class="fw-medium">{{ $detail->nam ?? 'Barang Dihapus' }}</span>
                    {{-- @if($detail->barang->merk ?? 'Merk Tersedia')
                    <br><small class="text-muted">{{ $detail->barang->merk ?? 'Merk Tidak Ada'}}</small>
                    @endif --}}
                  </div>
                </td>
                <td class="text-center">
                  <span class="badge bg-info">{{ number_format($detail->qty) }}</span> <small class="text-dark"> / {{$detail->sat}}</small>
                </td>
                <td class="text-end">
                  <span class="fw-medium">Rp {{ number_format($detail->har, 0, ',', '.') }}</span>
                </td>
                <td class="text-end">
                    <span class="fw-bold text-success">
                        Rp{{ number_format($detail->qty * $detail->har, 0, ',', '.') }}
                    </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted">
                  <i class="bx bx-info-circle me-2"></i>Tidak ada detail barang
                </td>
              </tr>
              @endforelse
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5" class="text-end">Total</th>
                <th class="text-end">
                  <span class="fw-bold text-success">
                    Rp {{ number_format($penjualan->totnet, 0, ',', '.') }}
                    
                  </span>
                </th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>


@endsection

@section('script')

@endsection