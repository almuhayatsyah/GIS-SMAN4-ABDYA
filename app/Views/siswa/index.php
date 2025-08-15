<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users"></i>
            Daftar Siswa
        </h1>
        <div>
            <a href="<?= site_url('siswa/create') ?>" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Siswa</span>
            </a>
            <a href="<?= site_url('siswa/export-pdf') ?>" class="btn btn-danger btn-icon-split ml-2">
                <span class="icon text-white-50">
                    <i class="fas fa-file-pdf"></i>
                </span>
                <span class="text">Export PDF</span>
            </a>
            <a href="<?= site_url('siswa/export-excel') ?>" class="btn btn-success btn-icon-split ml-2">
                <span class="icon text-white-50">
                    <i class="fas fa-file-excel"></i>
                </span>
                <span class="text">Export Excel</span>
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search"></i>
                Pencarian Siswa
            </h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('siswa') ?>" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Cari berdasarkan NISN, Nama, atau Kelas..." value="<?= $search ?? '' ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i>
                Data Siswa
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Umur</th>
                            <th class="text-center">Agama</th>
                            <th class="text-center">Tanggal Lahir</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Lokasi</th>
                            <th class="text-center">Jenis Kelamin</th>
                            <th class="text-center">Status KM</th>
                            <th class="text-center">Foto Rumah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $nomor_awal++; ?>
                        <?php foreach ($siswa as $s): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $s['nisn'] ?></td>
                                <td><?= $s['nama_siswa'] ?></td>
                                <td class="text-center"><?= $s['nama_kelas'] ?? '-' ?></td>
                                <td class="text-center"><?= $s['umur'] ?> Tahun</td>
                                <td class="text-center"><?= $s['agama'] ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($s['tanggal_lahir'])) ?></td>
                                <td>
                                    <?php
                                    $alamat_lengkap = [];
                                    if (!empty($s['alamat_lokasi'])) {
                                        $alamat_lengkap[] = esc($s['alamat_lokasi']);
                                    }
                                    if (!empty($s['kecamatan_lokasi'])) {
                                        $alamat_lengkap[] = 'Kec. ' . esc($s['kecamatan_lokasi']);
                                    }
                                    if (!empty($s['kabupaten_lokasi'])) {
                                        $alamat_lengkap[] = esc($s['kabupaten_lokasi']);
                                    }
                                    if (!empty($s['provinsi_lokasi'])) {
                                        $alamat_lengkap[] = esc($s['provinsi_lokasi']);
                                    }
                                    echo implode(', ', $alamat_lengkap ?: ['-']);
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php if (isset($s['latitude']) && isset($s['longitude'])): ?>
                                        <span class="badge badge-info">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= $s['latitude'] ?>, <?= $s['longitude'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Belum ditentukan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($s['jenis_kelamin'] == 'Laki-laki'): ?>
                                        <span class="badge badge-primary">
                                            <i class="fas fa-male"></i> Laki-laki
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-female"></i> Perempuan
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($s['status_kurang_mampu'] == 1): ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-user-shield"></i> Kurang Mampu
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-info">
                                            <i class="fas fa-user-check"></i> Tidak KM
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($s['foto_rumah'])): ?>
                                        <img src="<?= base_url('uploads/fotorumahsiswa/' . esc($s['foto_rumah'])) ?>"
                                            alt="Foto Rumah <?= esc($s['nama_siswa']) ?>"
                                            class="img-thumbnail foto-rumah-thumb"
                                            style="max-width: 50px; height: auto; cursor:pointer;"
                                            data-toggle="modal"
                                            data-target="#fotoModal"
                                            data-foto="<?= base_url('uploads/fotorumahsiswa/' . esc($s['foto_rumah'])) ?>">
                                    <?php else: ?>
                                        <span class="badge badge-secondary">No Photo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('siswa/edit/' . $s['id']) ?>"
                                        class="btn btn-warning btn-sm btn-circle"
                                        data-toggle="tooltip"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= site_url('siswa/hapus/' . $s['id']) ?>"
                                        class="btn btn-danger btn-sm btn-circle"
                                        onclick="confirmDelete('<?= $s['id'] ?>')"
                                        data-toggle="tooltip"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="mt-4">
                <?= $pager->links('siswa', 'bootstrap_pagination') ?>
            </div>
        </div>
    </div>

    <!-- Google Maps
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-map-marked-alt"></i> Peta Lokasi Siswa (Halaman Ini)
            </h6>
        </div>
        <div class="card-body">
            <div id="siswaListMapCanvas" style="height: 450px; width: 100%;"></div>
            <p class="mt-2 text-muted small">
                <i class="fas fa-info-circle"></i> Peta menampilkan lokasi siswa yang ada di tabel halaman ini.
            </p>
        </div>
    </div>
