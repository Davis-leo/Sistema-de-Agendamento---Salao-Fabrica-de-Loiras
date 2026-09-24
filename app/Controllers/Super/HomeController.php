<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Models\ScheduleModel;
use App\Models\ServiceModel;
use App\Models\UnitModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;

class HomeController extends BaseController
{
    public function index()
    {
        $scheduleModel = model(ScheduleModel::class);

        $nextSchedules = $scheduleModel
            ->select([
                'schedules.*',
                'DATE_FORMAT(schedules.chosen_date, "%d/%m/%Y às %H:%i") AS formated_chosen_date',
                'units.name AS unit',
                'COALESCE((SELECT GROUP_CONCAT(selected_services.name ORDER BY selected_services.name SEPARATOR ", ") FROM schedule_services JOIN services AS selected_services ON selected_services.id = schedule_services.service_id WHERE schedule_services.schedule_id = schedules.id), services.name) AS service',
                'COALESCE((SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(assigned_professionals.name, professionals.name), " (", (SELECT GROUP_CONCAT(DISTINCT grouped_services.name ORDER BY grouped_services.name SEPARATOR ", ") FROM schedule_services AS grouped_assignments JOIN services AS grouped_services ON grouped_services.id = grouped_assignments.service_id WHERE grouped_assignments.schedule_id = schedules.id AND COALESCE(grouped_assignments.professional_id, schedules.professional_id) = COALESCE(schedule_services.professional_id, schedules.professional_id)), ")") ORDER BY COALESCE(assigned_professionals.name, professionals.name) SEPARATOR " - ") FROM schedule_services LEFT JOIN professionals AS assigned_professionals ON assigned_professionals.id = schedule_services.professional_id WHERE schedule_services.schedule_id = schedules.id), CONCAT(services.name, " - ", COALESCE(professionals.name, "Profissional não definido"))) AS service_professionals',
                'professionals.name AS professional',
                'COALESCE(users.username, schedules.customer_name) AS user',
            ])
            ->join('units', 'units.id = schedules.unit_id')
            ->join('services', 'services.id = schedules.service_id')
            ->join('professionals', 'professionals.id = schedules.professional_id', 'left')
            ->join('users', 'users.id = schedules.user_id', 'left')
            ->where('schedules.canceled', 0)
            ->where('schedules.finished', 0)
            ->where('schedules.chosen_date >=', Time::now()->toDateTimeString())
            ->orderBy('schedules.chosen_date', 'ASC')
            ->findAll(5);

        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $weeklyCommissions = $scheduleModel
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

        $unitFinancialRows = model(ScheduleModel::class)
            ->select('schedules.unit_id, SUM(schedules.service_amount) AS gross_amount, SUM(schedules.commission_amount) AS commission_amount')
            ->join('units', 'units.id = schedules.unit_id')
            ->where('schedules.confirmed', 1)
            ->where('schedules.canceled', 0)
            ->where('DATE(schedules.confirmed_at) >=', $weekStart)
            ->where('DATE(schedules.confirmed_at) <=', $weekEnd)
            ->groupBy('schedules.unit_id')
            ->findAll();

        $financialByUnit = [];
        foreach ($unitFinancialRows as $row) {
            $financialByUnit[(int) $row->unit_id] = [
                'gross' => (float) $row->gross_amount,
                'commission' => (float) $row->commission_amount,
            ];
        }

        $unitFinancials = [];
        foreach (model(UnitModel::class)->where('active', 1)->orderBy('name', 'ASC')->findAll() as $unit) {
            $financial = $financialByUnit[(int) $unit->id] ?? ['gross' => 0.0, 'commission' => 0.0];
            $unitFinancials[] = (object) [
                'name' => $unit->name,
                'gross' => $financial['gross'],
                'commission' => $financial['commission'],
                'net' => $financial['gross'] - $financial['commission'],
            ];
        }

        $data = [
            'title'             => 'Painel',
            'activeUnits'       => model(UnitModel::class)->where('active', 1)->countAllResults(),
            'activeServices'    => model(ServiceModel::class)->where('active', 1)->countAllResults(),
            'totalSchedules'    => $scheduleModel->countAllResults(),
            'canceledSchedules' => $scheduleModel->where('canceled', 1)->countAllResults(),
            'nextSchedules'     => $nextSchedules,
            'weeklyCommissions' => $weeklyCommissions,
            'weekStart'         => $weekStart,
            'weekEnd'           => $weekEnd,
            'unitFinancials'    => $unitFinancials,
        ];   

        return view('Back/Home/index', $data);
    }
}
