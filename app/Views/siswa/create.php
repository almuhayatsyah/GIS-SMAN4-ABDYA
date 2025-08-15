<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Tambah Siswa Baru
    </h1>
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= site_url('siswa/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="nisn">NISN</label>
                    <input type="text" class="form-control <?= (session('errors.nisn')) ? 'is-invalid' : '' ?>" id="nisn" name="nisn" placeholder="Masukkan NISN" value="<?= old('nisn') ?>" required>
                    <?php if (session('errors.nisn')): ?> <div class="invalid-feedback"><?= session('errors.nisn') ?></div><?php endif ?>
                </div>
                <div class="form-group">
                    <label for="nama_siswa">Nama</label>
                    <input type="text" class="form-control <?= (session('errors.nama_siswa')) ? 'is-invalid' : '' ?>" id="nama_siswa" name="nama_siswa" placeholder="Masukkan Nama" value="<?= old('nama_siswa') ?>" required>
                    <?php if (session('errors.nama_siswa')): ?> <div class="invalid-feedback"><?= session('errors.nama_siswa') ?></div><?php endif ?>
                </div>
                <div class="form-group">
                    <label for="kelas_id">Kelas</label>
                    <select class="form-control <?= (session('errors.kelas_id')) ? 'is-invalid' : '' ?>" id="kelas_id" name="kelas_id" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= (old('kelas_id') == $k['id']) ? 'selected' : '' ?>>
                                <?= esc($k['nama_kelas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (session('errors.kelas_id')): ?> <div class="invalid-feedback"><?= session('errors.kelas_id') ?></div><?php endif ?>
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control <?= (session('errors.tanggal_lahir')) ? 'is-invalid' : '' ?>" name="tanggal_lahir" id="tanggal_lahir" value="<?= old('tanggal_lahir') ?>" required>
                    <?php if (session('errors.tanggal_lahir')): ?> <div class="invalid-feedback"><?= session('errors.tanggal_lahir') ?></div><?php endif ?>
                </div>
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-control <?= (session('errors.jenis_kelamin')) ? 'is-invalid' : '' ?>" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?= (old('jenis_kelamin') == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= (old('jenis_kelamin') == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                    <?php if (session('errors.jenis_kelamin')): ?> <div class="invalid-feedback"><?= session('errors.jenis_kelamin') ?></div><?php endif ?>
                </div>

                <hr>
                <h5 class="mb-3 text-gray-700">Detail Alamat & Koordinat Lokasi Siswa</h5>

                <div class="form-group">
                    <label for="alamat_lokasi">Alamat Lengkap Lokasi (Jalan, No Rumah, RT/RW, Dusun)</label>
                    <textarea class="form-control <?= (session('errors.alamat_lokasi')) ? 'is-invalid' : '' ?>" id="alamat_lokasi" name="alamat_lokasi" rows="3" placeholder="Contoh: Jl. Pendidikan No. 12, Dusun Harapan"><?= old('alamat_lokasi') ?></textarea>
                    <?php if (session('errors.alamat_lokasi')): ?> <div class="invalid-feedback"><?= session('errors.alamat_lokasi') ?></div><?php endif ?>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kecamatan_lokasi">Kecamatan</label>
                            <input type="text" class="form-control <?= (session('errors.kecamatan_lokasi')) ? 'is-invalid' : '' ?>" id="kecamatan_lokasi" name="kecamatan_lokasi" placeholder="Contoh: Blangpidie" value="<?= old('kecamatan_lokasi') ?>">
                            <?php if (session('errors.kecamatan_lokasi')): ?> <div class="invalid-feedback"><?= session('errors.kecamatan_lokasi') ?></div><?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kabupaten_lokasi">Kabupaten/Kota</label>
                            <input type="text" class="form-control <?= (session('errors.kabupaten_lokasi')) ? 'is-invalid' : '' ?>" id="kabupaten_lokasi" name="kabupaten_lokasi" placeholder="Contoh: Aceh Barat Daya" value="<?= old('kabupaten_lokasi') ?>">
                            <?php if (session('errors.kabupaten_lokasi')): ?> <div class="invalid-feedback"><?= session('errors.kabupaten_lokasi') ?></div><?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="provinsi_lokasi">Provinsi</label>
                            <input type="text" class="form-control <?= (session('errors.provinsi_lokasi')) ? 'is-invalid' : '' ?>" id="provinsi_lokasi" name="provinsi_lokasi" placeholder="Contoh: Aceh" value="<?= old('provinsi_lokasi') ?>">
                            <?php if (session('errors.provinsi_lokasi')): ?> <div class="invalid-feedback"><?= session('errors.provinsi_lokasi') ?></div><?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="latitude">Latitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (session('errors.latitude')) ? 'is-invalid' : '' ?>" id="latitude" name="latitude" placeholder="Contoh: 3.748812" value="<?= old('latitude') ?>" required>
                            <small class="form-text text-muted">Gunakan titik (.) sebagai pemisah desimal.</small>
                            <?php if (session('errors.latitude')): ?> <div class="invalid-feedback"><?= session('errors.latitude') ?></div><?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="longitude">Longitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (session('errors.longitude')) ? 'is-invalid' : '' ?>" id="longitude" name="longitude" placeholder="Contoh: 96.834201" value="<?= old('longitude') ?>" required>
                            <small class="form-text text-muted">Gunakan titik (.) sebagai pemisah desimal.</small>
                            <?php if (session('errors.longitude')): ?> <div class="invalid-feedback"><?= session('errors.longitude') ?></div><?php endif ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <select class="form-control <?= (session('errors.agama')) ? 'is-invalid' : '' ?>" id="agama" name="agama" required>
                        <option value="">Pilih Agama</option>
                        <option value="Islam" <?= (old('agama') == 'Islam') ? 'selected' : '' ?>>Islam</option>
                        <option value="Kristen" <?= (old('agama') == 'Kristen') ? 'selected' : '' ?>>Kristen</option>
                        <option value="Katolik" <?= (old('agama') == 'Katolik') ? 'selected' : '' ?>>Katolik</option>
                        <option value="Hindu" <?= (old('agama') == 'Hindu') ? 'selected' : '' ?>>Hindu</option>
                        <option value="Buddha" <?= (old('agama') == 'Buddha') ? 'selected' : '' ?>>Buddha</option>
                        <option value="Konghucu" <?= (old('agama') == 'Konghucu') ? 'selected' : '' ?>>Konghucu</option>
                    </select>
                    <?php if (session('errors.agama')): ?> <div class="invalid-feedback"><?= session('errors.agama') ?></div><?php endif ?>
                </div>
                <div class="form-group">
                    <label for="umur">Umur</label>
                    <input type="number" class="form-control <?= (session('errors.umur')) ? 'is-invalid' : '' ?>" id="umur" name="umur" placeholder="Masukkan Umur" value="<?= old('umur') ?>" required min="1" max="100">
                    <?php if (session('errors.umur')): ?> <div class="invalid-feedback"><?= session('errors.umur') ?></div><?php endif ?>
                </div>
                <div class="form-group">
                    <label for="foto_rumah">Foto Rumah Siswa <span class="text-danger">*</span></label>
                    <input type="file" class="form-control-file <?= (session('errors.foto_rumah')) ? 'is-invalid' : '' ?>" name="foto_rumah" required>
                    <?php if (session('errors.foto_rumah')): ?> <div class="invalid-feedback"><?= session('errors.foto_rumah') ?></div><?php endif ?>
                </div>

                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    <strong>Info Status Kurang Mampu:</strong> Status akan dihitung otomatis berdasarkan gaji orang tua setelah data orang tua diinput melalui menu "Data Orang Tua".
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Error Validasi!</h4>
        <p>Mohon periksa kembali input Anda:</p>
        <hr>
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): // Untuk pesan error umum dari controller 
?>
    <div class="alert alert-danger" role="alert">
        <?= session('error') ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>