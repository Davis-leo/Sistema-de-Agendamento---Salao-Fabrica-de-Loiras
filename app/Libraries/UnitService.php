<?php

namespace App\Libraries;

use App\Models\UnitModel;

class UnitService extends MyBaseService
{

    /**
     * Renderiza uma tabela HTML com os resultados
     * 
     * @return string
     */
    public function renderUnits(): string
    {

        $units = model(UnitModel::class)->orderBy('name', 'ASC')->findAll();

        if (empty($units)) {

            return "<div class='text-info'>Não há dados para serem exibidos</div>";
        }

        $this->htmlTable->setHeading('Nome', 'E-mail', 'Telefone', 'Início', 'Fim', 'Criado');

        foreach ($units as $unit) {

            $this->htmlTable->addRow([
                $unit->name,
                $unit->email,
                $unit->phone,
                $unit->starttime,
                $unit->endtime,
                $unit->created_at
            ]);
        }

        return $this->htmlTable->generate();
    }
}