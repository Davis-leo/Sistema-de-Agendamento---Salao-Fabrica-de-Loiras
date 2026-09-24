<?php

namespace App\Validation;

class Schedule
{
    public function rules(): array
    {
        return [

            'unit_id' => [

                'rules'  => 'is_natural_no_zero|is_not_unique[units.id]',
                'errors' => [
                    'is_natural_no_zero' => 'Unidade inválida',
                    'is_not_unique'      => 'Unidade inválida', 
                ],
            ],

            'service_ids' => [

                'rules'  => 'required',
                'errors' => [
                    'required' => 'Escolha pelo menos um serviço',
                ],
            ],

            'professional_assignments' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Escolha uma profissional para cada grupo de serviço',
                ],
            ],

            'month' => [

                'rules'  => 'required|max_length[2]',
                'errors' => [
                    'required'   => 'Informe o mês',
                    'max_length' => 'Mês com formato inválido', 
                ],
            ],

            'day' => [

                'rules'  => 'required|max_length[2]',
                'errors' => [
                    'required'   => 'Informe o dia',
                    'max_length' => 'Dia com formato inválido', 
                ],
            ],

            'hour' => [

                'rules'  => 'required|exact_length[5]', // hh:mm
                'errors' => [
                    'required'     => 'Informe a hora',
                    'exact_length' => 'Hora com formato inválido, precisa ser hh:mm', 
                ],
            ],
        ];
    }
}
