<?php

namespace App\Libraries;

use App\Models\ProfessionalModel;
use App\Models\ProfessionalServiceModel;
use App\Models\ProfessionalWorkingHourModel;
use App\Models\ScheduleModel;
use App\Models\UnitModel;
use DateTime;

class ProfessionalAvailabilityService
{
    public const MAX_SIMULTANEOUS_SCHEDULES = 3;

    public function availableForSlot(int $unitId, int $serviceId, string $chosenDate): array
    {
        $unit = model(UnitModel::class)->where('active', 1)->find($unitId);
        if (!$unit || !in_array($serviceId, array_map('intval', $unit->services ?? []), true)) {
            return [];
        }

        $date = DateTime::createFromFormat('Y-m-d H:i', $chosenDate);
        if (!$date || $date->format('Y-m-d H:i') !== $chosenDate) {
            return [];
        }

        $weekday = (int) $date->format('N') - 1;
        $time = $date->format('H:i:s');
        $professionals = model(ProfessionalModel::class)
            ->where('active', 1)
            ->where('unit_id', $unitId)
            ->orderBy('name', 'ASC')
            ->findAll();

        $serviceModel = model(ProfessionalServiceModel::class);
        $workingHourModel = model(ProfessionalWorkingHourModel::class);
        $scheduleModel = model(ScheduleModel::class);
        $available = [];

        foreach ($professionals as $professional) {
            $hasService = $serviceModel
                ->where(['professional_id' => $professional->id, 'service_id' => $serviceId])
                ->first();
            if (!$hasService) {
                continue;
            }

            $workingHour = $workingHourModel
                ->where(['professional_id' => $professional->id, 'weekday' => $weekday, 'active' => 1])
                ->where('start_time <=', $time)
                ->where('end_time >', $time)
                ->first();
            if (!$workingHour) {
                continue;
            }

            $count = $scheduleModel
                ->where(['professional_id' => $professional->id, 'canceled' => 0, 'finished' => 0])
                ->where('chosen_date', $chosenDate . ':00')
                ->countAllResults();
            if ($count < self::MAX_SIMULTANEOUS_SCHEDULES) {
                $available[] = $professional;
            }
        }

        return $available;
    }

    public function renderOptions(int $unitId, int $serviceId, string $chosenDate): string
    {
        $options = [null => '--- Selecione um profissional ---'];
        foreach ($this->availableForSlot($unitId, $serviceId, $chosenDate) as $professional) {
            $options[$professional->id] = $professional->name;
        }

        if (count($options) === 1) {
            return '<div class="alert alert-warning">Não há profissionais disponíveis para este horário.</div>';
        }

        return form_dropdown('professional_id', $options, old('professional_id'), [
            'id' => 'professional_id',
            'class' => 'form-select',
        ]);
    }

    public function isAvailable(int $unitId, int $professionalId, int $serviceId, string $chosenDate): bool
    {
        foreach (model(ProfessionalModel::class)->where('active', 1)->findAll() as $professional) {
            $date = DateTime::createFromFormat('Y-m-d H:i', $chosenDate);
            if (!$date || (int) $professional->id !== $professionalId || (int) $professional->unit_id !== $unitId) {
                continue;
            }
            $weekday = (int) $date->format('N') - 1;
            $workingHour = model(ProfessionalWorkingHourModel::class)
                ->where(['professional_id' => $professionalId, 'weekday' => $weekday, 'active' => 1])
                ->where('start_time <=', $date->format('H:i:s'))
                ->where('end_time >', $date->format('H:i:s'))
                ->first();
            $hasService = model(ProfessionalServiceModel::class)
                ->where(['professional_id' => $professionalId, 'service_id' => $serviceId])
                ->first();
            $count = model(ScheduleModel::class)
                ->where(['professional_id' => $professionalId, 'canceled' => 0, 'finished' => 0])
                ->where('chosen_date', $chosenDate . ':00')
                ->countAllResults();
            if ($workingHour && $hasService && $count < self::MAX_SIMULTANEOUS_SCHEDULES) {
                return true;
            }
        }
        return false;
    }
}
