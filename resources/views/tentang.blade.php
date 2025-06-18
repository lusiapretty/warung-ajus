@extends('layouts.app')

@section('title', 'Tentang Kami - Warung Ajus')

@push('styles')
    {{-- Tambahan CSS jika perlu --}}
    <link rel="stylesheet" href="{{ asset('css/tentang-kami.css') }}">
@endpush

@section('content')
    <!-- INFORMASI UMKM -->
    <section class="info-umkm" id="tentangkami" data-aos="fade-up">
        <h3>Tentang Kami</h3>
        <p>
            Warung Ajus merupakan usaha kuliner yang bergerak di bidang penyediaan makanan Indonesia. 
            Didirikan pada tahun 2012, Warung Ajus hadir dengan tujuan untuk menghadirkan cita rasa autentik 
            Bali dalam suasana yang ramah dan harga yang terjangkau bagi semua kalangan. Sejak awal berdiri, 
            Warung Ajus telah berkomitmen untuk menjaga kualitas makanan dengan bahan-bahan segar serta olahan yang higienis.<br><br>

            Seiring dengan meningkatnya minat konsumen terhadap kuliner, Warung Ajus terus mengalami perkembangan yang signifikan. 
            Kepercayaan pelanggan dan tingginya permintaan mendorong dibukanya cabang kedua pada tahun 2016, diikuti oleh cabang 
            ketiga pada tahun 2021. Pertumbuhan ini merupakan cerminan dari kualitas layanan dan produk yang konsisten serta strategi 
            pemasaran yang tepat sasaran.<br><br>

            Target pasar Warung Ajus mencakup seluruh lapisan masyarakat, mulai dari pelajar, pekerja, 
            hingga keluarga. Dengan menyasar segmen yang luas, Warung Ajus mampu menarik berbagai kalangan melalui keunggulan dalam rasa, 
            harga, dan kenyamanan tempat makan.
        </p>
    </section>

    <!-- VISI & MISI -->
    <section class="visi-misi">
        <div class="visi" data-aos="fade-right">
            <h2>Visi</h2>
            <p>"Menjadi destinasi kuliner Bali terpercaya yang dikenal nasional, dengan memperluas cabang dan inovasi menu tanpa meninggalkan cita rasa tradisional."</p>
        </div>
        <div class="misi" data-aos="fade-left">
            <h2>Misi</h2>
            <p>"Rasa autentik warisan keluarga, memberikan pengalaman makan berkualitas dengan harga terjangkau, serta menciptakan lapangan kerja bagi masyarakat lokal."</p>
        </div>
    </section>

    <!-- CABANG WARUNG -->
    <section class="cabang-warung" data-aos="zoom-in">
        <h2>Cabang Warung Ajus</h2>
        <div class="cabang-container">
            <!-- Cabang 1 -->
            <div class="cabang-card" data-aos="fade-up">
                <a href="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3942.848616815192!2d115.17131780000001!3d-8.800289500000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd244bbfe6b20fb%3A0xa1f6fe393df8f8a5!2sWarung%20Ajus!5e0!3m2!1sen!2sid!4v1747168588305!5m2!1sen!2sid" target="_blank">
                    <img src="{{ asset('img/es-campur.png') }}" alt="Cabang 1">
                </a>
                <h3>Cabang 1</h3>
                <p>Fakultas MIPA, Universitas Udayana</p>
            </div>

            <!-- Cabang 2 -->
            <div class="cabang-card" data-aos="fade-up">
                <a href="https://www.google.com/maps?q=Jl.+Raya+Kampus+Unud+Jimbaran" target="_blank">
                    <img src="{{ asset('img/soto-ayam.png') }}" alt="Cabang 2">
                </a>
                <h3>Cabang 2</h3>
                <p>Jl. Goa Gong No.3, Jimbaran</p>
            </div>

            <!-- Cabang 3 -->
            <div class="cabang-card" data-aos="fade-up">
                <a href="https://www.google.com/maps?q=Pasar+Kuliner+Malam+Kuta+Selatan" target="_blank">
                    <img src="{{ asset('img/soto-ayam.png') }}" alt="Cabang 3">
                </a>
                <h3>Cabang 3</h3>
                <p>Jl. Raya Kampus Unud, Jimbaran</p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- Tambahan script untuk AOS --}}
    <script>
        AOS.init();
    </script>
@endpush
