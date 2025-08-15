<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">
        <i class="fas fa-map-marked-alt"></i> <?= esc($title ?? 'Peta Sebaran Siswa') ?>
    </h1>
    <p class="mb-4">Peta ini menampilkan sebaran lokasi siswa yang terdaftar dalam sistem.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Visualisasi Peta Lokasi Siswa</h6>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div style="flex:1;">
                    <label for="siswaDropdown" class="form-label">Filter berdasarkan Nama Siswa:</label>
                    <select id="siswaDropdown" class="form-control">
                        <option value="">-- Tampilkan Semua Siswa --</option>
                        <?php
                        $lokasiData = json_decode($siswa_lokasi_json ?? '[]', true);
                        foreach ($lokasiData as $siswa) :
                            if (!empty($siswa['nama_siswa']) && !empty($siswa['latitude']) && !empty($siswa['longitude']) && $siswa['latitude'] !== 0 && $siswa['longitude'] !== 0) :
                        ?>
                                <option value="<?= esc($siswa['id'], 'attr') ?>">
                                    <?= esc($siswa['nama_siswa']) ?> (Kelas: <?= esc($siswa['nama_kelas'] ?? '-') ?>)
                                </option>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </select>
                </div>
                <button id="resetMapBtn" class="btn btn-warning ml-3" style="height:40px;margin-top:24px;">Reset</button>
            </div>
            

            <div id="mapCanvas" style="height: 70vh; width: 100%;"></div>
            <p class="mt-3 text-muted small">
                <i class="fas fa-info-circle"></i>
                <?php
                $jumlahLokasi = count(array_filter($lokasiData, function($s){
                    return !empty($s['latitude']) && !empty($s['longitude']) && $s['latitude'] !== 0 && $s['longitude'] !== 0;
                }));
                ?>
                <?php if ($jumlahLokasi > 0): ?>
                    Menampilkan <?= $jumlahLokasi ?> lokasi siswa. Klik pada marker untuk detail.
                <?php else: ?>
                    Tidak ada data lokasi siswa yang valid untuk ditampilkan pada peta saat ini.
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

