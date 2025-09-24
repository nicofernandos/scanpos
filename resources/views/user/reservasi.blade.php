  @extends('layouts.userlayouts')
  @section('title','Reservasi')
  @section('content')
  @section('styles')
  <style>
  .product-card {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .card-img-wrapper {
    height: 120px;
    overflow: hidden;
  }

  .card-img-top {
    height: 100%;
    object-fit: cover;
  }

  .cart-item {
    transition: background-color 0.2s;
  }

  .cart-item:hover {
    background-color: #f8f9fa;
  }

  .qty-btn {
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .nav-pills .nav-link {
    border-radius: 20px;
    margin-right: 8px;
  }

  .nav-pills .nav-link.active {
    background-color: #696cff;
  }

  @media (max-width: 768px) {
    .col-6 {
      flex: 0 0 50%;
    }
    
    .card-body.p-2 {
      padding: 0.75rem !important;
    }
  }

  @keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }
  </style>
  @endsection

  <div class="row g-3">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Form Reservasi -->
    <div class="col-12">
      <div class="card">
        <h5 class="card-header bg-primary text-white mb-3">Form Reservasi</h5>
        <div class="card-body mt-2">
          <form method="POST" action="{{ url('savereservasi') }}" enctype="multipart/form-data" id="reservation-form">
            @csrf
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="p-3 rounded mb-3">
                  <h4 class="text-dark mb-3">Biodata Diri</h4>
                  
                  <div class="mb-3">
                    <label class="form-label">Nomor Handphone</label>
                    <input type="text" name="nohp" class="form-control border-1 border-dark" placeholder="08xxxxxxxxxx" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control border-1 border-dark" placeholder="Masukkan nama lengkap" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempatlahir" class="form-control border-1 border-dark" placeholder="Masukkan tempat lahir" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggallahir" class="form-control border-1 border-dark" placeholder="Masukkan tempat lahir" required>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control border-1 border-dark" placeholder="Masukkan alamat lengkap" required>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control border-1 border-dark" placeholder="contoh@email.com" required>
                  </div>
                  
                </div>
              </div>
              
              <div class="col-lg-6">
                <div class="p-3 rounded mb-3">
                  <h6 class="text-dark mb-3">Tanggal dan Waktu Reservasi</h6>
                  
                  <div class="mb-3">
                    <label class="form-label text-dark">Tanggal Reservasi</label>
                    <input type="date" name="tanggalreservasi" class="form-control border-1 border-dark" required>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label text-dark">Waktu Reservasi</label>
                    <input type="time" name="waktureservasi" class="form-control border-dark" required>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Hidden field for cart items -->
            <input type="hidden" name="items" id="form-items">
            
          </form>
        </div>
      </div>
    </div>

    <!-- Keranjang dan Katalog Produk -->
    <div class="col-sm-6 col-md-6 col-lg-5">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between bg-success text-white">
          <h5 class="mb-0 text-white">
            <i class="bx bx-shopping-bag me-2"></i>Keranjang Pre-Order  
          </h5>
          <span class="badge bg-light text-success" id="cart-count">0</span>
        </div>
        <div class="card-body p-0">
          <!-- Cart Items -->
          <div class="cart-items" style="max-height: 400px; overflow-y: auto;">
            <div class="text-center p-4" id="empty-cart">
              <i class="bx bx-cart text-muted" style="font-size: 3rem;"></i>
              <p class="text-muted mt-2">Belum ada item yang dipilih</p>
            </div>
          </div>

          <!-- Cart Summary -->
          <div class="p-3 border-top bg-light">
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal:</span>
              <span class="fw-bold" id="subtotal">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
              <span>Total Items:</span>
              <span class="fw-bold" id="total-items">0</span>
            </div>
            <small class="text-muted">*Pre-order untuk reservasi</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-6 col-lg-7">
      <div class="card h-100">
        <div class="card-header bg-info d-flex align-items-center justify-content-between">
          <h5 class="mb-0 text-white">Katalog Produk</h5>
          <button class="btn btn-outline-light btn-sm" id="refresh-products">
            <i class="bx bx-refresh"></i>
          </button>
        </div>
        <div class="card-body">
          <div class="mb-3 mt-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-search"></i></span>
              <input type="text" class="form-control" placeholder="Cari produk..." id="search-products">
            </div>
          </div>

          <!-- Category Tabs -->
          <div class="mb-4">
            <ul class="nav nav-pills" id="category-tabs">
              <li class="nav-item">
                <button class="nav-link active" data-category="all">Semua</button>
              </li>
              <!-- Dynamic categories will be added here -->
            </ul>
          </div>

          <div class="row g-3 mb-3 mt-3" id="product-grid" style="max-height: 500px; overflow-y: auto;">
            @foreach($barangs as $barang)
              <div class="col-6 col-md-4 col-lg-3">
                <div class="card product-card h-100"
                    data-category="{{ $barang->kategori ?? '' }}"
                    data-id="{{ $barang->id }}"
                    data-name="{{ $barang->nam }}"
                    data-satuan="{{ $barang->sat }}"
                    data-price="{{ $barang->hargajual }}">
                  <div class="card-body p-2">
                    <div class="card-img-wrapper position-relative my-3">
                      <img src="{{ $barang->foto ? asset('foto/'.$barang->foto) : asset('foto/nopict.jpg') }}" 
                          class="card-img-top" 
                          alt="{{ $barang->nam }}">
                    </div>
                    <h6 class="card-title mb-1 text-truncate">{{ $barang->nam }}</h6>
                    <p class="card-text text-primary fw-bold mb-2">
                      Rp {{ number_format($barang->hargajual, 0, ',', '.') }}
                    </p>
                    <button class="btn btn-outline-info btn-sm w-100 add-to-cart">
                      <i class="bx bx-plus"></i> Tambah
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Button -->
    <div class="col-12">
      <div class="card">
        <div class="card-body text-center">
          <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center gap-2">
              <button type="button" class="btn btn-success btn-lg px-5" id="submit-reservation">
                <i class="bx bx-check-circle me-2"></i>Buat Reservasi
              </button>
              <button type="button" class="btn btn-secondary btn-lg text-white px-5" id="reset-form">
                <i class="bx bx-refresh me-2"></i>Reset Form
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  @endsection

  @section('script')
  <script>
    let cart = [];
    let cartTotal = 0;

    // Event delegation for dynamic elements
    document.addEventListener('click', function(e) {
      // Add to cart
      if (e.target.closest('.add-to-cart')) {
        e.preventDefault();
        const productCard = e.target.closest('.product-card');
        const product = {
          id: productCard.dataset.id,
          name: productCard.dataset.name,
          satuan: productCard.dataset.satuan,
          price: parseInt(productCard.dataset.price),
          quantity: 1
        };
        
        addToCart(product);
      }

      // Remove from cart
      if (e.target.closest('.remove-from-cart')) {
        e.preventDefault();
        const itemId = e.target.closest('.cart-item').dataset.id;
        removeFromCart(itemId);
      }

      // Quantity buttons
      if (e.target.classList.contains('qty-btn') || e.target.closest('.qty-btn')) {
        e.preventDefault();
        const button = e.target.classList.contains('qty-btn') ? e.target : e.target.closest('.qty-btn');
        const cartItem = button.closest('.cart-item');
        const itemId = cartItem.dataset.id;
        const action = button.dataset.action;
        updateQuantity(itemId, action);
      }

      // Category filter
      if (e.target.hasAttribute('data-category')) {
        e.preventDefault();
        document.querySelectorAll('[data-category]').forEach(link => link.classList.remove('active'));
        e.target.classList.add('active');
        filterProducts(e.target.dataset.category);
      }
    });

    function addToCart(product) {
      console.log('Adding to cart:', product);
      
      const existingItem = cart.find(item => item.id === product.id);
      
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({ ...product });
      }
      
      updateCartDisplay();
      showToast('success', `${product.name} ditambahkan ke keranjang`);
    }

    function removeFromCart(itemId) {
      console.log('Removing from cart:', itemId);
      cart = cart.filter(item => item.id !== itemId);
      updateCartDisplay();
      showToast('info', 'Item dihapus dari keranjang');
    }

    function updateQuantity(itemId, action) {
      console.log('Updating quantity:', itemId, action);
      
      const item = cart.find(item => item.id === itemId);
      if (!item) return;

      if (action === 'increase') {
        item.quantity += 1;
      } else if (action === 'decrease' && item.quantity > 1) {
        item.quantity -= 1;
      } else if (action === 'decrease' && item.quantity === 1) {
        removeFromCart(itemId);
        return;
      }
      
      updateCartDisplay();
    }

    function updateCartDisplay() {
      console.log('Updating cart display, current cart:', cart);
      
      const cartItemsContainer = document.querySelector('.cart-items');
      const cartCount = document.getElementById('cart-count');
      const subtotal = document.getElementById('subtotal');
      const totalItems = document.getElementById('total-items');

      if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
          <div class="text-center p-4" id="empty-cart">
            <i class="bx bx-cart text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2">Belum ada item yang dipilih</p>
          </div>
        `;
        
        if (cartCount) cartCount.textContent = '0';
        if (subtotal) subtotal.textContent = 'Rp 0';
        if (totalItems) totalItems.textContent = '0';
        
        cartTotal = 0;
        return;
      }

      let html = '';
      let total = 0;
      let itemCount = 0;

      cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        itemCount += item.quantity;

        html += `
          <div class="cart-item border-bottom p-3" data-id="${item.id}">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h6 class="mb-1">${item.name} / ${item.satuan}</h6>
              <button class="btn btn-outline-danger btn-sm remove-from-cart">
                <i class="bx bx-x"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-sm qty-btn" data-action="decrease">
                  <i class="bx bx-minus"></i>
                </button>
                <span class="mx-2 fw-bold">${item.quantity}</span>
                <button class="btn btn-outline-secondary btn-sm qty-btn" data-action="increase">
                  <i class="bx bx-plus"></i>
                </button>
              </div>
              <div class="text-end">
                <div class="text-muted small">Rp ${numberFormat(item.price)}</div>
                <div class="fw-bold text-success">Rp ${numberFormat(itemTotal)}</div>
              </div>
            </div>
          </div>
        `;
      });

      cartItemsContainer.innerHTML = html;
      
      // Update display elements
      if (cartCount) cartCount.textContent = itemCount;
      if (subtotal) subtotal.textContent = `Rp ${numberFormat(total)}`;
      if (totalItems) totalItems.textContent = itemCount;
      
      cartTotal = total;
      
      console.log('Display updated - Total:', total, 'Items:', itemCount);
    }

    function filterProducts(category) {
      const products = document.querySelectorAll('.product-card');
      products.forEach(product => {
        const productColumn = product.closest('.col-6, .col-md-4, .col-lg-3');
        if (category === 'all' || product.dataset.category === category) {
          productColumn.style.display = 'block';
        } else {
          productColumn.style.display = 'none';
        }
      });
    }

    document.getElementById('search-products').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const products = document.querySelectorAll('.product-card');
      
      products.forEach(product => {
        const productName = product.dataset.name.toLowerCase();
        const productColumn = product.closest('.col-6, .col-md-4, .col-lg-3');
        
        if (productName.includes(searchTerm)) {
          productColumn.style.display = 'block';
        } else {
          productColumn.style.display = 'none';
        }
      });
    });

    document.getElementById('submit-reservation').addEventListener('click', function() {
      const form = document.getElementById('reservation-form');
      const formData = new FormData(form);
      
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      document.getElementById('form-items').value = JSON.stringify(cart);

      let orderSummary = '';
      if (cart.length > 0) {
        orderSummary = `<p><strong>Pre-order:</strong> Rp ${numberFormat(cartTotal)} (${cart.reduce((sum, item) => sum + item.quantity, 0)} item)</p>`;
      }

      Swal.fire({
        title: 'Konfirmasi Reservasi',
        html: `<div class="text-start">
                <p><strong>Nama:</strong> ${formData.get('nama')}</p>
                <p><strong>Tanggal:</strong> ${formData.get('tanggalreservasi')}</p>
                <p><strong>Waktu:</strong> ${formData.get('waktureservasi')}</p>
                ${orderSummary}
              </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bx bx-check"></i> Ya, Buat Reservasi',
        cancelButtonText: '<i class="bx bx-x"></i> Batal',
        customClass: {
          popup: 'swal-wide'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Memproses Reservasi...',
            html: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            willOpen: () => {
              Swal.showLoading();
            }
          });
          
          form.submit();
        }
      });
    });

    document.getElementById('reset-form').addEventListener('click', function() {
      Swal.fire({
        title: 'Reset Form?',
        text: 'Semua data dan keranjang akan dihapus',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('reservation-form').reset();
          cart = [];
          updateCartDisplay();
          showToast('info', 'Form berhasil direset');
        }
      });
    });

    // Refresh products
    document.getElementById('refresh-products')?.addEventListener('click', function() {
      showToast('info', 'Produk telah diperbarui');
      location.reload();
    });

    // Utility functions
    function numberFormat(number) {
      return new Intl.NumberFormat('id-ID').format(number);
    }

    function showToast(type, message) {
      // Remove existing toasts
      const existingToasts = document.querySelectorAll('.toast-notification');
      existingToasts.forEach(toast => toast.remove());
      
      const toast = document.createElement('div');
      toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed toast-notification`;
      toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease;';
      toast.innerHTML = `
        <div class="d-flex align-items-center">
          <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2"></i>
          ${message}
          <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
      `;
      
      document.body.appendChild(toast);
      
      setTimeout(() => {
        if (toast.parentNode) {
          toast.remove();
        }
      }, 3000);
    }

    // Initialize categories
    function initializeCategories() {
      const categories = new Set();
      document.querySelectorAll('.product-card').forEach(card => {
        const category = card.dataset.category;
        if (category && category !== 'all') {
          categories.add(category);
        }
      });
      
      const categoryTabs = document.getElementById('category-tabs');
      categories.forEach(category => {
        const li = document.createElement('li');
        li.className = 'nav-item';
        li.innerHTML = `<button class="nav-link" data-category="${category}">${category.charAt(0).toUpperCase() + category.slice(1)}</button>`;
        categoryTabs.appendChild(li);
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      console.log('Reservation System initialized');
      updateCartDisplay();
      initializeCategories();
      
      const today = new Date().toISOString().split('T')[0];
      document.querySelector('input[name="tanggalreservasi"]').setAttribute('min', today);
    });


  document.querySelector('input[name="nohp"]').addEventListener('blur', function() {
      let nohp = this.value.trim();
      if(nohp.length < 8) return; 

      fetch(`/cekpelanggan?nohp=${nohp}`)
          .then(res => res.json())
          .then(data => {
              if(data.status === 'found'){
                  document.querySelector('input[name="nama"]').value   = data.nama;
                  document.querySelector('input[name="alamat"]').value = data.alamat;
                  document.querySelector('input[name="email"]').value  = data.email;
                  document.querySelector('input[name="tempatlahir"]').value  = data.tempatlahir;
                  document.querySelector('input[name="tanggallahir"]').value  = data.tanggallahir;

                  document.querySelector('input[name="nama"]').readOnly   = true;
                  document.querySelector('input[name="alamat"]').readOnly = true;
                  document.querySelector('input[name="email"]').readOnly  = true;
                  document.querySelector('input[name="tempatlahir"]').readOnly  = true;
                  document.querySelector('input[name="tanggallahir"]').readOnly  = true;

                  showToast('success', 'Data pelanggan ditemukan & otomatis terisi');
              } else {
                  document.querySelector('input[name="nama"]').value   = '';
                  document.querySelector('input[name="alamat"]').value = '';
                  document.querySelector('input[name="email"]').value  = '';
                  document.querySelector('input[name="tempatlahir"]').value  = '';
                  document.querySelector('input[name="tanggallahir"]').value  = '';

                  document.querySelector('input[name="nama"]').readOnly   = false;
                  document.querySelector('input[name="alamat"]').readOnly = false;
                  document.querySelector('input[name="email"]').readOnly  = false;
                  document.querySelector('input[name="tempatlahir"]').readOnly  = false;
                  document.querySelector('input[name="tanggallahir"]').readOnly  = false;

                  showToast('info', 'Pelanggan baru, silakan isi data lengkap');
              }
          })
          .catch(err => console.error(err));
  });
  </script>
  @endsection