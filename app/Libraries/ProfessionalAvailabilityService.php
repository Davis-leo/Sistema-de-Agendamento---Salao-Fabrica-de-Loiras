<?php

namespace App\Libraries;

use App\Models\ProfessionalModel;
use App\Models\ProfessionalServiceModel;
use App\Models\ProfessionalWorkingHourModel;
use App\Models\ServiceModel;
use DateTime;

class ProfessionalAvailabilityService
{
    public function availableForSlot(int $unitId, array $serviceIds, string $chosenDate): array
    {
        $groups = $this->serviceGroups($serviceIds);
        $available = [];
        foreach ($groups as $group) {
            foreach ($this->availableForServices($unitId, $group['service_ids'], $chosenDate) as $professional) {
                $available[$professional->id] = $professional;
            }
        }

        return array_values($available);
    }

    public function renderOptions(int $unitId, array $serviceIds, string $chosenDate): string
    {
        $groups = $this->serviceGroups($serviceIds);
        if (empty($groups)) {
            return '<div class="alert alert-warning">Serviços inválidos.</div>';
        }

        $html = '';
        foreach ($groups as $group) {
            $options = '';
            foreach ($this->availableForServices($unitId, $group['service_ids'], $chosenDate) as $professional) {
                $options .= '<button type="button" class="professional-choice" data-service-ids="'
                    . esc(implode(',', $group['service_ids']), 'attr') . '" data-professional-id="'
                    . $professional->id . '" aria-pressed="false">'
                    . esc($professional->name)
                    . '</button>';
            }

            $serviceNames = esc(implode(' + ', $group['service_names']));
            $html .= '<div class="professional-group" data-service-ids="' . esc(implode(',', $group['service_ids']), 'attr') . '">'
                . '<div class="professional-group-title"><span class="professional-group-kicker">Profissional responsável</span><span class="professional-group-service">' . $serviceNames . '</span></div>';
            $html .= $options === ''
                ? '<div class="alert alert-warning">Não há uma profissional disponível para todos esses serviços neste horário.</div>'
                : '<div class="professionals-grid" role="group" aria-label="Profissionais disponíveis">' . $options . '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    public function hasAvailableAssignment(int $unitId, array $serviceIds, string $chosenDate): bool
    {
        $groups = $this->serviceGroups($serviceIds);
        $candidates = [];
        foreach ($groups as $group) {
            $candidates[] = $this->availableForServices($unitId, $group['service_ids'], $chosenDate);
        }

        return !empty($groups) && $this->hasDistinctCandidate($candidates, 0, []);
    }

    public function isAvailableForAssignments(int $unitId, array $serviceIds, array $assignments, string $chosenDate): bool
    {
        $groups = $this->serviceGroups($serviceIds);
        if (empty($groups) || count($assignments) !== count(array_unique(array_map('intval', $serviceIds)))) {
            return false;
        }

        $usedProfessionals = [];
        foreach ($groups as $group) {
            $professionalId = null;
            foreach ($group['service_ids'] as $serviceId) {
                if (!isset($assignments[$serviceId])) {
                    return false;
                }
                $candidateId = (int) $assignments[$serviceId];
                if ($professionalId === null) {
                    $professionalId = $candidateId;
                } elseif ($professionalId !== $candidateId) {
                    return false;
                }
            }

            if (isset($usedProfessionals[$professionalId]) || !$this->isProfessionalAvailable($unitId, $professionalId, $chosenDate)) {
                return false;
            }
            if (!$this->professionalSupports($professionalId, $group['service_ids'])) {
                return false;
            }
            $usedProfessionals[$professionalId] = true;
        }

        return true;
    }

    public function isAvailable(int $unitId, int $professionalId, array $serviceIds, string $chosenDate): bool
    {
        $assignments = [];
        foreach (array_unique(array_map('intval', $serviceIds)) as $serviceId) {
            $assignments[$serviceId] = $professionalId;
        }

        return $this->isAvailableForAssignments($unitId, $serviceIds, $assignments, $chosenDate);
    }

    private function serviceGroups(array $serviceIds): array
    {
        $serviceIds = array_values(array_unique(array_filter(array_map('intval', $serviceIds))));
        if (empty($serviceIds)) {
            return [];
        }

        $services = model(ServiceModel::class)->whereIn('id', $serviceIds)->where('active', 1)->findAll();
        if (count($services) !== count($serviceIds)) {
            return [];
        }

        $groups = [];
        foreach ($services as $service) {
            $groupName = trim((string) ($service->service_group ?: 'Geral'));
            $key = mb_strtolower($groupName);
            $groups[$key]['name'] = $groupName;
            $groups[$key]['service_ids'][] = (int) $service->id;
            $groups[$key]['service_names'][] = $service->name;
        }

        return array_values($groups);
    }

    private function availableForServices(int $unitId, array $serviceIds, string $chosenDate): array
    {
        $available = [];
        foreach (model(ProfessionalModel::class)->where(['active' => 1, 'unit_id' => $unitId])->orderBy('name', 'ASC')->findAll() as $professional) {
            if ($this->professionalSupports((int) $professional->id, $serviceIds)
                && $this->isProfessionalAvailable($unitId, (int) $professional->id, $chosenDate)) {
                $available[] = $professional;
            }
        }

        return $available;
    }

    private function professionalSupports(int $professionalId, array $serviceIds): bool
    {
        return model(ProfessionalServiceModel::class)
            ->where('professional_id', $professionalId)
            ->whereIn('service_id', $serviceIds)
            ->countAllResults() === count(array_unique(array_map('intval', $serviceIds)));
    }

    private function isProfessionalAvailable(int $unitId, int $professionalId, string $chosenDate): bool
    {
        $date = DateTime::createFromFormat('Y-m-d H:i', $chosenDate);
        if (!$date) {
            return false;
        }

        $professional = model(ProfessionalModel::class)->where(['id' => $professionalId, 'active' => 1, 'unit_id' => $unitId])->first();
        if (!$professional) {
            return false;
        }

        $weekday = (int) $date->format('N') - 1;
        $workingHour = model(ProfessionalWorkingHourModel::class)
            ->where(['professional_id' => $professionalId, 'weekday' => $weekday, 'active' => 1])
            ->where('start_time <=', $date->format('H:i:s'))
            ->where('end_time >', $date->format('H:i:s'))
            ->first();
        if (!$workingHour) {
            return false;
        }

        $dateValue = $chosenDate . ':00';
        $query = db_connect()->query(
            'SELECT COUNT(DISTINCT schedules.id) AS total
             FROM schedules
             LEFT JOIN schedule_services assigned_services ON assigned_services.schedule_id = schedules.id
             WHERE schedules.canceled = 0
               AND schedules.chosen_date = ?
               AND (assigned_services.professional_id = ?
                    OR (schedules.professional_id = ? AND NOT EXISTS (
                        SELECT 1 FROM schedule_services existing_services
                        WHERE existing_services.schedule_id = schedules.id
                          AND existing_services.professional_id IS NOT NULL
                    )))',
            [$dateValue, $professionalId, $professionalId]
        );

        return (int) ($query->getRow()->total ?? 0) === 0;
    }

    private function hasDistinctCandidate(array $candidates, int $index, array $used): bool
    {
        if ($index >= count($candidates)) {
            return true;
        }

        foreach ($candidates[$index] as $professional) {
            if (!isset($used[$professional->id]) && $this->hasDistinctCandidate($candidates, $index + 1, $used + [$professional->id => true])) {
                return true;
            }
        }

        return false;
    }
}