<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Keranjang Modal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #FFD600;
    }

    .cart-container {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .cart-item {
      background-color: #fff8dc;
      border: 2px solid #FFD600;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .cart-image {
      width: 100%;
      height: auto;
      object-fit: cover;
      max-height: 140px;
      border-radius: 16px 0 0 16px;
    }

    #grandTotal {
      font-size: 1.5rem;
      color: #B80000;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-light bg-light px-3">
  <span class="navbar-brand mb-0 h1">My Store</span>
  <button type="button" class="btn btn-warning position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
    <i class="fa fa-shopping-cart"></i> 
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">0</span>
  </button>
</nav>

<!-- Modal Keranjang -->
{{-- <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
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
</div> --}}

<!-- Script -->
<script>
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
    const addonList = item.addons.map(addon => `<li>${addon.name} (+Rp${addon.price.toLocaleString()})</li>`).join('');
    const totalPerItem = (item.basePrice + item.addons.reduce((sum, a) => sum + a.price, 0)) * item.quantity;
    grandTotal += totalPerItem;

    container.innerHTML += `
      <div class="card cart-item mb-3">
        <div class="row g-0 align-items-center">
          <div class="col-md-3">
            <img src="${item.image}" class="img-fluid rounded-start cart-image" alt="${item.menu}">
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
if (!localStorage.getItem('cart')) {
  localStorage.setItem('cart', JSON.stringify([
    {
      menu_id: 1,
      menu: "Nasi Goreng",
      basePrice: 20000,
      quantity: 2,
      addons: [{ name: "Telur", price: 5000 }],
      note: "Tidak pedas",
      image: "https://source.unsplash.com/400x300/?fried-rice"
    }
  ]));
}

document.addEventListener('DOMContentLoaded', () => {
  loadCart();
  updateCartCount();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
