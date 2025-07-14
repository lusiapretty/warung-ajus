<header class="navbar">

    <div class="navbar-left">
      <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung" class="logo-warung">
    </div>  

      <nav>
        <a href="{{ route('home')}}">Beranda</a>
        <a href="{{ route('tentang')}}">Tentang Kami</a>
        <a href="{{ route('menu.makanan')}}">Menu</a>
        <a href="{{ route('home')}}">Kontak</a>
      </nav>
        <a href="{{ route('menu.makanan')}}" class="btn-pesan">Pesan Sekarang</a>

    <div class="navbar-right">
       <div class="icon-group">
          <button type="button" class="btn position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
                <i class="fas fa-shopping-cart icon"></i>
                <span class="cart-count" id="cart-count">0</span>
          </button>
          
              <div class="user-icon" onclick="toggleUserDropdown()">
                <i class="fas fa-user icon"></i>
              </div>

                  <!-- HAMBURGER ICON -->
                <div class="hamburger" id="hamburger">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
            </div>
        </div>
    </div>

<div class="mobile-menu" id="mobileMenu">
  <a href="{{ route('home')}}">Beranda</a>
  <a href="{{ route('tentang')}}">Tentang Kami</a>
  <a href="{{ route('menu.makanan')}}">Menu</a>
  <a href="{{ route('home')}}">Kontak</a>
  <a href="{{ route('menu.makanan')}}" class="btn-pesan">Pesan Sekarang</a>
</div>


    <!-- Dropdown Profil -->
    <div id="userDropdown" class="user-dropdown hidden">
      @if(Auth::check() && Auth::user()->role === 'pelanggan')
        <div class="dropdown-header">
          <p>Halo, <strong>{{ Auth::user()->nama }}</strong></p>
          <small>Kelola akun & pesanan Anda</small>
        </div>

        <ul class="dropdown-list">
          <li><a href="{{ route('profil.edit') }}"><i class="fas fa-user-circle"></i> Profil Saya</a></li>
          <li><a href="{{ route('pesanan.saya')}}"><i class="fas fa-box-open"></i> Pesanan Saya</a></li>
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
          <!-- Ringkasan Keranjang -->
            <h5 class="fw-bold">Detail Pesanan:</h5>
            <div id="checkoutSummary" class="checkout-summary"></div>
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
            <select class="form-control" id="no_meja" required>
              <option disabled selected>-- Pilih No Meja --</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="pembayaran" class="form-label">Metode Pembayaran</label>
            <select class="form-select" id="pembayaran" required>
              <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
              <option value="cash">Cash</option>
              <option value="midtrans">Bayar Online</option>
            </select>
          </div>
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
    const isLoggedIn = @json(Auth::check());
    const userId = {{ Auth::check() ? Auth::user()->id : 'null' }};
    const userRole = "{{ Auth::check() ? Auth::user()->role : '' }}";
    const cartKey = isLoggedIn ? `cart_user_${userId}` : 'cart_guest';
    const storage = isLoggedIn ? localStorage : sessionStorage;
</script>

