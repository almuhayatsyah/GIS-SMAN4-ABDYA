<?php

namespace App\Controllers;

use App\Models\LokasiModel;
use App\Models\SiswaModel; // Tambahkan ini untuk pengecekan saat delete

class PengaturanController extends BaseController // Pastikan BaseController sudah benar
{
    protected $lokasiModel;
    protected $siswaModel; // Tambahkan properti untuk SiswaModel

    public function __construct()
    {
        $this->lokasiModel = new LokasiModel();
        $this->siswaModel = new SiswaModel(); // Inisialisasi SiswaModel
        helper(['form', 'url', 'text']); // Muat helper jika perlu
    }

    // Method untuk menampilkan daftar lokasi
    public function lokasi(): string // Pastikan return type sesuai jika Anda menggunakan PHP 7.4+
    {
        $data['title'] = 'Pengaturan Lokasi';

        // Mulai query pada instance model
        $this->lokasiModel->select('lokasi.*, siswa.nama_siswa')
            ->join('siswa', 'siswa.lokasi_id = lokasi.id', 'left');

        $search = $this->request->getGet('search');
        if ($search) {
            $this->lokasiModel->groupStart()
                ->like('lokasi.alamat', $search)
                ->orLike('lokasi.kecamatan', $search)
                ->orLike('lokasi.kabupaten', $search)
                ->orLike('lokasi.provinsi', $search)
                ->orLike('siswa.nama_siswa', $search)
                ->orLike('lokasi.latitude', $search)
                ->orLike('lokasi.longitude', $search)
                ->groupEnd();
        }
        $data['search'] = $search;

        // Terapkan orderBy dan paginate pada model
        $data['lokasi_data'] = $this->lokasiModel->orderBy('lokasi.id', 'ASC') // <--- UBAH DI SINI
            ->paginate(10, 'lokasi_page_group');

        // Pager diambil dari model yang sama yang melakukan paginate
        $data['pager'] = $this->lokasiModel->pager;

        // Penomoran untuk paginasi
        $currentPage = $this->request->getVar('page_lokasi_page_group') ? (int) $this->request->getVar('page_lokasi_page_group') : 1; // Sesuaikan nama grup jika berbeda
        $perPage = 10; // Sesuaikan dengan jumlah item per halaman di paginate()
        $data['nomor_awal'] = ($currentPage - 1) * $perPage + 1;

        return view('pengaturan/lokasi/index', $data); // Pastikan path view sudah benar
    }
    // Method untuk menampilkan form tambah lokasi manual
    public function lokasiCreate()
    {
        $data = [
            'title' => 'Tambah Lokasi Manual', // Judul diubah agar lebih spesifik
            'validation' => \Config\Services::validation()
        ];
        // Pastikan view 'pengaturan/lokasi/create.php' memiliki input untuk:
        // alamat, kecamatan, kabupaten, provinsi, latitude, longitude
        return view('pengaturan/lokasi/create', $data);
    }

    // Method untuk menyimpan data lokasi manual baru
    public function lokasiStore()
    {
        // Ambil aturan validasi dari LokasiModel atau definisikan di sini
        // Pastikan aturan validasi di LokasiModel sudah benar (decimal untuk lat/long)
        $rules = $this->lokasiModel->getValidationRules();
        // Anda mungkin perlu menyesuaikan rules jika ada field yang tidak wajib untuk create manual
        // Misalnya, jika 'nama_lokasi' opsional, pastikan rules-nya 'permit_empty'

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataToSave = [
            'alamat'    => $this->request->getPost('alamat'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'kabupaten' => $this->request->getPost('kabupaten'),
            'provinsi'  => $this->request->getPost('provinsi'),
            'latitude'  => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            // Tambahkan field lain dari tabel lokasi jika ada (misalnya 'nama_lokasi')
        ];

        if ($this->lokasiModel->insert($dataToSave)) { // Gunakan insert() untuk data baru
            return redirect()->to('pengaturan/lokasi')->with('success', 'Data lokasi berhasil ditambahkan.');
        } else {
            $errors = $this->lokasiModel->errors();
            $errorMessage = $errors ? implode(', ', $errors) : 'Gagal menyimpan data lokasi.';
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    // Method untuk menampilkan form edit lokasi
    public function lokasiEdit($id = null) // Tambahkan $id = null untuk konsistensi
    {
        $lokasi = $this->lokasiModel->find($id);
        if (!$lokasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data Lokasi dengan ID ' . $id . ' tidak ditemukan');
        }

        $data = [
            'title'     => 'Edit Data Lokasi',
            'lokasi'    => $lokasi,
            'validation' => \Config\Services::validation()
        ];
        // Pastikan view 'pengaturan/lokasi/edit.php' memiliki input untuk semua field
        // dan nilainya diisi dari $lokasi
        return view('pengaturan/lokasi/edit', $data);
    }

    // Method untuk memperbarui data lokasi
    public function lokasiUpdate($id = null) // Tambahkan $id = null
    {
        $lokasi = $this->lokasiModel->find($id);
        if (!$lokasi) {
            return redirect()->to('pengaturan/lokasi')->with('error', 'Data lokasi tidak ditemukan untuk diperbarui.');
        }

        $rules = $this->lokasiModel->getValidationRules();
        // Sesuaikan rules jika perlu, misalnya jika ada validasi is_unique yang perlu mengabaikan ID saat ini
        // (Namun, untuk tabel lokasi biasanya tidak ada field unik selain ID)

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataToUpdate = [
            'alamat'    => $this->request->getPost('alamat'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'kabupaten' => $this->request->getPost('kabupaten'),
            'provinsi'  => $this->request->getPost('provinsi'),
            'latitude'  => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];

        if ($this->lokasiModel->update($id, $dataToUpdate)) {
            return redirect()->to('pengaturan/lokasi')->with('success', 'Data lokasi berhasil diperbarui.');
        } else {
            $errors = $this->lokasiModel->errors();
            $errorMessage = $errors ? implode(', ', $errors) : 'Gagal memperbarui data lokasi.';
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    // Method untuk menghapus data lokasi
    public function lokasiDelete($id = null) // Tambahkan $id = null
    {
        $lokasi = $this->lokasiModel->find($id);
        if (!$lokasi) {
            return redirect()->to('pengaturan/lokasi')->with('error', 'Data lokasi tidak ditemukan untuk dihapus.');
        }

        // PENTING: Cek apakah lokasi ini digunakan oleh siswa
        $jumlahSiswaTerhubung = $this->siswaModel->where('lokasi_id', $id)->countAllResults();

        if ($jumlahSiswaTerhubung > 0) {
            return redirect()->to('pengaturan/lokasi')->with('error', 'Lokasi tidak dapat dihapus karena masih terhubung dengan ' . $jumlahSiswaTerhubung . ' data siswa. Harap perbarui data siswa terkait terlebih dahulu.');
        }

        // Jika tidak ada siswa terhubung, baru hapus lokasi
        if ($this->lokasiModel->delete($id)) {
            return redirect()->to('pengaturan/lokasi')->with('success', 'Data lokasi berhasil dihapus.');
        } else {
            $errors = $this->lokasiModel->errors();
            $errorMessage = $errors ? implode(', ', $errors) : 'Gagal menghapus data lokasi.';
            return redirect()->to('pengaturan/lokasi')->with('error', $errorMessage);
        }
    }
}
