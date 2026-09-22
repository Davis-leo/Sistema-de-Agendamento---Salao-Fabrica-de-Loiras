<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommissionSettlements extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'professional_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'week_start' => ['type' => 'DATE'],
            'week_end' => ['type' => 'DATE'],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'paid_at' => ['type' => 'DATETIME', 'null' => true],
            'paid_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['professional_id', 'week_start']);
        $this->forge->addForeignKey('professional_id', 'professionals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('paid_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('commission_settlements');
    }

    public function down()
    {
        $this->forge->dropTable('commission_settlements');
    }
}
