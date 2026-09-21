<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Entities\Unit;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\View\RendererInterface;
use App\models\UnitModel;
use CodeIgniter\Config\Factories;
use App\Libraries\UnitService;

class UnitsController extends BaseController
{

    /** @var UnitService */
    private UnitService $unitService;

    /** @var UnitModel */
    private UnitModel $unitModel;

    /** construtor */
    public function __construct()
    {
        $this->unitService = Factories::class(UnitService::class);
        $this->unitModel = model(UnitModel::class);
    }

    /**
     * Renderiza a view para gerenciar as unidades
     * 
     * @return RendererInterface
     */
    public function index()
    {
        $data = [
            'title' => 'Unidades',
            'units' => $this->unitService->renderUnits()
        ];

        return view('Back/Units/index', $data);
    }


    /**
     * Renderiza a view para criar as unidades
     * 
     * @return RendererInterface
     */
    public function new()
    {
        $data = [
            'title' => 'Criar unidade',
            'unit' => new Unit(),
            'timesInterval' => $this->unitService->renderTimesInterval()
        ];

        return view('Back/Units/new', $data);
    }

    /**
     * Processa a criação do registro na base de dados
     * @return RedirectResponse
     */
    public function create()
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

    /**
     * Renderiza a view para gerenciar as unidades
     * 
     * @param integer $id
     * @return RendererInterface
     */
    public function edit(int $id)
    {

        $data = [
            'title' => 'Editar unidade',
            'unit' => $unit = $this->unitModel->findorFail($id),
            'timesInterval' => $this->unitService->renderTimesInterval($unit->servicetime)
        ];

        return view('Back/Units/edit', $data);
    }

    /**
     * Processa a atualização do registro na base de dados
     * @param integer $id
     * @return RedirectResponse
     */
    public function update(int $id)
    {
        $this->checkMethod('put');

        $unit = $this->unitModel->findorFail($id);

        $unit->fill($this->clearRequest());

        if (!$unit->hasChanged()) {

            return redirect()->back()->with('info', 'Não há dados para atualizar.');
        }

        $success = $this->unitModel->save($unit);

        if (!$success) {

            return redirect()->back()
                ->withInput()
                ->with('danger', 'Verifique os erros e tente novamente.')
                ->with('errorsValidation', $this->unitModel->errors());

        }

        return redirect()->route('units')->with('success', 'Unidade atualizada com sucesso!');
    }


    /**
     * Processa a ativação ou desativação do registro na base de dados
     * @param integer $id
     * @return RedirectResponse
     */
    public function action(int $id)
    {
        $this->checkMethod('put');

        $unit = $this->unitModel->findorFail($id);
        $unit->setAction();

        $this->unitModel->save($unit);

        return redirect()->route('units')->with('success', 'Unidade atualizada com sucesso!');
    }

    

    /**
     * Processa a exclusão do registro na base de dados
     * @param integer $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->checkMethod('delete');

        $unit = $this->unitModel->findorFail($id);

        $this->unitModel->delete($unit->id);

        return redirect()->route('units')->with('success', 'Unidade excluida com sucesso!');
    }

    /**
     * Renderiza a view para gerir os agendamentos da unidade
     * 
     * @param integer $id
     * @return RendererInterface
     */
    public function schedules(int $id)
    {

        $data = [
            'title'     => 'Gerenciar os agendamentos da unidade',
            'unit'      => $unit = $this->unitModel->findorFail($id),
            'schedules' => $this->unitService->renderUnitSchedules($unit->id)
        ];

        return view('Back/Units/schedules', $data);
    }
}
