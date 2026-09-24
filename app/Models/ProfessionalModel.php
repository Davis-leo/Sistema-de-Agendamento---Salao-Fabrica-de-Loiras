<?php

namespace App\Models;

use App\Entities\Professional;

class ProfessionalModel extends MyBaseModel
{
    protected $table = 'professionals';
    protected $primaryKey = 'id';
    protected $returnType = Professional::class;
    protected $allowedFields = ['unit_id', 'name', 'active'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'unit_id' => 'required|is_natural_no_zero|is_not_unique[units.id]',
        'name' => 'required|max_length[120]|is_unique[professionals.name,id,{id}]',
        'active' => 'required|in_list[0,1]',
    ];
    protected $validationMessages = [
        'unit_id' => ['required' => 'A unidade é obrigatória.', 'is_not_unique' => 'A unidade selecionada é inválida.'],
        'name' => ['required' => 'O nome do profissional é obrigatório.'],
    ];
}
