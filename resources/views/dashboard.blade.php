
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Beranda | Warung Ajus</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <!-- <link rel="icon" href="template/dist/assets/images/favicon.svg" type="image/x-icon"> [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="template/dist/assets/fonts/tabler-icons.min.css" >
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="template/dist/assets/fonts/feather.css" >
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="template/dist/assets/fonts/fontawesome.css" >
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="template/dist/assets/fonts/material.css" >
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="template/dist/assets/css/style.css" id="main-style-link" >
<link rel="stylesheet" href="template/dist/assets/css/style-preset.css" >

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
<div class="loader-bg">
  <div class="loader-track">
    <div class="loader-fill"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
 <!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header bg-red-700">
      <a href="#" class="b-brand text-white">
        <!-- ========   Change your logo from here   ============ -->
     <img src="{{ asset('img/logo-warung.png')}}" style="max-width: 20%; height: auto;"> 
       <span>WARUNG AJUS</span>
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">

        <li class="pc-item">
          <a href="#" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="{{ route('admin.menu.index')}}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-book"></i></span>
            <span class="pc-mtext">Daftar Menu</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('admin.addons.index')}}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-list"></i></span>
            <span class="pc-mtext">Add On</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="{{ route('admin.pelanggan.index')}}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Pelanggan</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('admin.orders.index')}}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-tools-kitchen"></i></span>
            <span class="pc-mtext">Pesanan Pelanggan</span>
          </a>
        </li>

        <li class="pc-item pc-caption">
          <label>Pages</label>
          <i class="ti ti-news"></i>
        </li>
        
        <li class="pc-item">
          <a href="#" class="pc-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <span class="pc-micon"><i class="ti ti-logout"></i></span>
              <span class="pc-mtext">Logout</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
       </li>
      </ul>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end --> 

<!-- [ Header Topbar ] start -->
<header class="pc-header">
  <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
<div class="me-auto pc-mob-drp">
  <ul class="list-unstyled">
    <!-- ======= Menu collapse Icon ===== -->
    <li class="pc-h-item pc-sidebar-collapse">
      <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
    <li class="pc-h-item pc-sidebar-popup">
      <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
  </ul>
</div>
<!-- [Mobile Media Block end] -->
<div class="ms-auto">
  <ul class="list-unstyled">
    <li class="dropdown pc-h-item header-user-profile">
      <a
        class="pc-head-link dropdown-toggle arrow-none me-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        data-bs-auto-close="outside"
        aria-expanded="false"
      >
        <img src="{{ asset('img/logo-admin.png')}}" alt="user-image" class="user-avtar">
        <span>Admin</span>
      </a>
    </li>
  </ul>
</div>
 </div>
</header>
<!-- [ Header ] end -->



  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="m-b-10">Dashboard</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">
        <!-- [ sample-page ] start -->

        {{-- Total Pesanan --}}
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Pesanan</h6>
              <h4 class="mb-3">
                {{ number_format($totalPesanan) }} 
                <span class="badge bg-light-{{ $persenPesanan >= 0 ? 'success' : 'danger' }} border border-{{ $persenPesanan >= 0 ? 'success' : 'danger' }}">
                  <i class="ti ti-trending-{{ $persenPesanan >= 0 ? 'up' : 'down' }}"></i> 
                  {{ number_format(abs($persenPesanan), 1) }}%
                </span>
              </h4>
              <p class="mb-0 text-muted text-sm">
                Kamu mendapatkan <span class="text-{{ $persenPesanan >= 0 ? 'success' : 'danger' }}">{{ $pesananBulanIni }}</span> pesanan lebih banyak bulan ini
              </p>
            </div>
          </div>
        </div>

        {{-- Total Pengguna --}}
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Pengguna</h6>
              <h4 class="mb-3">
                {{ number_format($totalPengguna) }} 
                <span class="badge bg-light-{{ $persenPengguna >= 0 ? 'success' : 'danger' }} border border-{{ $persenPengguna >= 0 ? 'success' : 'danger' }}">
                  <i class="ti ti-trending-{{ $persenPengguna >= 0 ? 'up' : 'down' }}"></i> 
                  {{ number_format(abs($persenPengguna), 1) }}%
                </span>
              </h4>
              <p class="mb-0 text-muted text-sm">
                Kamu mendapatkan <span class="text-{{ $persenPengguna >= 0 ? 'success' : 'danger' }}">{{ $penggunaBulanIni }}</span> pengguna baru bulan ini
              </p>
            </div>
          </div>
        </div>


        <div class="col-md-12 col-xl-8">
          <h5 class="mb-3">Pesanan Terbaru</h5>
          <div class="card tbl-card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Pelanggan</th>
                      <th>Menu</th>
                      <th>Jumlah</th>
                      <th class="text-end">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                        @forelse ($pesananTerbaru as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->user->name ?? $order->nama_pelanggan ?? '-' }}</td>
                                <td>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($order->items as $item)
                                            <li>{{ $item->menu->nama_menu ?? '-' }} ({{ $item->jumlah }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                 <td>{{ $order->items->sum('jumlah') }}</td>
                                <td class="text-end">
                                    Rp{{ number_format($order->items->sum(function ($item) {
                                        return $item->harga * $item->jumlah;
                                    }), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada pesanan terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-xl-4">
          <h5 class="mb-3">Riwayat Pembayaran</h5>
          <div class="card">
            <div class="list-group list-group-flush">
              @forelse ($riwayatPembayaran as $order)
                <a href="{{ route('admin.orders.show', $order->id) }}" class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <div class="avtar avtar-s rounded-circle text-success bg-light-success">
                        <i class="ti ti-credit-card f-18"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="mb-1">Order #{{ $order->order_id }}</h6>
                      <p class="mb-0 text-muted">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex-shrink-0 text-end">
                      <h6 class="mb-1">
                        Rp{{ number_format($order->items->sum(fn($i) => $i->harga * $i->jumlah), 0, ',', '.') }}
                      </h6>
                    </div>
                  </div>
                </a>
              @empty
                <div class="text-center text-muted p-3">Tidak ada pembayaran terbaru.</div>
              @endforelse
            </div>
          </div>
        </div>


          {{-- <div class="col-md-12 col-xl-12">
          <h5 class="mb-3">Ringkasan Pendapatan</h5>
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Statistik Minggu ini</h6>
              <h3 class="mb-3">Rp. 1.000.000</h3>
              <div id="income-overview-chart"></div>
            </div>
          </div>
        </div> --}}
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->


  <!-- [Page Specific JS] start -->
  <script src="template/dist/assets/js/plugins/apexcharts.min.js"></script>
  <script src="template/dist/assets/js/pages/dashboard-default.js"></script>
  <!-- [Page Specific JS] end -->
  <!-- Required Js -->
  <script src="template/dist/assets/js/plugins/popper.min.js"></script>
  <script src="template/dist/assets/js/plugins/simplebar.min.js"></script>
  <script src="template/dist/assets/js/plugins/bootstrap.min.js"></script>
  <script src="template/dist/assets/js/fonts/custom-font.js"></script>
  <script src="template/dist/assets/js/pcoded.js"></script>
  <script src="template/dist/assets/js/plugins/feather.min.js"></script>

  
  
  
  
  <script>layout_change('light');</script>
  
  
  
  
  <script>change_box_container('false');</script>
  
  
  
  <script>layout_rtl_change('false');</script>
  
  
  <script>preset_change("preset-1");</script>
  
  
  <script>font_change("Public-Sans");</script>
  
    

</body>
<!-- [Body] end -->

</html>