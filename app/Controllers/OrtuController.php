<?php

namespace App\Controllers;

use App\Models\OrtuModel;
use App\Models\SiswaModel;

class OrtuController extends BaseController
{
  protected $OrtuModel;
  protected $siswaModel;

  public function __construct() 
  {
    $this->OrtuModel = new OrtuModel();
    $this->siswaModel = new SiswaModel();
    helper(['form', 'url', 'text']);
  }

  public function index()
  {
    $data['title'] = 'Data Orang Tua';

    // 1. Mulai membangun query langsung pada instance $this->ortuModel
    // dan definisikan alias tabel siswa 's' langsung di join.
    $this->OrtuModel->select('ortu.*, s.nama_siswa, s.nisn AS nisn_siswa')
      ->join('siswa s', 's.id = ortu.id_siswa', 'left');

    // 2. Terapkan filter pencarian (jika ada) pada $this->ortuModel
    $search = $this->request->getGet('search');
    if ($search) {
      $this->OrtuModel->groupStart()
        ->like('ortu.nama_ayah', $search)
        ->orLike('ortu.nama_ibu', $search)
        ->orLike('s.nama_siswa', $search) // Gunakan alias 's'
        ->orLike('s.nisn', $search)       // Gunakan alias 's'
        ->groupEnd();
    }
    $data['search'] = $search;

    // 3. Terapkan orderBy dan paginate pada $this->ortuModel
    $data['ortu_list'] = $this->OrtuModel->orderBy('ortu.id', 'DESC')
      ->paginate(10, 'ortu_page_group'); // Data dikirim sebagai 'ortu_list'

    // 4. Ambil pager dari $this->ortuModel SETELAH paginate() dipanggil
    $data['pager'] = $this->OrtuModel->pager;

    // 5. Penomoran untuk paginasi
    $currentPage = $this->request->getVar('page_ortu_page_group') ? (int) $this->request->getVar('page_ortu_page_group') : 1;
    $perPage = 10; // Sesuaikan dengan jumlah item per halaman di paginate()
    $data['nomor_awal'] = ($currentPage - 1) * $perPage + 1;

    // HAPUS ATAU KOMENTARI dd() JIKA MASIH ADA SEBELUM BARIS INI
    return view('ortu/index', $data);
  }

  public function create()
  {

    $siswaList = $this->siswaModel->select('id, nisn, nama_siswa')
      ->orderBy('nama_siswa', 'ASC')
      ->findAll();

    $data = [
      'title'      => 'Tambah Data Orang Tua',
      'validation' => \Config\Services::validation(),
      'siswa_list' => $siswaList // Kirim daftar siswa ke view
    ];

    // View 'ortu/create.php' akan memerlukan input untuk semua field ortu
    // termasuk dropdown untuk memilih 'id_siswa'
    return view('ortu/create', $data);
  }

  // Di dalam app/Controllers/OrtuController.php

  public function store()
  {
    // Load helper status untuk kalkulasi otomatis
    helper('status');

    $rules = $this->OrtuModel->getValidationRules();

    // Validasi tambahan: Pastikan siswa yang dipilih belum punya data ortu
    $id_siswa_posted = $this->request->getPost('id_siswa');
    if ($id_siswa_posted) {
      $existingOrtuForSiswa = $this->OrtuModel->where('id_siswa', $id_siswa_posted)->first();
      if ($existingOrtuForSiswa) {
        return redirect()->back()->withInput()->with('error', 'Siswa yang dipilih sudah memiliki data orang tua terdaftar.');
      }
    }


    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $dataToSave = [
      'id_siswa'       => $id_siswa_posted,
      'nama_ayah'      => $this->request->getPost('nama_ayah'),
      'nama_ibu'       => $this->request->getPost('nama_ibu'),
      'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
      'pekerjaan_ibu'  => $this->request->getPost('pekerjaan_ibu'),
      'gaji_ayah'      => $this->request->getPost('gaji_ayah'),
      'gaji_ibu'       => $this->request->getPost('gaji_ibu'),
      'nomor_hp'       => $this->request->getPost('nomor_hp')
    ];

    if ($this->OrtuModel->insert($dataToSave)) {
      // Setelah berhasil insert data ortu, update status siswa otomatis
      $this->updateStatusSiswa($id_siswa_posted);

      $status_baru = hitung_status_kurang_mampu(
        $this->request->getPost('gaji_ayah'),
        $this->request->getPost('gaji_ibu')
      );
      $status_text = get_keterangan_status($status_baru);

      return redirect()->to('/ortu')->with('success', "Data Orang Tua berhasil ditambahkan! Status siswa otomatis diupdate menjadi: {$status_text}");
    } else {
      $errors = $this->OrtuModel->errors();
      $errorMessage = $errors ? implode(', ', $errors) : 'Gagal menyimpan data orang tua.';
      log_message('error', '[OrtuController::store] Gagal insert data ortu. Errors: ' . json_encode($errors) . " Data: " . json_encode($dataToSave));
      return redirect()->back()->withInput()->with('error', $errorMessage);
    }
  }
  // Di dalam app/Controllers/OrtuController.php

