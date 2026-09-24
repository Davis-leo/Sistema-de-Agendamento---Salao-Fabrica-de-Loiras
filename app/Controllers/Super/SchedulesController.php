<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Entities\Schedule;
use App\Models\ScheduleModel;
use App\Models\ServiceModel;
use App\Models\UnitModel;
use App\Models\ProfessionalModel;
use App\Models\ScheduleServiceModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
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
            'service_ids',
            'professional_assignments',
            'chosen_date',
        ]);

        $rules = [
            'customer_name'  => 'required|max_length[120]',
            'customer_phone' => 'required|exact_length[15]',
            'customer_email' => 'permit_empty|valid_email|max_length[120]',
            'unit_id'        => 'required|is_natural_no_zero',
            'service_ids'    => 'required',
            'chosen_date'    => 'required',
        ];

        $serviceIds = array_values(array_unique(array_filter(array_map('intval', (array) ($request['service_ids'] ?? [])))));
        $request['service_ids'] = $serviceIds;

        if (empty($serviceIds) || !$this->validateData($request, $rules)) {
            return redirect()->back()->withInput()->with('danger', 'Verifique os dados do agendamento.')->with('errorsValidation', $this->validator->getErrors());
        }

        $unit = model(UnitModel::class)->where('active', 1)->find($request['unit_id']);
        $services = model(ServiceModel::class)->whereIn('id', $serviceIds)->where('active', 1)->findAll();

        if (!$unit || count($services) !== count($serviceIds)) {
            return redirect()->back()->withInput()->with('danger', 'A unidade ou o serviço selecionado não está disponível.');
        }

        $unitServices = array_map('intval', $unit->services ?? []);
        if (count(array_diff($serviceIds, $unitServices)) > 0) {
            return redirect()->back()->withInput()->with('danger', 'Um ou mais serviços não estão associados à unidade.');
        }

        $chosenDate = str_replace('T', ' ', $request['chosen_date']);
        $date = DateTime::createFromFormat('Y-m-d H:i', $chosenDate);

        if (!$date || $date->format('Y-m-d H:i') !== $chosenDate || Time::parse($chosenDate)->isBefore(Time::now())) {
            return redirect()->back()->withInput()->with('danger', 'Escolha uma data e horário futuros válidos.');
        }

        $assignments = [];
        foreach ((array) ($request['professional_assignments'] ?? []) as $serviceId => $professionalId) {
            $assignments[(int) $serviceId] = (int) $professionalId;
        }
        if (!(new ProfessionalAvailabilityService())->isAvailableForAssignments((int) $unit->id, $serviceIds, $assignments, $chosenDate)) {
            return redirect()->back()->withInput()->with('danger', 'Escolha uma profissional disponível para cada serviço.');
        }
        $primaryProfessionalId = (int) reset($assignments);

        $scheduleModel = model(ScheduleModel::class);
        $schedule = new Schedule([
            'unit_id'       => $unit->id,
            'service_id'    => $serviceIds[0],
            'professional_id'=> $primaryProfessionalId,
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

        $scheduleServiceModel = model(ScheduleServiceModel::class);
        foreach ($serviceIds as $serviceId) {
            $scheduleServiceModel->insert([
                'schedule_id' => $scheduleModel->getInsertID(),
                'service_id'  => $serviceId,
                    'professional_id' => $assignments[$serviceId],
            ]);
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
        $model = model(ScheduleModel::class);
        $schedule = $model->where('id', $id)->where('canceled', 0)->where('confirmed', 0)->first();
        if (!$schedule) {
            return redirect()->back()->with('danger', 'Este agendamento não pode ser confirmado.');
        }
        $scheduleServiceModel = model(ScheduleServiceModel::class);
        $scheduleServices = $scheduleServiceModel->where('schedule_id', $id)->findAll();
        $hasScheduleServiceRows = !empty($scheduleServices);
        if (empty($scheduleServices)) {
            $scheduleServices = [['schedule_id' => $id, 'service_id' => $schedule->service_id]];
        }

        $amounts = (array) $this->request->getPost('service_amount');
        $percentages = (array) $this->request->getPost('commission_percentage');
        $totalAmount = 0.0;
        $totalCommission = 0.0;
        $financialItems = [];

        foreach ($scheduleServices as $item) {
            $serviceId = (int) $item['service_id'];
            $professionalId = (int) ($item['professional_id'] ?? $schedule->professional_id);
            if (!model(ProfessionalModel::class)->where(['id' => $professionalId, 'active' => 1])->first()) {
                return redirect()->back()->with('danger', 'Uma profissional do agendamento não foi encontrada.');
            }
            $amountValue = $amounts[$serviceId] ?? null;
            $percentageValue = $percentages[$serviceId] ?? null;
            if (!is_numeric($amountValue) || !is_numeric($percentageValue)) {
                return redirect()->back()->with('danger', 'Informe o valor e a comissão de todos os serviços.');
            }

            $amount = (float) $amountValue;
            $percentage = (float) $percentageValue;
            if ($amount < 0 || $percentage < 0 || $percentage > 100) {
                return redirect()->back()->with('danger', 'Informe valores e comissões válidos.');
            }

            $commission = round($amount * ($percentage / 100), 2);
            $totalAmount += $amount;
            $totalCommission += $commission;
            $financialItems[$serviceId] = [
                'service_amount' => $amount,
                'commission_percentage' => $percentage,
                'commission_amount' => $commission,
            ];
        }

        $schedule->confirmed = 1;
        $schedule->finished = 1;
        $schedule->service_amount = $totalAmount;
        $schedule->commission_percentage = $totalAmount > 0 ? round(($totalCommission / $totalAmount) * 100, 2) : 0;
        $schedule->commission_amount = $totalCommission;
        $schedule->confirmed_at = date('Y-m-d H:i:s');
        $schedule->confirmed_by = auth()->user()->id;

        $db = db_connect();
        $db->transStart();
        foreach ($financialItems as $serviceId => $financialItem) {
            $updated = $scheduleServiceModel
                ->where('schedule_id', $id)
                ->where('service_id', $serviceId)
                ->set($financialItem)
                ->update();
            if (!$updated && !$hasScheduleServiceRows) {
                $scheduleServiceModel->insert(array_merge([
                    'schedule_id' => $id,
                    'service_id' => $serviceId,
                ], $financialItem));
            }
        }
        $model->save($schedule);
        $db->transComplete();
        if (!$db->transStatus()) {
            return redirect()->back()->with('danger', 'Não foi possível registrar a confirmação.');
        }

        $confirmedSchedule = $model->getSchedule($id);
        $email = $schedule->customer_email;
        if ($schedule->user_id) {
            $identity = model(UserIdentityModel::class)
                ->where(['user_id' => $schedule->user_id, 'type' => 'email_password'])
                ->first();
            $email = is_array($identity) ? ($identity['secret'] ?? null) : ($identity?->secret ?? null);
        }
        if ($email) {
            Events::trigger('schedule_confirmed', $email, $confirmedSchedule);
        }

        return redirect()->back()->with('success', 'Atendimento confirmado e comissão registrada.');
    }

    public function edit(int $id): string|RedirectResponse
    {
        $schedule = model(ScheduleModel::class)
            ->select('schedules.*, users.username AS user')
            ->join('users', 'users.id = schedules.user_id', 'left')
            ->where('schedules.id', $id)
            ->where('schedules.canceled', 0)
            ->first();
        if (!$schedule) {
            return redirect()->back()->with('danger', 'Agendamento não encontrado.');
        }

        $serviceItems = model(ScheduleServiceModel::class)
            ->select('schedule_services.*, services.name AS service_name, professionals.name AS professional_name')
            ->join('services', 'services.id = schedule_services.service_id')
            ->join('professionals', 'professionals.id = schedule_services.professional_id', 'left')
            ->where('schedule_services.schedule_id', $id)
            ->orderBy('services.name', 'ASC')
            ->findAll();
        if (!$serviceItems) {
            $serviceItems = [[
                'service_id' => $schedule->service_id,
                'service_name' => 'Serviço principal',
                'professional_name' => $schedule->professional,
                'service_amount' => $schedule->service_amount,
                'commission_percentage' => $schedule->commission_percentage,
            ]];
        }

        return view('Back/Schedules/edit', [
            'title' => 'Editar agendamento',
            'schedule' => $schedule,
            'serviceItems' => $serviceItems,
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->checkMethod('post');
        $model = model(ScheduleModel::class);
        $schedule = $model->where('id', $id)->where('canceled', 0)->first();
        if (!$schedule) {
            return redirect()->back()->with('danger', 'Agendamento não encontrado.');
        }

        $scheduleServiceModel = model(ScheduleServiceModel::class);
        $items = $scheduleServiceModel->where('schedule_id', $id)->findAll();
        if (!$items) {
            $items = [['service_id' => $schedule->service_id]];
        }
        $amounts = (array) $this->request->getPost('service_amount');
        $percentages = (array) $this->request->getPost('commission_percentage');
        $totalAmount = 0.0;
        $totalCommission = 0.0;
        $financialItems = [];

        foreach ($items as $item) {
            $serviceId = (int) $item['service_id'];
            if (!is_numeric($amounts[$serviceId] ?? null) || !is_numeric($percentages[$serviceId] ?? null)) {
                return redirect()->back()->withInput()->with('danger', 'Informe valores e comissões válidos para todos os serviços.');
            }
            $amount = (float) $amounts[$serviceId];
            $percentage = (float) $percentages[$serviceId];
            if ($amount < 0 || $percentage < 0 || $percentage > 100) {
                return redirect()->back()->withInput()->with('danger', 'Informe valores e comissões válidos.');
            }
            $commission = round($amount * ($percentage / 100), 2);
            $totalAmount += $amount;
            $totalCommission += $commission;
            $financialItems[$serviceId] = [
                'service_amount' => $amount,
                'commission_percentage' => $percentage,
                'commission_amount' => $commission,
            ];
        }

        $schedule->service_amount = $totalAmount;
        $schedule->commission_percentage = $totalAmount > 0 ? round(($totalCommission / $totalAmount) * 100, 2) : 0;
        $schedule->commission_amount = $totalCommission;
        $db = db_connect();
        $db->transStart();
        foreach ($financialItems as $serviceId => $financialItem) {
            $updated = $scheduleServiceModel->where('schedule_id', $id)->where('service_id', $serviceId)->set($financialItem)->update();
            if (!$updated && count($items) === 1 && (int) $items[0]['service_id'] === $serviceId) {
                $scheduleServiceModel->insert(array_merge(['schedule_id' => $id, 'service_id' => $serviceId], $financialItem));
            }
        }
        $model->save($schedule);
        $db->transComplete();
        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('danger', 'Não foi possível atualizar o agendamento.');
        }

        return redirect()->back()->with('success', 'Agendamento atualizado com sucesso.');
    }
}
