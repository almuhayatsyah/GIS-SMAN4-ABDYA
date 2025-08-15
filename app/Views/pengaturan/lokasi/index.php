<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-map-marked-alt"></i> <?= esc($title ?? 'Pengaturan Lokasi') ?>
        </h1>
        <div>
            <a href="<?= site_url('pengaturan/lokasi/create') ?>" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                <span class="text">Tambah Lokasi Manual</span>
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search"></i> Pencarian Lokasi
            </h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('pengaturan/lokasi') ?>" method="get" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <input type="text" class="form-control" name="search" placeholder="Cari alamat, siswa, dll..." value="<?= esc($search ?? '', 'attr') ?>">
                </div>
                <button class="btn btn-primary mb-2" type="submit">
                    <i class="fas fa-search fa-sm"></i> Cari
                </button>
                <?php if (!empty($search)): ?>
                    <a href="<?= site_url('pengaturan/lokasi') ?>" class="btn btn-secondary ml-2 mb-2">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> Data Lokasi
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
                <table class="table table-bordered table-hover" id="dataTableLokasi" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">ID Lok.</th>
                            <th>Alamat Detail (dari tabel lokasi)</th>
                            <th>Kecamatan</th>
                            <th>Kabupaten/Kota</th>
                            <th>Provinsi</th>
                            <th class="text-center">Latitude</th>
                            <th class="text-center">Longitude</th>
                            <th>Siswa Terhubung</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lokasi_data) && is_array($lokasi_data)): ?>
                            <?php $no = $nomor_awal ?? 1; // Gunakan nomor awal dari controller 
                            ?>
                            <?php foreach ($lokasi_data as $lok): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= esc($lok['id']) ?></td>
                                    <td><?= esc($lok['alamat']) // Ini adalah lokasi.alamat 
                                        ?></td>
                                    <td><?= esc($lok['kecamatan']) ?></td>
                                    <td><?= esc($lok['kabupaten']) ?></td>
                                    <td><?= esc($lok['provinsi']) ?></td>
                                    <td class="text-center"><?= esc($lok['latitude']) ?></td>
                                    <td class="text-center"><?= esc($lok['longitude']) ?></td>
                                    <td><?= esc($lok['nama_siswa'] ?? '<em>Tidak terhubung</em>') // Menampilkan nama siswa 
                                        ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('pengaturan/lokasi/edit/' . $lok['id']) ?>"
                                            class="btn btn-warning btn-sm btn-circle"
                                            data-toggle="tooltip"
                                            title="Edit Lokasi">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-danger btn-sm btn-circle"
                                            onclick="confirmDeleteLokasi('<?= $lok['id'] ?>', '<?= esc("ID: " . $lok['id'] . " - " . $lok['alamat'] . ($lok['nama_siswa'] ? ' (milik ' . $lok['nama_siswa'] . ')' : ''), 'js') ?>')"
                                            data-toggle="tooltip"
                                            title="Hapus Lokasi">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data lokasi yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php if (isset($pager) && $pager) : ?>
                    <?= $pager->links('lokasi_page_group', 'bootstrap_pagination') // Pastikan 'lokasi_page_group' sama dengan di controller 
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteLokasiModal" tabindex="-1" role="dialog" aria-labelledby="deleteLokasiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLokasiModalLabel">Konfirmasi Hapus Lokasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data lokasi <strong id="lokasiToDeleteName"></strong>?
                <br><small class="text-danger">Perhatian: Jika lokasi ini masih terhubung dengan data siswa, penghapusan akan dibatalkan. Harap perbarui data siswa terkait terlebih dahulu jika ingin menghapus lokasi ini.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteLokasiButton" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk konfirmasi hapus
    function confirmDeleteLokasi(id, name) {
        document.getElementById('lokasiToDeleteName').innerHTML = name ? '<strong>"' + name + '"</strong>' : 'ini';
        var deleteUrl = '<?= site_url('pengaturan/lokasi/delete/') ?>' + id;
        document.getElementById('confirmDeleteLokasiButton').setAttribute('href', deleteUrl);
        $('#deleteLokasiModal').modal('show');
    }

    // Initialize tooltips (jika Anda menggunakan Bootstrap tooltips)
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<?= $this->endSection() ?>