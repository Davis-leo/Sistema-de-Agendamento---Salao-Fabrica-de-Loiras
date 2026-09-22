<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;

use App\Libraries\UnitService;

use App\Models\UnitModel;



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

        $data = $this->request->getPost();

        $entity = new \App\Entities\Unit($data);

        if (!$this->unitService->create($entity)) {

            return redirect()->back()->withInput()->with("errors", $this->unitService->errors());

        }

        return redirect()->to(route_to("super.units"))->with("success", "Unidade criada");

    }



    public function edit(int $id)

    {

        $unit = $this->unitModel->find($id);

        if (!$unit) {

            return redirect()->to(route_to("super.units"))->with("danger", "Nao encontrada");

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

            return redirect()->to(route_to("super.units"))->with("danger", "Nao encontrada");

        }

        $unit->fill($this->request->getPost());

        if (!$this->unitService->update($unit)) {

            return redirect()->back()->withInput()->with("errors", $this->unitService->errors());

        }

        return redirect()->to(route_to("super.units"))->with("success", "Atualizada");

    }



    public function delete(int $id)

    {

        if (!$this->unitService->delete($id)) {

            return redirect()->to(route_to("super.units"))->with("danger", $this->unitService->errors());

        }

        return redirect()->to(route_to("super.units"))->with("success", "Removida");

    }

}