</div> -->

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Siswa</h5>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="post" action="#" style="display: inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Foto Rumah Modal -->
<div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Foto Rumah Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="fotoModalImg" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<script>
    // ... (script tetap, tidak diubah)
    function prepareConfirmDelete(id, namaSiswa) {
        const siswaNameToDeleteElement = document.getElementById('siswaNameToDelete');
        if (siswaNameToDeleteElement) {
            siswaNameToDeleteElement.textContent = namaSiswa ? '"' + namaSiswa + '"' : 'ini';
        }
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.setAttribute('action', '<?= site_url('siswa/delete/') ?>' + id);
        }
        if (window.jQuery) {
            $('#deleteModal').modal('show');
        }
    }
    
    if (window.jQuery && typeof $('body').tooltip === 'function') {
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }

    function confirmDelete(id) {
        if (window.jQuery) {
            $('#deleteButton').attr('href', '<?= site_url('siswa/delete/') ?>' + id);
            $('#deleteModal').modal('show');
        } else {
            console.error("jQuery is not loaded for confirmDelete function.");
        }
    }
    if (window.jQuery) {
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $('.foto-rumah-thumb').on('click', function() {
                var imageUrl = $(this).data('foto');
                $('#fotoModalImg').attr('src', imageUrl);
            });
        });
    } else {
        console.warn("jQuery is not loaded, tooltips and photo modal might not work.");
    }
    // Google Maps code tetap
    let siswaListMapGlobal;
    const siswaMarkersDataGlobal = <?= $siswa_untuk_peta_json ?? '[]' ?>;

    function initSiswaListMap() {
        const defaultCenter = {
            lat: 3.7525,
            lng: 96.8294
        };
        let initialCenter = defaultCenter;
        let zoomLevel = 10;
        let bounds;
        if (Array.isArray(siswaMarkersDataGlobal) && siswaMarkersDataGlobal.length > 0) {
            if (siswaMarkersDataGlobal.length === 1 && siswaMarkersDataGlobal[0].latitude && siswaMarkersDataGlobal[0].longitude) {
                const lat = parseFloat(siswaMarkersDataGlobal[0].latitude);
                const lng = parseFloat(siswaMarkersDataGlobal[0].longitude);
                if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                    initialCenter = {
                        lat: lat,
                        lng: lng
                    };
                    zoomLevel = 15;
                }


            } else {
                bounds = new google.maps.LatLngBounds();
                siswaMarkersDataGlobal.forEach(function(dataSiswa) {
                    const lat = parseFloat(dataSiswa.latitude);
                    const lng = parseFloat(dataSiswa.longitude);
                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                        bounds.extend(new google.maps.LatLng(lat, lng));
                    }
                });
            }
        }
        const mapElement = document.getElementById("siswaListMapCanvas");
        if (!mapElement) {
            console.error("Elemen #siswaListMapCanvas TIDAK ditemukan!");
            return;
        }
        try {
            siswaListMapGlobal = new google.maps.Map(mapElement, {
                center: initialCenter,
                zoom: zoomLevel,
                mapTypeId: 'roadmap'
            });
            if (bounds && !bounds.isEmpty()) {
                siswaListMapGlobal.fitBounds(bounds);
                google.maps.event.addListenerOnce(siswaListMapGlobal, 'bounds_changed', function() {
                    if (this.getZoom() > 16) {
                        this.setZoom(16);
                    }
                    if (this.getZoom() < 3 && siswaMarkersDataGlobal.length > 0) {
                        this.setZoom(3);
                    }
                });
            } else if (siswaMarkersDataGlobal.length === 0 && siswaListMapGlobal.getCenter().equals(new google.maps.LatLng(defaultCenter.lat, defaultCenter.lng))) {
                siswaListMapGlobal.setZoom(8);
            }
            if (Array.isArray(siswaMarkersDataGlobal)) {
                const infoWindow = new google.maps.InfoWindow();
                siswaMarkersDataGlobal.forEach(function(dataSiswa) {
                    const lat = parseFloat(dataSiswa.latitude);
                    const lng = parseFloat(dataSiswa.longitude);
                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                        const marker = new google.maps.Marker({
                            position: {
                                lat: lat,
                                lng: lng
                            },
                            map: siswaListMapGlobal,
                            title: dataSiswa.nama_siswa
                        });
                        let contentString = `
                        <div style="max-width: 250px; font-size:0.9em;">
                            <h6><strong>${escHtmlSiswaList(dataSiswa.nama_siswa)}</strong></h6>
                            <p class="mb-0"><small>NISN: ${escHtmlSiswaList(dataSiswa.nisn ?? '-')}</small></p>
                            <p class="mb-0"><small>Kelas: ${escHtmlSiswaList(dataSiswa.nama_kelas ?? '-')}</small></p>
                            <p class="mb-0"><small>Alamat: ${escHtmlSiswaList(dataSiswa.alamat_lokasi ?? '-')}</small></p>
                            <p class="mb-0"><small>Status: ${dataSiswa.status_kurang_mampu == 1 ? '<span style="color:red; font-weight:bold;">Kurang Mampu</span>' : '<span style="color:green;">Tidak Kurang Mampu</span>'}</small></p>
                        </div>`;
                        marker.addListener("click", () => {
                            infoWindow.close();
                            infoWindow.setContent(contentString);
                            infoWindow.open(marker.getMap(), marker);
                        });
                    }
                });
                const sekolahMarker = new google.maps.Marker({
                    position: {
                        lat: 3.840012928296599,
                        lng: 96.7475849852848
                    },
                    map: siswaListMapGlobal,
                    title: "SMAN 4 ABDYA",
                    icon: {
                        url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    }
                });

                const sekolahInfo = new google.maps.InfoWindow({
                    content: `<div style="font-size: 0.95rem;"><strong>SMAN 4 ABDYA</strong><br>Lokasi Sekolah</div>`
                });
                sekolahMarker.addListener("click", () => {
                    sekolahInfo.open(siswaListMapGlobal, sekolahMarker);
                });
            }

        } catch (e) {
            console.error("Error saat inisialisasi Google Map atau membuat marker:", e);
        }
    }


    function escHtmlSiswaList(unsafe) {
        if (unsafe === null || typeof unsafe === 'undefined') return '';
        return unsafe.toString()
            .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
    window.initSiswaListMap = initSiswaListMap;
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key=<?= esc($Maps_api_key ?? '', 'attr') ?>&callback=initSiswaListMap&loading=async"></script>
<?= $this->endSection() ?>