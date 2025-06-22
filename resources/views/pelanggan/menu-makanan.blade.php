@extends('layouts.app')

@section('title', 'Menu - Warung Ajus')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
  <h1>MENU KAMI</h1>
</div>

<!-- Menu Section -->
<section class="menu-section">
  <div class="filter-buttons">
    <a href="{{ route('menu.makanan') }}" class="filter-btn active">MAKANAN</a>
    <a href="{{ route('menu.minuman') }}" class="filter-btn">MINUMAN</a>
  </div>

  <div class="menu-grid">
    @foreach ($menus as $menu)
      @if (strtolower($menu->kategori) === 'makanan')
        <div class="menu-item">
          <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}">
          <h5>{{ $menu->nama_menu }}</h5>
          <p>Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
          <i class="fas fa-plus" onclick="openAddonModal('{{ $menu->nama_menu }}', {{ $menu->id }})"></i>
        </div>
      @endif
    @endforeach
  </div>
</section>

<!-- Overlay dan Modal Add-on -->
<div id="addonOverlay" class="addon-overlay">
  <div class="addon-modal">
    <span class="close-btn" onclick="closeAddonModal()">&times;</span>
  <div class="modal-body-wrapper">
    <div class="modal-image-wrapper no-padding">
      <img id="modalImage" src="" alt="Makanan" class="modal-image">
    </div>

    <div class="modal-content-wrapper">
      <h3 id="modalTitle"></h3>
      <p id="modalPrice" class="modal-price"></p>
      <h6>Pilih Add-on</h6>
      <div id="addonContent" class="addon-list"></div>

      <label for="catatan">Catatan (opsional):</label>
      <textarea id="catatan" class="catatan-input" placeholder="Misal: pedas sedikit, tanpa sambal..."></textarea>

      <div class="quantity-control">
        <i class="fas fa-minus" onclick="updateQty(-1)"></i>
        <span id="qtyDisplay">1</span>
        <i class="fas fa-plus" onclick="updateQty(1)"></i>
      </div>

      <p id="totalPrice" class="modal-total-price"></p>
    </div>
  </div>

  <div class="modal-footer">
    <button class="add-to-cart-btn" onclick="submitAddon()">
      Tambah ke Keranjang - <span id="btnTotalHarga">Rp0</span>
    </button>
  </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  const baseMenuPrices = @json($menus->pluck('harga', 'nama_menu'));
  const menuImages = @json($menus->pluck('gambar', 'nama_menu'));
  const menuDataById = @json($menus->keyBy('id'));
  const menuDataByName = @json($menus->keyBy('nama_menu')); 

  const addonData = {
    "Nasi Campur": [
      { name: "Telur Rebus", price: 3000 },
      { name: "Ayam Suwir", price: 5000 },
      { name: "Sambal Matah", price: 2000 }
    ],
    "Soto Ayam": [
      { name: "Telur", price: 3000 },
      { name: "Kerupuk", price: 1000 }
    ],
  };

  let currentMenu = null;
  let currentMenuId = null;
  let quantity = 1;

  function openAddonModal(menuName, menuId) {
    currentMenu = menuName;
    currentMenuId = menuId;
    quantity = 1;
    document.getElementById('qtyDisplay').innerText = quantity;

    const basePrice = baseMenuPrices[menuName] || 0;
    document.getElementById('modalPrice').innerText = `Harga: Rp${basePrice.toLocaleString('id-ID')}`;

    const addons = addonData[menuName] || [];
    const addonContent = document.getElementById('addonContent');
    addonContent.innerHTML = '';

    addons.forEach((addon) => {
      addonContent.innerHTML += `
        <label>
          <span>${addon.name} - Rp${addon.price.toLocaleString()}</span>
          <input type="checkbox" name="addon" value="${addon.name}" data-price="${addon.price}" onchange="calculateTotal()">
        </label>
      `;
    });

    document.getElementById('modalImage').src = getImageUrl(menuName);
    document.getElementById('modalTitle').innerText = menuName;

    calculateTotal();

    document.body.classList.add('modal-open');

    document.getElementById('addonOverlay').style.display = 'flex';
    document.body.classList.add('blurred');
  }

  function getImageUrl(menuName) {
    const filename = menuImages[menuName] || 'default.png';
    return '/storage/' + filename;
  }

  function updateQty(amount) {
    quantity += amount;
    if (quantity < 1) quantity = 1;
    document.getElementById('qtyDisplay').innerText = quantity;
    calculateTotal();
  }

  function calculateTotal() {
    const basePrice = Number(baseMenuPrices[currentMenu]) || 0;
    const checkedAddons = document.querySelectorAll('input[name="addon"]:checked');
    const addonTotal = Array.from(checkedAddons).reduce((sum, el) => {
        return sum + Number(el.dataset.price); 
    }, 0);
    const total = (basePrice + addonTotal) * quantity;
    document.getElementById('totalPrice').innerText = `Total: Rp${total.toLocaleString('id-ID')}`;

    // Tampilkan total harga di tombol
    document.getElementById('btnTotalHarga').innerText = `Rp${total.toLocaleString('id-ID')}`;
  }

  function closeAddonModal() {
    document.body.classList.remove('modal-open');

    document.getElementById('addonOverlay').style.display = 'none';
    document.body.classList.remove('blurred');
  }

  function submitAddon() {
    const basePrice = baseMenuPrices[currentMenu] || 0;
    const checked = document.querySelectorAll('input[name="addon"]:checked');
    const selected = Array.from(checked).map(item => ({
      name: item.value,
      price: parseInt(item.dataset.price)
    }));

    const catatan = document.getElementById('catatan').value;
    document.getElementById('catatan').value = '';

    const image = getImageUrl(currentMenu).replace('/storage/', '');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    const menu_id = currentMenuId;

    if (!menu_id) {
      alert('Menu tidak ditemukan.');
      return;
    }

    cart.push({
      menu_id: menu_id,
      menu: currentMenu,
      basePrice: Number(basePrice),
      addons: selected,
      quantity,
      note: catatan,
      image: image 
    });

    localStorage.setItem('cart', JSON.stringify(cart));

    alert(`${currentMenu} berhasil ditambahkan ke keranjang.`);
    closeAddonModal();
    updateCartCount();
    loadCart();
  }

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
</script>
@endpush