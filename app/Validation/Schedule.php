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

            'service_id' => [

                'rules'  => 'is_natural_no_zero|is_not_unique[services.id]',
                'errors' => [
                    'is_natural_no_zero' => 'Dado errado',
                    'is_not_unique'      => 'Serviço inválido', 
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

                'rules'  => 'required|max_length[5]', // hh:mm
                'errors' => [
                    'required'   => 'Informe a hora',
                    'max_length' => 'Hora com formato inválido', 
                ],
            ],
        ];
    }
}
