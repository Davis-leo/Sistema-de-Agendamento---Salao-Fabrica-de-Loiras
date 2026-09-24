<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFinancialDataToScheduleServices extends Migration
{
    public function up()
    {
        $this->forge->addColumn('schedule_services', [
            'service_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'after' => 'service_id',
            ],
            'commission_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'after' => 'service_amount',
            ],
            'commission_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'after' => 'commission_percentage',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('schedule_services', [
            'service_amount',
            'commission_percentage',
            'commission_amount',
        ]);
    }
}
