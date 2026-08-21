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

    /** construtor */
    public function __construct()
    {
        $this->unitService = Factories::class(UnitService::class);
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
}
