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
          <a href="{{ route('dashboard')}}" class="pc-link">
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
<!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
