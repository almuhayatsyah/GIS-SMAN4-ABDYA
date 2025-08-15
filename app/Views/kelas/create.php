<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-plus"></i>
        Tambah Kelas Baru
    </h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('kelas/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="nama_kelas">Nama Kelas</label>
                    <input type="text" class="form-control <?= (session('errors.nama_kelas')) ? 'is-invalid' : '' ?>"
                        id="nama_kelas" name="nama_kelas" value="<?= old('nama_kelas') ?>" required>
                    <?php if (session('errors.nama_kelas')) : ?>
                        <div class="invalid-feedback">
                            <?= session('errors.nama_kelas') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="wali_kelas">Wali Kelas</label>
                    <input type="text" class="form-control <?= (session('errors.wali_kelas')) ? 'is-invalid' : '' ?>"
                        id="wali_kelas" name="wali_kelas" value="<?= old('wali_kelas') ?>" required>
                    <?php if (session('errors.wali_kelas')) : ?>
                        <div class="invalid-feedback">
                            <?= session('errors.wali_kelas') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= site_url('kelas') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>