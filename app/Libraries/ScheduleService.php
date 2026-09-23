<?php

namespace App\Libraries;

use App\Entities\Schedule;
use App\Models\ScheduleModel;
use App\Models\ScheduleServiceModel;
use App\Models\ServiceModel;
use App\Models\UnitModel;
use CodeIgniter\Events\Events;
use CodeIgniter\I18n\Time;
use Exception;
use InvalidArgumentException;

class ScheduleService
{
    private ProfessionalAvailabilityService $availabilityService;

    public function __construct()
    {
        $this->availabilityService = new ProfessionalAvailabilityService();
    }
    /**
     * Renderiza a lista com as opções de unidades ativas e que possuam serviços associados para serem escolhidos no agendamento.
     * @return string
     */
    public function renderUnits(): string
    {

        // unidades ativas e com serviços associados
        $where = [
            'active' => 1,
            'services !=' => null,
            'services !=' => '',
        ];

        $units = model(UnitModel::class)->where($where)->orderBy('name', 'ASC')->findAll();

        if(empty($units)){


            return '<div class="text-info mt-5">Não há unidades disponíveis para agendamento</div>';
        }

        // valor padrão
        $radios = '';

        foreach ($units as $unit) {

            $radios .= '<div class="form-check mb-2">';
            $radios .= "<input type='radio' name='unit_id' data-unit='{$unit->name} \nEndereço: {$unit->address}' value='{$unit->id}' class='form-check-input' id='radio-unit-{$unit->id}'>";
            $radios .= "<label class='form-check-label' for='radio-unit-{$unit->id}'>{$unit->name}<br>{$unit->address}</label>";
            $radios .= '</div>';
        }


        // Retornamos
        return $radios;
    }

    /**
    * Recupera os serviços associados à unidade como opções de seleção múltipla.
     * @param integer $unitId
     * @return string
     */
    public function renderUnitServices(int $unitId): string
    {
        // Validamos a existência da unidade, ativa, com serviços
        $unit = model(UnitModel::class)->where(['active' => 1, 'services!=' => null, 'services !=' => ''])->findOrfail($unitId);

        // buscamos os serviços dessa unidade
        $services = model(ServiceModel::class)->whereIn('id', $unit->services)->where('active', 1)->orderBy('name', 'ASC')->findAll();

        if(empty($services)){

            throw new InvalidArgumentException("Os serviços associados à Unidade {$unit->name} não estão ativos ou não existem.");
        }

        $options = '';

        foreach ($services as $service) {
            $options .= '<button type="button" class="service-choice" data-service-id="' . $service->id . '" data-service-name="' . esc($service->name, 'attr') . '" aria-pressed="false">'
                . esc($service->name)
                . '</button>';
        }

        return '<div class="services-grid" role="group" aria-label="Serviços disponíveis">' . $options . '</div>';

    }

    public function renderProfessionals(int $unitId, array $serviceIds, string $chosenDate): string
    {
        $unit = model(UnitModel::class)->where('active', 1)->find($unitId);
        $serviceIds = array_values(array_unique(array_map('intval', $serviceIds)));
        if (!$unit) {
            return '<div class="alert alert-warning">Unidade indisponível.</div>';
        }
        $availableServiceIds = array_map('intval', $unit->services ?? []);
        if (empty($serviceIds) || count(array_diff($serviceIds, $availableServiceIds)) > 0) {
            return '<div class="alert alert-warning">Serviço indisponível para esta unidade.</div>';
        }

        return $this->availabilityService->renderOptions($unitId, $serviceIds, $chosenDate);
    }

    /**
     * Tenta criar o agendamento do user logado
     * @param array $request
     * @throws Exception
     * @return boolean|string
     */
    public function createSchedule(array $request): bool|string
    {
        try{

            $model = model(ScheduleModel::class);

            $request = (object) $request;

            $currentYear = Time::now()->getYear();

            // Terei algo assim: 2026-09-16 15:15
            $chosenDate = "{$currentYear}-{$request->month}-{$request->day} {$request->hour}";

            $unit = model(UnitModel::class)->where('active', 1)->find($request->unit_id);
            $serviceIds = array_values(array_unique(array_map('intval', (array) ($request->service_ids ?? []))));
            $activeServices = !empty($serviceIds)
                ? model(ServiceModel::class)->whereIn('id', $serviceIds)->where('active', 1)->findAll()
                : [];
            if (!$unit || empty($serviceIds) || count($activeServices) !== count($serviceIds) || count(array_diff($serviceIds, array_map('intval', $unit->services ?? []))) > 0) {
                return 'A unidade ou o serviço selecionado não está disponível';
            }

            if (empty($request->professional_id) || !$this->availabilityService->isAvailable((int) $request->unit_id, (int) $request->professional_id, $serviceIds, $chosenDate)) {

                return "O profissional escolhido não está mais disponível nesse horário";
            }

            $schedule = new Schedule([
                'unit_id'     => $request->unit_id,
                'service_id'  => $serviceIds[0],
                'professional_id' => $request->professional_id,
                'chosen_date' => $chosenDate,
            ]);

            // Conseguimos criar o agendamento?
            if(!$createdId = $model->insert($schedule)){

                log_message('error', 'Erro ao criar agendamento: ', $model->errors());

                return "Não foi possível criar o agendamento";
            }

            $scheduleServiceModel = model(ScheduleServiceModel::class);
            foreach ($serviceIds as $serviceId) {
                $scheduleServiceModel->insert([
                    'schedule_id' => $createdId,
                    'service_id'  => $serviceId,
                ]);
            }

            /**
             * Disparar email para o usuário com os dados do agendamento criado
             */
            Events::trigger('schedule_created', auth()->user()->email, $model->getSchedule(id: $createdId));

            // Retornamos true
            return true;

        } catch (\Throwable $th){

            log_message('error', '[ERROR] {exception}', ['exception' => $th]);

            return "Internal Server Error";
        }
    }

}