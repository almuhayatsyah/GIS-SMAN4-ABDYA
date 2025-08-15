<?= $this->extend('layout/pengunjung'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="text-center mb-5">
        <h1 class="font-weight-bold" style="color:#4e2eff;">Formulir Pengajuan Bantuan</h1>
        <p class="lead text-muted">Silakan isi formulir di bawah ini untuk mengirimkan proposal atau pengajuan bantuan langsung ke email kami.</p>
      </div>

      <div class="card shadow-lg border-0">
        <div class="card-body p-4 p-md-5">

          <!-- Menampilkan notifikasi sukses atau error -->
          <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
              <?= session()->getFlashdata('success') ?>
            </div>
          <?php endif; ?>

          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
              <?= session()->getFlashdata('error') ?>
            </div>
          <?php endif; ?>

          <!-- Menampilkan error validasi -->
          <?php if (isset($validation)): ?>
            <div class="alert alert-danger">
              <?= $validation->listErrors() ?>
            </div>
          <?php endif; ?>

          <form action="<?= site_url('pengunjung/kirim-pengajuan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group mb-3">
              <label for="nama_pengaju" class="font-weight-bold">Nama Anda / Instansi</label>
              <input type="text" class="form-control form-control-lg" id="nama_pengaju" name="nama_pengaju" required value="<?= old('nama_pengaju') ?>">
            </div>

            <div class="form-group mb-3">
              <label for="email_pengaju" class="font-weight-bold">Alamat Email Anda</label>
              <input type="email" class="form-control form-control-lg" id="email_pengaju" name="email_pengaju" required placeholder="contoh: nama@email.com" value="<?= old('email_pengaju') ?>">
              <small class="form-text text-muted">Kami akan mengirimkan balasan ke email ini.</small>
            </div>

            <div class="form-group mb-3">
              <label for="subjek" class="font-weight-bold">Subjek Pengajuan</label>
              <input type="text" class="form-control form-control-lg" id="subjek" name="subjek" required value="<?= old('subjek') ?>">
            </div>

            <div class="form-group mb-3">
              <label for="pesan" class="font-weight-bold">Isi Pesan / Proposal</label>
              <textarea class="form-control form-control-lg" id="pesan" name="pesan" rows="6" required><?= old('pesan') ?></textarea>
            </div>

            <div class="form-group mb-4">
              <label for="lampiran" class="font-weight-bold">Lampiran (Opsional)</label>
              <input type="file" class="form-control-file" id="lampiran" name="lampiran">
              <small class="form-text text-muted">File yang diizinkan: PDF, DOC, DOCX. Maksimal 2MB.</small>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>