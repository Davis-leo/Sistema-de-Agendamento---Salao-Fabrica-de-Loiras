<?php
namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Entities\Unit;
use App\Libraries\UnitService;
use App\Models\UnitModel;
use CodeIgniter\Config\Factories;
use CodeIgniter\HTTP\RedirectResponse;

class UnitsController extends BaseController
{
    private UnitService $unitService;

    private UnitModel $unitModel;

    public function __construct()
    {
        $this->unitService = Factories::class(UnitService::class);
        $this->unitModel = model(UnitModel::class);
    }

    public function index()
    {
        return view('Back/Units/index', [
            'title' => 'Unidades',
            'units' => $this->unitModel->findAll(),
        ]);
    }

    public function new()
    {
        return view('Back/Units/new', [
            'title' => 'Nova Unidade',
            'unit' => new Unit(['active' => 1]),
            'timesInterval' => $this->getTimesInterval('30 minutes'),
        ]);
    }

    public function create(): RedirectResponse
    {
        $this->checkMethod('post');

        $unit = new Unit($this->clearRequest());

        if (!$this->unitModel->insert($unit)) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Verifique os erros e tente novamente.')
                ->with('errorsValidation', $this->unitModel->errors());
        }

        return redirect()->route('units')->with('success', 'Unidade criada com sucesso!');
    }

    public function edit(int $id)
    {
        $unit = $this->unitModel->findorFail($id);

        return view('Back/Units/edit', [
            'title' => 'Editar unidade',
            'unit' => $unit,
            'timesInterval' => $this->getTimesInterval($unit->servicetime),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->checkMethod('put');

        $unit = $this->unitModel->findorFail($id);
        $unit->fill($this->clearRequest());

        if (!$unit->hasChanged()) {
            return redirect()->back()->with('info', 'Não há dados para atualizar.');
        }

        if (!$this->unitModel->save($unit)) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Verifique os erros e tente novamente.')
                ->with('errorsValidation', $this->unitModel->errors());
        }

        return redirect()->route('units')->with('success', 'Unidade atualizada com sucesso!');
    }

    public function action(int $id): RedirectResponse
    {
        $this->checkMethod('put');

        $unit = $this->unitModel->findorFail($id);
        $unit->setAction();
        $this->unitModel->save($unit);

        return redirect()->route('units')->with('success', 'Unidade atualizada com sucesso!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkMethod('delete');

        $unit = $this->unitModel->findorFail($id);
        $this->unitModel->delete($unit->id);

        return redirect()->route('units')->with('success', 'Unidade excluida com sucesso!');
    }

    public function schedules(int $id)
    {
        $unit = $this->unitModel->findorFail($id);

        return view('Back/Units/schedules', [
            'title' => 'Gerenciar os agendamentos da unidade',
            'unit' => $unit,
            'schedules' => $this->unitService->renderUnitSchedules($unit->id),
        ]);
    }

    private function getTimesInterval(?string $selected): string
    {
        $options = [
            '10 minutes' => '10 minutos',
            '15 minutes' => '15 minutos',
            '20 minutes' => '20 minutos',
            '30 minutes' => '30 minutos',
            '60 minutes' => '60 minutos',
        ];

        $select = '<select name="servicetime" class="form-control">';

        foreach ($options as $value => $label) {
            $isSelected = old('servicetime', $selected) === $value ? ' selected' : '';
            $select .= '<option value="' . esc($value) . '"' . $isSelected . '>' . esc($label) . '</option>';
        }

        return $select . '</select>';
    }
}
