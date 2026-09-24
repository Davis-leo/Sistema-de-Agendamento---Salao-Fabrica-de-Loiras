<?php

namespace App\Libraries;

use App\Entities\Unit;
use App\Models\ScheduleModel;
use App\Models\ScheduleServiceModel;
use App\Models\UnitModel;
use CodeIgniter\Config\Factories;

class UnitService extends MyBaseService
{

    private static array $serviceTimes = [
        '10 minutes' => '10 minutos',
        '15 minutes' => '15 minutos',
        '30 minutes' => '30 minutos',
        '1 hour' => 'Uma hora',
        '2 hours' => 'Duas horas',
    ];

    /**
     * Renderiza uma tabela HTML com os resultados
     * 
     * @return string
     */
    public function renderUnits(): string
    {

        $units = model(UnitModel::class)->orderBy('name', 'ASC')->findAll();

        if (empty($units)) {

            return self::TEXT_FOR_NO_DATA;
        }

        $this->htmlTable->setHeading('Ações', 'Nome', 'E-mail', 'Telefone', 'Serviços', 'Situação', 'Criado');

        $unitServiceService = Factories::class(UnitServiceService::class);

        foreach ($units as $unit) {

            $this->htmlTable->addRow([
                $this->renderBtnActions($unit),
                $unit->name,
                $unit->email,
                $unit->phone,
                $unitServiceService->renderUnitServices($unit->services),
                $unit->status(),
                $unit->createdAt(),
            ]);
        }

        return $this->htmlTable->generate();
    }


    /**
     * Renderiza um dropdown HTML com as opções de tempo necessário para cada atendimento.
     * @param string|null $serviceTime $serviceTime intervalo já associado ao registro, quando for o caso.
     * @return string
     */
    public function renderTimesInterval(?string $serviceTime = null): string {

        $options =[];
        $options[''] = '--- Escolha ---';

        foreach(self::$serviceTimes as $key => $time){

            $options[$key] = $time;
        }

        return form_dropdown(data: 'servicetime', options: $options, selected: old('servicetime', $serviceTime), extra: ['class' => 'form-control']);
    }

    /**
     * Renderiza uma lista não ordenada HTML dos agendamentos da unidade
     * @param integer|string $unitId
     * @return void
     */
    public function renderUnitSchedules(int|string $unitId): string{

        // Buscamos os agendamentos
        $schedules = model(ScheduleModel::class)->getUnitSchedules($unitId);

        if(empty($schedules)){

            return self::TEXT_FOR_NO_DATA;
        }

        $list = [];

        foreach($schedules as $schedule){

            $serviceItems = model(ScheduleServiceModel::class)
                ->select('schedule_services.service_id, services.name AS service_name, assigned_professionals.name AS professional_name')
                ->join('services', 'services.id = schedule_services.service_id')
                ->join('professionals AS assigned_professionals', 'assigned_professionals.id = schedule_services.professional_id', 'left')
                ->where('schedule_services.schedule_id', $schedule->id)
                ->orderBy('services.name', 'ASC')
                ->findAll();
            if (empty($serviceItems)) {
                $serviceItems = [[
                    'service_id' => $schedule->service_id,
                    'service_name' => $schedule->service,
                    'professional_name' => $schedule->professional,
                ]];
            }

            $list[] = "<p>
                            <strong>Unidade:  </strong>{$schedule->unit}        <br>
                            <strong>Endereço: </strong>{$schedule->address}     <br>
                            <strong>Serviço e profissional: </strong>{$schedule->service_professionals} <br>
                            <strong>Situação: </strong>{$schedule->situation()} <br>
                           <strong>Cliente:  </strong>{$schedule->user}        <br>
                            <strong>Telefone: </strong>{$schedule->customer_phone} <br>";

            if (!$schedule->canceled) {
                $list[count($list) - 1] .= anchor(
                    route_to('super.schedules.edit', $schedule->id),
                    'Editar agendamento',
                    ['class' => 'btn btn-sm btn-outline-secondary mt-2 mr-2']
                );
            }

            if (!$schedule->canceled && !$schedule->finished) {

                $list[count($list) - 1] .= form_open(
                    route_to('super.schedules.cancel', $schedule->id),
                    [
                        'class' => 'd-inline',
                        'onsubmit' => 'return confirm("Deseja cancelar este agendamento?");',
                    ],
                    hidden: ['_method' => 'DELETE']
                );
                $list[count($list) - 1] .= form_button([
                    'class' => 'btn btn-sm btn-outline-primary mt-2',
                    'type' => 'submit',
                    'content' => 'Cancelar agendamento',
                ]);
                $list[count($list) - 1] .= form_close();
            }

            if (!$schedule->canceled && !$schedule->confirmed) {
                $list[count($list) - 1] .= form_open(route_to('super.schedules.confirm', $schedule->id), ['class' => 'd-inline ml-2']);
                foreach ($serviceItems as $serviceItem) {
                    $professionalName = $serviceItem['professional_name'] ?: ($schedule->professional ?: 'Profissional não definida');
                    $list[count($list) - 1] .= '<div class="mb-2"><strong>' . esc($serviceItem['service_name']) . ' - ' . esc($professionalName) . '</strong><br>'
                        . '<input type="number" name="service_amount[' . (int) $serviceItem['service_id'] . ']" min="0" step="0.01" class="form-control form-control-sm d-inline-block" style="max-width:130px" placeholder="Valor" required> '
                        . '<input type="number" name="commission_percentage[' . (int) $serviceItem['service_id'] . ']" min="0" max="100" step="0.01" class="form-control form-control-sm d-inline-block" style="max-width:130px" placeholder="Comissão %" required></div>';
                }
                $list[count($list) - 1] .= form_button(['class' => 'btn btn-sm btn-primary mt-2', 'type' => 'submit', 'content' => 'Confirmar atendimento']);
                $list[count($list) - 1] .= form_close();
            }

            $list[count($list) - 1] .= "
                       </p>";
        }

        // Retorno a lista HTML
        return ul($list);
    }


    /**
     * Renderiza os dropdowns com as ações possíveis para cada registro
     * @param Unit $unit
     * @return string
     */
    private function renderBtnActions(Unit $unit): string
    {


        $btnActions = '<div class="btn-group dropup">';
        $btnActions .= '<button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Ações
                        </button>';
        $btnActions .= '<ul class="dropdown-menu">';
        $btnActions .= anchor(route_to('units.edit', $unit->id), 'Editar', ['class' => 'dropdown-item']);
        $btnActions .= anchor(route_to('units.services', $unit->id), 'Serviços', ['class' => 'dropdown-item']);
        $btnActions .= anchor(route_to('units.schedules', $unit->id), 'Agendamentos', ['class' => 'dropdown-item']);
        $btnActions .= view_cell(
            library: 'ButtonsCell::action', 
            params: [
                'route'       => route_to('units.action', $unit->id),
                'text_action' => $unit->textToAction(),
                'activated'   => $unit->isActivated(),
                'btn_class'   => 'dropdown-item py2'
            ]
        );
        $btnActions .= view_cell(
            library: 'ButtonsCell::destroy', 
            params: [
                'route'       => route_to('units.destroy', $unit->id),
                'btn_class'   => 'dropdown-item py2'
            ]
        );

        $btnActions .= '</ul>
                    </div>';

        return $btnActions;
    }
}