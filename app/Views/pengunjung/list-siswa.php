<?= $this->extend('layout/pengunjung'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 p-3 mb-3 bg-white dashboard-header-stat text-center">
                <h4 class="font-weight-bold mb-0" style="color:#4e2eff;letter-spacing:1px;">Daftar Siswa</h4>
                <div class="small text-muted">Berikut adalah daftar seluruh siswa terdaftar</div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="<?= site_url('pengunjung/export-excel') ?>" class="btn btn-success btn-sm mr-2">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <a href="<?= site_url('pengunjung/export-pdf') ?>" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">Umur</th>
                                    <th class="text-center">Agama</th>
                                    <th class="text-center">Tanggal Lahir</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($siswa) && is_array($siswa)): ?>
                                    <?php $no = 1;
                                    foreach ($siswa as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= esc($row['nisn']); ?></td>
                                            <td><?= esc($row['nama_siswa']); ?></td>
                                            <td class="text-center"><?= esc($row['nama_kelas'] ?? '-'); ?></td>
                                            <td class="text-center"><?= esc($row['umur'] ?? '-'); ?></td>
                                            <td class="text-center"><?= esc($row['agama'] ?? '-'); ?></td>
                                            <td class="text-center"><?= esc($row['tanggal_lahir'] ?? '-'); ?></td>
                                            <td class="text-center">
                                                <?php if (isset($row['status_kurang_mampu'])): ?>
                                                    <span class="badge <?= $row['status_kurang_mampu'] == 1 ? 'badge-warning' : 'badge-success'; ?>">
                                                        <?= $row['status_kurang_mampu'] == 1 ? 'Biasa Saja' : 'Mampu'; ?>
                                                    </span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($row['foto_rumah'])): ?>
                                                    <img src="<?= base_url('uploads/fotorumahsiswa/' . $row['foto_rumah']) ?>" alt="Foto Rumah" style="max-width:60px;max-height:60px;border-radius:6px;">
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="12" class="text-center">Tidak ada data siswa.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>