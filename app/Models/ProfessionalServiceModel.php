<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfessionalServiceModel extends Model
{
    protected $table = 'professional_services';
    protected $allowedFields = ['professional_id', 'service_id'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
