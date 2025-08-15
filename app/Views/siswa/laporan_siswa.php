<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-chart-bar"></i>
      Laporan Jumlah Siswa
    </h1>
  </div>

  <!-- Content Row -->
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-table"></i>
            Data Siswa Per Kelas
          </h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead class="thead-dark">
                <tr class="text-center">
                  <th>Kelas</th>
                  <th>Laki-laki</th>
                  <th>Perempuan</th>
                  <th>Kurang Mampu</th>
                  <th>Tidak Kurang Mampu</th>
                  <th>Total Siswa</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $total_laki = 0;
                $total_perempuan = 0;
                $total_km = 0;
                $total_tidak_km = 0;
                $total_semua = 0;
                ?>
                <?php foreach ($laporan as $data): ?>
                  <tr class="text-center">
                    <td class="font-weight-bold"><?= $data['kelas'] ?? '-' ?> </td> <!-- Use 'nama_kelas' instead of 'kelas_id' -->
                    <td><?= $data['laki_laki'] ?></td>
                    <td><?= $data['perempuan'] ?></td>
                    <td><?= $data['kurang_mampu'] ?></td>
                    <td><?= $data['tidak_kurang_mampu'] ?></td>
                    <td class="font-weight-bold"><?= $data['total'] ?></td>
                  </tr>
                  <?php
                  $total_laki += $data['laki_laki'];
                  $total_perempuan += $data['perempuan'];
                  $total_km += $data['kurang_mampu'];
                  $total_tidak_km += $data['tidak_kurang_mampu'];
                  $total_semua += $data['total'];
                  ?>
                <?php endforeach; ?>
                <tr class="text-center table-primary font-weight-bold">
                  <td>TOTAL</td>
                  <td><?= $total_laki ?></td>
                  <td><?= $total_perempuan ?></td>
                  <td><?= $total_km ?></td>
                  <td><?= $total_tidak_km ?></td>
                  <td><?= $total_semua ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Cards Row -->
  <div class="row">
    <!-- Total Laki-laki Card -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Siswa Laki-laki</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_laki ?> Siswa</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-male fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Perempuan Card -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Total Siswa Perempuan</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_perempuan ?> Siswa</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-female fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Siswa Card -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Total Seluruh Siswa</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_semua ?> Siswa</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Kurang Mampu Cards Row -->
  <div class="row">
    <!-- Kurang Mampu Card -->
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Siswa Kurang Mampu</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_km ?> Siswa</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-shield fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tidak Kurang Mampu Card -->
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                Siswa Tidak Kurang Mampu</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_tidak_km ?> Siswa</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>