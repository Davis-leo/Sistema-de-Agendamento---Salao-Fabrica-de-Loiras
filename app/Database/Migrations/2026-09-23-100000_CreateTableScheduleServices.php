<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableScheduleServices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'schedule_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'service_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);

        $this->forge->addKey(['schedule_id', 'service_id'], true);
        $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('schedule_services');
    }

    public function down()
    {
        $this->forge->dropTable('schedule_services');
    }
}
