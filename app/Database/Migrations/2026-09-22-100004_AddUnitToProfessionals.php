<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitToProfessionals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('professionals', [
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id',
            ],
        ]);
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addKey('unit_id');
    }

    public function down()
    {
        $this->forge->dropForeignKey('professionals', 'professionals_unit_id_foreign');
        $this->forge->dropColumn('professionals', 'unit_id');
    }
}