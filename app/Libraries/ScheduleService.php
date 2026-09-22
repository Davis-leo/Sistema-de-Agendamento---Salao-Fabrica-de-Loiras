<?php

namespace App\Libraries;

use App\Entities\Schedule;
use App\Models\ScheduleModel;
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
     * Recupero os serviços associados à unidade informada como um dropdown HTML
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

        $options = [];
        $options [null] = '--- Selecione um serviço ---';
        

        foreach ($services as $service) {

            $options[$service->id] = $service->name;
        }

        return form_dropdown(data: 'service', options: $options, selected: [], extra: ['id' => 'service_id', 'class' => 'form-select']);

    }

    public function renderProfessionals(int $unitId, int $serviceId, string $chosenDate): string
    {
        $unit = model(UnitModel::class)->where('active', 1)->find($unitId);
        $service = model(ServiceModel::class)->where('active', 1)->find($serviceId);
        if (!$unit || !$service || !in_array($serviceId, array_map('intval', $unit->services ?? []), true)) {
            return '<div class="alert alert-warning">Serviço indisponível para esta unidade.</div>';
        }

        return $this->availabilityService->renderOptions($unitId, $serviceId, $chosenDate);
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
            $service = model(ServiceModel::class)->where('active', 1)->find($request->service_id);
            if (!$unit || !$service || !in_array((int) $request->service_id, array_map('intval', $unit->services ?? []), true)) {
                return 'A unidade ou o serviço selecionado não está disponível';
            }

            if (empty($request->professional_id) || !$this->availabilityService->isAvailable((int) $request->unit_id, (int) $request->professional_id, (int) $request->service_id, $chosenDate)) {

                return "O profissional escolhido não está mais disponível nesse horário";
            }

            $schedule = new Schedule([
                'unit_id'     => $request->unit_id,
                'service_id'  => $request->service_id,
                'professional_id' => $request->professional_id,
                'chosen_date' => $chosenDate,
            ]);

            // Conseguimos criar o agendamento?
            if(!$createdId = $model->insert($schedule)){

                log_message('error', 'Erro ao criar agendamento: ', $model->errors());

                return "Não foi possível criar o agendamento";
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