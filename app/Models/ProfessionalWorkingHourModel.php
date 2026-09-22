<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfessionalWorkingHourModel extends Model
{
    protected $table = 'professional_working_hours';
    protected $allowedFields = ['professional_id', 'weekday', 'start_time', 'end_time', 'active'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
