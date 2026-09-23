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

    public function availableForSlot(int $unitId, array $serviceIds, string $chosenDate): array
    {
        $unit = model(UnitModel::class)->where('active', 1)->find($unitId);
        $serviceIds = array_values(array_unique(array_map('intval', $serviceIds)));
        if (!$unit || empty($serviceIds) || count(array_diff($serviceIds, array_map('intval', $unit->services ?? []))) > 0) {
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
            $professionalServices = $serviceModel
                ->where('professional_id', $professional->id)
                ->whereIn('service_id', $serviceIds)
                ->findAll();
            if (count($professionalServices) !== count($serviceIds)) {
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

    public function renderOptions(int $unitId, array $serviceIds, string $chosenDate): string
    {
        $options = '';
        foreach ($this->availableForSlot($unitId, $serviceIds, $chosenDate) as $professional) {
            $options .= '<button type="button" class="professional-choice" data-professional-id="' . $professional->id . '" aria-pressed="false">'
                . esc($professional->name)
                . '</button>';
        }

        if ($options === '') {
            return '<div class="alert alert-warning">Não há profissionais disponíveis para este horário.</div>';
        }

        return '<div class="professionals-grid" role="group" aria-label="Profissionais disponíveis">' . $options . '</div>';
    }

    public function isAvailable(int $unitId, int $professionalId, array $serviceIds, string $chosenDate): bool
    {
        $serviceIds = array_values(array_unique(array_map('intval', $serviceIds)));
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
            $professionalServices = model(ProfessionalServiceModel::class)
                ->where('professional_id', $professionalId)
                ->whereIn('service_id', $serviceIds)
                ->findAll();
            $count = model(ScheduleModel::class)
                ->where(['professional_id' => $professionalId, 'canceled' => 0, 'finished' => 0])
                ->where('chosen_date', $chosenDate . ':00')
                ->countAllResults();
            if ($workingHour && count($professionalServices) === count($serviceIds) && $count < self::MAX_SIMULTANEOUS_SCHEDULES) {
                return true;
            }
        }
        return false;
    }
}
