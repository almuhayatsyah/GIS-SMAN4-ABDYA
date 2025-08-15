<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoles extends Migration
{
  public function up()
  {
    // Roles table
    $this->forge->addField([
      'id' => [
        'type' => 'INT',
        'constraint' => 5,
        'unsigned' => true,
        'auto_increment' => true,
      ],
      'name' => [
        'type' => 'VARCHAR',
        'constraint' => 50,
      ],
      'description' => [
        'type' => 'TEXT',
        'null' => true,
      ],
      'created_at' => [
        'type' => 'DATETIME',
        'null' => true,
      ],
      'updated_at' => [
        'type' => 'DATETIME',
        'null' => true,
      ],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('roles');

    // Add role_id to users table
    $this->forge->addColumn('users', [
      'role_id' => [
        'type' => 'INT',
        'constraint' => 5,
        'unsigned' => true,
        'after' => 'id'
      ]
    ]);

    // Add foreign key
    $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
  }

  public function down()
  {
    // Drop foreign key from users table
    $this->forge->dropForeignKey('users', 'users_role_id_foreign');

    // Drop role_id column
    $this->forge->dropColumn('users', 'role_id');

    // Drop roles table
    $this->forge->dropTable('roles');
  }
}
