@extends('layouts.app')

@section('title', 'Menu - Warung Ajus') {{-- Menentukan title halaman untuk halaman ini --}}

@section('content')


  <!-- Hero Section -->
  <div class="hero-section">
    <h1>MENU KAMI</h1>
  </div>

  <!-- Menu Section -->
  <section class="menu-section">
    <div class="filter-buttons">
      <a href="{{ route('menu-makanan')}}" class="filter-btn active">MAKANAN</a>
      <a href="{{ route('menu-minuman')}}" class="filter-btn">MINUMAN</a>
    </div>

    <div class="menu-grid">
      <div class="menu-item">
        <img src="{{ asset('img/nasi-campur.png')}}" alt="Nasi Campur">
        <h5>Nasi Campur</h5>
        <p>10.000</p>
        <i class="fas fa-plus" onclick="openAddonModal('Nasi Campur')"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/soto-ayam.png')}}" alt="Soto Ayam">
        <h5>Soto Ayam</h5>
        <p>10.000</p>
        <i class="fas fa-plus" onclick="openAddonModal('Soto Ayam')"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/indomie-goreng.png')}}" alt="Indomie Goreng">
        <h5>Indomie Goreng</h5>
        <p>5.000</p>
        <i class="fas fa-plus" onclick="openAddonModal('Indomie Goreng')"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/indomie-soto.png')}}" alt="Indomie Soto">
        <h5>Indomie Soto</h5>
        <p>5.000</p>
        <i class="fas fa-plus" onclick="openAddonModal('Indomie Soto')"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/tipat-cantok1.png')}}" alt="Tipat Cantok">
        <h5>Tipat Cantok</h5>
        <p>8.000</p>
        <i class="fas fa-plus" onclick="openAddonModal('Tipat Cantok')"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/rujak-kuah-pindang.png')}}" alt="Rujak Kuah Pindang">
        <h5>Rujak Kuah Pindang</h5>
        <p>8.000</p>
        <i class="fas fa-plus" onclick="openAddonModal('Rujak Kuah Pindang')"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/rujak-gula-merah.png')}}" alt="Rujak Gula Merah">
        <h5>Rujak Gula Merah</h5>
        <p>8.000</p>
        <i class="fas fa-plus" onclick="openAddonModal('Rujak Gula Merah')"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/rujak-gula-pasir.png')}}" alt="Rujak Gula Pasir">
        <h5>Rujak Gula Pasir</h5>
        <p>8.000</p>
        <i class="fas fa-plus" onclick="openAddonModal('Rujak Gula Pasir')"></i>
      </div>
    </div>
  </section>

  @endsection


    <!-- Overlay dan Modal Add-on -->
    <div id="addonOverlay" class="addon-overlay">
      <div class="addon-modal">
        <span class="close-btn" onclick="closeAddonModal()">&times;</span>
        <img id="modalImage" src="" alt="Makanan" class="modal-image">
        <h2 id="modalTitle"></h2>
        <p id="modalPrice" class="modal-price"></p>
        <ul id="menuContents" class="menu-contents"></ul>

        <h3>Pilih Add-on</h3>
        <div id="addonContent" class="addon-list">
          <!-- Add-on akan dimuat dinamis lewat JavaScript -->
        </div>

        <label for="catatan">Catatan (opsional):</label>
        <textarea id="catatan" class="catatan-input" placeholder="Misal: pedes sedikit, tanpa sambal..."></textarea>

        <div class="quantity-control">
          <button onclick="updateQty(-1)">-</button>
          <span id="qtyDisplay">1</span>
          <button onclick="updateQty(1)">+</button>
        </div>

        <p id="totalPrice" class="modal-total-price"></p>

        <button class="add-to-cart-btn" onclick="submitAddon()">Tambah ke Keranjang</button>
      </div>
    </div>

    <script>
      $('#submitOrderBtn').on('click', function () {
    // Ambil data dari form atau cart
    const orderData = {
        user_id: $('#user_id').val(),
        items: cartItems, // array of { product_id, quantity, price }
        total_price: calculateTotal(cartItems),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: '/orders', // atau route lain yang sesuai
        method: 'POST',
        data: orderData,
        success: function (response) {
            alert('Pesanan berhasil dikirim!');
            // Lakukan reset form/cart jika perlu
        },
        error: function () {
            alert('Gagal mengirim pesanan');
        }
    });
});

    </script>

    <script>
      // Data menu utama
      const baseMenuPrices = {
        "Nasi Campur": 10000,
        "Soto Ayam": 10000,
        "Indomie Goreng": 5000,
        "Indomie Soto": 5000,
        "Tipat Cantok": 8000,
        "Rujak Kuah Pindang": 8000,
        "Rujak Gula Merah": 8000,
        "Rujak Gula Pasir": 8000
      };
    
      // Data add-on
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
      let quantity = 1;
    
      function openAddonModal(menuName) {
        currentMenu = menuName;
        quantity = 1;
        document.getElementById('qtyDisplay').innerText = quantity;
    
        // Set harga dasar menu
        const basePrice = baseMenuPrices[menuName] || 0;
        document.getElementById('modalPrice').innerText = `Harga: Rp${basePrice.toLocaleString()}`;
    
        // Render add-ons
        const addons = addonData[menuName] || [];
        const addonContent = document.getElementById('addonContent');
        addonContent.innerHTML = '';
    
        addons.forEach((addon, index) => {
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
    
        document.getElementById('addonOverlay').style.display = 'flex';
        document.body.classList.add('blurred');
      }
    
      function getImageUrl(menuName) {
        const map = {
          "Nasi Campur": "{{ asset('img/nasi-campur.png') }}",
          "Soto Ayam": "{{ asset('img/soto-ayam.png') }}",
          "Indomie Goreng": "{{ asset('img/indomie-goreng.png') }}",
          "Indomie Soto": "{{ asset('img/indomie-soto.png') }}",
          "Tipat Cantok": "{{ asset('img/tipat-cantok1.png') }}",
          "Rujak Kuah Pindang": "{{ asset('img/rujak-kuah-pindang.png') }}",
          "Rujak Gula Merah": "{{ asset('img/rujak-gula-merah.png') }}",
          "Rujak Gula Pasir": "{{ asset('img/rujak-gula-pasir.png') }}"
        };
        return map[menuName] || '';
      }
    
      function updateQty(amount) {
        quantity += amount;
        if (quantity < 1) quantity = 1;
        document.getElementById('qtyDisplay').innerText = quantity;
        calculateTotal();
      }
    
      function calculateTotal() {
        const basePrice = baseMenuPrices[currentMenu] || 0;
        const checkedAddons = document.querySelectorAll('input[name="addon"]:checked');
        const addonTotal = Array.from(checkedAddons).reduce((sum, el) => sum + parseInt(el.dataset.price), 0);
        const total = (basePrice + addonTotal) * quantity;
    
        document.getElementById('modalPrice').innerText = `Total: Rp${total.toLocaleString()}`;
      }
    
      function closeAddonModal() {
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

        // Tambah ke localStorage
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
        cart.push({
          menu: currentMenu,
          basePrice,
          addons: selected,
          quantity,
          note: catatan
        });
    
        localStorage.setItem('cart', JSON.stringify(cart));
    
        alert(`✅ ${currentMenu} berhasil ditambahkan ke keranjang.`);
        closeAddonModal();
        function updateCartCount() {
          const cart = JSON.parse(localStorage.getItem('cart')) || [];
          const count = cart.reduce((sum, item) => sum + item.quantity, 0);
          document.getElementById('cartCount').innerText = count;
        }
      }
    </script>
    
      
      <script>
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
          e.preventDefault();
        
          let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
          if (cart.length === 0) {
            alert("Keranjang kosong!");
            return;
          }
        
          let nama = document.getElementById('nama').value;
          let nomor = document.getElementById('nomor').value;
        
          // Kirim data ke Laravel
          fetch("{{ route('checkout') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
              nama: nama,
              nomor: nomor,
              cart: cart.map(item => ({
                menu: item.menu,
                quantity: item.quantity || 1,
                addons: item.addons
              }))
            })
          })
          .then(response => response.json())
          .then(data => {
            alert(data.message);
            localStorage.removeItem('cart');
          })
          .catch(error => {
            alert("Terjadi kesalahan saat checkout.");
            console.error(error);
          });
        });
        </script>
        

</body>
</html>
