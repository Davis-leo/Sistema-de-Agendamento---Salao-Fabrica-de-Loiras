<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;
use InvalidArgumentException;

class CalendarService
{

    /** @var array meses */
    private static array $months = [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro',
    ];

    /**
     * Renderiza um dropdown dos meses para serem escolhidos no agendamento
     * @return string
     */
    public function renderMonths(): string
    {

        $today = Time::now();
        $currentYear = $today->getYear();
        $currentMonth = $today->getMonth();

        $options = [];
        $options[null] = '--- Selecione o Mês ---';

        foreach (self::$months as $key => $month) {

            // mês atual é maior que '$key'
            if ($currentMonth > $key) {

                // então continuamos para o próximo item do array,
                // pois não queremos exibir meses passados
                continue;
            }

            $options[$key] = "{$month} / {$currentYear}";

        }

        return form_dropdown(data: 'month', options: $options, selected: [], extra: ['id' => 'month', 'class' => 'form-select']);
    }

    /**
     * Renderiza os dias para o mês informado para serem escolhidos no front
     * @param integer $month
     * @return string
     */
    public function generate (int $month): string 
    {

        try {
            
            // Tempo atual
            $now = Time::now();

            // Mês atual
            $currentMonth = (int) $now->getMonth();

            // Ano atual
            $year = (int) $now->getYear();

            if($month < $currentMonth || !in_array($month, array_keys(self::$months)) ){

                throw new InvalidArgumentException("O mês {$month} não é um mês válido para gerar o calendário");
            }

            // Criamos um novo objeto para termos acesso ao dia da semana do primeiro dia do mês
            $firstDayObject = $now::create(year: $year, month: $month, day: 1);

            // Obtém a quantidade de dias do mês
            $daysOfMonth = $firstDayObject->format('t');
            
            // Obtém a representação numérica do dia da semana. 0 (domingo) até 6 (sábado)
            $startDay = (int) $firstDayObject->format('w'); // minúsculo

            // Aberturda da div que comporta o calendário
            $calendar = '<div class="table-responsive">';

            // Abertura da tabela
            $calendar .= '<table class="table table-sm table-borderless">';

            // dias da semana (primeira linha da tabela)
            $calendar .= '<tr class="text-center">
                            <td>Dom</td>
                            <td>Seg</td>
                            <td>Ter</td>
                            <td>Qua</td>
                            <td>Qui</td>
                            <td>Sex</td>
                            <td>Sab</td>
                          </tr>
                         ';

            return $calendar;

        } catch (\Throwable $th) {

            log_message('error', '[ERROR] {exception}', ['exception' => $th]);

            return "Não foi possível gerar o calendário para o mês informado";
        }

    }

}