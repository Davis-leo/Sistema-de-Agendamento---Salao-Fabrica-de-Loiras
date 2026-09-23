<?php

namespace App\Libraries;

use App\Models\UnitModel;
use CodeIgniter\I18n\Time;
use DateInterval;
use DatePeriod;

class UnitAvaiableHoursService
{

    /**
     * Renderiza as horas disponíveis para serem escolhidas no agendamento.
     * @param array $request
     * @return string|null
     */
    public function renderHours(array $request): string|null
    {

        try {

            // Transformo em objeto
            $request = (object) $request;

            // Precisamos obter a unidade mês e dia desejados
            $unitId = (string) $request->unit_id;
            $month  = (string) $request->month;
            $day    = (string) $request->day;
            $rawServiceIds = (array) ($request->service_ids ?? []);
            $serviceIds = [];
            foreach ($rawServiceIds as $rawServiceId) {
                $serviceIds = array_merge($serviceIds, explode(',', (string) $rawServiceId));
            }
            $serviceIds = array_values(array_unique(array_filter(array_map('intval', $serviceIds))));

            // Adicionamos um zero à esquerda do mês e dia, quando for o caso
            $month = strlen($month) < 2 ? sprintf("%02d", $month) : $month;
            $day   = strlen($day) < 2 ? sprintf("%02d", $day) : $day;

            // Validamos a existência da unidade, ativa, com serviços
            $unit = model(UnitModel::class)->where(['active' => 1, 'services!=' => null, 'services !=' => ''])->findOrfail($unitId);

            // Data atual
            $now = Time::now();

            // Obtemos a data desejada
            // Terei algo como: 2023-07-16
            $dateWanted = "{$now->getYear()}-{$month}-{$day}";

            // debug
            // $dateWanted = '2026-09-17';

            // Precisamos identificar se a data desejada é a data atual
            $isCurrentDay = $dateWanted === $now->format('Y-m-d');

            $timeRange = $this->createUnitTimeRange(
                start: $unit->starttime,
                end: $unit->endtime,
                interval: $unit->servicetime,
                isCurrentDay: $isCurrentDay
            );

            // Abertura da grade de horários com valor padrão null
            $divHours = '<div class="hours-grid">';
            $hasAvailableHour = false;

            // Precorro os horários gerados
            foreach($timeRange as $hour){
                $chosenDate = $dateWanted . ' ' . $hour;
                $availableProfessionals = !empty($serviceIds)
                    ? (new ProfessionalAvailabilityService())->availableForSlot((int) $unit->id, $serviceIds, $chosenDate)
                    : [];

                if(!empty($availableProfessionals)){

                    $hasAvailableHour = true;
                    $divHours .= form_button(data: ['class' => 'btn btn-hour btn-primary', 'data-hour' => $hour], content: $hour);
                }

                
            }

            $divHours .= '</div>';

            if (!$hasAvailableHour) {
                return '<div class="alert alert-info">Não há profissionais disponíveis para este serviço neste dia. Cadastre um profissional, associe o serviço e defina o horário de trabalho.</div>';
            }

            // Finalmente retornamos o range de horários
            return $divHours;
        } catch (\Throwable $th) {

            log_message('error', '[ERROR] {exception}', ['exception' => $th]);

            return "Não foi possível recuperar os horários disponíveis";
        }
    }

    /**
     * Cria um array com range de horários de acordo com início, fim e intervalo
     * @param string $start
     * @param string $end
     * @param string $interval
     * @param boolean $isCurrentDay
     * @return array
     */
    private function createUnitTimeRange(string $start, string $end, string $interval, bool $isCurrentDay): array 
    {

        $period = new DatePeriod(
            new Time($start),
            DateInterval::createFromDateString($interval),
            new Time($end)
        );

        // Receberá os tempos  gerados
        $timeRange = [];

        // Tempo atual em hh:mm para comparar com a hora e minutos gerados no foreach abaixo
        $now = Time::now()->format('H:i');

        foreach ($period as $instance){

            // Recuperamos o tempo no formato 'hh:mm'
            $hour = Time::createFromInstance($instance)->format('H:i');

            // Se não for o dia atual, fazemos o push normal
            if(!$isCurrentDay){

                $timeRange[] = $hour;
            }else{

                // aqui dentro é o dia atual
                // verificamos se a hora de início é maior que a hora atual
                // dessa forma só apresentamos horários que forem maiores que o horário atual,
                //ou seja, não apresentamos horas passadas

                if($hour > $now){

                    $timeRange[] = $hour;
                }
            }
        }

        // Finalmente retornamos os horários gerados
        return $timeRange;
    }
}