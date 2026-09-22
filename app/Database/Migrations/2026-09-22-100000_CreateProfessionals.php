<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfessionals extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'commission_percentage' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 30.00],
            'active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('name');
        $this->forge->createTable('professionals');
    }

    public function down()
    {
        $this->forge->dropTable('professionals');
    }
}
