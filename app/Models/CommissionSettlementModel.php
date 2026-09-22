<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionSettlementModel extends Model
{
    protected $table = 'commission_settlements';
    protected $allowedFields = ['professional_id', 'week_start', 'week_end', 'total_amount', 'paid_at', 'paid_by'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
}
