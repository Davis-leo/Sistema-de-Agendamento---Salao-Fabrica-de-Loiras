<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;
use Exception;
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
     * @throws Exception
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
                
                // Enquanto o dia de início for maior que zero, adiciono as células vazias através do for,
                // até que encontremos o dia inicial da semana
                if($startDay > 0){

                    for($i = 0; $i < $startDay; $i++){

                        $calendar .= '<td>&nbsp;</td>';
                    }
                }

                // Nesse ponto podemos popular o calendário
                for($day = 1; $day <= $daysOfMonth; $day++) {

                    /**
                     * @todo renderizar botão com o dia
                     */
                    $btnDay = $this->renderDayButton(
                        day: $day, 
                        month: $month,
                        //isWeekend: $this->isWeekend(year: $year, month: $month, day: $day),
                    );

                    $calendar .= "<td>{$btnDay}</td>";

                    // Vamos incrementar o dia de inicio
                    $startDay++;

                    // Se $startDay for igual a 7 (domingo), adicionamos uma nova linha na tabela
                    if($startDay === 7){

                        // Reinicio o starDay em zero
                        $startDay = 0;

                        // Se o dia corrente for menor que $daysOfMonth, então realiazmos a abertura <tr> (nova linha)
                        if($day <$daysOfMonth){

                            $calendar .='<tr>';
                        }
                    }
                } // fim do for

                // agora preenchemos as células restantes com espaço
                if($startDay > 0){

                    for($i = $startDay; $i < 7; $i++){

                        $calendar .= '<td>&nbsp;</td>';
                    }

                    //e fechamos a linha 
                    $calendar .= '</tr>';
                }

                // fechamos a tabela
                $calendar .= '</table>';

                // fechamos a div table-responsive
                $calendar .= '</div>';

                // Finalmente retornamos o calendário com os dias para o mês desejado

            return $calendar;

        } catch (\Throwable $th) {

            log_message('error', '[ERROR] {exception}', ['exception' => $th]);

            return "Não foi possível gerar o calendário para o mês informado";
        }

    }

    /**
     * Verifica se a data informadaé um final de semana.
     * @param integer $year
     * @param integer $month
     * @param integer $day
     * @return boolean
     */
    private function isWeekend(int $year, int $month, int $day): bool 
    {
        // Vamos obter o primerio dia do mês informado no formato unix timestamp
        $timeCreated = Time::create(year: $year, month: $month, day: $day);

        // Obtém a representação numérica do dia da semana. 0 (domingo) até 6 (sábado)
        $dayOfWeek = (int) $timeCreated->format('w'); //minúsculo

        //0 => domingo ou 6 sábado
        return($dayOfWeek === 0 || $dayOfWeek=== 6);
    }

    /**
     * Renderiza o botão HTML para click no front
     * @param integer $day
     * @param integer $month
     * @param boolean $isWeekend
     * @return string
     */
    private function renderDayButton(int $day, int $month, bool $isWeekend = false): string 
    {
        // Atributos padrão para o botão
        $attributes = [
            'type'  => 'button',
            'class' => 'btn btn-primary btn-calendar-day',
        ];

        // data atual
        $now          = Time::now();
        $currentDay   = (int) $now->getDay();
        $currentMonth = (int) $now->getMonth();
        
        // se o dia for menor que o dia atual e o mês for igual ao mês corrente
        // então desabilitamos o botão
        if($day < $currentDay && $month === $currentMonth || $isWeekend){

            $attributes['disabled'] = true;
        }else{
            
            $attributes['class'] = "chosenDay {$attributes['class']}"; // usados no front
            $attributes['data-day'] = $day; // usados no front
        }

        return form_button(data: $attributes, content: "{$day}");
    }

}