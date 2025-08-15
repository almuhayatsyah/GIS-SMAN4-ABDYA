<?php

namespace App\Controllers;

use App\Models\SiswaModel; // Pastikan ini sudah di-use

class PetaController extends BaseController
{
    protected $siswaModel;
    protected $db; // Pastikan ini dideklarasikan


    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->db = \Config\Database::connect(); // Pastikan ini diinisialisasi
        helper(['url', 'text']);
    }

    public function index()
    {
        $data['title'] = 'Peta Sebaran Lokasi Siswa';

        // Ambil data siswa beserta detail lokasi dan informasi lain yang relevan untuk marker
        $builder = $this->db->table($this->siswaModel->getTable() . ' s');
        $builder->select('s.id AS id, s.nama_siswa, s.nisn, s.status_kurang_mampu,
                        k.nama_kelas, 
                        l.latitude, l.longitude, l.alamat AS alamat_lokasi');
        $builder->join('lokasi l', 'l.id = s.lokasi_id', 'inner');
        $builder->join('kelas k', 'k.id = s.kelas_id', 'left');

        // Filter penting: Hanya ambil siswa yang punya koordinat valid (tidak NULL dan tidak kosong)
        $builder->where('l.latitude IS NOT NULL');
        $builder->where("l.latitude != ''");
        $builder->where('l.longitude IS NOT NULL');
        $builder->where("l.longitude != ''");


        // OPSIONAL: Jika Anda ingin peta ini defaultnya hanya menampilkan siswa kurang mampu
        // $builder->where('s.status_kurang_mampu', 1); 

        $siswaUntukPeta = $builder->get()->getResultArray();

        // Ubah data menjadi format JSON agar mudah dibaca oleh JavaScript di view
        $data['siswa_lokasi_json'] = json_encode($siswaUntukPeta);

        // API Key Google Maps Anda
        // INGAT: Idealnya simpan di file .env dan ambil dengan getenv('Maps_API_KEY')
        $data['Maps_api_key'] = getenv('MAPS_API_KEY');; // GANTI DENGAN API KEY ANDA JIKA BERBEDA

        return view('peta/tampil', $data); // Mengarah ke view peta/tampil.php
    }
}
