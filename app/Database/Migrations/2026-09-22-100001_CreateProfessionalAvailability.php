<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfessionalAvailability extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'professional_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'service_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey(['professional_id', 'service_id'], true);
        $this->forge->addForeignKey('professional_id', 'professionals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('professional_services');

        $this->forge->addField([
            'professional_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'weekday' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'start_time' => ['type' => 'TIME'],
            'end_time' => ['type' => 'TIME'],
            'active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addKey(['professional_id', 'weekday'], true);
        $this->forge->addForeignKey('professional_id', 'professionals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('professional_working_hours');
    }

    public function down()
    {
        $this->forge->dropTable('professional_working_hours');
        $this->forge->dropTable('professional_services');
    }
}
