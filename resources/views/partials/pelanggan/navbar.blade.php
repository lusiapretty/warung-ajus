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
      <div class="modal-header bg-success text-white">
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

          <div class="mb-3">
            <label for="metode-pembayaran" class="form-label">Metode Pembayaran</label>
            <select id="metode_pembayaran" class="form-select">
              <option value="tunai">Tunai</option>
              <option value="qris">QRIS</option>
              <option value="transfer">Transfer Bank</option>
            </select>
          </div>
        </form>    
      </div>
      <div class="modal-footer justify-content-between">
        <h5 id="checkoutTotal">Total: Rp0</h5>
        <button type="submit" form="checkoutForm" class="btn btn-lg btn-success px-4 fw-bold">Buat Pesanan</button>
      </div>
    </div>
  </div>
</div>


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
                <h5 class="card-title">${item.menu}</h5>
                <p class="card-text">Catatan: ${item.note || '-'}</p>
                <ul>${addonList || '<li>Tidak ada add-on</li>'}</ul>
                <p><strong>Total: Rp${totalPerItem.toLocaleString('id-ID')}</strong></p>
                <div class="d-flex align-items-center gap-2 mb-2">
                  <button class="btn btn-sm btn-secondary" onclick="changeQty(${index}, -1)">-</button>
                  <span class="fw-bold">${item.quantity}</span>
                  <button class="btn btn-sm btn-secondary" onclick="changeQty(${index}, 1)">+</button>
                </div>
                <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">Hapus</button>
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
                <ul>${addonList || '<li>Tidak ada add-on</li>'}</ul>
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
                <h5 class="card-title">${item.menu}</h5>
                <p class="card-text">Jumlah: ${item.quantity}</p>
                <p class="card-text">Catatan: ${item.note || '-'}</p>
                <ul>${addonList || '<li>Tidak ada add-on</li>'}</ul>
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
      const metode_pembayaran = document.getElementById('metode_pembayaran').value;

      const cart = JSON.parse(localStorage.getItem('cart')) || [];

      if (!tipe_pesanan || !nama || !metode_pembayaran || (tipe_pesanan === 'dine_in' && !no_meja)) {
        alert("Mohon lengkapi semua data pelanggan.");
        return;
    }

    const payload = {
      nama_pelanggan: nama,
      tipe_pesanan,
      no_meja: tipe_pesanan === 'dine_in' ? no_meja : null,
      pembayaran: metode_pembayaran,
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

    // AJAX pakai fetch
    fetch('/checkout', {
      method: 'POST',
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(payload)
    })
    .then(response => {
      if (!response.ok) {
        return response.text().then(text => {
          // console.error("Response dari server:", text);
          throw new Error('Response bukan JSON yang valid');

          localStorage.removeItem("cart");
          loadCart();
      });
    }
      return response.json(); 
    })

    .then(data => {
      // console.log("Response berhasil:", data);
      if (data.message) {
        alert(data.message);
      localStorage.removeItem('cart');
      loadCart();
      updateCartCount();
      document.getElementById('checkoutForm').reset();

      // Hapus isi keranjang
      const cartContainer = document.getElementById('cartContainer');
      // document.getElementById('cartContainer').innerHTML = '0';

      const modalElement = document.getElementById('checkoutModal');
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      // const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
      if(modalInstance) {
        modalInstance.hide();
      } else {
        const fallbackmodal = new bootstrap.Modal(modalElement);
        fallbackmodal.hide();
      }
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
</script>