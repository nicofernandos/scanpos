@extends('layouts.userlayouts')
@section('title','Detail Sale Order')

@section('content')

@section('style')
<style>
.order-status-badge {
  font-size: 0.875rem;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 600;
}

.status-completed {
  background-color: #28a745;
  color: white;
}

.status-pending {
  background-color: #ffc107;
  color: #000;
}

.status-cancelled {
  background-color: #dc3545;
  color: white;
}

.info-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 12px;
  padding: 1.5rem;
}

.info-card .info-label {
  opacity: 0.9;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.info-card .info-value {
  font-size: 1.25rem;
  font-weight: 600;
}

.timeline {
  position: relative;
  padding-left: 2rem;
}

.timeline::before {
  content: '';
  position: absolute;
  left: 15px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: #e3e6ed;
}

.timeline-item {
  position: relative;
  margin-bottom: 1.5rem;
}

.timeline-item::before {
  content: '';
  position: absolute;
  left: -23px;
  top: 4px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #696cff;
}

.timeline-item.completed::before {
  background: #28a745;
}

.product-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 8px;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

@media (max-width: 768px) {
  .action-buttons {
    justify-content: center;
  }
  
  .action-buttons .btn {
    flex: 1;
    min-width: 120px;
  }
}

.product-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
}

/* Mobile optimizations */
@media (max-width: 767px) {
  .card-body {
    padding: 0 !important;
  }
  
  .border-bottom:last-child {
    border-bottom: none !important;
  }
}

/* Tablet optimizations */
@media (min-width: 768px) and (max-width: 991px) {
  .table th, .table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.875rem;
  }
  
  .product-image {
    width: 45px;
    height: 45px;
  }
}

/* Small desktop optimizations */
@media (min-width: 992px) and (max-width: 1199px) {
  .table th, .table td {
    padding: 0.75rem;
  }
}

</style>
@endsection

