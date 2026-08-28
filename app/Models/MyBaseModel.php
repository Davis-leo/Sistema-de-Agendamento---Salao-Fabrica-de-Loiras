<?php

namespace App\Models;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Model;

class MyBaseModel extends Model
{
    /**
     * Escapa os dados antes de inserir
     * @param array $data
     * @return array
     */
    protected function escapeData(array $data): array
    {

        if(!isset($data['data'])){
            return $data;
        }

        return esc($data);
    }

    /**
     * Recupera o registro
     * @param int|string $id
     * @throws PageNotFoundException
     * @return array|object
     */
    public function findorFail(int | string $id): object{

        $row = $this ->find($id);

        return $row ?? throw new PageNotFoundException("Registro {$id} não encontrado");
    }
}
