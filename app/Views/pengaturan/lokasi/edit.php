<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-map-marker-alt"></i> Edit Lokasi
        </h1>
        <a href="<?= base_url('pengaturan/lokasi'); ?>" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Lokasi ID: <?= esc($lokasi['id']); ?></h6>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">Mohon periksa kembali input Anda:</h6>
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif ?>

                    <form action="<?= base_url('pengaturan/lokasi/update/' . $lokasi['id']); ?>" method="post">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap (Jalan, No Rumah, RT/RW, Dusun)</label>
                            <textarea class="form-control <?= (session('errors.alamat')) ? 'is-invalid' : '' ?>" id="alamat" name="alamat" rows="3" required><?= old('alamat', esc($lokasi['alamat'])); ?></textarea>
                            <?php if (session('errors.alamat')): ?>
                                <div class="invalid-feedback"><?= session('errors.alamat') ?></div>
                            <?php endif ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kecamatan">Kecamatan</label>
                                    <input type="text" class="form-control <?= (session('errors.kecamatan')) ? 'is-invalid' : '' ?>" id="kecamatan" name="kecamatan" value="<?= old('kecamatan', esc($lokasi['kecamatan'])); ?>" required>
                                    <?php if (session('errors.kecamatan')): ?>
                                        <div class="invalid-feedback"><?= session('errors.kecamatan') ?></div>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kabupaten">Kabupaten/Kota</label>
                                    <input type="text" class="form-control <?= (session('errors.kabupaten')) ? 'is-invalid' : '' ?>" id="kabupaten" name="kabupaten" value="<?= old('kabupaten', esc($lokasi['kabupaten'])); ?>" required>
                                    <?php if (session('errors.kabupaten')): ?>
                                        <div class="invalid-feedback"><?= session('errors.kabupaten') ?></div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <input type="text" class="form-control <?= (session('errors.provinsi')) ? 'is-invalid' : '' ?>" id="provinsi" name="provinsi" value="<?= old('provinsi', esc($lokasi['provinsi'])); ?>" required>
                            <?php if (session('errors.provinsi')): ?>
                                <div class="invalid-feedback"><?= session('errors.provinsi') ?></div>
                            <?php endif ?>
                        </div>

                        <hr>
                        <h6 class="text-gray-700 mb-2">Koordinat</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" class="form-control <?= (session('errors.latitude')) ? 'is-invalid' : '' ?>" id="latitude" name="latitude" value="<?= old('latitude', esc($lokasi['latitude'])); ?>" placeholder="Contoh: 3.748812" required>
                                    <small class="form-text text-muted">Gunakan titik (.) sebagai pemisah desimal.</small>
                                    <?php if (session('errors.latitude')): ?>
                                        <div class="invalid-feedback"><?= session('errors.latitude') ?></div>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" class="form-control <?= (session('errors.longitude')) ? 'is-invalid' : '' ?>" id="longitude" name="longitude" value="<?= old('longitude', esc($lokasi['longitude'])); ?>" placeholder="Contoh: 96.834201" required>
                                    <small class="form-text text-muted">Gunakan titik (.) sebagai pemisah desimal.</small>
                                    <?php if (session('errors.longitude')): ?>
                                        <div class="invalid-feedback"><?= session('errors.longitude') ?></div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Perbarui Lokasi</button>
                        <a href="<?= base_url('pengaturan/lokasi'); ?>" class="btn btn-secondary"><i class="fas fa-times mr-1"></i> Batal</a>
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
                    <p>Pastikan data lokasi yang Anda masukkan akurat.</p>
                    <p>Perubahan pada data lokasi ini akan mempengaruhi data siswa yang terhubung jika ada.</p>
                    <?php if (isset($lokasi['nama_siswa']) && !empty($lokasi['nama_siswa'])): // Jika Anda mengirim data nama_siswa dari controller edit 
                    ?>
                        <p class="mt-3"><strong>Lokasi Terhubung Dengan Siswa:</strong></p>
                        <p><?= esc($lokasi['nama_siswa']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>