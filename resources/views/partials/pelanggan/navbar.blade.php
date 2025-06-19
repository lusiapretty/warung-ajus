
<header class="navbar">
    <div class="navbar-left">
      <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung" class="logo-warung">
    </div>  
        <nav>
                <a href="{{ route('home')}}">Beranda</a>
                <a href="{{ route('tentang')}}">Tentang Kami</a>
                <a href="{{ route('menu-makanan')}}">Menu</a>
                <a href="#kontak">Kontak</a>
        </nav>
        <a href="{{ route('menu-makanan')}}" class="btn-pesan">Pesan Sekarang</a>
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
        <button class="btn btn-lg btn-warning fw-bold px-5" onclick="checkout()">
          <i class="fa fa-shopping-cart me-2"></i> Checkout Sekarang
        </button>
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

  if (cart.length === 0) {
    container.innerHTML = '<p>Keranjang masih kosong.</p>';
    document.getElementById('grandTotal').innerText = 'Total: Rp0';
    return;
  }

  let grandTotal = 0;

  cart.forEach((item, index) => {
    const basePrice = item.basePrice || 0;
    const addonList = item.addons.map(addon => `<li>${addon.name} (+Rp${addon.price.toLocaleString()})</li>`).join('');
    const totalPerItem = (item.basePrice + item.addons.reduce((sum, a) => sum + a.price, 0)) * item.quantity;
    grandTotal += totalPerItem;

    container.innerHTML += `
      <div class="card cart-item mb-3">
        <div class="row g-0 align-items-center">
          <div class="col-md-3">
            <img src="${baseAssetUrl + (item.image || 'default.png')}" class="img-fluid rounded-start cart-image" alt="${item.menu}">
          </div>
          <div class="col-md-9">
            <div class="card-body">
              <h5 class="card-title">${item.menu}</h5>
              <p class="card-text">Catatan: ${item.note || '-'}</p>
              <ul>${addonList || '<li>Tidak ada add-on</li>'}</ul>
              <p><strong>Total: Rp${totalPerItem.toLocaleString()}</strong></p>
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
}

function changeQty(index, delta) {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  if (!cart[index]) return;

  cart[index].quantity += delta;
  if (cart[index].quantity < 1) cart[index].quantity = 1;

  localStorage.setItem('cart', JSON.stringify(cart));
  loadCart();
}

function checkout() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  if (cart.length === 0) {
    alert("Keranjang kosong!");
    return;
  }

  const nama = prompt("Masukkan nama Anda:");
  const no_meja = prompt("Masukkan nomor meja Anda:");

  if (!nama || !no_meja) {
    alert("Nama dan nomor meja wajib diisi.");
    return;
  }

  // Simulasi pengiriman (ganti dengan fetch jika diperlukan)
  console.log("Kirim ke backend:", { nama, no_meja, cart });

  alert("Pesanan berhasil dikirim!");
  localStorage.removeItem('cart');
  loadCart();

  // Tutup modal
  const modal = bootstrap.Modal.getInstance(document.getElementById('cartModal'));
  modal.hide();
}

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

// Contoh data awal (hapus di produksi)
// if (!localStorage.getItem('cart')) {
//   localStorage.setItem('cart', JSON.stringify([
//     {
//       menu_id: 1,
//       menu: "Nasi Goreng",
//       basePrice: 20000,
//       quantity: 2,
//       addons: [{ name: "Telur", price: 5000 }],
//       note: "Tidak pedas",
//       image: "{{ asset('img/nasi-campur.png')}}"
//     }
//   ]));
// }

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

</script>