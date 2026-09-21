<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Entities\Schedule;
use App\Models\ScheduleModel;
use App\Models\ServiceModel;
use App\Models\UnitModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;
use DateTime;

class SchedulesController extends BaseController
{
    public function new(): string
    {
        return view('Back/Schedules/new', [
            'title'    => 'Novo agendamento',
            'units'    => model(UnitModel::class)->where('active', 1)->orderBy('name', 'ASC')->findAll(),
            'services' => model(ServiceModel::class)->where('active', 1)->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function create(): RedirectResponse
    {
        $this->checkMethod('post');

        $request = $this->request->getPost([
            'customer_name',
            'customer_phone',
            'unit_id',
            'service_id',
            'chosen_date',
        ]);

        $rules = [
            'customer_name'  => 'required|max_length[120]',
            'customer_phone' => 'required|max_length[30]',
            'unit_id'        => 'required|is_natural_no_zero',
            'service_id'     => 'required|is_natural_no_zero',
            'chosen_date'    => 'required',
        ];

        if (!$this->validateData($request, $rules)) {
            return redirect()->back()->withInput()->with('danger', 'Verifique os dados do agendamento.')->with('errorsValidation', $this->validator->getErrors());
        }

        $unit = model(UnitModel::class)->where('active', 1)->find($request['unit_id']);
        $service = model(ServiceModel::class)->where('active', 1)->find($request['service_id']);

        if (!$unit || !$service) {
            return redirect()->back()->withInput()->with('danger', 'A unidade ou o serviço selecionado não está disponível.');
        }

        $unitServices = array_map('intval', $unit->services ?? []);
        if (!in_array((int) $service->id, $unitServices, true)) {
            return redirect()->back()->withInput()->with('danger', 'O serviço selecionado não está associado à unidade.');
        }

        $chosenDate = str_replace('T', ' ', $request['chosen_date']);
        $date = DateTime::createFromFormat('Y-m-d H:i', $chosenDate);

        if (!$date || $date->format('Y-m-d H:i') !== $chosenDate || Time::parse($chosenDate)->isBefore(Time::now())) {
            return redirect()->back()->withInput()->with('danger', 'Escolha uma data e horário futuros válidos.');
        }

        $scheduleModel = model(ScheduleModel::class);
        if (!$scheduleModel->chosenDateisFree((int) $unit->id, $chosenDate)) {
            return redirect()->back()->withInput()->with('danger', 'A data e o horário escolhidos não estão mais disponíveis.');
        }

        $schedule = new Schedule([
            'unit_id'       => $unit->id,
            'service_id'    => $service->id,
            'user_id'       => null,
            'customer_name' => $request['customer_name'],
            'customer_phone'=> $request['customer_phone'],
            'created_by'    => auth()->user()->id,
            'chosen_date'   => $chosenDate,
        ]);

        if (!$scheduleModel->insert($schedule)) {
            return redirect()->back()->withInput()->with('danger', 'Não foi possível criar o agendamento.');
        }

        return redirect()->route('super.schedules.new')->with('success', 'Agendamento criado com sucesso.');
    }

    public function cancel(int $id): RedirectResponse
    {
        $this->checkMethod('delete');

        $scheduleModel = model(ScheduleModel::class);
        $schedule = $scheduleModel
            ->where('id', $id)
            ->where('created_by', auth()->user()->id)
            ->where('user_id IS NULL', null, false)
            ->where('canceled', 0)
            ->first();

        if (!$schedule || !$schedule->canBeCanceled()) {
            return redirect()->back()->with('danger', 'Esse agendamento não pode mais ser cancelado.');
        }

        $schedule->canceled = 1;
        $scheduleModel->save($schedule);

        return redirect()->back()->with('success', 'Agendamento cancelado com sucesso.');
    }
}
