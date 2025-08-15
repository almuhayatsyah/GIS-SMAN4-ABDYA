<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\LokasiModel;



class SiswaController extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $lokasiModel;
    protected $db; // Untuk transaksi

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->lokasiModel = new LokasiModel();
        $this->db = \Config\Database::connect();
        helper(['form', 'url', 'text']);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Siswa',
            'kelas' => $this->kelasModel->findAll(),
            // 'lokasi' => $this->lokasiModel->findAll(), // Tidak diperlukan lagi untuk dropdown
            'validation' => \Config\Services::validation()
        ];
        return view('siswa/create', $data);
    }

    public function store()
    {
        $siswaValidationRules = [
            'nisn' => [
                'rules' => 'required|is_unique[siswa.nisn]',
                'errors' => ['required' => 'NISN harus diisi.', 'is_unique' => 'NISN sudah terdaftar.']
            ],
            'nama_siswa'    => 'required',
            'kelas_id'      => 'required|integer',
            'tanggal_lahir' => 'permit_empty|valid_date[Y-m-d]',
            'jenis_kelamin' => 'required',
            'agama'         => 'permit_empty',
            'umur'          => 'permit_empty|integer',
            'nomor_hp'      => 'permit_empty|max_length[15]',
            'foto_rumah'    => [ // Ini adalah input di form, akan disimpan ke 'foto_rumah'
                'rules' => 'max_size[foto_rumah,2048]|is_image[foto_rumah]|mime_in[foto_rumah,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran foto rumah terlalu besar (maks 2MB).',
                    'is_image' => 'File yang diupload bukan gambar.',
                    'mime_in'  => 'Format foto rumah harus JPG, JPEG, atau PNG.'
                ]
            ],

        ];

        $lokasiValidationRules = [
            'alamat_lokasi'    => 'permit_empty|string|max_length[255]',
            'kecamatan_lokasi' => 'permit_empty|string|max_length[100]',
            'kabupaten_lokasi' => 'permit_empty|string|max_length[100]',
            'provinsi_lokasi'  => 'permit_empty|string|max_length[100]',
            'latitude'         => 'required|decimal',
            'longitude'        => 'required|decimal',
        ];

        // Jika foto rumah wajib saat create, tambahkan 'uploaded[foto_siswa]'
        // Jika opsional, biarkan seperti ini atau tambahkan kondisi.
        // Untuk sekarang, kita asumsikan foto rumah tidak wajib saat create berdasarkan error sebelumnya.
        // Jika ingin wajib: $siswaValidationRules['foto_siswa']['rules'] = 'uploaded[foto_siswa]|'. $siswaValidationRules['foto_siswa']['rules'];

        $allValidationRules = array_merge($siswaValidationRules, $lokasiValidationRules);

        if (!$this->validate($allValidationRules)) {
            log_message('debug', "[SiswaController::store] Validasi gagal. Errors: " . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->transStart();

        $lokasiData = [
            'alamat'    => $this->request->getPost('alamat_lokasi'),
            'kecamatan' => $this->request->getPost('kecamatan_lokasi'),
            'kabupaten' => $this->request->getPost('kabupaten_lokasi'),
            'provinsi'  => $this->request->getPost('provinsi_lokasi'),
            'latitude'  => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];
        $newLokasiId = null;

        if (!$this->lokasiModel->insert($lokasiData)) {
            $lokasiErrors = $this->lokasiModel->errors();
            $errorMessage = $lokasiErrors ? implode(', ', $lokasiErrors) : 'Gagal menyimpan data lokasi.';
            log_message('error', '[SiswaController::store] Gagal insert lokasi. Errors: ' . json_encode($lokasiErrors));
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
        $newLokasiId = $this->lokasiModel->getInsertID();
        if ($newLokasiId === null || $newLokasiId === 0) {
            log_message('error', "[SiswaController::store] Gagal mendapatkan insertID setelah insert lokasi baru.");
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat entri lokasi baru (ID tidak didapatkan).');
        }

        $fotoName = null;
        $fotoFile = $this->request->getFile('foto_rumah'); // Input di form bernama 'foto_siswa'
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $fotoName = $fotoFile->getRandomName();
            try {
                $uploadPath = 'uploads/fotorumahsiswa';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $fotoFile->move($uploadPath, $fotoName);
            } catch (\Exception $e) {
                log_message('error', '[SiswaController::store] Gagal upload foto: ' . $e->getMessage());
                $this->db->transRollback(); // Rollback karena foto gagal, lokasi sudah terlanjur insert
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload foto rumah: ' . $e->getMessage());
            }
        }

        $siswaData = [
            'nisn'          => $this->request->getPost('nisn'),
            'nama_siswa'    => $this->request->getPost('nama_siswa'),
            'kelas_id'      => $this->request->getPost('kelas_id'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ? date('Y-m-d', strtotime($this->request->getPost('tanggal_lahir'))) : null,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'agama'         => $this->request->getPost('agama'),
            'umur'          => $this->request->getPost('umur'),
            'nomor_hp'      => $this->request->getPost('nomor_hp'),
            'lokasi_id'     => $newLokasiId,
            'foto_rumah'    => $fotoName, // Menyimpan ke field 'foto_rumah'
            'status_kurang_mampu' => 0, // Default: Tidak Kurang Mampu, akan diupdate otomatis saat data ortu diinput
            // 'ortu_id'       => $this->request->getPost('ortu_id'),
        ];

        if (!$this->siswaModel->insert($siswaData)) {
            $siswaErrors = $this->siswaModel->errors();
            $errorMessage = $siswaErrors ? implode(', ', $siswaErrors) : 'Gagal menyimpan data siswa.';
            log_message('error', "[SiswaController::store] Gagal insert siswa. Errors: " . json_encode($siswaErrors) . " Data: " . json_encode($siswaData));
            $this->db->transRollback();
            if ($fotoName && file_exists('uploads/fotorumahsiswa/' . $fotoName)) {
                unlink('uploads/fotorumahsiswa/' . $fotoName); // Hapus foto jika siswa gagal disimpan
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        // Catat log aktivitas tambah siswa
        helper('log_helper');
        $userId = session('id');
        log_activity('Tambah siswa: ' . $this->request->getPost('nama_siswa'), $userId, $this->request->getPost('nisn'));

        $this->db->transCommit();
        return redirect()->to('/siswa')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $builder = $this->siswaModel->select('siswa.*, 
                                            lokasi.alamat AS alamat_lokasi,
                                            lokasi.kecamatan AS kecamatan_lokasi,
                                            lokasi.kabupaten AS kabupaten_lokasi,
                                            lokasi.provinsi AS provinsi_lokasi,
                                            lokasi.latitude,
                                            lokasi.longitude')
            ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left')
            ->where('siswa.id', $id);
        $siswa = $builder->first();

        if (!$siswa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data Siswa dengan ID ' . $id . ' tidak ditemukan');
        }

        // Pastikan semua key ada meskipun mungkin null, untuk menghindari error di view jika old() tidak ada
        $siswa['alamat_lokasi'] = $siswa['alamat_lokasi'] ?? '';
        $siswa['kecamatan_lokasi'] = $siswa['kecamatan_lokasi'] ?? '';
        $siswa['kabupaten_lokasi'] = $siswa['kabupaten_lokasi'] ?? '';
        $siswa['provinsi_lokasi'] = $siswa['provinsi_lokasi'] ?? '';
        $siswa['latitude'] = $siswa['latitude'] ?? '';
        $siswa['longitude'] = $siswa['longitude'] ?? '';


        $data = [
            'title'      => 'Edit Siswa',
            'siswa'      => $siswa,
            'kelas'      => $this->kelasModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('siswa/edit', $data);
    }

    public function update($id)
    {
        $currentSiswaData = $this->siswaModel->find($id);
        if (!$currentSiswaData) {
            log_message('error', "[SiswaController::update] Data siswa ID: {$id} tidak ditemukan.");
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        // --- TAMBAHKAN INI ---
        $nisnFromForm = $this->request->getPost('nisn');
        log_message('debug', "[SiswaController::update] NISN dari form: " . ($nisnFromForm ?? 'NULL/Kosong') . " (Tipe: " . gettype($nisnFromForm) . ")");
        log_message('debug', "[SiswaController::update] Current NISN (dari DB): " . ($currentSiswaData['nisn'] ?? 'NULL/Kosong') . " (Tipe: " . gettype($currentSiswaData['nisn']) . ")");
        // --- END TAMBAHAN ---

        $currentLokasiId = $currentSiswaData['lokasi_id'];
        $currentSiswaData = $this->siswaModel->find($id);
        if (!$currentSiswaData) {
            log_message('error', "[SiswaController::update] Data siswa ID: {$id} tidak ditemukan.");
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }
        $currentLokasiId = $currentSiswaData['lokasi_id'];
        $currentFotoRumah = $currentSiswaData['foto_rumah'];

        $siswaValidationRules = [
            'nisn' => [
                'rules'  => "required|is_unique[siswa.nisn,id,{$id}]", // <-- PERIKSA BARIS INI DENGAN SANGAT TELITI
                'errors' => [
                    'required'  => 'NISN harus diisi.',
                    'is_unique' => 'NISN ini sudah terdaftar.' // Pesan error bisa Anda sesuaikan
                ]
            ],
        ];

        $ruleNisnTerbentuk = $siswaValidationRules['nisn']['rules'];
        log_message('debug', 'Aturan Validasi NISN yang Terbentuk: ' . $ruleNisnTerbentuk . ' untuk ID: ' . $id);


        $lokasiValidationRules = [
            'alamat_lokasi'    => 'permit_empty|string|max_length[255]',
            'kecamatan_lokasi' => 'permit_empty|string|max_length[100]',
            'kabupaten_lokasi' => 'permit_empty|string|max_length[100]',
            'provinsi_lokasi'  => 'permit_empty|string|max_length[100]',
            'latitude'         => 'required|decimal',
            'longitude'        => 'required|decimal',
        ];
        $fotoValidationRule = [];
        if ($this->request->getFile('foto_rumah') && $this->request->getFile('foto_rumah')->isValid()) {
            $fotoValidationRule = [];
        }
        $allValidationRules = array_merge($siswaValidationRules, $lokasiValidationRules, $fotoValidationRule);

        if (!$this->validate($allValidationRules)) {
            log_message('debug', "[SiswaController::update] Validasi gagal ID: {$id}. Errors: " . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->transStart();

        $lokasiDataFromForm = [
            'alamat'    => $this->request->getPost('alamat_lokasi'),
            'kecamatan' => $this->request->getPost('kecamatan_lokasi'),
            'kabupaten' => $this->request->getPost('kabupaten_lokasi'),
            'provinsi'  => $this->request->getPost('provinsi_lokasi'),
            'latitude'  => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];
        $processedLokasiId = $currentLokasiId;

        try {
            if ($currentLokasiId) {
                if (!$this->lokasiModel->update($currentLokasiId, $lokasiDataFromForm)) {
                    // ... (error handling & rollback seperti di store) ...
                    $lokasiErrors = $this->lokasiModel->errors();
                    $errorMessage = $lokasiErrors ? implode(', ', $lokasiErrors) : 'Gagal memperbarui data lokasi.';
                    log_message('error', "[SiswaController::update] Gagal update lokasi ID: {$currentLokasiId}. Errors: " . json_encode($lokasiErrors));
                    $this->db->transRollback();
                    return redirect()->back()->withInput()->with('error', $errorMessage);
                }
            } else { // Siswa belum punya lokasi_id, buat baru
                if ($this->lokasiModel->insert($lokasiDataFromForm)) {
                    $processedLokasiId = $this->lokasiModel->getInsertID();
                    if ($processedLokasiId === null || $processedLokasiId === 0) {
                        // ... (error handling & rollback seperti di store) ...
                        log_message('error', "[SiswaController::update] Gagal mendapatkan insertID lokasi baru.");
                        $this->db->transRollback();
                        return redirect()->back()->withInput()->with('error', 'Gagal membuat entri lokasi baru (ID tidak didapatkan).');
                    }
                } else {
                    // ... (error handling & rollback seperti di store) ...
                    $lokasiErrors = $this->lokasiModel->errors();
                    $errorMessage = $lokasiErrors ? implode(', ', $lokasiErrors) : 'Gagal membuat entri lokasi baru.';
                    log_message('error', "[SiswaController::update] Gagal insert lokasi baru. Errors: " . json_encode($lokasiErrors));
                    $this->db->transRollback();
                    return redirect()->back()->withInput()->with('error', $errorMessage);
                }
            }
        } catch (\Exception $e) {
            // ... (error handling & rollback seperti di store) ...
            log_message('error', '[SiswaController::update] Exception saat proses lokasi: ' . $e->getMessage());
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Kesalahan internal saat memproses data lokasi.');
        }

        if ($processedLokasiId === null && !$currentLokasiId) {
            log_message('error', "[SiswaController::update] processedLokasiId masih null.");
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal mendapatkan ID untuk lokasi.');
        }


        $newFotoName = $currentFotoRumah;
        $fotoFile = $this->request->getFile('foto_rumah');
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $newFotoName = $fotoFile->getRandomName();
            try {
                $uploadPath = 'uploads/fotorumahsiswa';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $fotoFile->move($uploadPath, $newFotoName);
                if ($currentFotoRumah && $currentFotoRumah != $newFotoName && file_exists($uploadPath . '/' . $currentFotoRumah)) {
                    unlink($uploadPath . '/' . $currentFotoRumah);
                }
            } catch (\Exception $e) {
                // ... (error handling & rollback seperti di store) ...
                log_message('error', '[SiswaController::update] Gagal upload foto: ' . $e->getMessage());
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload foto rumah baru: ' . $e->getMessage());
            }
        }

        $siswaDataToUpdate = [
            'nisn'          => $this->request->getPost('nisn'),
            'nama_siswa'    => $this->request->getPost('nama_siswa'),
            'kelas_id'      => $this->request->getPost('kelas_id'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ? date('Y-m-d', strtotime($this->request->getPost('tanggal_lahir'))) : $currentSiswaData['tanggal_lahir'],
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'agama'         => $this->request->getPost('agama'),
            'umur'          => $this->request->getPost('umur'),
            'nomor_hp'      => $this->request->getPost('nomor_hp'),
            'lokasi_id'     => $processedLokasiId,
            'foto_rumah'    => $newFotoName, // Menyimpan ke field 'foto_rumah'
            'status_kurang_mampu' => $currentSiswaData['status_kurang_mampu'], // Status tidak boleh diubah manual, hanya melalui update data ortu
            // 'ortu_id'       => $this->request->getPost('ortu_id'),
        ];

        if (!$this->siswaModel->update($id, $siswaDataToUpdate)) {
            // ... (error handling & rollback seperti di store) ...
            $siswaErrors = $this->siswaModel->errors();
            $errorMessage = $siswaErrors ? implode(', ', $siswaErrors) : 'Gagal memperbarui data siswa.';
            log_message('error', "[SiswaController::update] Gagal update siswa ID: {$id}. Errors: " . json_encode($siswaErrors) . " Data: " . json_encode($siswaDataToUpdate));
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $this->db->transCommit();
        return redirect()->to('/siswa')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $this->siswaModel
            ->select('siswa.*, kelas.nama_kelas, 
                      lokasi.latitude, lokasi.longitude, 
                      lokasi.alamat AS alamat_lokasi,
                      lokasi.kecamatan AS kecamatan_lokasi, 
                      lokasi.kabupaten AS kabupaten_lokasi, 
                      lokasi.provinsi AS provinsi_lokasi')
            ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
            ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left'); // Tetap LEFT JOIN jika ada siswa tanpa lokasi

        if ($search) {
            $this->siswaModel->groupStart()
                ->like('siswa.nisn', $search)
                ->orLike('siswa.nama_siswa', $search)
                ->orLike('kelas.nama_kelas', $search)
                ->orLike('lokasi.alamat', $search)
                ->orLike('lokasi.kecamatan', $search)
                ->orLike('lokasi.kabupaten', $search)
                ->orLike('lokasi.provinsi', $search)
                ->groupEnd();
        }

        $data['search'] = $search;
        $data['title'] = "Daftar Siswa";

        // Paginasi untuk data tabel
        $paginatedSiswa = $this->siswaModel->orderBy('siswa.id', 'DESC')->paginate(10, 'siswa_group');
        $data['siswa'] = $paginatedSiswa;
        $data['pager'] = $this->siswaModel->pager;

        $currentPage = $this->request->getVar('page_siswa_group') ? (int) $this->request->getVar('page_siswa_group') : 1;
        $perPage = 10; // Pastikan ini sama dengan parameter paginate
        $data['nomor_awal'] = ($currentPage - 1) * $perPage + 1;

        // Menyiapkan data untuk peta (hanya siswa dengan koordinat valid dari halaman saat ini)
        $siswaUntukPeta = [];
        if (!empty($paginatedSiswa)) {
            foreach ($paginatedSiswa as $s) {
                if (
                    isset($s['latitude']) && $s['latitude'] !== null && $s['latitude'] !== '' && $s['latitude'] != 0 &&
                    isset($s['longitude']) && $s['longitude'] !== null && $s['longitude'] !== '' && $s['longitude'] != 0
                ) {
                    $siswaUntukPeta[] = $s;
                }
            }
        }
        $data['siswa_untuk_peta_json'] = json_encode($siswaUntukPeta);

        // API Key Google Maps (pastikan nama variabel konsisten dengan yang di view)
        $data['Maps_api_key'] = 'AIzaSyBDa3U0FxRS3H3IosxPMOEKnZTBJIwxshE'; // API KEY ANDA

        return view('siswa/index', $data);
    }

    public function laporanSiswa()

    {

        $data['laporan'] = $this->siswaModel->getLaporanRekap();

        return view('siswa/laporan_siswa', $data);
    }

    public function exportPdf()
    {
        // Ambil data siswa dengan JOIN ke tabel kelas, lokasi, dan ortu
        $siswa = $this->siswaModel
            ->select('siswa.*, kelas.nama_kelas, 
                    lokasi.alamat AS alamat_lokasi, 
                lokasi.kecamatan AS kecamatan_lokasi, 
                lokasi.kabupaten AS kabupaten_lokasi, 
                lokasi.provinsi AS provinsi_lokasi,
                ortu.nama_ayah, ortu.nama_ibu, 
                ortu.pekerjaan_ayah, ortu.pekerjaan_ibu, 
                  ortu.gaji_ayah, ortu.gaji_ibu, ortu.nomor_hp AS nomor_hp_ortu') // Ambil field ortu
            ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
            ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left')
            ->join('ortu', 'ortu.id_siswa = siswa.id', 'left') // LEFT JOIN ke tabel ortu berdasarkan id_siswa
            ->orderBy('siswa.nama_siswa', 'ASC')
            ->findAll();

        $data = [
            'title'      => 'Laporan Data Siswa Beserta Orang Tua - SMAN 4 Abdya', // Judul diupdate
            'siswa_list' => $siswa
        ];

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('siswa/laporan_pdf', $data);

        $dompdf->loadHtml($html);
        // Pertimbangkan ukuran kertas 'A4' atau 'Legal' jika kolomnya banyak
        // Jika terlalu banyak kolom untuk A4 landscape, mungkin 'A3' atau pecah jadi beberapa laporan
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Laporan_Data_Siswa_Ortu_' . date('YmdHis') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }


    public function exportExcel()
    {
        // 1. Ambil data siswa (tidak perlu mengambil siswa.tempat_lahir jika memang tidak dipakai sama sekali)
        $siswaList = $this->siswaModel
            ->select('siswa.nisn, siswa.nama_siswa, siswa.tanggal_lahir, siswa.jenis_kelamin, siswa.agama, siswa.umur, siswa.nomor_hp AS nomor_hp_siswa, siswa.status_kurang_mampu,
                  kelas.nama_kelas, 
                  lokasi.alamat AS alamat_lokasi, 
                  lokasi.kecamatan AS kecamatan_lokasi, 
                  lokasi.kabupaten AS kabupaten_lokasi, 
                  lokasi.provinsi AS provinsi_lokasi,
                  lokasi.latitude, lokasi.longitude,
                  ortu.nama_ayah, ortu.pekerjaan_ayah, ortu.gaji_ayah,
                  ortu.nama_ibu, ortu.pekerjaan_ibu, ortu.gaji_ibu, ortu.nomor_hp AS nomor_hp_ortu')
            // siswa.tempat_lahir Dihilangkan dari SELECT
            ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
            ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left')
            ->join('ortu', 'ortu.id_siswa = siswa.id', 'left')
            ->orderBy('siswa.nama_siswa', 'ASC')
            ->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menentukan kolom terakhir berdasarkan jumlah header baru
        // Jumlah header sekarang adalah 22 (karena Tempat Lahir dihapus dari 23)
        // A=1, B=2, ..., V=22, W=23. Jika tempat lahir hilang, kolom terakhir jadi V.
        $lastHeaderColumnLetter = 'V'; // Kolom ke-22

        // Judul Laporan
        $sheet->mergeCells('A1:' . $lastHeaderColumnLetter . '1');
        $sheet->setCellValue('A1', 'LAPORAN DATA SISWA BESERTA ORANG TUA - SMAN 4 ACEH BARAT DAYA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(20);

        // Tanggal Cetak
        $sheet->mergeCells('A2:' . $lastHeaderColumnLetter . '2');
        $sheet->setCellValue('A2', 'Tanggal Cetak: ' . date('d M Y H:i:s'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // 2. Definisikan Header Kolom (tanpa "Tempat Lahir")
        $headers = [
            'No',
            'NISN',
            'Nama Siswa', /* 'Tempat Lahir' DIHAPUS */
            'Tanggal Lahir',
            'Umur',
            'Jenis Kelamin',
            'Agama',
            'No. HP Siswa',
            'Status KM',
            'Alamat Detail',
            'Kecamatan',
            'Kabupaten/Kota',
            'Provinsi',
            'Latitude',
            'Longitude',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Gaji Ayah (Rp)',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Gaji Ibu (Rp)',
            'No. HP Ortu'
        ];
        $column = 'A';
        $startHeaderRow = 4; // Mulai header di baris ke-4
        foreach ($headers as $header) {
            $sheet->setCellValue($column . $startHeaderRow, $header);
            $sheet->getStyle($column . $startHeaderRow)->getFont()->setBold(true);
            $sheet->getStyle($column . $startHeaderRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle($column . $startHeaderRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D3D3D3');
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }
        $sheet->getRowDimension($startHeaderRow)->setRowHeight(25);

        // 3. Isi Data Siswa (tanpa "Tempat Lahir")
        $rowNum = $startHeaderRow + 1; // Mulai isi data dari baris setelah header
        $no = 1;
        if (!empty($siswaList)) {
            foreach ($siswaList as $siswa) {
                $sheet->setCellValue('A' . $rowNum, $no++);
                $sheet->setCellValue('B' . $rowNum, $siswa['nisn']);
                $sheet->setCellValue('C' . $rowNum, $siswa['nama_siswa']);
                // Kolom D sekarang untuk Tanggal Lahir (Tempat Lahir dilewati)
                $sheet->setCellValue('D' . $rowNum, $siswa['tanggal_lahir'] ? date('d/m/Y', strtotime($siswa['tanggal_lahir'])) : '-');
                $sheet->setCellValue('E' . $rowNum, $siswa['umur'] ? $siswa['umur'] . ' Tahun' : '-');
                $sheet->setCellValue('F' . $rowNum, $siswa['jenis_kelamin']);
                $sheet->setCellValue('G' . $rowNum, $siswa['agama']);
                $sheet->setCellValue('H' . $rowNum, $siswa['nomor_hp_siswa'] ?? '-');
                $sheet->setCellValue('I' . $rowNum, ($siswa['status_kurang_mampu'] == 1) ? 'Kurang Mampu' : 'Tidak Kurang Mampu');

                $sheet->setCellValue('J' . $rowNum, $siswa['alamat_lokasi'] ?? '-');
                $sheet->setCellValue('K' . $rowNum, $siswa['kecamatan_lokasi'] ?? '-');
                $sheet->setCellValue('L' . $rowNum, $siswa['kabupaten_lokasi'] ?? '-');
                $sheet->setCellValue('M' . $rowNum, $siswa['provinsi_lokasi'] ?? '-');
                $sheet->setCellValue('N' . $rowNum, $siswa['latitude'] ?? '-');
                $sheet->setCellValue('O' . $rowNum, $siswa['longitude'] ?? '-');

                $sheet->setCellValue('P' . $rowNum, $siswa['nama_ayah'] ?? '-');
                $sheet->setCellValue('Q' . $rowNum, $siswa['pekerjaan_ayah'] ?? '-');
                $sheet->setCellValue('R' . $rowNum, $siswa['gaji_ayah'] ? (float)$siswa['gaji_ayah'] : 0);
                $sheet->getStyle('R' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

                $sheet->setCellValue('S' . $rowNum, $siswa['nama_ibu'] ?? '-');
                $sheet->setCellValue('T' . $rowNum, $siswa['pekerjaan_ibu'] ?? '-');
                $sheet->setCellValue('U' . $rowNum, $siswa['gaji_ibu'] ? (float)$siswa['gaji_ibu'] : 0);
                $sheet->getStyle('U' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

                $sheet->setCellValue('V' . $rowNum, $siswa['nomor_hp_ortu'] ?? '-');

                $rowNum++;
            }
        } else {
            $sheet->mergeCells('A' . ($startHeaderRow + 1) . ':' . $lastHeaderColumnLetter . ($startHeaderRow + 1));
            $sheet->setCellValue('A' . ($startHeaderRow + 1), 'Tidak ada data siswa.');
            $sheet->getStyle('A' . ($startHeaderRow + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // Memberikan border ke seluruh tabel data
        $lastDataColumn = $sheet->getHighestDataColumn(); // Dapatkan kolom terakhir yang punya data
        $lastDataRow = $sheet->getHighestDataRow();
        if ($lastDataRow >= $startHeaderRow) {
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A' . $startHeaderRow . ':' . $lastDataColumn . $lastDataRow)->applyFromArray($styleArray);
        }

        // 4. Set Nama File dan Header untuk Download
        $filename = 'Laporan_Data_Siswa_Ortu_Lengkap_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        // ... (header lainnya untuk cache control jika diperlukan) ...

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function delete($id)
    {
        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        $this->db->transStart();

        // Hapus foto rumah jika ada
        if ($siswa['foto_rumah'] && file_exists('uploads/fotorumahsiswa/' . $siswa['foto_rumah'])) {
            unlink('uploads/fotorumahsiswa/' . $siswa['foto_rumah']);
        }

        // Hapus data siswa
        if (!$this->siswaModel->delete($id)) {
            $this->db->transRollback();
            return redirect()->to('/siswa')->with('error', 'Gagal menghapus data siswa.');
        }

        // Hapus data lokasi jika tidak ada siswa lain yang menggunakannya
        if ($siswa['lokasi_id']) {
            $siswaLain = $this->siswaModel->where('lokasi_id', $siswa['lokasi_id'])->countAllResults();
            if ($siswaLain == 0) {
                $this->lokasiModel->delete($siswa['lokasi_id']);
            }
        }

        $this->db->transCommit();
        return redirect()->to('/siswa')->with('success', 'Data siswa berhasil dihapus.');
    }
}
