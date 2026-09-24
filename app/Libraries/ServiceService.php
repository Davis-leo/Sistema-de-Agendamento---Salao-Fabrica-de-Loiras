<?php

namespace App\Libraries;

use App\Entities\Service;
use App\Entities\Unit;
use App\Models\ServiceModel;
use App\Models\UnitModel;

class ServiceService extends MyBaseService
{
    /**
     * Renderiza uma tabela HTML com os resultados
     * 
     * @return string
     */
    public function renderServices(): string
    {

        $services = model(ServiceModel::class)->orderBy('name', 'ASC')->findAll();

        if (empty($services)) {

            return self::TEXT_FOR_NO_DATA;
        }

        $this->htmlTable->setHeading('Ações', 'Nome', 'Grupo', 'Situação', 'Criado');

        foreach ($services as $service) {

            $this->htmlTable->addRow([
                $this->renderBtnActions($service),
                $service->name,
                $service->service_group,
                $service->status(),
                $service->createdAt(),
            ]);
        }

        return $this->htmlTable->generate();
    }


    /**
     * Renderiza os dropdowns com as ações possíveis para cada registro
     * @param Service $service
     * @return string
     */
    private function renderBtnActions(Service $service): string
    {


        $btnActions = '<div class="btn-group dropup">';
        $btnActions .= '<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Ações
                        </button>';
        $btnActions .= '<ul class="dropdown-menu">';
        $btnActions .= anchor(route_to('services.edit', $service->id), 'Editar', ['class' => 'dropdown-item']);
        $btnActions .= view_cell(
            library: 'ButtonsCell::action', 
            params: [
                'route'       => route_to('services.action', $service->id),
                'text_action' => $service->textToAction(),
                'activated'   => $service->isActivated(),
                'btn_class'   => 'dropdown-item py2'
            ]
        );
        $btnActions .= view_cell(
            library: 'ButtonsCell::destroy', 
            params: [
                'route'       => route_to('services.destroy', $service->id),
                'btn_class'   => 'dropdown-item py2'
            ]
        );

        $btnActions .= '</ul>
                    </div>';

        return $btnActions;
    }
}