<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\OrtuModel;
use App\Models\UserModel;
use App\Models\LogModel;

class Dashboard extends BaseController // Lebih baik extend BaseController
{
  protected $siswaModel;
  protected $kelasModel;
  protected $ortuModel;
  protected $userModel;
  protected $logModel;
  protected $db; // Untuk query builder jika diperlukan

  public function __construct()
  {

    $this->siswaModel = new SiswaModel();
    $this->kelasModel = new KelasModel();
    $this->ortuModel = new OrtuModel();
    $this->userModel = new UserModel();
    $this->logModel = new LogModel(); // Pastikan file LogModel.php ada di app/Models
    $this->db = \Config\Database::connect(); // Inisialisasi koneksi database
    helper(['text', 'url', 'form']); // Tambahkan helper yang mungkin berguna
  }

  public function index()
  {
    $role = session()->get('role');
    if ($role !== 'operator' && $role !== 'kesiswaan') {
      return redirect()->to('/login');
    }

    $data['title'] = 'Dashboard Admin'; // Tambahkan title untuk view

    // Mengambil data total
    $data['total_siswa'] = $this->siswaModel->countAllResults(false); // false agar tidak mereset builder jika ada query lain
    $data['total_kelas'] = $this->kelasModel->countAllResults(false);
    $data['total_orang_tua'] = $this->ortuModel->countAllResults(false);
    $data['total_user'] = $this->userModel->countAllResults(false);


    $data['siswa_baru'] = $this->siswaModel
      ->select('siswa.id, siswa.nisn, siswa.nama_siswa, siswa.created_at, kelas.nama_kelas')
      ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
      ->orderBy('siswa.id', 'DESC') // Atau 'siswa.created_at', 'DESC'
      ->limit(5) // Ambil 5 siswa terbaru, Anda bisa sesuaikan jumlahnya
      ->findAll();

    // Data untuk pie chart
    $data['kurang_mampu'] = $this->siswaModel->where('status_kurang_mampu', 1)->countAllResults();
    $data['tidak_kurang_mampu'] = $this->siswaModel->where('status_kurang_mampu', 0)->countAllResults();
    $data['laki_laki'] = $this->siswaModel->where('jenis_kelamin', 'Laki-laki')->countAllResults();
    $data['perempuan'] = $this->siswaModel->where('jenis_kelamin', 'Perempuan')->countAllResults();

    // Mengambil data untuk grafik siswa per kelas (ini sudah ada di kode Anda)
    $kelas = $this->kelasModel->findAll();
    $data['kelas_labels'] = [];
    $data['siswa_per_kelas_data'] = []; // Ganti nama variabel agar lebih jelas
    foreach ($kelas as $k) {
      $data['kelas_labels'][] = $k['nama_kelas'];
      $data['siswa_per_kelas_data'][] = $this->siswaModel->where('kelas_id', $k['id'])->countAllResults();
    }
    // Pastikan Anda menggunakan $kelas_labels dan $siswa_per_kelas_data di JS untuk chart ini jika ada

    // Mengambil log aktivitas terbaru (ini sudah ada di kode Anda)
    // Pastikan method getRecentLogs() ada di LogModel Anda
    if (class_exists('App\Models\LogModel') && method_exists($this->logModel, 'getRecentLogs')) {
      $data['log_aktivitas'] = $this->logModel->getRecentLogs();
    } else {
      $data['log_aktivitas'] = []; // Default array kosong jika LogModel/method tidak ada
      log_message('warning', 'LogModel atau method getRecentLogs tidak ditemukan.');
    }


    // Data untuk Tren Siswa Kurang Mampu vs Tidak Kurang Mampu (ini sudah ada di kode Anda)
    $trendDataResult = $this->siswaModel
      ->select("YEAR(created_at) AS tahun, MONTH(created_at) AS bulan_angka, 
                      SUM(CASE WHEN status_kurang_mampu = 1 THEN 1 ELSE 0 END) AS jumlah_kurang_mampu,
                      SUM(CASE WHEN status_kurang_mampu = 0 THEN 1 ELSE 0 END) AS jumlah_tidak_kurang_mampu")
      ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-6 months'))) // Ambil data 6 bulan terakhir penuh
      ->groupBy('YEAR(created_at), MONTH(created_at)')
      ->orderBy('tahun', 'ASC')
      ->orderBy('bulan_angka', 'ASC')
      ->findAll();

    $trendLabels = [];
    $trendKurangMampuValues = []; // Ubah nama agar tidak konflik
    $trendTidakKurangMampuValues = []; // Ubah nama agar tidak konflik

    for ($i = 5; $i >= 0; $i--) { // Loop untuk 6 bulan (0 sampai 5 bulan lalu)
      $dateForLabel = strtotime(date('Y-m-01') . " -$i months");
      $trendLabels[] = date('M Y', $dateForLabel);
      $yearMonthKey = date('Y-n', $dateForLabel);
      $trendKurangMampuValues[$yearMonthKey] = 0;
      $trendTidakKurangMampuValues[$yearMonthKey] = 0;
    }

    foreach ($trendDataResult as $row) {
      $yearMonthKey = $row['tahun'] . '-' . $row['bulan_angka'];
      if (isset($trendKurangMampuValues[$yearMonthKey])) {
        $trendKurangMampuValues[$yearMonthKey] = (int) $row['jumlah_kurang_mampu'];
        $trendTidakKurangMampuValues[$yearMonthKey] = (int) $row['jumlah_tidak_kurang_mampu'];
      }
    }
    $data['trend_chart_labels'] = json_encode(array_values($trendLabels));
    $data['trend_chart_kurang_mampu'] = json_encode(array_values($trendKurangMampuValues));
    $data['trend_chart_tidak_kurang_mampu'] = json_encode(array_values($trendTidakKurangMampuValues));


    // --- TAMBAHKAN LOGIKA UNTUK DATA PETA DI SINI ---
    $builderPeta = $this->db->table($this->siswaModel->getTable() . ' s');
    // ... (sisa query peta) ...// Alias 's' untuk siswa
    $builderPeta->select('s.nama_siswa, s.nisn, s.status_kurang_mampu, k.nama_kelas, l.latitude, l.longitude, l.alamat AS alamat_lokasi');
    $builderPeta->join('lokasi l', 'l.id = s.lokasi_id', 'inner');
    $builderPeta->join('kelas k', 'k.id = s.kelas_id', 'left');
    $builderPeta->where('l.latitude IS NOT NULL AND l.latitude != ""');
    $builderPeta->where('l.longitude IS NOT NULL AND l.longitude != ""');
    $siswaUntukPetaDashboard = $builderPeta->get()->getResultArray();
    $data['siswa_peta_dashboard_json'] = json_encode($siswaUntukPetaDashboard);

    // API Key Google Maps
    $data['Maps_api_key']  = getenv('MAPS_API_KEY');; // GANTI DENGAN API KEY ANDA atau ambil dari .env

    // --- AKHIR LOGIKA DATA PETA ---

    return view('dashboard_Admin/index', $data); // Pastikan path view ini benar
  }

  // Method delete($id) dan update($id) yang Anda punya sebelumnya sepertinya untuk Kelas,
  // jadi sebaiknya ada di KelasController. Saya biarkan di sini jika memang ada tujuan khusus.
  public function delete($id)
  {
    return redirect()->to(base_url('/dashboard')); // Redirect sementara
  }

  public function update($id)
  {
    return redirect()->to(base_url('/dashboard')); // Redirect sementara
  }
}
