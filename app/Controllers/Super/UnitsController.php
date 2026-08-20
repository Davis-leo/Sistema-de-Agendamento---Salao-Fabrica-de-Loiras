<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use CodeIgniter\View\RendererInterface;

class UnitsController extends BaseController
{
    /**
     * Renderiza a view para gerenciar as unidades
     * 
     * @return RendererInterface
     */
    public function index()
    {
        $data = [
            'title' => 'Unidades',
        ];   

        return view('Back/Units/index', $data);
        
    }
}
