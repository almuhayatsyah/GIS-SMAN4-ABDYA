<?php

namespace App\Models;

use CodeIgniter\Model;

class OrtuModel extends Model // <-- PERUBAHAN DI SINI
{
  protected $table            = 'ortu';
  protected $primaryKey       = 'id';   // Primary key tabel
  protected $useAutoIncrement = true;   // Menggunakan auto-increment untuk primary key
  protected $returnType       = 'array'; // Data dikembalikan sebagai array
  protected $useSoftDeletes   = false;  // Tidak menggunakan soft deletes

  // Kolom-kolom yang diizinkan untuk diisi melalui Mass Assignment (save, insert, update)
  // Pastikan semua kolom yang akan diisi dari form siswa ada di sini.
  protected $allowedFields    = [
    'id_siswa',
    'nama_ayah',
    'nama_ibu',
    'pekerjaan_ayah',
    'pekerjaan_ibu',
    'gaji_ayah',
    'gaji_ibu',
    'nomor_hp'
    // Tambahkan field lain jika ada yang perlu dikelola melalui model ini
  ];

  // Timestamps
  // Jika tabel 'ortu' Anda memiliki kolom created_at dan updated_at yang diisi otomatis oleh CodeIgniter,
  // maka set $useTimestamps menjadi true. Jika tidak, biarkan false atau hapus baris ini.
  protected $useTimestamps    = false;
  // protected $createdField     = 'created_at';
  // protected $updatedField     = 'updated_at';
  // protected $deletedField     = 'deleted_at'; // Hanya jika $useSoftDeletes = true

  // Aturan Validasi Dasar
  // Anda bisa membuat ini lebih ketat atau menambahkan lebih banyak aturan sesuai kebutuhan.
  // Validasi ini akan berjalan jika Anda menggunakan $model->save(), $model->insert(), $model->update()
  // dan tidak men-skip validasi.
  protected $validationRules = [
    'id_siswa'          => 'required|integer|is_not_unique[siswa.id]', // Memastikan id_siswa ada di tabel siswa
    'nama_ayah'         => 'permit_empty|string|max_length[100]',
    'nama_ibu'          => 'permit_empty|string|max_length[100]',
    'pekerjaan_ayah'    => 'permit_empty|string|max_length[100]',
    'pekerjaan_ibu'     => 'permit_empty|string|max_length[100]',
    'gaji_ayah'         => 'permit_empty|decimal', // Untuk angka desimal
    'gaji_ibu'          => 'permit_empty|decimal', // Untuk angka desimal
    'nomor_hp'          => 'permit_empty|string|max_length[15]', // Bisa ditambahkan validasi regex untuk format nomor HP
  ];

  // Pesan Kustom untuk Error Validasi (opsional)
  protected $validationMessages = [
    'id_siswa' => [
      'required'      => 'ID Siswa harus diisi untuk data orang tua.',
      'integer'       => 'ID Siswa tidak valid.',
      'is_not_unique' => 'Siswa dengan ID tersebut tidak ditemukan.' // Pesan untuk is_not_unique
    ],
    'gaji_ayah' => [
      'decimal' => 'Format gaji ayah tidak valid (gunakan titik . sebagai pemisah desimal jika ada).'
    ],
    'gaji_ibu' => [
      'decimal' => 'Format gaji ibu tidak valid (gunakan titik . sebagai pemisah desimal jika ada).'
    ],

  ];
}
