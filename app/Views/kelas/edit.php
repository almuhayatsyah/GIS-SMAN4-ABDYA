<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-edit"></i>
        Edit Kelas
    </h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= site_url('kelas/update/' . $kelas['id']) ?>" method="post">
                <?= csrf_field() ?> 
                <div class="form-group">
                    <label for="nama_kelas">Nama Kelas</label>
                    <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="<?= $kelas['nama_kelas'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="wali_kelas">Wali Kelas</label>
                    <input type="text" class="form-control" id="wali_kelas" name="wali_kelas" value="<?= $kelas['wali_kelas'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= site_url('kelas') ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>