<script>
const baseAssetUrl = "{{ asset('img') }}/";
  function loadCart() {
    const cart = JSON.parse(storage.getItem(cartKey)) || [];
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
    const cart = JSON.parse(storage.getItem(cartKey)) || [];
    cart.splice(index, 1);
    storage.setItem(cartKey, JSON.stringify(cart));
    loadCart();
    updateCartCount();
  }

  function changeQty(index, delta) {
    const cart = JSON.parse(storage.getItem(cartKey)) || [];
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
          storage.setItem(cartKey, JSON.stringify(cart));
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

    storage.setItem(cartKey, JSON.stringify(cart));
    loadCart();
  }

  function showCheckoutModal() {
    const cart = JSON.parse(storage.getItem(cartKey)) || [];
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

  function promptLoginFirst() {
    Swal.fire({
      title: 'Login Diperlukan',
      text: 'Silakan login terlebih dahulu untuk melakukan pemesanan.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Login',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/login";
      }
    });
  }

  function openCheckoutModal() {
    const cart = JSON.parse(storage.getItem(cartKey)) || [];

    if (cart.length === 0) {
      Swal.fire({
        title: 'Keranjang Kosong',
        text: 'Silakan tambahkan menu terlebih dahulu sebelum melakukan checkout.',
        icon: 'info',
        confirmButtonText: 'Oke'
      }).then(() => {
        const cartModalEl = document.getElementById('cartModal');
        const cartModalInstance = bootstrap.Modal.getInstance(cartModalEl);
        if (cartModalInstance) {
          cartModalInstance.hide();
        }
      });
      return;
    }

    const cartModalEl = document.getElementById('cartModal');
    const cartModalInstance = bootstrap.Modal.getInstance(cartModalEl);
    if (cartModalInstance) {
      cartModalInstance.hide();
    }

    // Jika user belum login atau bukan pelanggan, arahkan untuk login
    if (!isLoggedIn || userRole !== 'pelanggan') {
      setTimeout(() => {
        promptLoginFirst();
      }, 300); 
      return;
    }

    // lanjut render ringkasan pesanan & tampilkan modal checkout seperti sebelumnya
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
    const noMejaSelect = document.getElementById('no_meja');

    if (tipe === 'dine_in') {
      noMeja.style.display = 'block';
      noMejaSelect.required = true;

      // ambil data meja yang sudah terpakai
      fetch('/meja-terpakai')
        .then(response => {
          if (!response.ok) throw new Error('Network response not OK');
          return response.json(); 
        })
        .then(data => {
          
          const mejaTerpakai = data.meja_terpakai;
          
          // reset dan isi ulang
          noMejaSelect.innerHTML = '<option disabled selected>-- Pilih No Meja --</option>';
          for (let i = 1; i <= 20; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = 'Meja ' + i;
            if (mejaTerpakai.includes(i)) {
              option.disabled = true;
              option.textContent += ' (terisi)';
            }
            noMejaSelect.appendChild(option);
          }
        })
        .catch(error => {
          console.error('Gagal ambil meja:', error);
        });

    } else {
      noMeja.style.display = 'none';
      noMejaSelect.required = false;
      noMejaSelect.innerHTML = '';
    }
  }

    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const tipe_pesanan = document.getElementById('tipe_pesanan').value;
      const nama = document.getElementById('nama_pelanggan').value;
      const no_meja = document.getElementById('no_meja').value;
      const pembayaran = document.getElementById('pembayaran').value;

      const cart = JSON.parse(storage.getItem(cartKey)) || [];

      if (!tipe_pesanan || !nama || !pembayaran || (tipe_pesanan === 'dine_in' && !no_meja)) {
        alert("Mohon lengkapi semua data pelanggan dan pilih metode pembayaran.");
        return;
      }

      const generatedOrderId = 'ORDER-' + Date.now(); 
      let grandTotal = 0;
      cart.forEach(item => {
        const basePrice = Number(item.basePrice || 0);
        const addonTotal = item.addons.reduce((sum, a) => sum + Number(a.price || 0), 0);
        grandTotal += (basePrice + addonTotal) * (item.quantity || 1);
      });

    const payload = {
      order_id: generatedOrderId,
      nama_pelanggan: nama,
      tipe_pesanan,
      no_meja: tipe_pesanan === 'dine_in' ? no_meja : null,
      pembayaran,
      menu: cart.map(item => ({
        menu_id: item.menu_id,
        basePrice: item.basePrice,
        quantity: item.quantity,
        catatan: item.note || '',
        addons: item.addons || [,]
      }))
    };

    if (pembayaran === 'cash') {
      fetch('/checkout', {
        method: 'POST',
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        Swal.fire({
          title: 'Pesanan Berhasil!',
          text: 'Pesanan Anda telah dibuat dan akan segera diproses.',
          icon: 'success',
          confirmButtonText: 'Lihat Pesanan Saya'
        }).then(() => {
          storage.removeItem(cartKey);
          updateCartCount();
          loadCart();
          window.location.href = '/pesanan-saya';
        });
      })
      .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan saat membuat pesanan.");
      });
    } else {

    // console.log('Payload yang dikirim:', JSON.stringify(payload, null, 2));

    // console.log('Cart:', cart);

    // Panggil endpoint untuk ambil snap token Midtrans

      // Midtrans
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
                order_id: generatedOrderId,
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
              .then(res => {
                if (!res.ok) {
                  return res.text().then(errText => {
                    console.error("Response bukan JSON:", errText);
                    throw new Error("Gagal menyimpan pesanan.");
                  });
                }
                return res.json();
              })
              .then(data => {
                storage.removeItem(cartKey);
                updateCartCount();
                loadCart();
                window.location.href = '/pesanan-saya';
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
        alert("Terjadi kesalahan saat mengirim pesanan.");
      });
    }
  });

  // Fungsi untuk memperbarui angka keranjang
  function updateCartCount() {
    const cart = JSON.parse(storage.getItem(cartKey)) || [];
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
    const cart = JSON.parse(storage.getItem(cartKey)) || [];
    const cartItem = cart[index];

    if (!cartItem) {
      alert("Item tidak ditemukan!");
      return;
    }

    const cartModal = bootstrap.Modal.getInstance(document.getElementById('cartModal'));
    if (cartModal) {
      cartModal.hide();
    }

    openAddonModal(cartItem.menu, cartItem.menu_id, cartItem, index);
  }
</script>