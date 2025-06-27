<header class="navbar">
    <div class="navbar-left">
      <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung" class="logo-warung">
    </div>  
        <nav>
                <a href="{{ route('home')}}">Beranda</a>
                <a href="{{ route('tentang')}}">Tentang Kami</a>
                <a href="{{ route('menu.makanan')}}">Menu</a>
                <a href="#kontak">Kontak</a>
        </nav>
        <a href="{{ route('menu.makanan')}}" class="btn-pesan">Pesan Sekarang</a>
    <div class="navbar-right">
        <div class="icon-group">
           <button type="button" class="btn position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fas fa-shopping-cart icon"></i>
                <span class="cart-count" id="cart-count">0</span>
            </button>
       <div class="user-dropdown-wrapper" style="position: relative;">
    <div class="user-icon" onclick="toggleUserDropdown()">
      <i class="fas fa-user icon"></i>
    </div>

    <!-- Dropdown Profil -->
    <div id="userDropdown" class="user-dropdown hidden">
      @if(Auth::check() && Auth::user()->role === 'pelanggan')
        <div class="dropdown-header">
          <p>Halo, <strong>{{ Auth::user()->name }}</strong></p>
          <small>Kelola akun & pesanan Anda</small>
        </div>

        <ul class="dropdown-list">
          <li><a href="{{ route('profil.edit') }}"><i class="fas fa-user-circle"></i> Profil Saya</a></li>
          <li><a href="#"><i class="fas fa-box-open"></i> Pesanan Saya</a></li>
        </ul>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn logout-btn">Logout</button>
        </form>
      @else
        <div class="dropdown-header">
          <p>Selamat Datang di <br><strong>Warung Ajus</strong></p>
          <small>Akses akun & kelola pesanan</small>
        </div>

        <div class="dropdown-actions">
          <a href="{{ route('login') }}" class="btn login-btn">Login</a>
          <a href="{{ route('register') }}" class="btn register-btn">Daftar</a>
        </div>
      @endif
    </div>
  </div>
</div>
</header>

<!-- Modal Keranjang -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title fw-bold" id="cartModalLabel">🛒 Keranjang Anda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div id="cartContainer" class="cart-container"></div>
      </div>
      <div class="modal-footer justify-content-between">
        <h5 id="grandTotal">Total: Rp0</h5>
        <button class="btn btn-lg btn-warning fw-bold px-5" onclick="openCheckoutModal()">
          <i class="fa fa-shopping-cart me-2"></i> Checkout Sekarang
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title fw-bold" id="checkoutModalLabel">🧾Konfirmasi Pesanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <!-- Form Pelanggan -->
        <form id="checkoutForm">
          <div class="mb-3">
            <label for="tipe-pesanan" class="form-label">Tipe Pesanan</label>
            <select id="tipe_pesanan" class="form-select" onchange="toggleCheckoutFields()" required>
              <option value="" disabled selected>-- Pilih Tipe Pesanan --</option>
              <option value="dine_in">Makan di Tempat</option>
              <option value="take_away">Bungkus / Take Away</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nama-pelanggan" class="form-label">Nama Pelanggan</label>
            <input type="text" class="form-control" id="nama_pelanggan" required>
          </div>
          <div class="mb-3" id="no_meja_field" style="display: none;">
            <label for="no-meja" class="form-label">Nomor Meja</label>
            <input type="text" class="form-control" id="no_meja" required>
          </div>

          <!-- Ringkasan Keranjang -->
          <h5 class="fw-bold mt-4">Detail Pesanan:</h5>
          <div id="checkoutSummary" class="checkout-summary"></div>

        </form>    
      </div>
      <div class="modal-footer justify-content-between">
        <h5 id="checkoutTotal">Total: Rp0</h5>
        <button type="submit" form="checkoutForm" class="btn btn-lg btn-warning px-4 fw-bold">Buat Pesanan</button>
      </div>
    </div>
  </div>
</div>


