<?php

namespace App\Entities;

class Professional extends MyBaseEntity
{
    protected $casts = [
        'active' => 'boolean',
        'commission_percentage' => 'float',
    ];
}
