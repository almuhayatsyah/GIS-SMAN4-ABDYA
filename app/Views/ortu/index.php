<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-user-friends"></i> <?= esc($title ?? 'Data Orang Tua') ?>
    </h1>
    <div>
      <a href="<?= site_url('ortu/create') ?>" class="btn btn-primary btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
        <span class="text">Tambah Data Ortu</span>
      </a>
    </div>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-search"></i> Pencarian Data Orang Tua
      </h6>
    </div>
    <div class="card-body">
      <form action="<?= site_url('ortu') ?>" method="get" class="form-inline">
        <div class="form-group mr-2 mb-2">
          <input type="text" class="form-control" name="search" placeholder="Cari Nama Ayah/Ibu, Siswa, NISN..." value="<?= esc($search ?? '', 'attr') ?>">
        </div>
        <button class="btn btn-primary mb-2" type="submit">
          <i class="fas fa-search fa-sm"></i> Cari
        </button>
        <?php if (!empty($search)): ?>
          <a href="<?= site_url('ortu') ?>" class="btn btn-secondary ml-2 mb-2">Reset</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-table"></i> Daftar Data Orang Tua
      </h6>
    </div>
    <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= session()->getFlashdata('success') ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= session()->getFlashdata('error') ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dataTableOrtu" width="100%" cellspacing="0">
          <thead class="thead-dark">
            <tr>
              <th class="text-center">No</th>
              <th class="text-center">ID Ortu</th>
              <th>Nama Ayah</th>
              <th>Nama Ibu</th>
              <th>Pekerjaan Ayah</th>
              <th>Pekerjaan Ibu</th>
              <th>Penghasilan Ayah</th>
              <th>Penghasilan Ibu</th>
              <th>No. HP Ortu</th>
              <th>Siswa Terhubung (NISN - Nama)</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($ortu_list) && is_array($ortu_list)): ?>
              <?php $no = $nomor_awal ?? 1; ?>
              <?php foreach ($ortu_list as $o): ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td class="text-center"><?= esc($o['id']) ?></td>
                  <td><?= esc($o['nama_ayah']) ?></td>
                  <td><?= esc($o['nama_ibu'] ?? '-') ?></td>
                  <td><?= esc($o['pekerjaan_ayah'] ?? '-') ?></td>
                  <td><?= esc($o['pekerjaan_ibu'] ?? '-') ?></td>
                  <td>
                    <?= !empty($o['gaji_ayah']) ? 'Rp ' . number_format($o['gaji_ayah'], 0, ',', '.') : '-' ?>
                  </td>
                  <td>
                    <?= !empty($o['gaji_ibu']) ? 'Rp ' . number_format($o['gaji_ibu'], 0, ',', '.') : '-' ?>
                  </td>

                  <td><?= esc($o['nomor_hp'] ?? '-') ?></td>
                  <td>
                    <?php if (!empty($o['id_siswa'])): ?>
                      <?= esc($o['nisn_siswa'] ?? 'NISN Tidak Ada') ?> - <?= esc($o['nama_siswa'] ?? 'Nama Siswa Tidak Ada') ?>
                      (ID Siswa: <?= esc($o['id_siswa']) ?>)
                    <?php else: ?>
                      <em>Belum terhubung ke siswa</em>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a href="<?= site_url('ortu/edit/' . $o['id']) ?>"
                      class="btn btn-warning btn-sm btn-circle"
                      data-toggle="tooltip"
                      title="Edit Data Ortu">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="#"
                      class="btn btn-danger btn-sm btn-circle"
                      onclick="confirmDeleteOrtu('<?= $o['id'] ?>', '<?= esc("data ortu untuk siswa " . ($o['nama_siswa'] ?? "ID:" . $o['id_siswa']), 'js') ?>')"
                      data-toggle="tooltip"
                      title="Hapus Data Ortu">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10" class="text-center">Tidak ada data orang tua yang ditemukan.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="mt-4">
        <?php if (isset($pager) && $pager) : ?>
          <?= $pager->links('ortu_page_group', 'bootstrap_pagination') // Pastikan 'ortu_page_group' sama dengan di controller 
          ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- delete -->
<div class="modal fade" id="deleteOrtuModal" tabindex="-1" role="dialog" aria-labelledby="deleteOrtuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteOrtuModalLabel">Konfirmasi Hapus Data Orang Tua</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus <strong id="ortuToDeleteName"></strong>?
        <br><small>Tindakan ini hanya menghapus data orang tua, tidak data siswanya.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <a href="#" id="confirmDeleteOrtuButton" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>

<!-- end -->

<script>
  // Fungsi untuk konfirmasi hapus
  function confirmDeleteOrtu(id, name) {
    document.getElementById('ortuToDeleteName').innerHTML = name ? '<strong>"' + name + '"</strong>' : 'data ini';
    var deleteUrl = '<?= site_url('ortu/delete/') ?>' + id;
    document.getElementById('confirmDeleteOrtuButton').setAttribute('href', deleteUrl);
    $('#deleteOrtuModal').modal('show');
  }

  // Initialize tooltips (jika Anda menggunakan Bootstrap tooltips)
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>

<?= $this->endSection() ?>