<script>
    let map;
    const markersData = <?= $siswa_lokasi_json ?? '[]' ?>; // Ini akan berisi semua data siswa
    let markers = []; // Array untuk menyimpan objek marker dan data siswa terkait
    let infoWindow; // Deklarasikan infoWindow di scope yang lebih luas

    function initMap() {
        const defaultCenter = {
            lat: 3.7525,
            lng: 96.8294
        }; // Contoh koordinat tengah Indonesia
        let initialCenter = defaultCenter;
        let zoomLevel = 10;

        // Jika hanya ada satu lokasi siswa yang valid, set pusat peta ke lokasi tersebut
        const validMarkersData = markersData.filter(s => !isNaN(parseFloat(s.latitude)) && !isNaN(parseFloat(s.longitude)) && parseFloat(s.latitude) !== 0 && parseFloat(s.longitude) !== 0);

        if (validMarkersData.length === 1) {
            initialCenter.lat = parseFloat(validMarkersData[0].latitude);
            initialCenter.lng = parseFloat(validMarkersData[0].longitude);
            zoomLevel = 15;
        }

        map = new google.maps.Map(document.getElementById("mapCanvas"), {
            center: initialCenter,
            zoom: zoomLevel,
            mapTypeId: 'roadmap'
        });

        infoWindow = new google.maps.InfoWindow(); // Inisialisasi infoWindow di sini

        // Tambahkan semua marker saat inisialisasi
        addMarkersToMap(markersData);

        // Tambahkan marker lokasi sekolah (statis)
        const sekolahMarker = new google.maps.Marker({
            position: {
                lat: 3.801945752962619, 
                lng: 96.77563314105208
            },
            map: map,
            title: "SMAN 4 ABDYA",
            icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            }
        });

        const sekolahInfo = new google.maps.InfoWindow({
            content: `<div style="font-size: 0.95rem;"><strong>SMAN 4 ABDYA</strong><br>Lokasi Sekolah</div>`
        });

        sekolahMarker.addListener("click", () => {
            infoWindow.close(); // Tutup infoWindow siswa jika terbuka
            sekolahInfo.open(map, sekolahMarker);
        });
    }

    // Fungsi untuk menambahkan marker ke peta
    function addMarkersToMap(dataArray) {
        // Hapus marker yang sudah ada sebelum menambahkan yang baru
        markers.forEach(obj => obj.marker.setMap(null));
        markers = []; // Reset array markers

        dataArray.forEach(function(dataSiswa) {
            const lat = parseFloat(dataSiswa.latitude);
            const lng = parseFloat(dataSiswa.longitude);

            if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                const marker = new google.maps.Marker({
                    position: {
                        lat: lat,
                        lng: lng
                    },
                    map: map,
                    title: dataSiswa.nama_siswa,
                    // Anda bisa menambahkan icon berbeda jika status kurang mampu
                    icon: dataSiswa.status_kurang_mampu == 1 ? 'http://maps.google.com/mapfiles/ms/icons/red-dot.png' : 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
                });

                let contentString = `
                    <div style="max-width: 250px; font-size: 0.9rem; line-height: 1.4;">
                        <h6 style="margin-bottom: 5px; font-size: 1rem;"><strong>${escHtml(dataSiswa.nama_siswa)}</strong></h6>
                        <hr style="margin-top: 2px; margin-bottom: 8px;">
                        <p style="margin-bottom: 3px;"><strong>NISN:</strong> ${escHtml(dataSiswa.nisn ?? '-')}</p>
                        <p style="margin-bottom: 3px;"><strong>Kelas:</strong> ${escHtml(dataSiswa.nama_kelas ?? '-')}</p>
                        <p style="margin-bottom: 3px;"><strong>Alamat:</strong> ${escHtml(dataSiswa.alamat_lokasi ?? '-')}</p>
                        <p style="margin-bottom: 0;"><strong>Status:</strong> ${dataSiswa.status_kurang_mampu == 1 ? '<span style="color:red; font-weight:bold;">Kurang Mampu</span>' : '<span style="color:green;">Tidak Kurang Mampu</span>'}</p>
                    </div>`;

                marker.addListener("click", () => {
                    infoWindow.close();
                    infoWindow.setContent(contentString);
                    infoWindow.open(marker.getMap(), marker);
                });

                markers.push({
                    marker: marker,
                    data: dataSiswa
                });
            }
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

    // Fungsi untuk memfilter marker berdasarkan ID siswa yang dipilih
    function filterMarkersById(selectedSiswaId) {
        let visibleCount = 0;
        let foundMarker = null;

        markers.forEach(obj => {
            if (selectedSiswaId === "" || obj.data.id.toString() === selectedSiswaId) {
                obj.marker.setMap(map);
                if (selectedSiswaId !== "" && !foundMarker) { // Jika ID spesifik dipilih
                    foundMarker = obj.marker;
                }
                visibleCount++;
            } else {
                obj.marker.setMap(null); // Sembunyikan marker
            }
        });

        // Jika ID siswa spesifik dipilih, pusat peta ke marker tersebut
        if (foundMarker) {
            map.setCenter(foundMarker.getPosition());
            map.setZoom(15);
            // Buka infoWindow secara otomatis jika ada
            infoWindow.close(); // Tutup infoWindow yang mungkin terbuka
            google.maps.event.trigger(foundMarker, 'click');
        } else if (selectedSiswaId === "") {
            // Jika "Tampilkan Semua Siswa" dipilih, sesuaikan zoom atau fit bounds
            if (validMarkersData.length > 1) { // Jika ada banyak siswa, sesuaikan zoom atau fit bounds
                const bounds = new google.maps.LatLngBounds();
                markers.forEach(obj => {
                    if (obj.marker.getMap() !== null) { // Hanya marker yang terlihat
                        bounds.extend(obj.marker.getPosition());
                    }
                });
                if (!bounds.isEmpty()) {
                     map.fitBounds(bounds);
                }
            } else if (validMarkersData.length === 1) { // Jika hanya ada satu siswa
                map.setCenter({ lat: parseFloat(validMarkersData[0].latitude), lng: parseFloat(validMarkersData[0].longitude) });
                map.setZoom(15);
            } else { // Tidak ada siswa
                map.setCenter(defaultCenter); // Kembali ke default jika tidak ada siswa
                map.setZoom(10);
            }
        }

        const infoText = document.querySelector('.card-body .text-muted');
        if (infoText) {
            if (selectedSiswaId === "") {
                 // Tampilkan kembali info asli untuk "Tampilkan Semua"
                infoText.innerHTML = `<i class="fas fa-info-circle"></i> Menampilkan ${jumlahLokasi} lokasi siswa. Klik pada marker untuk detail.`;
            } else if (visibleCount > 0) {
                infoText.innerHTML = `<i class="fas fa-info-circle"></i> Menampilkan ${visibleCount} lokasi siswa terpilih.`;
            } else {
                infoText.innerHTML = `<i class="fas fa-info-circle"></i> Data lokasi siswa tidak ditemukan untuk pilihan ini.`;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const siswaDropdown = document.getElementById('siswaDropdown');
        if (siswaDropdown) {
            siswaDropdown.addEventListener('change', function() {
                filterMarkersById(this.value);
            });
        }
        // Reset button logic
        const resetBtn = document.getElementById('resetMapBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                // Reset dropdown
                siswaDropdown.value = "";
                // Reset map view and show all markers
                filterMarkersById("");
            });
        }
    });

    window.initMap = initMap;
</script>


<script async src="https://maps.googleapis.com/maps/api/js?key=<?= esc($Maps_api_key, 'attr') ?>&callback=initMap&loading=async"></script>
<?= $this->endSection() ?>