<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;

use App\Libraries\UnitService;

use App\Models\UnitModel;
use CodeIgniter\HTTP\RedirectResponse;



class UnitsController extends BaseController

{

    private ?UnitService $unitService = null;

    private ?UnitModel $unitModel = null;



    public function __construct()

    {

        $this->unitService = new UnitService();

        $this->unitModel = new UnitModel();

    }



    public function index()

    {

        return view("Back/Units/index", [

            "title" => "Unidades",

            "units" => $this->unitModel->findAll()

        ]);

    }



    private function getTimesInterval(?string $selected = null): string

    {

        $options = [

            '10 minutes' => '10 min',

            '15 minutes' => '15 min',

            '20 minutes' => '20 min',

            '30 minutes' => '30 min',

            '60 minutes' => '60 min',

        ];

        $html = '<select name="servicetime" id="servicetime" class="form-control">';

        foreach ($options as $val => $label) {

            $sel = ($selected === $val) ? ' selected' : '';

            $html .= "<option value=\"{$val}\"{$sel}>{$label}</option>";

        }

        $html .= '</select>';

        return $html;

    }



    public function new()

    {

        return view("Back/Units/new", [

            "title" => "Nova Unidade",

            "unit" => new \App\Entities\Unit(['active' => 1]),

            "timesInterval" => $this->getTimesInterval('30 minutes')

        ]);

    }



    public function create()

    {

        $data = $this->clearRequest();

        $entity = new \App\Entities\Unit($data);

        if (!$this->unitModel->insert($entity)) {

            return redirect()->back()
                ->withInput()
                ->with('danger', 'Verifique os dados da unidade e tente novamente.')
                ->with('errorsValidation', $this->unitModel->errors());

        }

        return redirect()->to(route_to("units.services", $this->unitModel->getInsertID()))
            ->with("success", "Unidade criada. Agora associe os serviços disponíveis.");

    }



    public function edit(int $id)

    {

        $unit = $this->unitModel->find($id);

        if (!$unit) {

            return redirect()->to(route_to("units"))->with("danger", "Nao encontrada");

        }

        return view("Back/Units/edit", [

            "title" => "Editar Unidade",

            "unit" => $unit,

            "timesInterval" => $this->getTimesInterval($unit->servicetime ?? '30 minutes')

        ]);

    }



    public function update(int $id)

    {

        $unit = $this->unitModel->find($id);

        if (!$unit) {

            return redirect()->to(route_to("units"))->with("danger", "Nao encontrada");

        }

        $unit->fill($this->clearRequest());

        if (!$unit->hasChanged()) {

            return redirect()->back()->with('info', 'Não há dados para atualizar.');
        }

        if (!$this->unitModel->save($unit)) {

            return redirect()->back()
                ->withInput()
                ->with('danger', 'Verifique os dados da unidade e tente novamente.')
                ->with('errorsValidation', $this->unitModel->errors());

        }

        return redirect()->to(route_to("units"))->with("success", "Atualizada");

    }



    public function action(int $id): RedirectResponse
    {
        $this->checkMethod('put');

        $unit = $this->unitModel->findOrFail($id);
        $unit->setAction();
        $this->unitModel->save($unit);

        return redirect()->to(route_to('units'))->with('success', 'Status atualizado com sucesso.');
    }

    public function destroy(int $id): RedirectResponse

    {

        if (!$this->unitModel->delete($id)) {

            return redirect()->to(route_to("units"))->with("danger", $this->unitModel->errors());

        }

        return redirect()->to(route_to("units"))->with("success", "Removida");
    }

    public function schedules(int $id): string
    {
        $unit = $this->unitModel->findOrFail($id);

        return view('Back/Units/schedules', [
            'title' => 'Agendamentos da Unidade',
            'schedules' => $this->unitService->renderUnitSchedules($id),
            'unit' => $unit,
        ]);

    }

}