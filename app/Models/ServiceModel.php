<?php

namespace App\Models;

use App\Entities\Service;

class ServiceModel extends MyBaseModel
{
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Service::class;
    protected $useSoftDeletes   = false; //O registro será deletado fisicamente do banco de dados, sem a possibilidade de recuperação.
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'active',      
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id'            => 'permit_empty|is_natural_no_zero',
        'name'          => 'required|max_length[69]|is_unique[services.name,id,{id}]',
    ];
    protected $validationMessages   = [
        'name' => [
            'required'      => 'O Nome é obrigatório.',
            'max_length'    => 'O Nome deve ter no máximo 69 caracteres.',
            'is_unique'     => 'O Nome deve ser único. Já existe um serviço com este nome.'
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['escapeData'];
    protected $beforeUpdate   = ['escapeData'];
}
