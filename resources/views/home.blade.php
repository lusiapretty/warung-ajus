{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Beranda - Warung Ajus')

@section('content')
<!-- Hero Section -->
<section class="hero" id="beranda" style="background-image: url('{{ asset('img/hero-bg.jpg') }}')">
  <div class="hero-overlay"></div>
  <div class="container hero-container">
    
    <!-- Kiri: Konten -->
    <div class="hero-content">
      <h1 class="hero-heading">Tipat Cantok ,<br> Satu Gigitan yang Berkesan</h1>
      <p class="hero-description">
        Tipat cantok adalah hidangan khas Bali yang mirip dengan gado-gado. Hidangan ini terdiri dari ketupat (tipat), 
        sayuran rebus (seperti kacang panjang, tauge, dan kangkung), dan tahu goreng yang disiram dengan saus kacang yang
        dihaluskan (cantok). 
      </p>
      <div class="hero-info">
        <i class="fas fa-clock"></i>
        <span>Buka: Senin – Jumat, 08.00 – 19.00</span>
      </div>
      <div class="hero-buttons">
        <a href="{{ route('menu.makanan') }}" class="btn btn-outline">LIHAT MENU</a>
      </div>
      </div>

    <!-- Kanan: Gambar -->
    <figure class="hero-banner">
      <img src="{{ asset('img/hero-banner-bg.png') }}" alt="" class="hero-img-bg">
      <img src="{{ asset('img/tipat-cantok.png') }}" alt="Tipat Cantok" class="hero-img">
    </figure>

  </div>
</section>


<section class="menu-favorit-modern" id="menu">
  <h2 class="menu-title">Menu Favorit</h2>
  <div class="menu-cards">
    <!-- Item 1 -->
    <div class="menu-card">
      <div class="menu-image">
        <img src="{{ asset('img/rujak-kuah-pindang.png') }}" alt="Rujak Kuah Pindang">
      </div>
      <div class="menu-body">
        <h3 class="menu-name">Rujak Kuah Pindang</h3>
        <p class="menu-desc">Buah segar dengan kuah pindang khas Bali.</p>
      </div>
    </div>
    <!-- Item 2 -->
    <div class="menu-card">
      <div class="menu-image">
        <img src="{{ asset('img/es-campur.png') }}" alt="Es Campur">
      </div>
      <div class="menu-body">
        <h3 class="menu-name">Es Campur</h3>
        <p class="menu-desc">Es segar campuran buah dan sirup manis.</p>
      </div>
    </div>
    <!-- Item 3 -->
    <div class="menu-card">
      <div class="menu-image">
        <img src="{{ asset('img/soto-ayam.png') }}" alt="Soto Ayam">
      </div>
      <div class="menu-body">
        <h3 class="menu-name">Soto Ayam</h3>
        <p class="menu-desc">Soto ayam gurih lengkap dengan telur dan sambal.</p>
      </div>
    </div>
    <!-- Item 4 -->
    <div class="menu-card">
      <div class="menu-image">
        <img src="{{ asset('img/nasi-campur1.jpg') }}" alt="Nasi Campur">
      </div>
      <div class="menu-body">
        <h3 class="menu-name">Nasi Campur</h3>
        <p class="menu-desc">Nasi dengan berbagai lauk khas Bali lengkap.</p>
      </div>
    </div>
  </div>
</section>


<!-- TENTANG KAMI -->
<section class="tentang-kami-elegan" id="tentangkami" data-aos="fade-up">
  <div class="tentang-container-elegan">
    <!-- Kiri: Teks -->
    <div class="tentang-konten-elegan">
      <h2>Tentang Kami</h2>
      <p class="tentang-subjudul">Cita rasa autentik Bali sejak 2012.</p>
      <p>
        Warung Ajus merupakan usaha kuliner yang bergerak di bidang penyediaan makanan khas Indonesia, terutama hidangan Bali.
        Sejak berdiri tahun 2012, kami hadir untuk menghadirkan pengalaman makan yang ramah, nikmat, dan terjangkau bagi seluruh kalangan.
        Komitmen kami terletak pada kualitas, kebersihan, dan pelayanan terbaik.
      </p>

      <div class="kutipan-tentang">
        <img src="{{ asset('img/logo-warung.png') }}" alt="Logo Warung Ajus" class="logo-mini">
        <div>
          <p class="quote-tentang">
            "Masakan adalah bahasa yang menyatukan rasa, budaya, dan kehangatan keluarga."
          </p>
          <p class="quote-author">– Warung Ajus</p>
        </div>
      </div>

      <!-- Tombol Selengkapnya -->
      <div style="max-width: 100%;">
        <a href="{{ route('tentang') }}" class="btn-selengkapnya">Selengkapnya</a>
      </div>
    </div>

    <!-- Kanan: Gambar Bertumpuk -->
    <div class="tentang-gambar-elegan">
      <div class="gambar-utama">
        <img src="{{ asset('img/warung-ajus1.png') }}" alt="Gambar Warung">
        <div class="est-tahun">Est.<br>2012</div>
      </div>
     </div>
  </div>
</section>


<!-- === SECTION LOKASI KAMI === -->
<section class="lokasi-section" id="lokasi-kami">
  <div class="lokasi-heading">
    <h2>Lokasi Kami</h2>
    <p>Kunjungi lokasi Warung Ajus atau hubungi kami untuk informasi lebih lanjut.</p>
  </div>

  <div class="lokasi-wrapper">
    <!-- MAP -->
    <div class="lokasi-map">
       <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3942.848616815192!2d115.17131780000001!3d-8.800289500000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd244bbfe6b20fb%3A0xa1f6fe393df8f8a5!2sWarung%20Ajus!5e0!3m2!1sen!2sid!4v1751600787059!5m2!1sen!2sid"
    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
    </div>
    

    <!-- KONTAK -->
    <div class="lokasi-info-box">
      <h3 class="lokasi-title">Hubungi Kami</h3>
      <p class="lokasi-subtitle">Kunjungi kami hari ini</p>

      <div class="lokasi-item">
        <i class="fas fa-map-marker-alt"></i>
        <div>
          <strong>Alamat</strong>
          <p>Jln. Goa Gong No. 3, Bukit Jimbaran, Kampus UNUD</p>
        </div>
      </div>

      <div class="lokasi-item">
        <i class="fas fa-phone-alt"></i>
        <div>
          <strong>Telepon</strong>
          <p>0821-4408-3032</p>
        </div>
      </div>

      <div class="lokasi-item">
        <i class="fas fa-clock"></i>
        <div>
          <strong>Jam Operasional</strong>
          <p>
            Senin – Jumat: 08.00 – 19.00<br>
            Sabtu & Minggu: Libur
          </p>
        </div>
      </div>
    </div>
  </div>
</section>






@endsection
@section('scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
  AOS.init();
</script>

@endsection
