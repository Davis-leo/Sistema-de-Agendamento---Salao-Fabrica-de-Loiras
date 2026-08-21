<?php

namespace App\Libraries;

use CodeIgniter\Config\Factories;
use CodeIgniter\View\Table as HTMLTable;

class MyBaseService
{

    /** @var HTMLTable */
    protected HTMLTable $htmlTable;


    public function __construct()
    {
        $this->htmlTable = Factories::class(HtmlTable::class);

        $this->htmlTable->setTemplate([
            'table_open' => '<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">',
        ]);

    }
}