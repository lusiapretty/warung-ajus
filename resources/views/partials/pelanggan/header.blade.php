<section class="hero" id="beranda" style="background-image: url('{{ asset('img/hero-bg.jpg') }}')">
  <div class="container">
    <div class="hero-content">
      <p class="hero-subtitle">Rasa Tradisional</p>
      <h2 class="h1 hero-title">Tipat Cantok <br> Khas Bali yang Lezat!</h2>
      <p class="hero-text">
        Tipat Cantok adalah hidangan khas Bali seperti gado-gado, 
        terdiri dari ketupat, sayuran rebus, dan siraman bumbu kacang 
        yang diulek atau “dicantok”.
      </p>
      <a href="{{ route('menu.makanan') }}" class="btn">Pesan Sekarang</a>
    </div>

    <figure class="hero-banner">
      <img src="{{ asset('img/hero-banner-bg.png') }}" alt="" class="hero-img-bg"> <!-- background bentuk merah -->
      <img src="{{ asset('img/tipat-cantok.png') }}" alt="Tipat Cantok" class="hero-img">
    </figure>
  </div>
</section>