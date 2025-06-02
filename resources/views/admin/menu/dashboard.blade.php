@extends('layouts.app')

@section('title', 'Dashboard | Warung Ajus')

@section('content')
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
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Pengunjung Website</h6>
              <h4 class="mb-3">4,42,236 <span class="badge bg-light-primary border border-primary"><i
                    class="ti ti-trending-up"></i> 59.3%</span></h4>
              <p class="mb-0 text-muted text-sm">Kamu mendapatkan <span class="text-primary">3.000</span> pengunjung lebih banyak bulan ini
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Pengguna</h6>
              <h4 class="mb-3">78,250 <span class="badge bg-light-success border border-success"><i
                    class="ti ti-trending-up"></i> 70.5%</span></h4>
              <p class="mb-0 text-muted text-sm">Kamu mendapatkan <span class="text-success">100</span> pengguna baru bulan ini</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Pesanan</h6>
              <h4 class="mb-3">18,800 <span class="badge bg-light-warning border border-warning"><i
                    class="ti ti-trending-down"></i> 27.4%</span></h4>
              <p class="mb-0 text-muted text-sm">Kamu mendapatkan <span class="text-warning">190</span> pesanan lebih banyak bulan ini</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Total Penjualan</h6>
              <h4 class="mb-3">Rp. 5,078 <span class="badge bg-light-danger border border-danger"><i
                    class="ti ti-trending-down"></i> 27.4%</span></h4>
              <p class="mb-0 text-muted text-sm">Kamu mendapatkan <span class="text-danger">Rp. 800.000</span> lebih banyak bulan ini
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-xl-8">
          <h5 class="mb-3">Pesanan Terbaru</h5>
          <div class="card tbl-card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-borderless mb-0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Pelanggan</th>
                      <th>Nama Menu</th>
                      <th>Jumlah</th>
                      <th class="text-end">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><a href="#" class="text-muted">84564564</a></td>
                      <td>Laptop</td>
                      <td>300</td>
                      <td><span class="d-flex align-items-center gap-2"><i
                            class="fas fa-circle text-warning f-10 m-r-5"></i>Pending</span>
                      </td>
                      <td class="text-end">$180,139</td>
                    </tr>
                    <tr>
                      <td><a href="#" class="text-muted">84564564</a></td>
                      <td>Mobile</td>
                      <td>355</td>
                      <td><span class="d-flex align-items-center gap-2"><i
                            class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span></td>
                      <td class="text-end">$180,139</td>
                    </tr>
                    <tr>
                      <td><a href="#" class="text-muted">84564564</a></td>
                      <td>Mobile</td>
                      <td>355</td>
                      <td><span class="d-flex align-items-center gap-2"><i
                            class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span></td>
                      <td class="text-end">$180,139</td>
                    </tr>
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
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <div class="avtar avtar-s rounded-circle text-success bg-light-success">
                      <i class="ti ti-gift f-18"></i>
                    </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Order #002434</h6>
                    <p class="mb-0 text-muted">Today, 2:00 AM</P>
                  </div>
                  <div class="flex-shrink-0 text-end">
                    <h6 class="mb-1">+ $1,430</h6>
                  </div>
                </div>
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <div class="avtar avtar-s rounded-circle text-primary bg-light-primary">
                      <i class="ti ti-message-circle f-18"></i>
                    </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Order #984947</h6>
                    <p class="mb-0 text-muted">5 August, 1:45 PM</P>
                  </div>
                  <div class="flex-shrink-0 text-end">
                    <h6 class="mb-1">- $302</h6>
                  </div>
                </div>
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <div class="avtar avtar-s rounded-circle text-danger bg-light-danger">
                      <i class="ti ti-settings f-18"></i>
                    </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Order #988784</h6>
                    <p class="mb-0 text-muted">7 hours ago</P>
                  </div>
                  <div class="flex-shrink-0 text-end">
                    <h6 class="mb-1">- $682</h6>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>

          <div class="col-md-12 col-xl-12">
          <h5 class="mb-3">Ringkasan Pendapatan</h5>
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Statistik Minggu ini</h6>
              <h3 class="mb-3">Rp. 1.000.000</h3>
              <div id="income-overview-chart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  @endsection