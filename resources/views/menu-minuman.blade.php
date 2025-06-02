<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu- Warung Ajus</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung" class="logo-warung">
        </div>
            <nav>
                    <a href="{{ route('home')}}">Beranda</a>
                    <a href="#tentangkami">Tentang Kami</a>
                    <a href="{{ route('menu-minuman')}}">Menu</a>
                    <a href="#kontak">Kontak</a>
            </nav>
            <a href="#pesan" class="btn-pesan">Pesan Sekarang</a>
        <div class="navbar-right">
            <div class="icon-group">
                <i class="fas fa-shopping-cart icon"></i>
                <i class="fas fa-user icon"></i>
            </div>
        </div>
    </header>

  <!-- Hero Section -->
  <div class="hero-section">
    <h1>MENU KAMI</h1>
  </div>

  <!-- Menu Section -->
  <section class="menu-section">
    <div class="filter-buttons">
        <a href="{{ route('menu-makanan')}}" class="filter-btn ">MAKANAN</a>
        <a href="{{ route('menu-minuman')}}" class="filter-btn active">MINUMAN</a>    
    </div>

    <div class="menu-grid">
      <div class="menu-item">
        <img src="{{ asset('img/es-teh.png')}}" alt="Es Teh Manis">
        <h5>Es Teh Manis</h5>
        <p>4.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/es-gula.png')}}" alt="Es Gula">
        <h5>Es Gula</h5>
        <p>4.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/es-jeruk.png')}}" alt="Es Jeruk">
        <h5>Es Jeruk</h5>
        <p>5.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/extrajos.png')}}" alt="Extrajoss">
        <h5>Extrajoss</h5>
        <p>5.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/es-susu.jpg')}}" alt="Es Susu">
        <h5>Es Susu</h5>
        <p>4.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/es-campur.jpg')}}" alt="Rujak Kuah Pindang">
        <h5>Es Campur</h5>
        <p>6.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/nutrisari.png')}}" alt="Nutri Sari">
        <h5>Nutri Sari</h5>
        <p>4.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/pop-ice.jpg')}}" alt="Pop Ice">
        <h5>Pop Ice</h5>
        <p>8.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/teh-kotak.jpg')}}" alt="Teh Kotak">
        <h5>Teh Kotak</h5>
        <p>3.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/floridina.jpg')}}" alt="Floridina">
        <h5>Floridina</h5>
        <p>6.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/sprite.jpg')}}" alt="Sprite">
        <h5>Sprite</h5>
        <p>8.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/teh-pucuk.jpg')}}" alt="Teh Pucuk">
        <h5>Teh Pucuk</h5>
        <p>8.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/pocari-sweat.jpg')}}" alt="Pocari Sweat">
        <h5>Pocari Sweat</h5>
        <p>8.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/nipis-madu.jpg')}}" alt="Nipis Madu">
        <h5>Nipis Madu</h5>
        <p>8.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/ultra-milk.jpg')}}" alt="Ultra Milk">
        <h5>Ultra Milk</h5>
        <p>8.000</p>
        <i class="fas fa-plus"></i>
      </div>
      <div class="menu-item">
        <img src="{{ asset('img/teh-botol.jpg')}}" alt="Teh Botol">
        <h5>Teh Botol</h5>
        <p>8.000</p>
        <i class="fas fa-plus"></i>
      </div>
    </div>
  </section>

    <!-- Footer -->
    <footer id="kontak">
        <div class="footer-item">📍 Alamat <br> Jl. Goa gong No.3 Jimbaran</div>
        <div class="footer-item">⏰ Jam Operasional <br> Senin - Sabtu: 08.00 - 16.00</div>
        <div class="footer-item">📞 No Telp <br> 087887920415 </div>
    </footer>
</body>
</html>
