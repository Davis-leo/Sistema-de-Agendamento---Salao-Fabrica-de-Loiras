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
                'services.name AS service',
                'COALESCE(users.username, schedules.customer_name) AS user',
            ])
            ->join('units', 'units.id = schedules.unit_id')
            ->join('services', 'services.id = schedules.service_id')
            ->join('users', 'users.id = schedules.user_id', 'left')
            ->where('schedules.canceled', 0)
            ->where('schedules.finished', 0)
            ->where('schedules.chosen_date >=', Time::now()->toDateTimeString())
            ->orderBy('schedules.chosen_date', 'ASC')
            ->findAll(5);

        $data = [
            'title'             => 'Painel',
            'activeUnits'       => model(UnitModel::class)->where('active', 1)->countAllResults(),
            'activeServices'    => model(ServiceModel::class)->where('active', 1)->countAllResults(),
            'totalSchedules'    => $scheduleModel->countAllResults(),
            'canceledSchedules' => $scheduleModel->where('canceled', 1)->countAllResults(),
            'nextSchedules'     => $nextSchedules,
        ];   

        return view('Back/Home/index', $data);
    }
}
