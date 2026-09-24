<?php

namespace App\Models;

class UserModel extends \CodeIgniter\Shield\Models\UserModel
{
    protected $allowedFields = [
        'username',
        'phone',
        'status',
        'status_message',
        'active',
        'last_active',
    ];
}
