<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfessionalsAndFinancialDataToSchedules extends Migration
{
    public function up()
    {
        $this->forge->addColumn('schedules', [
            'professional_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'service_id'],
            'customer_email' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true, 'after' => 'customer_phone'],
            'confirmed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'finished'],
            'service_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true, 'after' => 'confirmed'],
            'commission_percentage' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'after' => 'service_amount'],
            'commission_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true, 'after' => 'commission_percentage'],
            'confirmed_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'commission_amount'],
            'confirmed_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'confirmed_at'],
            'canceled_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'created_by'],
            'cancel_reason' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'canceled_by'],
        ]);
        $this->forge->addForeignKey('professional_id', 'professionals', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('confirmed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('canceled_by', 'users', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('schedules', 'schedules_professional_id_foreign');
        $this->forge->dropForeignKey('schedules', 'schedules_confirmed_by_foreign');
        $this->forge->dropForeignKey('schedules', 'schedules_canceled_by_foreign');
        $this->forge->dropColumn('schedules', [
            'professional_id', 'customer_email', 'confirmed', 'service_amount',
            'commission_percentage', 'commission_amount', 'confirmed_at', 'confirmed_by',
            'canceled_by', 'cancel_reason',
        ]);
    }
}