<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const baseAssetUrl = "{{ asset('img') }}/";
  function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const container = document.getElementById('cartContainer');
    container.innerHTML = '';
    let grandTotal = 0;


    if (cart.length === 0) {
      container.innerHTML = '<p>Keranjang masih kosong.</p>';
      document.getElementById('grandTotal').innerText = 'Total: Rp0';
      return;
    }

    cart.forEach((item, index) => {
      const basePrice = Number(item.basePrice) || 0;
      const addonTotal = item.addons.reduce((sum, a) => {
        return sum + Number(a.price || 0);
      }, 0);
      const totalPerItem = (basePrice + addonTotal) * (item.quantity || 1);
      grandTotal += totalPerItem;

      const addonList = item.addons.map(addon => `<li>${addon.name} (+Rp${addon.price.toLocaleString('id-ID')})</li>`).join('');

      container.innerHTML += `
        <div class="card cart-item mb-3">
          <div class="row g-0 align-items-center">
            <div class="col-md-3">
              <img src="/storage/${item.image || 'default.png'}" class="img-fluid rounded-start cart-image" alt="${item.menu}">
            </div>
            <div class="col-md-9">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="card-title"><strong>${item.menu}</strong></h6>
                  <p>${totalPerItem.toLocaleString('id-ID')}</p>
                </div>
                <ul>${addonList || ''}</ul>
                <p class="card-text">Catatan: ${item.note || '-'}</p>
                <div class="mb-2">
                  <button class="btn btn-warning btn-sm btn-edit-menu" onclick="editItem(${index})">Edit</button>
                </div>
                <div class="quantity-control-cart">
                  <i class="fas fa-minus" onclick="changeQty(${index}, -1)"></i>
                  <span class="fw-bold">${item.quantity}</span>
                  <i class="fas fa-plus" onclick="changeQty(${index}, +1)"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
    });

    document.getElementById('grandTotal').innerText = `Total: Rp${grandTotal.toLocaleString()}`;
    updateCartCount();
  }

  function removeItem(index) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
    updateCartCount();
  }

  function changeQty(index, delta) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (!cart[index]) return;

    const currentQty = cart[index].quantity;

    // Jika quantity == 1 dan dikurangi
    if (delta === -1 && currentQty === 1) {
      Swal.fire({
        title: 'Hapus item ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          cart.splice(index, 1);
          localStorage.setItem('cart', JSON.stringify(cart));
          loadCart();
          updateCartCount();
          Swal.fire({
            title: 'Dihapus!',
            text: 'Item berhasil dihapus dari keranjang.',
            icon: 'success',
            timer: 1000,
            showConfirmButton: false
          });
        }
      });
      return;
    }

    cart[index].quantity += delta;
    if (cart[index].quantity < 1) cart[index].quantity = 1;

    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
  }

  function showCheckoutModal() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
      alert("Keranjang masih kosong!");
      return;
    }

    let grandTotal = 0;
    const summaryContanier = document.getElementById('checkoutSummary');
    summaryContanier.innerHTML = '';

    cart.forEach(item => {
      const basePrice = Number(item.basePrice || 0);
      const addonTotal = item.addons.reduce((sum, a) => sum + Number(a.price || 0), 0);
      const total = (basePrice + addonTotal) * (item.quantity || 1);
      grandTotal += total;

      const addonList = item.addons.map(addon => `<li>${addon.name} (+Rp${addon.price.toLocaleString('id-ID')})</li>`).join('');

      summaryContanier.innerHTML += `
        <div class="card mb-3">
          <div class="row g-0 align-items-center">
            <div class="col-md-3">
              <img src="/storage/${item.image || 'default.png'}" class="img-fluid rounded-start cart-image" alt="${item.menu}">
            </div>
            <div class="col-md-9">
              <div class="card-body">
                <h5 class="card-title">${item.menu}</h5>
                <p class="card-text">Jumlah: ${item.quantity}</p>
                <p class="card-text">Catatan: ${item.note || '-'}</p>
                <ul>${addonList || ''}</ul>
                <p><strong>Total: Rp${total.toLocaleString('id-ID')}</strong></p>
              </div>
            </div>
          </div>
        </div>
      `;
    });

    document.getElementById('checkoutTotal').innerText = `Total: Rp${grandTotal.toLocaleString('id-ID')}`;

    new bootstrap.Modal(document.getElementById('checkoutModal')).show();
  }

  function openCheckoutModal() {
    const cartModalEl = document.getElementById('cartModal');
    const cartModalInstance = bootstrap.Modal.getInstance(cartModalEl);
    if (cartModalInstance) {
      cartModalInstance.hide();
    }
    
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
      alert("Keranjang masih kosong!");
      return;
    }

    let grandTotal = 0;
    const summaryContanier = document.getElementById('checkoutSummary');
    summaryContanier.innerHTML = '';

    cart.forEach(item => {
      const basePrice = Number(item.basePrice || 0);
      const addonTotal = item.addons.reduce((sum, a) => sum + Number(a.price || 0), 0);
      const total = (basePrice + addonTotal) * (item.quantity || 1);
      grandTotal += total;

      const addonList = item.addons.map(addon => `<li>${addon.name} (+Rp${addon.price.toLocaleString('id-ID')})</li>`).join('');

      summaryContanier.innerHTML += `
        <div class="card mb-3">
          <div class="row g-0 align-items-center">
            <div class="col-md-3">
              <img src="/storage/${item.image || 'default.png'}" class="img-fluid rounded-start cart-image" alt="${item.menu}">
            </div>
            <div class="col-md-9">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="card-title"><strong>${item.menu}</strong></h6>
                  <p class="card-text">x ${item.quantity}</p>
                </div>
                <p class="card-text">Catatan: ${item.note || '-'}</p>
                <ul>${addonList || ''}</ul>
                <p><strong>Total: Rp${total.toLocaleString('id-ID')}</strong></p>
              </div>
            </div>
          </div>
        </div>
      `;
    });

    document.getElementById('checkoutTotal').innerText = `Total: Rp${grandTotal.toLocaleString('id-ID')}`;

    new bootstrap.Modal(document.getElementById('checkoutModal')).show();
  }

  function toggleCheckoutFields() {
    const tipe = document.getElementById('tipe_pesanan').value;
    const noMeja = document.getElementById('no_meja_field');
    if (tipe === 'dine_in') {
      noMeja.style.display = 'block';
      document.getElementById('no_meja').required = true;
    } else {
      noMeja.style.display = 'none';
      document.getElementById('no_meja').required = false;
    }
  }

    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const tipe_pesanan = document.getElementById('tipe_pesanan').value;
      const nama = document.getElementById('nama_pelanggan').value;
      const no_meja = document.getElementById('no_meja').value;

      const cart = JSON.parse(localStorage.getItem('cart')) || [];

      if (!tipe_pesanan || !nama || (tipe_pesanan === 'dine_in' && !no_meja)) {
        alert("Mohon lengkapi semua data pelanggan.");
        return;
      }

      let grandTotal = 0;
      cart.forEach(item => {
        const basePrice = Number(item.basePrice || 0);
        const addonTotal = item.addons.reduce((sum, a) => sum + Number(a.price || 0), 0);
        grandTotal += (basePrice + addonTotal) * (item.quantity || 1);
      });

    const payload = {
      nama_pelanggan: nama,
      tipe_pesanan,
      no_meja: tipe_pesanan === 'dine_in' ? no_meja : null,
      menu: cart.map(item => ({
        menu_id: item.menu_id,
        basePrice: item.basePrice,
        quantity: item.quantity,
        catatan: item.note || '',
        addons: item.addons || [,]
      }))
    };

    // console.log('Payload yang dikirim:', JSON.stringify(payload, null, 2));

    // console.log('Cart:', cart);

    // Panggil endpoint untuk ambil snap token Midtrans
    fetch('/midtrans/token', {
      method: 'POST',
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
      if (data.snap_token) {
        snap.pay(data.snap_token, {
            onSuccess: function(result) {
            // Setelah pembayaran berhasil, simpan order
            const simpanPayload = {
              nama_pelanggan: nama,
              tipe_pesanan,
              no_meja: tipe_pesanan === 'dine_in' ? no_meja : null,
              total: grandTotal,
              menu: cart
            };

            fetch('/simpan-order', {
              method: 'POST',
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify(simpanPayload)
            })
            .then(res => res.json())
            .then(data => {
              localStorage.removeItem('cart');
              updateCartCount();
              loadCart();
              window.location.href = '/';
            })
            .catch(err => {
              console.error(err);
              alert("Pesanan berhasil dibayar, tapi gagal disimpan. Hubungi admin.");
            });
          },

          onPending: function(result) {
            alert("Transaksi belum selesai. Silahkan selesaikan pembayaran.");
          },
          onError: function(result) {
            alert("Gagal melakukan pembayaran. Silahkan coba lagi.");
          },
          onClose: function() {
            alert("Kamu menutup popup tanpa menyelesaikan pembayaran.");
          }
        });
      } else {
        alert("Gagal mengambil token. Silahkan coba lagi.");
      }
    })

    .catch(error => {
      // console.error('Error:', error);
      alert("Terjadi kesalahan saat mengirim pesanan.");
      // console.error(error);
    });
  });

  // Fungsi untuk memperbarui angka keranjang
  function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    let totalQty = 0;

    cart.forEach(item => {
      totalQty += item.quantity || 1;
    });

    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
      cartCountElement.innerText = totalQty;
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadCart();
    updateCartCount();
  });

  function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('hidden');
  }

  // Menutup dropdown 
  window.addEventListener('click', function (e) {
    const icon = document.querySelector('.user-icon');
    const dropdown = document.getElementById('userDropdown');
    if (!icon.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    loadCart();
    updateCartCount();
  });


  // Fungsi editItem 
  function editItem(index) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartitem = cart[index];

    if (!cartItem) {
      alert("Item tidak ditemukan!");
      return;
    }

    // 🚨 Tambahkan ini sebelum buka modal baru
    const cartModal = bootstrap.Modal.getInstance(document.getElementById('cartModal'));
    if (cartModal) {
      cartModal.hide();
    }

    openAddonModal(cartItem.menu, item.menu_id, item, index);
  }
</script>