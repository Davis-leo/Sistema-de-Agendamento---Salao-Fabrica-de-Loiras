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
                'schedules.customer_name',
                'schedules.customer_phone',
                'units.name AS unit',
                'COALESCE((SELECT GROUP_CONCAT(selected_services.name ORDER BY selected_services.name SEPARATOR ", ") FROM schedule_services JOIN services AS selected_services ON selected_services.id = schedule_services.service_id WHERE schedule_services.schedule_id = schedules.id), services.name) AS service',
                'professionals.name AS professional',
            ])
            ->join('units', 'units.id = schedules.unit_id')
            ->join('services', 'services.id = schedules.service_id')
            ->join('professionals', 'professionals.id = schedules.professional_id', 'left')
            ->where('schedules.confirmed', 0)
            ->where('schedules.canceled', 0)
            ->where('DATE(schedules.chosen_date) >=', $weekStart)
            ->where('DATE(schedules.chosen_date) <=', $weekEnd)
            ->orderBy('schedules.chosen_date', 'ASC')
            ->findAll();

        $rows = $scheduleModel
            ->select('professionals.id AS professional_id, professionals.name AS professional, COUNT(schedules.id) AS appointments, SUM(schedules.commission_amount) AS total')
            ->join('professionals', 'professionals.id = schedules.professional_id')
            ->where('schedules.confirmed', 1)
            ->where('schedules.canceled', 0)
            ->where('DATE(schedules.confirmed_at) >=', $weekStart)
            ->where('DATE(schedules.confirmed_at) <=', $weekEnd)
            ->groupBy('schedules.professional_id')
            ->orderBy('professionals.name', 'ASC')
            ->findAll();
        $settlements = model(CommissionSettlementModel::class)
            ->where(['week_start' => $weekStart, 'week_end' => $weekEnd])
            ->findAll();
        $paid = [];
        foreach ($settlements as $settlement) {
            $paid[$settlement['professional_id']] = $settlement;
        }
        return view('Back/Commissions/index', compact('rows', 'paid', 'pendingSchedules', 'weekStart', 'weekEnd'));
    }

    public function pay(int $professionalId): RedirectResponse
    {
        $this->checkMethod('post');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $scheduleModel = model(ScheduleModel::class);
        $total = $scheduleModel
            ->selectSum('commission_amount')
            ->where(['professional_id' => $professionalId, 'confirmed' => 1, 'canceled' => 0])
            ->where('DATE(confirmed_at) >=', $weekStart)
            ->where('DATE(confirmed_at) <=', $weekEnd)
            ->first();
        $model = model(CommissionSettlementModel::class);
        $existing = $model->where(['professional_id' => $professionalId, 'week_start' => $weekStart])->first();
        $data = ['professional_id' => $professionalId, 'week_start' => $weekStart, 'week_end' => $weekEnd, 'total_amount' => (float) ($total->commission_amount ?? 0), 'paid_at' => date('Y-m-d H:i:s'), 'paid_by' => auth()->user()->id];
        if ($existing) {
            $model->update($existing['id'], $data);
        } else {
            $model->insert($data);
        }
        return redirect()->back()->with('success', 'Comissão marcada como paga.');
    }
}
