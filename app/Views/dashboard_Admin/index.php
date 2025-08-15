<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-1 text-gray-800">Dashboard</h1>
      <p class="mb-0 text-muted">Selamat datang kembali, <span class="font-weight-bold text-primary"><?= esc(session('username')) ?></span>!</p>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-secondary"><i class="fas fa-bolt mr-2"></i>Aksi Cepat</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
              <a href="<?= site_url('siswa/create') ?>" class="btn btn-primary btn-icon-split btn-block">
                <span class="icon text-white-50"><i class="fas fa-user-plus"></i></span>
                <span class="text">Tambah Siswa Baru</span>
              </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
              <a href="<?= site_url('siswa') ?>" class="btn btn-info btn-icon-split btn-block">
                <span class="icon text-white-50"><i class="fas fa-users"></i></span>
                <span class="text">Daftar Siswa</span>
              </a>
            </div>
            <?php if (session()->get('role') === 'operator'): ?>
              <div class="col-lg-3 col-md-6 mb-2 mb-md-0">
                <a href="<?= site_url('user') ?>" class="btn btn-warning btn-icon-split btn-block">
                  <span class="icon text-white-50"><i class="fas fa-users-cog"></i></span>
                  <span class="text">Kelola Pengguna</span>
                </a>
              </div>
            <?php endif; ?>
            <div class="col-lg-3 col-md-6">
              <a href="<?= site_url('siswa/laporan_siswa') // Pastikan URL ini benar 
                        ?>" class="btn btn-success btn-icon-split btn-block">
                <span class="icon text-white-50"><i class="fas fa-chart-line"></i></span>
                <span class="text">Lihat Laporan Siswa</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_siswa ?? 0) ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
          <a href="<?= base_url('siswa') ?>" class="stretched-link small text-primary view-details">Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i></a>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kelas</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_kelas ?? 0) ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-school fa-2x text-gray-300"></i>
            </div>
          </div>
          <a href="<?= base_url('kelas') ?>" class="stretched-link small text-success view-details">Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i></a>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Orang Tua</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_orang_tua ?? 0) ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-friends fa-2x text-gray-300"></i>
            </div>
          </div>
          <a href="<?= base_url('ortu') ?>" class="stretched-link small text-info view-details">Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i></a>
        </div>
      </div>
    </div>

    <?php if (session()->get('role') === 'operator'): ?>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total User Sistem</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_user ?? 0) ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users-cog fa-2x text-gray-300"></i>
              </div>
            </div>
            <a href="<?= base_url('user') ?>" class="stretched-link small text-warning view-details">Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i></a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>




  <div class="row">
    <div class="col-xl-4 col-lg-5 mb-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Status Siswa Kurang Mampu</h6>
        </div>
        <div class="card-body">
          <div class="chart-pie pt-2"><canvas id="kurangMampuChart"></canvas></div>
        </div>
      </div>
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Jenis Kelamin Siswa</h6>
        </div>
        <div class="card-body">
          <div class="chart-pie pt-2"><canvas id="jkChart"></canvas></div>
        </div>
      </div>
    </div>
    <div class="col-xl-8 col-lg-7 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Tren Jumlah Siswa (6 Bulan Terakhir)</h6>
        </div>
        <div class="card-body">
          <div class="chart-area" style="height: 320px;"><canvas id="trendSiswaChart"></canvas></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-success">
            <i class="fas fa-user-clock"></i> Siswa Baru Ditambahkan
          </h6>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
          <?php if (!empty($siswa_baru) && is_array($siswa_baru)): ?>
            <ul class="list-group list-group-flush">
              <?php foreach ($siswa_baru as $sb): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong class="d-block"><?= esc($sb['nama_siswa']) ?></strong>
                    <small class="text-muted">NISN: <?= esc($sb['nisn']) ?> | Kelas: <?= esc($sb['nama_kelas'] ?? '-') ?></small>
                    <small class="text-muted d-block">Ditambahkan pada: <?= esc($sb['created_at']) ?></small>
                  </div>
                  <a href="<?= site_url('siswa/edit/' . $sb['id']) // Atau link ke detail siswa 
                            ?>" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" title="Lihat/Edit Detail">
                    <i class="fas fa-search-plus"></i>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-center text-muted mt-3">Belum ada siswa baru yang ditambahkan baru-baru ini.</p>
          <?php endif; ?>
        </div>
        <div class="card-footer text-center">
          <a href="<?= site_url('siswa') ?>" class="btn btn-sm btn-outline-info">Lihat Semua Daftar Siswa <i class="fas fa-arrow-right ml-1"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card shadow h-100">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-map-marker-alt"></i> Sebaran Lokasi Siswa (Dashboard)
          </h6>
        </div>
        <div class="card-body p-2">
          <div id="dashboardMapCanvas" style="height: 350px; width: 100%; border-radius: 0.35rem;"></div>
        </div>
        <div class="card-footer text-center py-2">
          <small class="text-muted">
            <a href="<?= site_url('peta-sebaran') ?>" class="btn btn-sm btn-info">
              <i class="fas fa-map-signs mr-1"></i> Lihat Peta Interaktif Lebih Detail
            </a>
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  /* ... (CSS tambahan Anda yang sudah ada untuk .view-details, .chart-pie, .chart-area, canvas) ... */
  .card .view-details {
    position: absolute;
    bottom: 0.5rem;
    left: 1.25rem;
    font-size: 0.8rem;
  }

  .card .stretched-link::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
    content: "";
  }

  .chart-pie {
    position: relative;
    height: 180px;
    width: 100%;
  }

  .chart-area {
    position: relative;
    width: 100%;
  }

  canvas {
    max-width: 100%;
    max-height: 100%;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // --- Data dari Controller (pastikan semua ada dan benar) ---
    const kurangMampuCount = <?= $kurang_mampu ?? 0 ?>;
    const tidakKurangMampuCount = <?= $tidak_kurang_mampu ?? 0 ?>;
    const lakiLakiCount = <?= $laki_laki ?? 0 ?>;
    const perempuanCount = <?= $perempuan ?? 0 ?>;
    const trendLabels = <?= $trend_chart_labels ?? '[]' ?>;
    const trendDataKurangMampu = <?= $trend_chart_kurang_mampu ?? '[]' ?>;
    const trendDataTidakKurangMampu = <?= $trend_chart_tidak_kurang_mampu ?? '[]' ?>;

    // Data untuk peta dashboard dari controller
    const dashboardMapMarkersData = <?= $siswa_peta_dashboard_json ?? '[]' ?>;

    // --- Inisialisasi Pie Charts (kode Anda yang sudah ada) ---
    const pieChartOptions = {
      /* ... opsi pie chart Anda ... */
    };
    const kurangMampuCtx = document.getElementById('kurangMampuChart');
    if (kurangMampuCtx) {
      new Chart(kurangMampuCtx, {
        type: 'doughnut',
        data: {
          labels: ['Kurang Mampu', 'Tidak Kurang Mampu'],
          datasets: [{
            data: [kurangMampuCount, tidakKurangMampuCount],
            backgroundColor: ['#E74A3B', '#1CC88A'],
            hoverBackgroundColor: ['#C73022', '#13A372'],
            borderColor: '#ffffff',
            borderWidth: 2
          }]
        },
        options: pieChartOptions
      });
    }
    const jkCtx = document.getElementById('jkChart');
    if (jkCtx) {
      new Chart(jkCtx, {
        type: 'doughnut',
        data: {
          labels: ['Laki-laki', 'Perempuan'],
          datasets: [{
            data: [lakiLakiCount, perempuanCount],
            backgroundColor: ['#4E73DF', '#F6C23E'],
            hoverBackgroundColor: ['#2E59D9', '#D4A622'],
            borderColor: '#ffffff',
            borderWidth: 2
          }]
        },
        options: pieChartOptions
      });
    }

    // --- Inisialisasi Trend Line Chart (kode Anda yang sudah ada) ---
    const trendSiswaCtx = document.getElementById('trendSiswaChart');
    if (trendSiswaCtx && Array.isArray(trendLabels) && trendLabels.length > 0) {
      new Chart(trendSiswaCtx, {
        type: 'line',
        data: {
          labels: trendLabels,
          datasets: [{
            label: 'Siswa Kurang Mampu',
            data: trendDataKurangMampu,
            borderColor: '#E74A3B',
            backgroundColor: 'rgba(231, 74, 59, 0.1)',
            tension: 0.3,
            /* ...properti lain... */
          }, {
            label: 'Siswa Tidak Kurang Mampu',
            data: trendDataTidakKurangMampu,
            borderColor: '#1CC88A',
            backgroundColor: 'rgba(28, 200, 138, 0.1)',
            tension: 0.3,
            /* ...properti lain... */
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          /* ...sisa opsi trend chart... */
        }
      });
    }

    // --- Inisialisasi Peta Dashboard ---
    window.initDashboardMap = function() { // Buat fungsi global
      const defaultDashboardCenter = {
        lat: 3.7525,
        lng: 96.8294
      }; // Sesuaikan
      let initialCenter = defaultDashboardCenter;
      let zoomLevel = 9; // Zoom lebih luas untuk overview dashboard

      if (dashboardMapMarkersData.length === 1 && dashboardMapMarkersData[0].latitude && dashboardMapMarkersData[0].longitude) {
        initialCenter.lat = parseFloat(dashboardMapMarkersData[0].latitude);
        initialCenter.lng = parseFloat(dashboardMapMarkersData[0].longitude);
        zoomLevel = 13;
      } else if (dashboardMapMarkersData.length > 1) {
        const bounds = new google.maps.LatLngBounds();
        dashboardMapMarkersData.forEach(function(data) {
          const lat = parseFloat(data.latitude);
          const lng = parseFloat(data.longitude);
          if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
            bounds.extend(new google.maps.LatLng(lat, lng));
          }
        });
        if (!bounds.isEmpty()) {
          // Tidak perlu set initialCenter jika menggunakan fitBounds
        }
      }

      const dashboardMapElement = document.getElementById("dashboardMapCanvas");
      if (!dashboardMapElement) {
        console.error("Elemen #dashboardMapCanvas tidak ditemukan!");
        return;
      }

      const mapOnDashboard = new google.maps.Map(dashboardMapElement, {
        center: initialCenter,
        zoom: zoomLevel,
        mapTypeId: 'roadmap',
        streetViewControl: false,
        mapTypeControl: false,
        fullscreenControl: true // Mungkin berguna untuk dashboard
      });

      if (dashboardMapMarkersData.length > 1 && typeof bounds !== 'undefined' && !bounds.isEmpty()) {
        mapOnDashboard.fitBounds(bounds);
        google.maps.event.addListenerOnce(mapOnDashboard, 'bounds_changed', function() {
          if (this.getZoom() > 16) {
            this.setZoom(16);
          } // Batasi zoom maks
        });
      }


      const infoWindowDashboard = new google.maps.InfoWindow();
      dashboardMapMarkersData.forEach(function(dataSiswa) {
        const lat = parseFloat(dataSiswa.latitude);
        const lng = parseFloat(dataSiswa.longitude);

        if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
          const marker = new google.maps.Marker({
            position: {
              lat: lat,
              lng: lng
            },
            map: mapOnDashboard,
            title: dataSiswa.nama_siswa,
          });
          let contentString = `<div><strong>${escHtmlDashboard(dataSiswa.nama_siswa)}</strong><br><small>Status: ${dataSiswa.status_kurang_mampu == 1 ? 'Kurang Mampu' : 'Tidak Kurang Mampu'}</small></div>`;
          marker.addListener("click", () => {
            infoWindowDashboard.close();
            infoWindowDashboard.setContent(contentString);
            infoWindowDashboard.open(marker.getMap(), marker);
          });
          
        }
      });

    }

    function escHtmlDashboard(unsafe) {
      if (unsafe === null || typeof unsafe === 'undefined') return '';
      return unsafe.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
  });
</script>

<script async src="https://maps.googleapis.com/maps/api/js?key=<?= esc($Maps_api_key ?? '', 'attr') ?>&callback=initDashboardMap&loading=async"></script>

<?= $this->endSection(); ?>