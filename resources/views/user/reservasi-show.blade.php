@extends('layouts.userlayouts')
@section('title','Detail Reservasi')
@section('content')
@section('styles')
<style>
    @media print {
    .btn, .card-header small {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    body {
        background: white !important;
    }
}
</style>

@endsection

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Card Utama -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between bg-info">
                    <h5 class="mb-0 text-white">
                        Reservasi #{{ $reservasi->id }}
                    </h5>
                    <small class="text-muted float-end">
                        <span class="badge bg-label-{{ $reservasi->status == 'pending' ? 'warning' : ($reservasi->status == 'success' ? 'success' : 'danger') }}">
                            {{ ucfirst($reservasi->status) }}
                        </span>
                    </small>
                </div>

                <div class="card-body mt-4">
                    <div class="row g-4">
                        <div class="col-12 col-lg-7">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-dark fw-normal mb-2">
                                        <i class="bx bx-user me-1"></i>
                                        Informasi Pelanggan
                                    </h6>
                                    <div class="ps-3 mb-3">
                                        <p class="mb-1"><strong>Nama:</strong> {{ $reservasi->pelanggan->nam ?? '-' }}</p>
                                        <p class="mb-1"><strong>No. HP:</strong> {{ $reservasi->pelanggan->nowa ?? '-' }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $reservasi->pelanggan->ema ?? '-' }}</p>
                                        <p class="mb-0"><strong>Alamat:</strong> {{ $reservasi->pelanggan->ala ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-5">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="text-dark fw-normal mb-2">
                                        <i class="bx bx-calendar-event me-1"></i>
                                        Detail Reservasi
                                    </h6>
                                    <div class="ps-3">
                                         <div class="ps-3">
                                        <p class="mb-1"><strong>Tanggal:</strong> {{ date('d/m/Y', strtotime($reservasi->tanggalreservasi)) }}</p>
                                        <p class="mb-1"><strong>Waktu:</strong> {{ $reservasi->waktureservasi }}</p>
                                        <p class="mb-0"><strong>Dibuat:</strong> {{ $reservasi->created_at ? \Carbon\Carbon::parse($reservasi->created_at)->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 colp-md-12 col-sm-12">
                            <div class="card h-100">

                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                         <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="card h-100">
                                <div class="card-body">
                                    @if($reservasi->items && $reservasi->items->count() > 0)
                                    <h6 class="text-dark fw-bold mb-3">
                                        <i class="bx bx-list-ul me-1"></i>
                                        Detail Pesanan
                                    </h6>
                                    <div class="table-responsive text-nowrap mb-3">
                                        <table class="table table-sm ">
                                            <thead>
                                                <tr class="text-nowrap">
                                                    <th>Item</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($reservasi->items as $item)
                                                <tr>
                                                    <td>{{ $item->nama_barang }}</td>
                                                    <td class="text-center">{{ $item->qty }}</td>
                                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif

                                    <!-- Total -->
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-3">
                                        <h6 class="mb-0">
                                            <i class="bx bx-money me-2"></i>
                                            Total:
                                        </h6>
                                        <h5 class="mb-0 text-primary fw-bold">
                                            Rp {{ number_format($reservasi->total_preorder, 0, ',', '.') }}
                                        </h5>
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <div class="d-flex gap-2">
                                        @if($reservasi->status == 'pending' && $snapToken)
                                        <button type="button" class="btn btn-primary w-100" id="pay-button">
                                            <i class="bx bx-credit-card me-2"></i> Bayar Sekarang
                                        </button>
                                        @endif

                                        <a href="{{ url('reservasi') }}" class="btn btn-outline-secondary w-100">
                                            <i class="bx bx-arrow-back me-2"></i> Kembali
                                        </a>

                                        @if($reservasi->status == 'success')
                                        <button type="button" class="btn btn-outline-success w-100" onclick="window.print()">
                                            <i class="bx bx-printer me-2"></i> Cetak
                                        </button>
                                        @endif
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

@section('script')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

@if($snapToken)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        
        if (payButton) {
            payButton.addEventListener('click', function() {
                snap.pay("{{ $snapToken }}", {
                    onSuccess: function(result) {
                        console.log("Payment success", result);
                        
                        // Show success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Terima kasih, pembayaran Anda telah berhasil diproses.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect atau refresh halaman
                                window.location.reload();
                            }
                        });
                    },
                    onPending: function(result) {
                        console.log("Payment pending", result);
                        
                        Swal.fire({
                            icon: 'info',
                            title: 'Pembayaran Tertunda',
                            text: 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.',
                            confirmButtonText: 'OK'
                        });
                    },
                    onError: function(result) {
                        console.error("Payment error", result);
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
                            confirmButtonText: 'OK'
                        });
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        
                        // Optional: show notification when user closes popup
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'warning',
                            title: 'Pembayaran dibatalkan'
                        });
                    }
                });
            });
        }

        // Auto-trigger payment jika dari redirect langsung
        @if(session('auto_pay'))
        // Trigger pembayaran otomatis jika diperlukan
        setTimeout(() => {
            if (payButton) {
                payButton.click();
            }
        }, 1000);
        @endif
    });
</script>
@endif

@endsection
