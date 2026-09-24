<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddServiceGroupsAndAssignedProfessionals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('services', [
            'service_group' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
                'default' => 'Geral',
                'after' => 'name',
            ],
        ]);

        $this->forge->addColumn('schedule_services', [
            'professional_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'service_id',
            ],
        ]);
        $this->forge->addForeignKey('professional_id', 'professionals', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('schedule_services', 'schedule_services_professional_id_foreign');
        $this->forge->dropColumn('schedule_services', 'professional_id');
        $this->forge->dropColumn('services', 'service_group');
    }
}