  public function update($id = null) // $id adalah id dari tabel 'ortu'
  {
    // Load helper status untuk kalkulasi otomatis
    helper('status');

    $ortu = $this->OrtuModel->find($id);
    if (!$ortu) {
      return redirect()->to('/ortu')->with('error', 'Data Orang Tua tidak ditemukan untuk diperbarui.');
    }

    $rules = $this->OrtuModel->getValidationRules();

    $id_siswa_posted = $this->request->getPost('id_siswa');
    if ($id_siswa_posted != $ortu['id_siswa']) {
      $existingOrtuForNewSiswa = $this->OrtuModel->where('id_siswa', $id_siswa_posted)->first();
      if ($existingOrtuForNewSiswa) {
        return redirect()->back()->withInput()->with('error', 'Siswa baru yang dipilih sudah memiliki data orang tua. Satu siswa hanya boleh memiliki satu data orang tua.');
      }
    }
    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $dataToUpdate = [
      'id_siswa'       => $id_siswa_posted,
      'nama_ayah'      => $this->request->getPost('nama_ayah'),
      'nama_ibu'       => $this->request->getPost('nama_ibu'),
      'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
      'pekerjaan_ibu'  => $this->request->getPost('pekerjaan_ibu'),
      'gaji_ayah'      => $this->request->getPost('gaji_ayah'),
      'gaji_ibu'       => $this->request->getPost('gaji_ibu'),
      'nomor_hp'       => $this->request->getPost('nomor_hp')
    ];

    if ($this->OrtuModel->update($id, $dataToUpdate)) {
      // Update status siswa setelah update data ortu
      $this->updateStatusSiswa($id_siswa_posted);

      $status_baru = hitung_status_kurang_mampu(
        $this->request->getPost('gaji_ayah'),
        $this->request->getPost('gaji_ibu')
      );
      $status_text = get_keterangan_status($status_baru);

      return redirect()->to('/ortu')->with('success', "Data Orang Tua berhasil diperbarui! Status siswa otomatis diupdate menjadi: {$status_text}");
    } else {
      $errors = $this->OrtuModel->errors();
      $errorMessage = $errors ? implode(', ', $errors) : 'Gagal memperbarui data orang tua.';
      log_message('error', '[OrtuController::update] Gagal update data ortu ID: ' . $id . '. Errors: ' . json_encode($errors) . " Data: " . json_encode($dataToUpdate));
      return redirect()->back()->withInput()->with('error', $errorMessage);
    }
  }
  public function delete($id = null) // $id adalah id dari tabel 'ortu'
  {
    $ortu = $this->OrtuModel->find($id);
    if (!$ortu) {
      return redirect()->to('/ortu')->with('error', "Data Orang Tua dengan ID $id tidak ditemukan.");
    }
    if ($this->OrtuModel->delete($id)) {
      return redirect()->to('/ortu')->with('success', 'Data Orang Tua berhasil dihapus.');
    } else {
      $errors = $this->OrtuModel->errors();
      $errorMessage = $errors ? implode(', ', $errors) : 'Gagal menghapus data orang tua.';
      return redirect()->to('/ortu')->with('error', $errorMessage);
    }
  }


  // Di dalam app/Controllers/OrtuController.php

  public function edit($id = null) // $id di sini adalah id dari tabel 'ortu'
  {
    $ortu = $this->OrtuModel->find($id);
    if (!$ortu) {
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data Orang Tua dengan ID $id tidak ditemukan.");
    }

    // Ambil semua data siswa untuk ditampilkan di dropdown pilihan (jika ingin bisa mengubah siswa terkait)
    // atau hanya untuk menampilkan nama siswa yang saat ini terhubung.
    $siswaList = $this->siswaModel->select('id, nisn, nama_siswa')
      ->orderBy('nama_siswa', 'ASC')
      ->findAll();

    $data = [
      'title'      => 'Edit Data Orang Tua',
      'ortu'       => $ortu, // Data ortu yang akan diedit
      'validation' => \Config\Services::validation(),
      'siswa_list' => $siswaList,
      'current_siswa_id' => $ortu['id_siswa'] // ID siswa yang saat ini terhubung dengan data ortu ini
    ];

    // View 'ortu/edit.php' perlu input untuk semua field, 
    // dan nilainya diisi dari $ortu.
    // Pertimbangkan apakah field 'id_siswa' boleh diedit atau hanya ditampilkan.
    return view('ortu/edit', $data);
  }

  /**
   * Method untuk update status siswa berdasarkan data orang tua
   */
  private function updateStatusSiswa($id_siswa)
  {
    helper('status');

    // Ambil data ortu yang baru
    $ortu = $this->OrtuModel->where('id_siswa', $id_siswa)->first();

    if ($ortu) {
      // Hitung status baru
      $status_baru = hitung_status_kurang_mampu(
        $ortu['gaji_ayah'],
        $ortu['gaji_ibu']
      );

      // Update status siswa
      $this->siswaModel->update($id_siswa, [
        'status_kurang_mampu' => $status_baru
      ]);

      log_message('info', "Status siswa ID {$id_siswa} diupdate menjadi: " . get_keterangan_status($status_baru));
    }
  }
}
