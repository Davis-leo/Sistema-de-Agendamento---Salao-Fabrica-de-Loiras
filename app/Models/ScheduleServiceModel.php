<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleServiceModel extends Model
{
    protected $table = 'schedule_services';
    protected $allowedFields = [
        'schedule_id',
        'service_id',
        'professional_id',
        'service_amount',
        'commission_percentage',
        'commission_amount',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
