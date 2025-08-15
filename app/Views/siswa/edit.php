<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fa fa-edit" aria-hidden="true"></i>
        Edit Data Siswa
    </h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= site_url('siswa/update/' . $siswa['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="nisn">NISN</label>
                    <input type="text" class="form-control" id="nisn" name="nisn" value="<?= $siswa['nisn'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="nama_siswa">Nama</label>
                    <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="<?= $siswa['nama_siswa'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="kelas_id">Kelas</label>
                    <select class="form-control" id="kelas_id" name="kelas_id" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($kelas as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= ($siswa['kelas_id'] == $k['id']) ? 'selected' : '' ?>>
                                <?= $k['nama_kelas'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" required value="<?= $siswa['tanggal_lahir'] ?>">
                </div>
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?= ($siswa['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="Perempuan" <?= ($siswa['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <hr>
                <h5 class="mb-3 text-gray-700">Detail Alamat & Koordinat Lokasi Siswa</h5>

                <div class="form-group">
                    <label for="alamat_lokasi">Alamat Lengkap Lokasi (Jalan, No Rumah, RT/RW, Dusun)</label>
                    <textarea class="form-control <?= (session('errors.alamat_lokasi')) ? 'is-invalid' : '' ?>" id="alamat_lokasi" name="alamat_lokasi" rows="3" placeholder="Contoh: Jl. Pendidikan No. 12, Dusun Harapan"><?= old('alamat_lokasi', $siswa['alamat_lokasi']) ?></textarea>
                    <?php if (session('errors.alamat_lokasi')): ?> <div class="invalid-feedback"><?= session('errors.alamat_lokasi') ?></div><?php endif ?>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kecamatan_lokasi">Kecamatan</label>
                            <input type="text" class="form-control <?= (session('errors.kecamatan_lokasi')) ? 'is-invalid' : '' ?>" id="kecamatan_lokasi" name="kecamatan_lokasi" placeholder="Contoh: Kuala Bate" value="<?= old('kecamatan_lokasi', $siswa['kecamatan_lokasi']) ?>">
                            <?php if (session('errors.kecamatan_lokasi')): ?> <div class="invalid-feedback"><?= session('errors.kecamatan_lokasi') ?></div><?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kabupaten_lokasi">Kabupaten/Kota</label>
                            <input type="text" class="form-control <?= (session('errors.kabupaten_lokasi')) ? 'is-invalid' : '' ?>" id="kabupaten_lokasi" name="kabupaten_lokasi" placeholder="Contoh: Aceh Barat Daya" value="<?= old('kabupaten_lokasi', $siswa['kabupaten_lokasi']) ?>">
                            <?php if (session('errors.kabupaten_lokasi')): ?> <div class="invalid-feedback"><?= session('errors.kabupaten_lokasi') ?></div><?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="provinsi_lokasi">Provinsi</label>
                            <input type="text" class="form-control <?= (session('errors.provinsi_lokasi')) ? 'is-invalid' : '' ?>" id="provinsi_lokasi" name="provinsi_lokasi" placeholder="Contoh: Aceh" value="<?= old('provinsi_lokasi', $siswa['provinsi_lokasi']) ?>">
                            <?php if (session('errors.provinsi_lokasi')): ?> <div class="invalid-feedback"><?= session('errors.provinsi_lokasi') ?></div><?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="latitude">Latitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (session('errors.latitude')) ? 'is-invalid' : '' ?>" id="latitude" name="latitude" placeholder="Contoh: 3.748812" value="<?= old('latitude', $siswa['latitude']) ?>" required>
                            <small class="form-text text-muted">Gunakan titik (.) sebagai pemisah desimal.</small>
                            <?php if (session('errors.latitude')): ?> <div class="invalid-feedback"><?= session('errors.latitude') ?></div><?php endif ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="longitude">Longitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (session('errors.longitude')) ? 'is-invalid' : '' ?>" id="longitude" name="longitude" placeholder="Contoh: 96.834201" value="<?= old('longitude', $siswa['longitude']) ?>" required>
                            <small class="form-text text-muted">Gunakan titik (.) sebagai pemisah desimal.</small>
                            <?php if (session('errors.longitude')): ?> <div class="invalid-feedback"><?= session('errors.longitude') ?></div><?php endif ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <select class="form-control" id="agama" name="agama" required>
                        <option value="">Pilih Agama</option>
                        <option value="Islam" <?= ($siswa['agama'] == 'Islam') ? 'selected' : '' ?>>Islam</option>
                        <option value="Kristen" <?= ($siswa['agama'] == 'Kristen') ? 'selected' : '' ?>>Kristen</option>
                        <option value="Katolik" <?= ($siswa['agama'] == 'Katolik') ? 'selected' : '' ?>>Katolik</option>
                        <option value="Hindu" <?= ($siswa['agama'] == 'Hindu') ? 'selected' : '' ?>>Hindu</option>
                        <option value="Buddha" <?= ($siswa['agama'] == 'Buddha') ? 'selected' : '' ?>>Buddha</option>
                        <option value="Konghucu" <?= ($siswa['agama'] == 'Konghucu') ? 'selected' : '' ?>>Konghucu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="umur">Umur</label>
                    <input type="number" class="form-control" id="umur" name="umur" required min="1" max="100" value="<?= $siswa['umur'] ?>">
                </div>

                <div class="form-group">
                    <label for="foto_rumah">Foto Rumah Siswa</label>
                    <?php if ($siswa['foto_rumah']): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/fotorumahsiswa/' . $siswa['foto_rumah']) ?>" alt="Foto Rumah Siswa" class="img-thumbnail" style="max-width: 200px">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="foto_rumah" accept="image/*">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                </div>

                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    <strong>Info Status Kurang Mampu:</strong> Status saat ini: <strong><?= ($siswa['status_kurang_mampu'] == 1) ? 'Kurang Mampu' : 'Tidak Kurang Mampu' ?></strong><br>
                    Status akan diupdate otomatis berdasarkan gaji orang tua melalui menu "Data Orang Tua".
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= site_url('siswa') ?>" class="btn btn-secondary">Kembali</a>
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

<?php if (session()->has('error')): // Untuk pesan error umum 
?>
    <div class="alert alert-danger" role="alert">
        <?= session('error') ?>
    </div>
<?php endif; ?>



<?= $this->endSection() ?>