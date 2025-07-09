@extends('layouts.app')

@section('title', 'Menu - Warung Ajus')

@section('content')

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
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 id="modalTitle"></h4>
        <p id="modalPrice" class="modal-price"></p>
      </div>
      <h6>Pilih Add-on</h6>
      <div id="addonContent" class="addon-list"></div>

      <label for="catatan">Catatan (opsional):</label>
      <textarea id="catatan" class="catatan-input" placeholder="Misal: pedas sedikit, tanpa sambal..."></textarea>

      <div class="quantity-control">
        <i class="fas fa-minus" onclick="updateQty(-1)"></i>
        <span id="qtyDisplay">1</span>
        <i class="fas fa-plus" onclick="updateQty(1)"></i>
      </div>

      {{-- <p id="totalPrice" class="modal-total-price"></p> --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  const baseMenuPrices = @json($menus->pluck('harga', 'nama_menu'));
  const menuImages = @json($menus->pluck('gambar', 'nama_menu'));
  const menuDataById = @json($menus->keyBy('id'));
  const menuDataByName = @json($menus->keyBy('nama_menu')); 

  let currentMenu = null;
  let currentMenuId = null;
  let quantity = 1;
  let editingIndex = null;

  function openAddonModal(menuName, menuId, itemData = null, index = null) {
    currentMenu = menuName;
    currentMenuId = menuId;
    quantity = 1;
    document.getElementById('qtyDisplay').innerText = quantity;
    editingIndex = index !== null ? index : null;

    const menuData = menuDataById[menuId];
    const basePrice = baseMenuPrices[menuName] || 0;
    const addons = menuData.addons || [];

    document.getElementById('modalPrice').innerText = `${basePrice.toLocaleString('id-ID')}`;

    const addonContent = document.getElementById('addonContent');
    addonContent.innerHTML = '';

    addons.forEach((addon) => {
      addonContent.innerHTML += `
        <label>
          <span>${addon.nama} - Rp${addon.harga.toLocaleString()}</span>
          <input type="checkbox" name="addon" value="${addon.nama}" data-price="${addon.harga}" onchange="calculateTotal()">
        </label>
      `;
    });

    document.getElementById('modalImage').src = getImageUrl(menuName);
    document.getElementById('modalTitle').innerText = menuName;

  // Jika sedang edit
  if (itemData) {
  quantity = itemData.quantity || 1;
  document.getElementById('qtyDisplay').innerText = quantity;

  setTimeout(() => {
  const catatanInput = document.getElementById('catatan');
  if (catatanInput && itemData.note) {
    catatanInput.value = itemData.note;
  }

  const addonCheckboxes = document.querySelectorAll('input[name="addon"]');
  addonCheckboxes.forEach(cb => {
    const selected = itemData.addons.find(a => a.name === cb.value);
    if (selected) cb.checked = true;
  });

  calculateTotal();
  }, 10);
  } else {
    document.getElementById('catatan').value = '';
    quantity = 1;
    document.getElementById('qtyDisplay').innerText = quantity;
  }

  console.log("ItemData.note:", itemData?.note);

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
    // document.getElementById('totalPrice').innerText = `Total: Rp${total.toLocaleString('id-ID')}`;

    // Tampilkan total harga di tombol
    document.getElementById('btnTotalHarga').innerText = `Rp${total.toLocaleString('id-ID')}`;
  }

  function closeAddonModal() {
    document.body.classList.remove('modal-open');

    document.getElementById('addonOverlay').style.display = 'none';
    document.body.classList.remove('blurred');
    document.getElementById('catatan').value = '';
    editingIndex = null;
  }

  function submitAddon() {
    const basePrice = baseMenuPrices[currentMenu] || 0;
    const checked = document.querySelectorAll('input[name="addon"]:checked');
    const selected = Array.from(checked).map(item => ({
      name: item.value,
      price: parseInt(item.dataset.price)
    }));

    const catatan = document.getElementById('catatan').value;
    // document.getElementById('catatan').value = '';
    console.log("CATATAN:", document.getElementById('catatan').value);

    const image = getImageUrl(currentMenu).replace('/storage/', '');
    const cart = JSON.parse(storage.getItem(cartKey)) || [];

    const menu_id = currentMenuId;

    if (!menu_id) {
      alert('Menu tidak ditemukan.');
      return;
    }

    const newItem = {
      menu_id: menu_id,
      menu: currentMenu,
      basePrice: Number(basePrice),
      addons: selected,
      quantity,
      note: catatan,
      image: image 
    };

  const isEdit = editingIndex !== null;

  if (isEdit) {
    cart[editingIndex] = newItem;
  } else {
    cart.push(newItem);
  }

  editingIndex = null; // reset setelah pakai

  storage.setItem(cartKey, JSON.stringify(cart));

  if (isEdit) {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil Diubah',
      text: `${currentMenu} berhasil diperbarui.`,
      timer: 1600,
      showConfirmButton: false
    });
  } else {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil Ditambahkan',
      text: `${currentMenu} berhasil ditambahkan ke keranjang.`,
      timer: 1600,
      showConfirmButton: false
    });
  }

    closeAddonModal();
    updateCartCount();
    loadCart();
  }

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

   function editItem(index) {
    const cart = JSON.parse(storage.getItem(cartKey)) || [];
    const cartItem = cart[index];

    if (!cartItem) {
      alert("Item tidak ditemukan!");
      return;
    }

    openAddonModal(cartItem.menu, cartItem.menu_id, cartItem, index);
  }
</script>
@endpush