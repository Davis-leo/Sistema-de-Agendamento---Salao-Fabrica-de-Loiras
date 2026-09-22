<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Entities\Schedule;
use App\Models\ScheduleModel;
use App\Models\ServiceModel;
use App\Models\UnitModel;
use App\Models\ProfessionalModel;
use App\Libraries\ProfessionalAvailabilityService;
use CodeIgniter\Events\Events;
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
            'professionals' => model(ProfessionalModel::class)->where('active', 1)->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function create(): RedirectResponse
    {
        $this->checkMethod('post');

        $request = $this->request->getPost([
            'customer_name',
            'customer_phone',
            'customer_email',
            'unit_id',
            'service_id',
            'professional_id',
            'chosen_date',
        ]);

        $rules = [
            'customer_name'  => 'required|max_length[120]',
            'customer_phone' => 'required|max_length[30]',
            'customer_email' => 'permit_empty|valid_email|max_length[120]',
            'unit_id'        => 'required|is_natural_no_zero',
            'service_id'     => 'required|is_natural_no_zero',
            'professional_id'=> 'required|is_natural_no_zero',
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

        $professional = model(ProfessionalModel::class)->where('active', 1)->find($request['professional_id']);
        if (!$professional || !(new ProfessionalAvailabilityService())->isAvailable((int) $unit->id, (int) $professional->id, (int) $service->id, $chosenDate)) {
            return redirect()->back()->withInput()->with('danger', 'O profissional não está disponível nesse horário.');
        }

        $scheduleModel = model(ScheduleModel::class);
        $schedule = new Schedule([
            'unit_id'       => $unit->id,
            'service_id'    => $service->id,
            'professional_id'=> $professional->id,
            'user_id'       => null,
            'customer_name' => $request['customer_name'],
            'customer_phone'=> $request['customer_phone'],
            'customer_email'=> $request['customer_email'] ?: null,
            'created_by'    => auth()->user()->id,
            'chosen_date'   => $chosenDate,
        ]);

        if (!$scheduleModel->insert($schedule)) {
            return redirect()->back()->withInput()->with('danger', 'Não foi possível criar o agendamento.');
        }

        if ($request['customer_email']) {
            Events::trigger('schedule_created', $request['customer_email'], $scheduleModel->getSchedule($scheduleModel->getInsertID()));
        }

        return redirect()->route('super.schedules.new')->with('success', 'Agendamento criado com sucesso.');
    }

    public function cancel(int $id): RedirectResponse
    {
        $this->checkMethod('delete');

        $scheduleModel = model(ScheduleModel::class);
        $schedule = $scheduleModel->where('id', $id)->where('canceled', 0)->first();

        if (!$schedule || !$schedule->canBeCanceled()) {
            return redirect()->back()->with('danger', 'Esse agendamento não pode mais ser cancelado.');
        }

        $schedule->canceled = 1;
        $schedule->canceled_by = auth()->user()->id;
        $schedule->cancel_reason = $this->request->getPost('cancel_reason') ?: 'Cancelado pelo administrador.';
        $scheduleModel->save($schedule);

        $schedule = $scheduleModel->getSchedule($id);
        $email = $schedule->user_id ? auth()->user()->email : $schedule->customer_email;
        if ($email) {
            Events::trigger('schedule_canceled', $email, $schedule);
        }

        return redirect()->back()->with('success', 'Agendamento cancelado com sucesso.');
    }

    public function confirm(int $id): RedirectResponse
    {
        $this->checkMethod('post');
        $amount = (float) $this->request->getPost('service_amount');
        if ($amount < 0) {
            return redirect()->back()->with('danger', 'Informe um valor válido para o serviço.');
        }
        $model = model(ScheduleModel::class);
        $schedule = $model->where('id', $id)->where('canceled', 0)->where('confirmed', 0)->first();
        if (!$schedule) {
            return redirect()->back()->with('danger', 'Este agendamento não pode ser confirmado.');
        }
        $professional = model(ProfessionalModel::class)->find($schedule->professional_id);
        if (!$professional) {
            return redirect()->back()->with('danger', 'O profissional do agendamento não foi encontrado.');
        }
        $schedule->confirmed = 1;
        $schedule->finished = 1;
        $schedule->service_amount = $amount;
        $schedule->commission_percentage = $professional->commission_percentage;
        $schedule->commission_amount = round($amount * ((float) $professional->commission_percentage / 100), 2);
        $schedule->confirmed_at = date('Y-m-d H:i:s');
        $schedule->confirmed_by = auth()->user()->id;
        $model->save($schedule);
        return redirect()->back()->with('success', 'Atendimento confirmado e comissão registrada.');
    }
}
