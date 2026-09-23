<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IncreaseUnitPhoneLength extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('units', [
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'comment'    => '(99) 99999-9999',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('units', [
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 14,
                'comment'    => '(99)99999-9999',
            ],
        ]);
    }
}
