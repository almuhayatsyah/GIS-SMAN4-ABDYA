<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'name' => 'operator',
        'description' => 'Operator/Admin Sistem',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ],
      [
        'name' => 'kesiswaan',
        'description' => 'Petugas Kesiswaan',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ],
      [
        'name' => 'pengunjung',
        'description' => 'User Eksternal (Publik)',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]
    ];

    $this->db->table('roles')->insertBatch($data);
  }
}
