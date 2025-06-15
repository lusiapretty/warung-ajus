<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <link rel="stylesheet" href="{{ asset('template/dist/assets/fonts/tabler-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/dist/assets/fonts/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('template/dist/assets/fonts/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('template/dist/assets/fonts/material.css') }}">
  <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('template/dist/assets/css/style-preset.css') }}">
</head>
<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">

  <!-- Loader -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>

  <!-- Sidebar -->
  @include('partials.admin.sidebar')

  <!-- Header -->
  @include('partials.admin.header')

  <!-- Main Content -->
  <div class="pc-container">
    <div class="pc-content">
      @yield('content')
    </div>
  </div>

  <!-- [Page Specific JS] start -->
  <script src="{{ asset('template/dist/assets/js/plugins/apexcharts.min.js') }}"></script>
  <script src="{{ asset('template/dist/assets/js/pages/dashboard-default.js') }}"></script>
  <!-- [Page Specific JS] end -->
  <!-- Required Js -->
  <script src="{{ asset('template/dist/assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('template/dist/assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('template/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('template/dist/assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('template/dist/assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('template/dist/assets/js/plugins/feather.min.js') }}"></script>


  <script>layout_change('light');</script>

  <script>change_box_container('false');</script>
  
  <script>layout_rtl_change('false');</script>
  
  <script>preset_change("preset-1");</script>
   
  <script>font_change("Public-Sans");</script>
  
</body>
</html>
