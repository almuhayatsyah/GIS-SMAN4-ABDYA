<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class PengunjungController extends Controller
{
  public function index()
  {
    // Pengecekan role, pastikan pengunjung sudah login

    $siswaModel = new SiswaModel();
    $keyword = $this->request->getGet('search');
    $perPage = 10;

    // --- PERBAIKAN DIMULAI DI SINI ---
    // Menghapus join ke tabel 'ortu' dan seleksi 'ortu.foto_rumah'
    // karena 'foto_rumah' sudah ada di tabel 'siswa' dan terambil oleh 'siswa.*'
    $query = $siswaModel
      ->select([
        'siswa.*',
        'kelas.nama_kelas',
        'lokasi.latitude',
        'lokasi.longitude',
        'lokasi.alamat as alamat_lokasi',
        'lokasi.kecamatan as kecamatan_lokasi',
        'lokasi.kabupaten as kabupaten_lokasi',
        'lokasi.provinsi as provinsi_lokasi'
        // 'ortu.foto_rumah' dihapus dari sini
      ])
      ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
      ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left');
    // ->join('ortu', 'ortu.siswa_id = siswa.id', 'left') dihapus dari sini

    if ($keyword) {
      $query->groupStart()
        ->like('siswa.nama_siswa', $keyword)
        ->orLike('siswa.nisn', $keyword)
        ->groupEnd();
    }

    // --- Data untuk Tabel dengan Pagination ---
    $dataSiswaTabel = $query->paginate($perPage, 'siswa');

    // --- Data untuk Peta (Hanya Siswa Kurang Mampu) ---
    // Query ini juga diperbarui untuk info window yang lebih kaya
    $dataSiswaPeta = $siswaModel
      ->select('siswa.nama_siswa, siswa.status_kurang_mampu, lokasi.latitude, lokasi.longitude, kelas.nama_kelas, lokasi.alamat as alamat_lokasi')
      ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left')
      ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
      ->where('lokasi.latitude IS NOT NULL')
      ->where('lokasi.longitude IS NOT NULL')
      ->where('siswa.status_kurang_mampu', 1)
      ->findAll();

    // --- Statistik ---
    $statsData = $siswaModel->select("
            COUNT(CASE WHEN jenis_kelamin = 'Laki-laki' THEN 1 END) as total_laki,
            COUNT(CASE WHEN jenis_kelamin = 'Perempuan' THEN 1 END) as total_perempuan,
            COUNT(CASE WHEN status_kurang_mampu = 1 THEN 1 END) as total_km,
            COUNT(CASE WHEN status_kurang_mampu = 0 THEN 1 END) as total_tidak_km
        ")->first();

    // Menambahkan query untuk mengambil 5 siswa terbaru
    $siswaTerbaru = $siswaModel
      ->select([
        'siswa.nisn',
        'siswa.nama_siswa',
        'kelas.nama_kelas',
        'siswa.status_kurang_mampu',
        'ortu.nama_ayah',
        'ortu.nama_ibu',
        'ortu.gaji_ayah',
        'ortu.gaji_ibu'
      ])
      ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
      ->join('ortu', 'ortu.id_siswa = siswa.id', 'left')
      ->orderBy('siswa.id', 'DESC')
      ->limit(5)
      ->findAll();

    // Ambil API key dari .env
    $Maps_api_key = getenv('MAPS_API_KEY');

    $data = [
      'title'             => 'Beranda | Sistem Informasi Geografis Siswa',
      'siswa'             => $dataSiswaTabel,
      'siswaForMap'       => $dataSiswaPeta,
      'pager'             => $siswaModel->pager,
      'stat_laki'         => $statsData['total_laki'] ?? 0,
      'stat_perempuan'    => $statsData['total_perempuan'] ?? 0,
      'stat_kurang_mampu' => $statsData['total_km'] ?? 0,
      'stat_tidak_km'     => $statsData['total_tidak_km'] ?? 0,
      'keyword'           => $keyword,
      'siswaTerbaru'      => $siswaTerbaru, // Menambahkan data siswa terbaru ke view
      'Maps_api_key'      => $Maps_api_key // Menambahkan API key ke data yang dikirim ke view
    ];

    return view('pengunjung/index', $data);
  }


  public function hubungi()
  {

    $siswaModel = new SiswaModel();
    $statsData = $siswaModel->select("
            COUNT(CASE WHEN jenis_kelamin = 'Laki-laki' THEN 1 END) as total_laki,
            COUNT(CASE WHEN jenis_kelamin = 'Perempuan' THEN 1 END) as total_perempuan,
            COUNT(CASE WHEN status_kurang_mampu = 1 THEN 1 END) as total_km,
            COUNT(CASE WHEN status_kurang_mampu = 0 THEN 1 END) as total_tidak_km
        ")->first();

    $data = [
      'title'             => 'Hubungi Kami | SMAN 4 ABDYA',
      'stat_laki'         => $statsData['total_laki'] ?? 0,
      'stat_perempuan'    => $statsData['total_perempuan'] ?? 0,
      'stat_kurang_mampu' => $statsData['total_km'] ?? 0,
      'stat_tidak_km'     => $statsData['total_tidak_km'] ?? 0,
      'validation'        => \Config\Services::validation()
    ];

    return view('pengunjung/hubungi', $data);
  }

  // == PASTIKAN METHOD INI JUGA ADA ==
  public function kirim_pengajuan()
  {
    $rules = [
      'nama_pengaju'  => 'required|min_length[3]',
      'email_pengaju' => 'required|valid_email',
      'subjek'        => 'required',
      'pesan'         => 'required',
      'lampiran'      => 'max_size[lampiran,2048]|ext_in[lampiran,pdf,doc,docx]',
    ];

    if (!$this->validate($rules)) {
      return redirect()->to('pengunjung/hubungi')->withInput();
    }

    $email = \Config\Services::email();
    $nama_pengaju = $this->request->getPost('nama_pengaju');
    $email_pengaju = $this->request->getPost('email_pengaju');
    $subjek = $this->request->getPost('subjek');
    $pesan = $this->request->getPost('pesan');

    $email->setTo('email.sekolah.tujuan@gmail.com');
    $email->setFrom($email_pengaju, $nama_pengaju);
    $email->setSubject($subjek);
    $email->setMessage($pesan);

    $lampiran = $this->request->getFile('lampiran');
    if ($lampiran->isValid() && !$lampiran->hasMoved()) {
      $email->attach($lampiran->getTempName(), '', $lampiran->getName());
    }

    if ($email->send()) {
      session()->setFlashdata('success', 'Pengajuan Anda telah berhasil dikirim. Terima kasih!');
    } else {
      session()->setFlashdata('error', 'Gagal mengirim pengajuan. Silakan coba lagi.');
    }

    return redirect()->to('pengunjung/hubungi');
  }

  public function export_excel()
  {
    // 1. Ambil semua data siswa (tanpa pagination)
    $siswaModel = new SiswaModel();
    $semuaSiswa = $siswaModel->select('siswa.*, kelas.nama_kelas, lokasi.alamat as alamat_lokasi, lokasi.kecamatan as kecamatan_lokasi, lokasi.kabupaten as kabupaten_lokasi, lokasi.provinsi as provinsi_lokasi, lokasi.latitude, lokasi.longitude')
      ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
      ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left')
      ->orderBy('siswa.nama_siswa', 'ASC')
      ->findAll();

    // 2. Buat objek Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // 3. Buat Header Tabel
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'NISN');
    $sheet->setCellValue('C1', 'Nama Siswa');
    $sheet->setCellValue('D1', 'Kelas');
    $sheet->setCellValue('E1', 'Alamat');
    $sheet->setCellValue('F1', 'Latitude');
    $sheet->setCellValue('G1', 'Longitude');
    $sheet->setCellValue('H1', 'Status Kurang Mampu');

    // 4. Isi Data Siswa
    $row = 2;
    foreach ($semuaSiswa as $no => $s) {
      $alamat_lengkap = implode(', ', array_filter([$s['alamat_lokasi'], 'Kec. ' . $s['kecamatan_lokasi'], $s['kabupaten_lokasi'], $s['provinsi_lokasi']]));
      $status_km = ($s['status_kurang_mampu'] == 1) ? 'Ya' : 'Tidak';

      $sheet->setCellValue('A' . $row, $no + 1);
      $sheet->setCellValue('B' . $row, $s['nisn']);
      $sheet->setCellValue('C' . $row, $s['nama_siswa']);
      $sheet->setCellValue('D' . $row, $s['nama_kelas']);
      $sheet->setCellValue('E' . $row, $alamat_lengkap);
      $sheet->setCellValue('F' . $row, $s['latitude']);
      $sheet->setCellValue('G' . $row, $s['longitude']);
      $sheet->setCellValue('H' . $row, $status_km);
      $row++;
    }

    // 5. Kirim file ke browser
    $writer = new Xlsx($spreadsheet);
    $filename = 'data_siswa_sman4abdya_' . date('Y-m-d') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit();
  }

  // == METHOD BARU UNTUK EXPORT PDF ==
  public function export_pdf()
  {
    // 1. Ambil semua data siswa (sama seperti excel)
    $siswaModel = new SiswaModel();
    $data['semuaSiswa'] = $siswaModel->select('siswa.*, kelas.nama_kelas, lokasi.alamat as alamat_lokasi, lokasi.kecamatan as kecamatan_lokasi, lokasi.kabupaten as kabupaten_lokasi, lokasi.provinsi as provinsi_lokasi, lokasi.latitude, lokasi.longitude')
      ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
      ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left')
      ->orderBy('siswa.nama_siswa', 'ASC')
      ->findAll();

    // 2. Siapkan data judul untuk view
    $data['title'] = "Laporan Data Siswa SMAN 4 ABDYA";

    // 3. Render view ke dalam HTML
    $html = view('pengunjung/pdf_template', $data);

    // 4. Buat objek Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // 5. Load HTML dan render PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape'); // Ukuran kertas: A4, Orientasi: landscape
    $dompdf->render();

    // 6. Kirim file ke browser
    $filename = 'data_siswa_sman4abdya_' . date('Y-m-d') . '.pdf';
    $dompdf->stream($filename, ['Attachment' => true]);
  }

  public function listSiswa()
  {
    // Hapus pengecekan role agar bisa diakses oleh semua pengunjung tanpa login
    $siswaModel = new \App\Models\SiswaModel();
    $siswa = $siswaModel
      ->select([
        'siswa.*',
        'kelas.nama_kelas',
        'lokasi.latitude',
        'lokasi.longitude',
        'lokasi.alamat as alamat_lokasi',
        'lokasi.kecamatan as kecamatan_lokasi',
        'lokasi.kabupaten as kabupaten_lokasi',
        'lokasi.provinsi as provinsi_lokasi',
        'ortu.nama_ayah',
        'ortu.gaji_ayah',
        'ortu.nama_ibu',
        'ortu.gaji_ibu'
      ])
      ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
      ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left')
      ->join('ortu', 'ortu.id_siswa = siswa.id', 'inner') // Changed to 'inner' to only show students with parent data
      ->where('ortu.id_siswa IS NOT NULL') // Additional filter to ensure parent data exists
      ->orderBy('siswa.nama_siswa', 'ASC')
      ->findAll();

    $data = [
      'title' => 'Daftar Siswa',
      'siswa' => $siswa
    ];
    return view('pengunjung/list-siswa', $data);
  }

  public function petasiswa()
  {

    $siswaModel = new \App\Models\SiswaModel();
    $siswaForMap = $siswaModel
      ->select([
        'siswa.*',
        'kelas.nama_kelas',
        'lokasi.latitude',
        'lokasi.longitude',
        'lokasi.alamat as alamat_lokasi',
        'lokasi.kecamatan as kecamatan_lokasi',
        'lokasi.kabupaten as kabupaten_lokasi',
        'lokasi.provinsi as provinsi_lokasi',
        'ortu.nama_ayah',
        'ortu.gaji_ayah',
        'ortu.nama_ibu',
        'ortu.gaji_ibu'
      ])
      ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
      ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left')
      ->join('ortu', 'ortu.id_siswa = siswa.id', 'left')
      ->where('lokasi.latitude IS NOT NULL')
      ->where('lokasi.latitude !=', '')
      ->where('lokasi.longitude IS NOT NULL')
      ->where('lokasi.longitude !=', '')
      ->findAll();

    // Ambil API key dari .env
    $Maps_api_key = getenv('MAPS_API_KEY');

    $data = [
      'title' => 'Peta Lokasi Siswa',
      'siswaForMap' => $siswaForMap,
      'Maps_api_key' => $Maps_api_key
    ];
    return view('pengunjung/petasiswa', $data);
  }
}
