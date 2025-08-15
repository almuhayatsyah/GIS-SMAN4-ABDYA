<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Disesuaikan dengan kolom aktual di tabel 'siswa' Anda
    protected $allowedFields    = [
        'nisn',
        'nama_siswa',
        'kelas_id',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'umur',
        'nomor_hp',
        'foto_rumah',
        'status_kurang_mampu',
        'ortu_id',
        'lokasi_id',
        'created_at',
        'updated_at'
    ];

    protected $validationRules = [
        'nisn'          => 'required|is_unique[siswa.nisn,id,{id}]|max_length[20]',
        'nama_siswa'    => 'required|max_length[100]',
        'kelas_id'      => 'required|integer',
        'tanggal_lahir' => 'permit_empty',
        'jenis_kelamin' => 'required|in_list[Laki-laki,Perempuan]',
        'agama'         => 'permit_empty|max_length[15]',
        'umur'          => 'permit_empty|max_length[11]',
        'nomor_hp'      => 'permit_empty|max_length[15]',
        // 'status_kurang_mampu' => 'required|in_list[0,1]', // Status dihitung otomatis, tidak perlu validasi manual
        'lokasi_id'     => 'permit_empty|integer',
        'ortu_id'       => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'nisn' => [
            'required' => 'NISN harus diisi.',
            'is_unique' => 'NISN ini sudah terdaftar.',
        ],
        'nama_siswa' => [
            'required' => 'Nama siswa harus diisi.',
        ],
    ];


    public function getLaporanRekap()
    {
        return $this->select('kelas.nama_kelas as kelas, 
                        SUM(CASE WHEN LOWER(siswa.jenis_kelamin) = "laki-laki" THEN 1 ELSE 0 END) as laki_laki,
                        SUM(CASE WHEN LOWER(siswa.jenis_kelamin) = "perempuan" THEN 1 ELSE 0 END) as perempuan,
                        SUM(CASE WHEN siswa.status_kurang_mampu = 1 THEN 1 ELSE 0 END) as kurang_mampu,
                        SUM(CASE WHEN siswa.status_kurang_mampu = 0 THEN 1 ELSE 0 END) as tidak_kurang_mampu,
                        COUNT(siswa.id) as total')
            ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
            ->groupBy('kelas.nama_kelas')
            ->findAll();
    }

    public function getLaporanSiswa()
    {
        return $this->select('siswa.nama_siswa, kelas.nama_kelas as kelas, siswa.status_kurang_mampu, siswa.jenis_kelamin')
            ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')

            ->findAll();
    }

    public function getSiswaWithLokasi(array $filters = [], bool $paginate = false, int $perPage = 10, string $groupName = 'default_siswa_lokasi')
    {
        $builder = $this->select('siswa.*, kelas.nama_kelas, 
                            lokasi.latitude, lokasi.longitude, lokasi.alamat AS alamat_lokasi,lokasi.kecamatan AS kecamatan_lokasi,lokasi.kabupaten AS kabupaten_lokasi,lokasi.provinsi AS provinsi_lokasi')
            ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
            ->join('lokasi', 'lokasi.id = siswa.lokasi_id', 'left');

        if (!empty($filters['status_kurang_mampu']) && ($filters['status_kurang_mampu'] === '1' || $filters['status_kurang_mampu'] === 1)) {
            $builder->where('siswa.status_kurang_mampu', 1);
        } elseif (isset($filters['status_kurang_mampu']) && ($filters['status_kurang_mampu'] === '0' || $filters['status_kurang_mampu'] === 0)) {
            $builder->where('siswa.status_kurang_mampu', 0);
        }

        if (!empty($filters['kelas_id'])) {
            $builder->where('siswa.kelas_id', $filters['kelas_id']);
        }

        if (isset($filters['has_coordinates']) && $filters['has_coordinates'] === true) {
            $builder->where('lokasi.latitude IS NOT NULL AND lokasi.latitude != "" AND lokasi.latitude != 0');
            $builder->where('lokasi.longitude IS NOT NULL AND lokasi.longitude != "" AND lokasi.longitude != 0');
        }

        $builder->orderBy('siswa.nama_siswa', 'ASC');

        if ($paginate) {
            return [
                'data'  => $builder->paginate($perPage, $groupName),
                'pager' => $this->pager,
            ];
        }
        return $builder->findAll();
    }
}
