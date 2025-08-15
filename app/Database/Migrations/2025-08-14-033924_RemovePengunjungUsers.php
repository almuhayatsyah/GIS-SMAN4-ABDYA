<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemovePengunjungUsers extends Migration
{
    public function up()
    {
        $this->db->table('user')->where('role', 'pengunjung')->delete();
    }

    public function down()
    {
        //
    }
}
