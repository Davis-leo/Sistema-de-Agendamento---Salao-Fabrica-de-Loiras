<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExternalCustomerToSchedules extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE schedules MODIFY user_id INT UNSIGNED NULL');

        $this->forge->addColumn('schedules', [
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'user_id',
            ],
            'customer_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'customer_name',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'customer_phone',
            ],
        ]);

        $this->forge->addForeignKey('created_by', 'users', 'id', 'SET NULL', 'CASCADE', 'schedules_created_by_foreign');
    }

    public function down()
    {
        $this->forge->dropForeignKey('schedules', 'schedules_created_by_foreign');
        $this->forge->dropColumn('schedules', ['customer_name', 'customer_phone', 'created_by']);
        $this->db->query('ALTER TABLE schedules MODIFY user_id INT UNSIGNED NOT NULL');
    }
}
