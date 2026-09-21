<?php

namespace App\Models;

use App\Entities\Schedule;
use Exception;

class ScheduleModel extends MyBaseModel
{
    protected $table            = 'schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Schedule::class;
    protected $useSoftDeletes   = false; //O registro será deletado fisicamente do banco de dados, sem a possibilidade de recuperação.
    protected $protectFields    = true;
    protected $allowedFields    = [
        'unit_id',
        'service_id',
        'user_id',
        'customer_name',
        'customer_phone',
        'created_by',
        'finished',
        'canceled',   
        'chosen_date',     
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = []; // Temos uma classe específica de valida~]ao
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['escapeData', 'setUserId'];
    protected $beforeUpdate   = ['escapeData'];

    /**
     * Define no array de dados o id do usuário logado
     * @param array $data
     * @return array
     */
    protected function setUserId(array $data): array
    {
        // O usuário está logado?
        if(!auth()->loggedIn()){
            throw new Exception('Não existe uma sessão válida');
        }

        if(!isset($data['data'])){

            return $data;
        }

        if (!array_key_exists('user_id', $data['data'])) {
            $data['data']['user_id'] = auth()->user()->id;
        }

        return $data;
    }

    /**
     * Verifica se a data e horário escolhidos não estão agendados.
     * Útil para fazer um último 'check' antes de inserir o registro, pois o usuário pode ficar com a página aberta por bastante tempo
     * @param integer|string $unitId
     * @param string $chosenDate
     * @return boolean
     */
    public function chosenDateisFree(int|string $unitId, string $chosenDate): bool
    {

        $normalizedDate = date('Y-m-d H:i', strtotime($chosenDate));

        return $this->where('unit_id', $unitId)
            ->where('DATE_FORMAT(chosen_date, "%Y-%m-%d %H:%i")', $normalizedDate)
            ->where('canceled', 0)
            ->first() === null;
    }

    /**
     * Recupera o agendamento de acordo com o id
     * @param integer|string $id
     * @return Schedule
     */
    public function getSchedule(int|string $id): Schedule
    {

        $this->select([
            'schedules.*',
            'units.name AS unit',
            'units.address',
            'services.name AS service',
        ]);

        $this->join('units', 'units.id = schedules.unit_id');
        $this->join('services', 'services.id = schedules.service_id');

        return $this-> findorFail($id);
    }

    /**
     * Recupera os agendamentos não finalizados de acordo com a unidade.
     * @param integer|string $unitId $unitId
     * @param string $dateWanted $dateWanted Exemplo: 2023-11-29
     * @return array horas. Exemplo: ['07:00', '07:30', 'HH:ss', etc]
     */
    public function getScheduledHoursByDate(int|string $unitId, string $dateWanted): array
    {

        $this->select('DATE_FORMAT(chosen_date, "%H:%i") AS hour'); //terei: 15:10
        $this->where('unit_id', $unitId);
        $this->where('finished', 0); // agendamento em aberto
        $this->where('canceled', 0); // agendamento não cancelado
        $this->where('DATE_FORMAT(chosen_date, "%Y-%m-%d")', $dateWanted); // apenas de acordo com a data desejada

        $result = $this->findAll();

        if(empty($result)){
        
            return [];
        }

        return array_column($result, 'hour'); // ['07:00', '07:30', 'HH:ss', etc]
    }

    /**
     * Recupera os agendamentos do usuário logado
     * @return array
     */
    public function getLoggedUserSchedules(): array 
    {
        if(! auth()->loggedIn()){
            return [];
        }

        $this->select([
            'schedules.*',
            'DATE_FORMAT(schedules.chosen_date, "%d/%m/%Y às %H:%i") AS formated_chosen_date', // 23/03/2026 às 15:15
            'units.name AS unit',
            'units.address',
            'services.name AS service',
        ]);

        $this->join('units', 'units.id = schedules.unit_id');
        $this->join('services', 'services.id = schedules.service_id');
        $this->where('schedules.user_id', auth()->user()->id); // do user logado
        $this->orderBy('schedules.id', 'DESC');

        return $this->findAll();
    }

    /**
     * Recupera os agendamentos da unidade
     * @param integer|string $unitId
     * @return array
     */
    public function getUnitSchedules (int|string $unitId): array 
    {        
        
        $this->select([
            'schedules.*',
            'DATE_FORMAT(schedules.chosen_date, "%d/%m/%Y às %H:%i") AS formated_chosen_date', // 23/03/2026 às 15:15
            'units.name AS unit',
            'units.address',
            'services.name AS service',
            'COALESCE(users.username, schedules.customer_name) AS user',
            'schedules.customer_phone',
        ]);

        $this->join('units', 'units.id = schedules.unit_id');
        $this->join('services', 'services.id = schedules.service_id');
        $this->join('users', 'users.id = schedules.user_id', 'left');
        $this->where('schedules.unit_id', $unitId);
        $this->orderBy('schedules.id', 'DESC');

        return $this->findAll();
    }
}
