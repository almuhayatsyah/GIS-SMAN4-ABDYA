<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="fas fa-user-plus"></i> <?= esc($title ?? 'Tambah Data Orang Tua') ?>
    </h1>
    <a href="<?= site_url('ortu') ?>" class="btn btn-sm btn-secondary shadow-sm">
      <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar Ortu
    </a>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Form Tambah Data Orang Tua</h6>
        </div>
        <div class="card-body">
          <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <h6 class="alert-heading">Error Validasi!</h6>
              <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                  <li><?= esc($error) ?></li>
                <?php endforeach ?>
              </ul>
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


          <form action="<?= site_url('ortu/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
              <label for="id_siswa">Siswa Terkait <span class="text-danger">*</span></label>
              <select class="form-control <?= (session('errors.id_siswa')) ? 'is-invalid' : '' ?>" id="id_siswa" name="id_siswa" required>
                <option value="">-- Pilih Siswa --</option>
                <?php if (!empty($siswa_list)): ?>
                  <?php foreach ($siswa_list as $siswa): ?>
                    <option value="<?= esc($siswa['id']) ?>" <?= (old('id_siswa') == $siswa['id']) ? 'selected' : '' ?>>
                      <?= esc($siswa['nisn']) ?> - <?= esc($siswa['nama_siswa']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php if (session('errors.id_siswa')): ?>
                <div class="invalid-feedback"><?= session('errors.id_siswa') ?></div>
              <?php endif ?>
            </div>
            <hr>

            <div class="form-group">
              <label for="nama_ayah">Nama Ayah</label>
              <input type="text" class="form-control <?= (session('errors.nama_ayah')) ? 'is-invalid' : '' ?>" id="nama_ayah" name="nama_ayah" value="<?= old('nama_ayah') ?>" placeholder="Nama lengkap ayah">
              <?php if (session('errors.nama_ayah')): ?>
                <div class="invalid-feedback"><?= session('errors.nama_ayah') ?></div>
              <?php endif ?>
            </div>

            <div class="form-group">
              <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
              <input type="text" class="form-control <?= (session('errors.pekerjaan_ayah')) ? 'is-invalid' : '' ?>" id="pekerjaan_ayah" name="pekerjaan_ayah" value="<?= old('pekerjaan_ayah') ?>" placeholder="Pekerjaan ayah">
              <?php if (session('errors.pekerjaan_ayah')): ?>
                <div class="invalid-feedback"><?= session('errors.pekerjaan_ayah') ?></div>
              <?php endif ?>
            </div>

            <div class="form-group">
              <label for="gaji_ayah">Penghasilan Ayah (Rp per bulan)</label>
              <input type="number" step="any" class="form-control <?= (session('errors.gaji_ayah')) ? 'is-invalid' : '' ?>" id="gaji_ayah" name="gaji_ayah" value="<?= old('gaji_ayah') ?>" placeholder="Contoh: 3000000">
              <small class="form-text text-muted">Masukkan angka saja, contoh: 3000000 untuk Rp 3.000.000</small>
              <?php if (session('errors.gaji_ayah')): ?>
                <div class="invalid-feedback"><?= session('errors.gaji_ayah') ?></div>
              <?php endif ?>
            </div>
            <hr>

            <div class="form-group">
              <label for="nama_ibu">Nama Ibu</label>
              <input type="text" class="form-control <?= (session('errors.nama_ibu')) ? 'is-invalid' : '' ?>" id="nama_ibu" name="nama_ibu" value="<?= old('nama_ibu') ?>" placeholder="Nama lengkap ibu">
              <?php if (session('errors.nama_ibu')): ?>
                <div class="invalid-feedback"><?= session('errors.nama_ibu') ?></div>
              <?php endif ?>
            </div>

            <div class="form-group">
              <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
              <input type="text" class="form-control <?= (session('errors.pekerjaan_ibu')) ? 'is-invalid' : '' ?>" id="pekerjaan_ibu" name="pekerjaan_ibu" value="<?= old('pekerjaan_ibu') ?>" placeholder="Pekerjaan ibu">
              <?php if (session('errors.pekerjaan_ibu')): ?>
                <div class="invalid-feedback"><?= session('errors.pekerjaan_ibu') ?></div>
              <?php endif ?>
            </div>

            <div class="form-group">
              <label for="gaji_ibu">Penghasilan Ibu (Rp per bulan)</label>
              <input type="number" step="any" class="form-control <?= (session('errors.gaji_ibu')) ? 'is-invalid' : '' ?>" id="gaji_ibu" name="gaji_ibu" value="<?= old('gaji_ibu') ?>" placeholder="Contoh: 1500000">
              <small class="form-text text-muted">Masukkan angka saja. Kosongkan jika tidak berpenghasilan.</small>
              <?php if (session('errors.gaji_ibu')): ?>
                <div class="invalid-feedback"><?= session('errors.gaji_ibu') ?></div>
              <?php endif ?>
            </div>
            <hr>

            <div class="form-group">
              <label for="nomor_hp">Nomor HP Orang Tua (yang bisa dihubungi)</label>
              <input type="text" class="form-control <?= (session('errors.nomor_hp')) ? 'is-invalid' : '' ?>" id="nomor_hp" name="nomor_hp" value="<?= old('nomor_hp') ?>" placeholder="Nomor HP aktif">
              <?php if (session('errors.nomor_hp')): ?>
                <div class="invalid-feedback"><?= session('errors.nomor_hp') ?></div>
              <?php endif ?>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Data Orang Tua</button>
            <a href="<?= site_url('ortu') ?>" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-1"></i> Info</h6>
        </div>
        <div class="card-body">
          <p>Gunakan form ini untuk menambahkan data orang tua dan menghubungkannya dengan siswa yang sudah ada.</p>
          <p>Pastikan memilih siswa yang benar.</p>
          <p>Satu siswa hanya dapat memiliki satu set data orang tua.</p>

          <hr>

          <div class="alert alert-info">
            <h6 class="alert-heading"><i class="fas fa-calculator mr-1"></i> Kalkulasi Otomatis Status</h6>
            <p class="mb-2">Status "Kurang Mampu" akan dihitung otomatis berdasarkan:</p>
            <ul class="mb-0">
              <li><strong>Gaji Ayah + Gaji Ibu</strong></li>
              <li><strong>Dibagi jumlah anggota keluarga</strong> (default: 4 orang)</li>
              <li><strong>Batas:</strong>
                < Rp 1.920.000 per kapita/bulan</li>
            </ul>
            <p class="mb-0 mt-2"><small><i class="fas fa-info-circle"></i> Setelah data disimpan, status siswa akan langsung diupdate otomatis.</small></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>