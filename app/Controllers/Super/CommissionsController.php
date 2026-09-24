<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Models\CommissionSettlementModel;
use App\Models\ScheduleModel;
use CodeIgniter\HTTP\RedirectResponse;

class CommissionsController extends BaseController
{
    public function index(): string
    {
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $scheduleModel = model(ScheduleModel::class);
        $pendingSchedules = $scheduleModel
            ->select([
                'schedules.id',
                'schedules.chosen_date',
                'COALESCE(NULLIF(schedules.customer_name, ""), users.username) AS customer_name',
                'schedules.customer_phone',
                'schedules.service_id AS primary_service_id',
                'units.name AS unit',
                'COALESCE((SELECT GROUP_CONCAT(selected_services.name ORDER BY selected_services.name SEPARATOR ", ") FROM schedule_services JOIN services AS selected_services ON selected_services.id = schedule_services.service_id WHERE schedule_services.schedule_id = schedules.id), services.name) AS service',
                'COALESCE((SELECT GROUP_CONCAT(DISTINCT assigned_professionals.name ORDER BY assigned_professionals.name SEPARATOR ", ") FROM schedule_services assigned_services JOIN professionals AS assigned_professionals ON assigned_professionals.id = assigned_services.professional_id WHERE assigned_services.schedule_id = schedules.id), professionals.name) AS professional',
            ])
            ->join('units', 'units.id = schedules.unit_id')
            ->join('services', 'services.id = schedules.service_id')
            ->join('users', 'users.id = schedules.user_id', 'left')
            ->join('professionals', 'professionals.id = schedules.professional_id', 'left')
            ->where('schedules.confirmed', 0)
            ->where('schedules.canceled', 0)
            ->where('DATE(schedules.chosen_date) >=', $weekStart)
            ->where('DATE(schedules.chosen_date) <=', $weekEnd)
            ->orderBy('schedules.chosen_date', 'ASC')
            ->findAll();

        $pendingServices = [];
        $pendingIds = array_map(static fn ($schedule) => (int) $schedule->id, $pendingSchedules);
        if ($pendingIds) {
            $serviceRows = model(\App\Models\ScheduleServiceModel::class)
                ->select('schedule_services.schedule_id, schedule_services.service_id, services.name')
                ->join('services', 'services.id = schedule_services.service_id')
                ->whereIn('schedule_services.schedule_id', $pendingIds)
                ->orderBy('services.name', 'ASC')
                ->findAll();
            foreach ($serviceRows as $serviceRow) {
                $pendingServices[(int) $serviceRow['schedule_id']][] = $serviceRow;
            }
        }
        foreach ($pendingSchedules as $schedule) {
            if (!isset($pendingServices[(int) $schedule->id])) {
                $pendingServices[(int) $schedule->id] = [[
                    'service_id' => (int) $schedule->primary_service_id,
                    'name' => $schedule->service,
                ]];
            }
        }

        $rows = $scheduleModel
            ->select('COALESCE(schedule_services.professional_id, schedules.professional_id) AS professional_id, professionals.name AS professional, COUNT(DISTINCT schedules.id) AS appointments, SUM(COALESCE(schedule_services.commission_amount, schedules.commission_amount)) AS total')
            ->join('schedule_services', 'schedule_services.schedule_id = schedules.id', 'left')
            ->join('professionals', 'professionals.id = COALESCE(schedule_services.professional_id, schedules.professional_id)')
            ->where('schedules.confirmed', 1)
            ->where('schedules.canceled', 0)
            ->where('DATE(schedules.confirmed_at) >=', $weekStart)
            ->where('DATE(schedules.confirmed_at) <=', $weekEnd)
            ->groupBy([
                'COALESCE(schedule_services.professional_id, schedules.professional_id)',
                'professionals.name',
            ])
            ->orderBy('professionals.name', 'ASC')
            ->findAll();
        $settlements = model(CommissionSettlementModel::class)
            ->where(['week_start' => $weekStart, 'week_end' => $weekEnd])
            ->findAll();
        $paid = [];
        foreach ($settlements as $settlement) {
            $paid[$settlement['professional_id']] = $settlement;
        }
        return view('Back/Commissions/index', compact('rows', 'paid', 'pendingSchedules', 'pendingServices', 'weekStart', 'weekEnd'));
    }

    public function pay(int $professionalId): RedirectResponse
    {
        $this->checkMethod('post');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $scheduleModel = model(ScheduleModel::class);
        $total = $scheduleModel
            ->select('SUM(CASE WHEN schedule_services.professional_id IS NOT NULL THEN schedule_services.commission_amount ELSE schedules.commission_amount END) AS commission_total')
            ->join('schedule_services', 'schedule_services.schedule_id = schedules.id', 'left')
            ->where(['schedules.confirmed' => 1, 'schedules.canceled' => 0])
            ->groupStart()
                ->where('schedule_services.professional_id', $professionalId)
                ->orGroupStart()
                    ->where('schedule_services.professional_id IS NULL', null, false)
                    ->where('schedules.professional_id', $professionalId)
                ->groupEnd()
            ->groupEnd()
            ->where('DATE(confirmed_at) >=', $weekStart)
            ->where('DATE(confirmed_at) <=', $weekEnd)
            ->first();
        $model = model(CommissionSettlementModel::class);
        $existing = $model->where(['professional_id' => $professionalId, 'week_start' => $weekStart])->first();
        $data = ['professional_id' => $professionalId, 'week_start' => $weekStart, 'week_end' => $weekEnd, 'total_amount' => (float) ($total->commission_total ?? 0), 'paid_at' => date('Y-m-d H:i:s'), 'paid_by' => auth()->user()->id];
        if ($existing) {
            $model->update($existing['id'], $data);
        } else {
            $model->insert($data);
        }
        return redirect()->back()->with('success', 'Comissão marcada como paga.');
    }
}
