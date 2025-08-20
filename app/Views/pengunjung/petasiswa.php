<?= $this->extend('layout/pengunjung'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 p-3 mb-3 bg-white dashboard-header-stat text-center map-container">
                <h4 class="font-weight-bold mb-0" style="color:#4e2eff;letter-spacing:1px;">Peta Lokasi Siswa</h4>
                <div class="small text-muted">Visualisasi Lokasi Siswa Terdaftar</div>
            </div>
            <div class="mb-3 d-flex align-items-center">
                <label for="siswaDropdown" class="mr-2 mb-0 font-weight-bold">Pilih Siswa:</label>
                <select id="siswaDropdown" class="form-control w-auto">
                    <option value="">-- Tampilkan Semua Siswa --</option>
                    <?php foreach ($siswaForMap as $siswa): ?>
                        <?php if (!empty($siswa['nama_siswa']) && !empty($siswa['latitude']) && !empty($siswa['longitude']) && $siswa['latitude'] != 0 && $siswa['longitude'] != 0): ?>
                            <option value="<?= esc($siswa['nisn'], 'attr') ?>">
                                <?= esc($siswa['nama_siswa']) ?> (Kelas: <?= esc($siswa['nama_kelas'] ?? '-') ?>)
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <button id="resetMapBtn" class="btn btn-warning ml-3">Reset</button>
            </div>
            <div id="map" style="width:100%;height:500px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1);border:1px solid #ddd;"></div>
        </div>
    </div>
</div>

<script>
    // Data siswa dari controller (pastikan $siswaForMap dan $Maps_api_key dikirim ke view)
    const siswaUntukPeta = <?= json_encode($siswaForMap ?? []) ?>;
    const Maps_api_key = "<?= esc($Maps_api_key ?? '', 'attr') ?>";

    let map;
    let infoWindow;
    let allMarkers = [];
    let lastInfoWindow = null;

    window.initMap = function() {
        const defaultCenter = {
            lat: 3.801945752962619,
            lng: 96.77563314105208
        };
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
                    <div>NISN: ${escHtml(siswa.nisn ?? '-')}</div>
                    <div>Kelas: ${escHtml(siswa.nama_kelas ?? '-')}</div>
                    <div>Alamat: ${escHtml(siswa.alamat_lokasi ?? '-')}</div>
                    <div>Status: <span style="color: ${siswa.status_kurang_mampu == 1 ? '#dc3545' : '#28a745'};">${siswa.status_kurang_mampu == 1 ? 'Kurang Mampu' : 'Mampu'}</span></div>
                `);
                infoWindow.open(map, marker);
                lastInfoWindow = infoWindow;
            });
            allMarkers.push({
                marker,
                data: siswa
            });
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

    document.addEventListener('DOMContentLoaded', function() {
        const siswaDropdown = document.getElementById('siswaDropdown');
        const resetBtn = document.getElementById('resetMapBtn');
        siswaDropdown.addEventListener('change', function() {
            filterMarkersByNisn(this.value);
        });
        resetBtn.addEventListener('click', function() {
            siswaDropdown.value = "";
            filterMarkersByNisn("");
        });
    });

    function filterMarkersByNisn(nisn) {
        if (!window.map) return;
        let foundMarker = null;
        let visibleCount = 0;
        allMarkers.forEach(obj => {
            if (!nisn || obj.data.nisn === nisn) {
                obj.marker.setMap(map);
                if (nisn && !foundMarker) foundMarker = obj.marker;
                visibleCount++;
            } else {
                obj.marker.setMap(null);
            }
        });
        if (foundMarker) {
            map.setCenter(foundMarker.getPosition());
            map.setZoom(15);
            if (lastInfoWindow) lastInfoWindow.close();
            google.maps.event.trigger(foundMarker, 'click');
        } else if (!nisn && allMarkers.length > 1) {
            // Reset ke fitBounds semua marker
            const bounds = new google.maps.LatLngBounds();
            allMarkers.forEach(obj => {
                if (obj.marker.getMap() !== null) bounds.extend(obj.marker.getPosition());
            });
            if (!bounds.isEmpty()) map.fitBounds(bounds);
        }
    }
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= esc($Maps_api_key ?? '', 'attr') ?>&callback=initMap"></script>

<?= $this->endSection(); ?>