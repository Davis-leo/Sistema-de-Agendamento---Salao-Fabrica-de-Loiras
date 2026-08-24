<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
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
     * Renderiza a view para gerenciar as unidades
     * 
     * @param integer $id
     * @return RendererInterface
     */
    public function edit(int $id)
    {

        $data = [
            'title' => 'Editar unidade',
            'unit'  => $this->unitModel->findorFail($id)
        ];
    
        return view('Back/Units/edit', $data);
    }
}
