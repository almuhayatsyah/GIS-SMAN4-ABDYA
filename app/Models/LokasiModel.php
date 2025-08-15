<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiModel extends Model
{
    protected $table            = 'lokasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Sesuaikan dengan field yang ada di tabel 'lokasi' Anda
    // dan ingin dikelola melalui form siswa.
    protected $allowedFields    = [
        'latitude',
        'longitude',
        'alamat',      // Untuk detail jalan, nomor rumah, RT/RW, dusun, dll.
        'kecamatan',
        'kabupaten',
        'provinsi'
    ];

    // Aturan Validasi
    // 'permit_empty' berarti field boleh kosong.
    // Ubah menjadi 'required' jika field tersebut wajib diisi.
    protected $validationRules = [
        'latitude'    => 'required|decimal',
        'longitude'   => 'required|decimal',
        'alamat'      => 'permit_empty|string', // Sesuaikan jika alamat wajib diisi
        'kecamatan'   => 'permit_empty|string|max_length[100]',
        'kabupaten'   => 'permit_empty|string|max_length[100]',
        'provinsi'    => 'permit_empty|string|max_length[100]',

    ];

    // Pesan Kustom untuk Validasi (opsional, jika pesan default kurang sesuai)
    protected $validationMessages = [
        'latitude' => [
            'required' => 'Latitude harus diisi.',
            'numeric'  => 'Latitude harus berupa angka (gunakan titik . sebagai pemisah desimal).'
        ],
        'longitude' => [
            'required' => 'Longitude harus diisi.',
            'numeric'  => 'Longitude harus berupa angka (gunakan titik . sebagai pemisah desimal).'
        ],
        'alamat' => [
            // 'required' => 'Detail alamat lokasi harus diisi.' // Contoh jika Anda mewajibkannya
        ],
        // Tambahkan pesan kustom lainnya jika perlu
    ];
}
