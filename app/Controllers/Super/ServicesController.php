<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use App\Entities\Service;
use App\Libraries\ServiceService;
use App\Models\ServiceModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\View\RendererInterface;
use CodeIgniter\Config\Factories;

class ServicesController extends BaseController
{

    /** @var ServiceService */
    private ServiceService $serviceService;

    /** @var ServiceModel */
    private ServiceModel $serviceModel;

    /** construtor */
    public function __construct()
    {
        $this->serviceService = Factories::class(ServiceService::class);
        $this->serviceModel = model(ServiceModel::class);
    }

    /**
     * Renderiza a view para gerenciar os serviços
     * 
     * @return RendererInterface
     */
    public function index()
    {
        $data = [
            'title'     => 'Serviços',
            'services'  => $this->serviceService->renderServices()
        ];

        return view('Back/Services/index', $data);
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

        return view('Back/Services/new', $data);
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

        return view('Back/Services/edit', $data);
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
}
