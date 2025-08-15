<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_kelas',
        'wali_kelas'
    ];
    protected $useTimestamps = false;

    public function getLaporanRekap()
    {
        return $this->select('kelas.nama_kelas as kelas, 
                    SUM(CASE WHEN LOWER(jenis_kelamin) = \"laki-laki\" THEN 1 ELSE 0 END) as laki_laki,
                    SUM(CASE WHEN LOWER(jenis_kelamin) = \"perempuan\" THEN 1 ELSE 0 END) as perempuan,
                    SUM(CASE WHEN status_kurang_mampu = 1 THEN 1 ELSE 0 END) as kurang_mampu,
                    SUM(CASE WHEN status_kurang_mampu = 0 THEN 1 ELSE 0 END) as tidak_kurang_mampu,
                    COUNT(*) as total')
            ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
            ->groupBy('kelas.nama_kelas')
            ->findAll();
    }
}
