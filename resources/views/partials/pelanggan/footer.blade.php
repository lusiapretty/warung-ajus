<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Footer -->
<footer class="footer">
  <div class="footer-container">

    <!-- Logo & Deskripsi -->
    <div class="footer-section">
      <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung Ajus" class="footer-logo">
      <p class="footer-desc">Jl. Goa Gong No. 3, Bukit Jimbaran<br>Depan ATM Sepeda Motor</p>
    </div>

    <!-- Navigasi -->
    <div class="footer-section">
      <h3>Informasi</h3>
      <ul class="footer-links">
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('tentang')}}">Tentang Kami</a></li>
        <li><a href="{{ route('menu.makanan')}}">Menu</a></li>
        <li><a href="{{ route('home')}}#lokasi-kami">Kontak</a></li>
      </ul>
    </div>

    <!-- Sosial Media -->
    <div class="footer-section">
      <h3>Ikuti Kami</h3>
      <ul class="footer-social">
        <li>
          <a href="https://www.instagram.com/warungajus.id/" target="_blank">
            <i class="fab fa-instagram"></i> @warungajus.id
          </a>
        </li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    &copy; {{ date('Y') }} Warung Ajus.
  </div>
</footer>
