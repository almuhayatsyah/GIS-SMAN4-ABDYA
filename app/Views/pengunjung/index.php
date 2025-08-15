<?= $this->extend('layout/pengunjung'); ?>
<?= $this->section('content'); ?>

<style>
    /* Style untuk peta */
    #map {
        width: 100%;
        height: 500px; /* Atau sesuaikan tinggi yang Anda inginkan */
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #ddd;
    }
    /* Opsional: Style untuk memastikan judul peta terlihat bagus */
    .dashboard-header-stat {
        padding: 1.5rem;
        background-color: #f8f9fc; /* Sedikit berbeda dari bg-white */
        border-bottom: 1px solid #e3e6f0;
    }
    .map-container {
        margin-top: 2rem; /* Jarak dari konten di atasnya jika ada */
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 p-3 mb-3 bg-white dashboard-header-stat text-center map-container">
                <h4 class="font-weight-bold mb-0" style="color:#4e2eff;letter-spacing:1px;">Peta Sebaran Siswa Kurang Mampu</h4>
                <div class="small text-muted">Visualisasi Lokasi Siswa Penerima Bantuan</div>
            </div>
            <div id="map"></div>
            <!-- Daftar Siswa (otomatis dari database) -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white font-weight-bold">Beberapa Siswa Terbaru</div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Nama Ayah</th>
                                <th>Gaji Ayah</th>
                                <th>Nama Ibu</th>
                                <th>Gaji Ibu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($siswaTerbaru)): ?>
                                <?php $no = 1; foreach ($siswaTerbaru as $s): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= esc($s['nisn']) ?></td>
                                        <td><?= esc($s['nama_siswa']) ?></td>
                                        <td><?= esc($s['nama_kelas'] ?? '-') ?></td>
                                        <td><?= esc($s['nama_ayah'] ?? '-') ?></td>
                                        <td><?= !empty($s['gaji_ayah']) ? 'Rp ' . number_format($s['gaji_ayah'],0,',','.') : '-' ?></td>
                                        <td><?= esc($s['nama_ibu'] ?? '-') ?></td>
                                        <td><?= !empty($s['gaji_ibu']) ? 'Rp ' . number_format($s['gaji_ibu'],0,',','.') : '-' ?></td>
                                        <td>
                                            <span class="badge <?= $s['status_kurang_mampu'] == 1 ? 'badge-danger' : 'badge-success' ?>">
                                                <?= $s['status_kurang_mampu'] == 1 ? 'Kurang Mampu' : 'Mampu' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="9" class="text-center">Belum ada data siswa.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Daftar Siswa -->
        </div>
    </div>
</div>

<script>
    // Data siswa dari controller untuk digunakan oleh peta
    const siswaUntukPeta = <?= json_encode($siswaForMap ?? []) ?>;
    const Maps_api_key = "<?= esc($Maps_api_key ?? '', 'attr') ?>";

    let map;
    let infoWindow;
    let allMarkers = [];
    let lastInfoWindow = null;

    window.initMap = function() {
        const defaultCenter = { lat: 3.801945752962619, lng: 96.77563314105208 };
        let initialCenter = defaultCenter;
        let zoomLevel = 13;
        let bounds;
        const validMarkersData = siswaUntukPeta.filter(siswa =>
            siswa.latitude && siswa.longitude && !isNaN(parseFloat(siswa.latitude)) && !isNaN(parseFloat(siswa.longitude)) && parseFloat(siswa.latitude) !== 0 && parseFloat(siswa.longitude) !== 0
        );

        if (validMarkersData.length === 1) {
            initialCenter = {
                lat: parseFloat(validMarkersData[0].latitude),
                lng: parseFloat(validMarkersData[0].longitude)
            };
            zoomLevel = 15;
        } else if (validMarkersData.length > 1) {
            bounds = new google.maps.LatLngBounds();
            validMarkersData.forEach(siswa => {
                bounds.extend(new google.maps.LatLng(parseFloat(siswa.latitude), parseFloat(siswa.longitude)));
            });
        } else {
            initialCenter = defaultCenter;
            zoomLevel = 17;
        }

        const mapElement = document.getElementById("map");
        if (!mapElement) {
            console.error("Elemen #map tidak ditemukan!");
            return;
        }

        map = new google.maps.Map(mapElement, {
            center: initialCenter,
            zoom: zoomLevel,
            mapTypeId: 'roadmap',
            streetViewControl: false,
            mapTypeControl: false,
            fullscreenControl: true
        });

        if (validMarkersData.length > 1 && bounds && !bounds.isEmpty()) {
            map.fitBounds(bounds);
            google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                if (this.getZoom() > 17) this.setZoom(17);
                if (this.getZoom() < 10) this.setZoom(10);
            });
        }

        infoWindow = new google.maps.InfoWindow();

        // Marker sekolah
        const sekolahMarker = new google.maps.Marker({
            position: defaultCenter,
            map: map,
            title: "SMAN 4 ABDYA",
            icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            }
        });
        sekolahMarker.addListener('click', () => {
            infoWindow.setContent('<div style="font-size: 0.95rem;"><strong>SMAN 4 ABDYA</strong><br>Lokasi Sekolah</div>');
            infoWindow.open(map, sekolahMarker);
        });

        // Marker siswa
        allMarkers = [];
        validMarkersData.forEach(siswa => {
            const marker = new google.maps.Marker({
                position: new google.maps.LatLng(parseFloat(siswa.latitude), parseFloat(siswa.longitude)),
                map: map,
                title: siswa.nama_siswa,
                icon: {
                    url: siswa.status_kurang_mampu == 1 ? "http://maps.google.com/mapfiles/ms/icons/red-dot.png" : "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
                }
            });
            marker.addListener('click', () => {
                if (lastInfoWindow) lastInfoWindow.close();
                infoWindow.setContent(`
                    <div style="font-weight: bold; margin-bottom: 5px;">${escHtml(siswa.nama_siswa)}</div>
                    <div>NISN: ${escHtml(siswa.nisn ?? '-') }</div>
                    <div>Kelas: ${escHtml(siswa.nama_kelas ?? '-') }</div>
                    <div>Alamat: ${escHtml(siswa.alamat_lokasi ?? '-') }</div>
                    <div>Status: <span style="color: ${siswa.status_kurang_mampu == 1 ? '#dc3545' : '#28a745'};">${siswa.status_kurang_mampu == 1 ? 'Kurang Mampu' : 'Mampu'}</span></div>
                `);
                infoWindow.open(map, marker);
                lastInfoWindow = infoWindow;
            });
            allMarkers.push({ marker, data: siswa });
        });
    }

    function escHtml(unsafe) {
        if (unsafe === null || typeof unsafe === 'undefined') return '';
        return unsafe.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Modal untuk menampilkan foto lebih besar (tetap di sini jika Anda ingin fitur ini)
    document.addEventListener('DOMContentLoaded', function() {
        const fotoModal = document.getElementById('fotoModal');
        if (fotoModal) {
            fotoModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const fotoUrl = button.getAttribute('data-foto');
                const namaSiswa = button.getAttribute('data-nama');
                const modalImage = document.getElementById('fotoModalImage');
                const modalTitle = document.getElementById('fotoModalLabel');

                modalImage.src = fotoUrl;
                modalTitle.textContent = 'Foto Rumah: ' + namaSiswa;
            });
        }
    });
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= esc($Maps_api_key ?? '', 'attr') ?>&callback=initMap"></script>

<?= $this->endSection(); ?>