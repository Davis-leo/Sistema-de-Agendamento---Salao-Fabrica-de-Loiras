<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Entities\Professional;
use App\Models\ProfessionalModel;
use App\Models\ProfessionalServiceModel;
use App\Models\ProfessionalWorkingHourModel;
use App\Models\ServiceModel;
use App\Models\UnitModel;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\RedirectResponse;

class ProfessionalsController extends BaseController
{
    private array $weekdays = [
        0 => 'Segunda-feira', 1 => 'Terça-feira', 2 => 'Quarta-feira',
        3 => 'Quinta-feira', 4 => 'Sexta-feira', 5 => 'Sábado', 6 => 'Domingo',
    ];

    public function index(): string
    {
        $units = model(UnitModel::class)->findAll();
        return view('Back/Professionals/index', [
            'title' => 'Profissionais',
            'professionals' => model(ProfessionalModel::class)->orderBy('name', 'ASC')->findAll(),
            'unitsById' => array_column($units, 'name', 'id'),
        ]);
    }

    public function new(): string
    {
        return view('Back/Professionals/form', $this->formData(new Professional(), 'Criar profissional'));
    }

    public function create(): RedirectResponse
    {
        $this->checkMethod('post');
        $model = model(ProfessionalModel::class);
        $professional = new Professional($this->clearRequest());
        if (!$model->insert($professional)) {
            return redirect()->back()->withInput()->with('danger', 'Verifique os dados do profissional.')->with('errorsValidation', $model->errors());
        }
        $this->saveRelations((int) $model->getInsertID());
        return redirect()->route('professionals')->with('success', 'Profissional criado com sucesso.');
    }

    public function edit(int $id): string
    {
        $professional = model(ProfessionalModel::class)->findorFail($id);
        return view('Back/Professionals/form', $this->formData($professional, 'Editar profissional'));
    }

    public function update(int $id): RedirectResponse
    {
        $this->checkMethod('put');
        $model = model(ProfessionalModel::class);
        $professional = $model->findorFail($id);
        $professional->fill($this->clearRequest());
        try {
            $saved = $model->save($professional);
        } catch (DataException $exception) {
            $this->saveRelations($id);
            return redirect()->route('professionals')->with('info', 'Nenhum dado principal foi alterado.');
        }
        if (!$saved) {
            return redirect()->back()->withInput()->with('danger', 'Verifique os dados do profissional.')->with('errorsValidation', $model->errors());
        }
        $this->saveRelations($id);
        return redirect()->route('professionals')->with('success', 'Profissional atualizado com sucesso.');
    }

    public function action(int $id): RedirectResponse
    {
        $this->checkMethod('put');
        $model = model(ProfessionalModel::class);
        $professional = $model->findorFail($id);
        $professional->setAction();
        $model->save($professional);
        return redirect()->route('professionals')->with('success', 'Status atualizado com sucesso.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkMethod('delete');
        $model = model(ProfessionalModel::class);
        $professional = $model->findorFail($id);
        $model->delete($professional->id);
        return redirect()->route('professionals')->with('success', 'Profissional excluído com sucesso.');
    }

    private function formData(Professional $professional, string $title): array
    {
        $serviceModel = model(ProfessionalServiceModel::class);
        $hourModel = model(ProfessionalWorkingHourModel::class);
        $serviceIds = $professional->id ? array_column($serviceModel->where('professional_id', $professional->id)->findAll(), 'service_id') : [];
        $hours = $professional->id ? array_column($hourModel->where('professional_id', $professional->id)->findAll(), null, 'weekday') : [];
        return [
            'title' => $title,
            'professional' => $professional,
            'services' => model(ServiceModel::class)->where('active', 1)->orderBy('name', 'ASC')->findAll(),
            'units' => model(UnitModel::class)->where('active', 1)->orderBy('name', 'ASC')->findAll(),
            'serviceIds' => array_map('intval', $serviceIds),
            'hours' => $hours,
            'weekdays' => $this->weekdays,
        ];
    }

    private function saveRelations(int $professionalId): void
    {
        $serviceModel = model(ProfessionalServiceModel::class);
        $hourModel = model(ProfessionalWorkingHourModel::class);
        $serviceModel->where('professional_id', $professionalId)->delete();
        $hourModel->where('professional_id', $professionalId)->delete();
        foreach ((array) $this->request->getPost('service_ids') as $serviceId) {
            if ((int) $serviceId > 0) {
                $serviceModel->insert(['professional_id' => $professionalId, 'service_id' => (int) $serviceId]);
            }
        }
        foreach ((array) $this->request->getPost('workdays') as $weekday => $data) {
            if (!empty($data['active']) && !empty($data['start_time']) && !empty($data['end_time'])) {
                $hourModel->insert([
                    'professional_id' => $professionalId,
                    'weekday' => (int) $weekday,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'active' => 1,
                ]);
            }
        }
    }
}
