<?php

namespace App\Models;

use App\Entities\Unit;

class UnitModel extends MyBaseModel
{
    protected $table            = 'units';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Unit::class;
    protected $useSoftDeletes   = false; //O registro será deletado fisicamente do banco de dados, sem a possibilidade de recuperação.
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'phone',
        'coordinator',
        'address',
        'services',
        'starttime',
        'endtime',
        'servicetime',
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
        'name'          => 'required|max_length[69]|is_unique[units.name,id,{id}]',
        'phone'         => 'required|exact_length[14]|is_unique[units.phone,id,{id}]',
        'email'         => 'required|valid_email|max_length[99]|is_unique[units.email,id,{id}]',
        'coordinator'   => 'required|max_length[69]',
        'address'       => 'required|max_length[128]',
        'starttime'     => 'required',
        'endtime'       => 'required',
        'servicetime'   => 'required',
    ];
    protected $validationMessages   = [
        'name' => [
            'required'      => 'O Nome é obrigatório.',
            'max_length'    => 'O Nome deve ter no máximo 69 caracteres.',
            'is_unique'     => 'O Nome deve ser único. Já existe uma unidade com este nome.'
        ],
        'phone' => [
            'required'      => 'O Telefone é obrigatório.',
            'exact_length'  => 'O Telefone deve ter exatamente 14 caracteres.',
            'is_unique'     => 'O Telefone deve ser único. Já existe uma unidade com este telefone.'
        ],
        'email' => [
            'required'      => 'O E-mail é obrigatório.',
            'valid_email'   => 'O E-mail deve conter um endereço de e-mail válido.',
            'max_length'    => 'O E-mail deve ter no máximo 99 caracteres.',
            'is_unique'     => 'O E-mail deve ser único. Já existe uma unidade com este e-mail.'
        ],
        'coordinator' => [
            'required'      => 'O nome do Gerente é obrigatório.',
            'max_length'    => 'O Gerente deve ter no máximo 69 caracteres.'
        ],
        'address' => [
            'required'      => 'O Endereço é obrigatório.',
            'max_length'    => 'O Endereço deve ter no máximo 128 caracteres.'
        ],
        'starttime' => [
            'required'      => 'O Início do Expediente é obrigatório.'
        ],
        'endtime' => [
            'required'      => 'O Fim do Expediente é obrigatório.'
        ],
        'servicetime' => [
            'required'      => 'O Tempo de cada atendimento é obrigatório.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['escapeCustomData'];
    protected $beforeUpdate   = ['escapeCustomData'];

    /**
     * Escapa os dados de forma controlada, pois temos uma coluna 'services' que é um JSON.
     * @param array $data
     * @return array
     */
    protected function escapeCustomData(array $data): array 
    {
        if(!isset($data['data'])){

            return $data;
        }

        foreach($this->allowedFields as $attribute){
            if (isset($data['data'][$attribute])){

                if($attribute === 'services') {

                    continue;
                }

                $data['data'][$attribute] = esc($data['data'][$attribute]);
            }
        }
        return $data;
    }
    

}
