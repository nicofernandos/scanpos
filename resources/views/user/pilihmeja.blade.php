@extends('layouts.userlayouts')

@section('title','Pilih Meja - Point Of Sale')

@section('content')

@section('style')
<style>
.table-card {
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  height: 100%;
}

.table-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  border-color: #696cff;
  padding: 10px;
}

.table-card.occupied {
  border-color: #ff3e1d;
  background-color: #fff5f5;
}

.table-card.available {
  border-color: #71dd37;
  background-color: #f8fff8;
}

.table-card.reserved {
  border-color: #ffab00;
  background-color: #fffbf0;
}

.table-image {
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
}

.table-status {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.status-available {
  background-color: #71dd37;
  color: white;
}

.status-occupied {
  background-color: #ff3e1d;
  color: white;
}

.status-reserved {
  background-color: #ffab00;
  color: white;
}

.table-info {
  text-align: center;
}

.table-name {
  font-size: 1.1rem;
  font-weight: 600;
  color: #5f61e6;
  margin-bottom: 8px;
}

.table-capacity {
  color: #6c757d;
  font-size: 0.9rem;
}

.search-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 12px;
  padding: 2rem;
  margin-bottom: 2rem;
}

.filter-buttons .btn {
  margin-right: 8px;
  margin-bottom: 8px;
  border-radius: 20px;
}

@media (max-width: 768px) {
  .table-card {
    margin-bottom: 1rem;
  }
  
  .search-section {
    padding: 1.5rem;
  }
}
</style>
@endsection

<div class="row" id="tablesGrid">
  @foreach($mejas as $meja)
    @php
      // Tentukan status class sesuai kolom "sta" dari database
      $status = match($meja->sta) {
        'occupied' => 'occupied',
        'reserved' => 'reserved',
        default => 'available',
      };

      // Tentukan label status
      $statusLabel = match($meja->sta) {
        'occupied' => 'Terisi',
        'reserved' => 'Reservasi',
        default => 'Tersedia',
      };
    @endphp

    <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-3 mb-4" 
         data-status="{{ $status }}" 
         data-name="{{ $meja->nam }}">
      <div class="card table-card {{ $status }}" 
           onclick="selectTable({{ $meja->id }}, '{{ $meja->nam }}', '{{ $status }}')">
        <div class="card-body p-3 position-relative">
          <img src="{{ $meja->img ? asset('storage/'.$meja->img) :  asset('foto/nopict.jpg') }}" 
               alt="{{ $meja->nam }}" class="table-image w-100 mb-3">
          <span class="table-status status-{{ $status }}">{{ $statusLabel }}</span>
          <div class="table-info">
            <div class="table-name">{{ $meja->nam }}</div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<!-- Empty State -->
<div id="emptyState" class="text-center py-5" style="display: none;">
  <i class="bx bx-search-alt-2 text-muted" style="font-size: 4rem;"></i>
  <h5 class="mt-3 text-muted">Tidak ada meja ditemukan</h5>
  <p class="text-muted">Coba ubah filter atau kata kunci pencarian</p>
</div>

@endsection

@section('script')
<script>
// Function untuk memilih meja
function selectTable(tableId, tableName, status) {
  if (status !== 'available') {
    Swal.fire({
      icon: 'warning',
      title: 'Meja tidak tersedia',
      text: 'Silahkan pilih meja lain yang tersedia.',
      confirmButtonText: 'OK'
    });
    return;
  }

  // Konfirmasi 
  Swal.fire({
    title: `Pilih ${tableName}?`,
    text: "Apakah Anda yakin memilih meja ini?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#696cff',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, pilih',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      // Redirect ke halaman saleorder dengan id meja
      window.location.href = `/saleorder?meja=${tableId}`;
    }
  });
}


// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
  const filterButtons = document.querySelectorAll('[data-filter]');
  const tableCards = document.querySelectorAll('[data-status]');
  const searchInput = document.getElementById('searchTable');
  const emptyState = document.getElementById('emptyState');
  const tablesGrid = document.getElementById('tablesGrid');

  // Filter buttons event
  filterButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Update active button
      filterButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      
      const filter = this.dataset.filter;
      filterTables(filter);
    });
  });

  // Search input event
  searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    searchTables(searchTerm);
  });

  function filterTables(filter) {
    let visibleCount = 0;
    
    tableCards.forEach(card => {
      const status = card.dataset.status;
      const shouldShow = filter === 'all' || status === filter;
      
      if (shouldShow) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    toggleEmptyState(visibleCount === 0);
  }

  function searchTables(searchTerm) {
    let visibleCount = 0;
    
    tableCards.forEach(card => {
      const tableName = card.dataset.name.toLowerCase();
      const shouldShow = tableName.includes(searchTerm);
      
      if (shouldShow) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    toggleEmptyState(visibleCount === 0);
  }

  function toggleEmptyState(show) {
    if (show) {
      emptyState.style.display = 'block';
      tablesGrid.style.display = 'none';
    } else {
      emptyState.style.display = 'none';
      tablesGrid.style.display = 'flex';
    }
  }
});

// Notification function
function showNotification(type, title, message) {
  // Buat elemen notifikasi
  const notification = document.createElement('div');
  notification.className = `alert alert-${type === 'warning' ? 'warning' : type} alert-dismissible fade show position-fixed`;
  notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
  notification.innerHTML = `
    <strong>${title}</strong><br>
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  document.body.appendChild(notification);
  
  // Auto remove setelah 5 detik
  setTimeout(() => {
    if (notification.parentNode) {
      notification.remove();
    }
  }, 5000);
}

// Update status meja secara real-time (opsional)
function updateTableStatus(tableId, newStatus) {
  const tableCard = document.querySelector(`[onclick*="selectTable(${tableId}"]`).closest('[data-status]');
  const statusBadge = tableCard.querySelector('.table-status');
  const cardElement = tableCard.querySelector('.table-card');
  
  // Update data attribute
  tableCard.dataset.status = newStatus;
  
  // Update visual
  cardElement.className = `card table-card ${newStatus}`;
  statusBadge.className = `table-status status-${newStatus}`;
  
  // Update text
  const statusText = {
    'available': 'Tersedia',
    'occupied': 'Terisi', 
    'reserved': 'Reservasi'
  };
  statusBadge.textContent = statusText[newStatus];
  
  // Update onclick untuk meja yang tidak tersedia
  if (newStatus !== 'available') {
    cardElement.removeAttribute('onclick');
  }
}
</script>
@endsection