<div class="row">
  <div class="col-lg-12 col-md-6 col-sm-12">
    <!-- Header Card -->
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between bg-white">
        <div>
          <h4 class="mb-1">
            <i class="bx bx-receipt me-2"></i>Detail Sale Order
          </h4>
          <p class="text-muted mb-0">Order {{ $saleorder->noso ?? '001' }}</p>
        </div>

      </div>
    </div>

    <!-- Order Summary -->
    <div class="row mb-4">
      <div class="col-lg-12 col-md-6 col-sm-6">
        <div class="row g-3">
          <div class="col-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-start justify-content-start">
                    <div class="text-start">
                        <div class="info-label">Total Pesananan</div>
                        <div class="info-value">Rp {{ number_format($saleorder->tot, 0, ',','.') }}  </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-start justify-content-start">
                    <div class="text-start">
                        <div class="info-label">Total Item</div>
                        <div class="info-value"> {{ $saleorder->salesorderdetails->sum('qty')  }} </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Order Information -->
      <div class="col-lg-12 col-md-6 col-sm-6">
        <!-- Customer Information -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="bx bx-user me-2"></i>Informasi Pelanggan
            </h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-muted">No. HP</label>
                  <div class="fw-medium">{{ $saleorder->nohp ?? '+62 812-3456-7890' }}</div>
                </div>
                <div class="mb-3">
                  <label class="form-label text-muted">Nama Meja</label>
                  <div class="fw-medium">
                    <i class="bx bx-table me-1"></i>{{ $saleorder->meja->nam ?? 'Meja 1' }}
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-muted">Tanggal Order</label>
                  <div class="fw-medium">{{ date('d M Y', strtotime($saleorder->tgl)) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Items -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="bx bx-list-ul me-2"></i>Detail Pesanan
            </h5>
          </div>
          <div class="card-body p-0">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center" width="60">No</th>
                      <th class="text-start"  >Nama Produk</th>
                      <th class="text-center" width="100">Qty</th>
                      <th class="text-end" width="120">Harga</th>
                      <th class="text-end" width="140">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no = 1; @endphp
                    @forelse($saleorder->salesorderdetails as $item)
                    <tr>
                      <td class="text-center">{{ $no++ }}</td>
                      <td>
                        <div class="fw-medium">{{ $item->nam ?? 'Produk Tidak Ditemukan' }}</div>
                        <small class="text-muted">Per {{ $item->sat ?? 'pcs' }}</small>
                      </td>
                      <td class="text-center">
                        <span class="badge bg-primary rounded-pill">{{ number_format($item->qty) }}</span>
                      </td>
                      <td class="text-end">
                        <span class="fw-medium">Rp {{ number_format($item->har, 0, ',', '.') }}</span>
                      </td>
                      <td class="text-end">
                        <span class="fw-bold text-success">
                          Rp {{ number_format($item->qty * $item->har, 0, ',', '.') }}
                        </span>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="6" class="text-center py-4">
                        <i class="bx bx-info-circle text-muted me-2"></i>
                        <span class="text-muted">Tidak ada item pesanan</span>
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                  <tfoot class="table-light">
                    <tr>
                      <th colspan="5" class="text-end py-3">
                        <span class="fs-5">Grand Total:</span>
                      </th>
                      <th class="text-end py-3">
                        <span class="fs-5 fw-bold text-success">
                          Rp {{ number_format($saleorder->salesorderdetails->sum(function($item) { return $item->qty * $item->har; }), 0, ',', '.') }}
                        </span>
                      </th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <!-- Mobile Card View -->
            <div class="d-block d-md-none">
              @php $no = 1; @endphp
              @forelse($saleorder->salesorderdetails as $item)
              <div class="border-bottom p-3">
                <div class="row align-items-center">
                  <div class="col-9">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <h6 class="mb-1 fw-medium">{{ $item->barang->nam ?? 'Produk Tidak Ditemukan' }}</h6>
                        <small class="text-muted">Per {{ $item->barang->sat ?? 'pcs' }}</small>
                      </div>
                      <span class="badge bg-primary rounded-pill">{{ number_format($item->qty) }}x</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                      <div> 
                        <div class="fw-medium">Rp {{ number_format($item->har, 0, ',', '.') }}</div>
                      </div>
                      <div class="text-end">
                        <div class="text-muted small">Subtotal</div>
                        <div class="fw-bold text-success">Rp {{ number_format($item->qty * $item->har, 0, ',', '.') }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @empty
              <div class="text-center py-4">
                <i class="bx bx-info-circle text-muted me-2" style="font-size: 2rem;"></i>
                <div class="text-muted mt-2">Tidak ada item pesanan</div>
              </div>
              @endforelse

              <!-- Mobile Total -->
              @if($saleorder->salesorderdetails->count() > 0)
              <div class="p-3 bg-light">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold fs-5">Grand Total:</span>
                  <span class="fw-bold fs-5 text-success">
                    Rp {{ number_format($saleorder->salesorderdetails->sum(function($item) { return $item->qty * $item->har; }), 0, ',', '.') }}
                  </span>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script>
function printOrder() {
  // Implementasi print
  window.print();
}

function shareOrder() {
  if (navigator.share) {
    navigator.share({
      title: 'Sale Order Details',
      text: 'Detail pesanan #SO-{{ $saleorder->id ?? "001" }}',
      url: window.location.href
    });
  } else {
    // Fallback: copy link to clipboard
    navigator.clipboard.writeText(window.location.href).then(() => {
      showNotification('success', 'Link berhasil disalin!');
    });
  }
}

function showNotification(type, message) {
  const toast = document.createElement('div');
  toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
  toast.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  document.body.appendChild(toast);
  
  setTimeout(() => {
    if (toast.parentNode) {
      toast.remove();
    }
  }, 3000);
}

// Auto refresh status (optional)
// setInterval(() => {
//   // Implementasi untuk update status real-time
//   console.log('Checking order status...');
// }, 30000);
</script>
@endsection