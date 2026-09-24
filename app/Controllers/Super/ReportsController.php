<?php

namespace App\Controllers\Super;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\HTTP\ResponseInterface;
use DateTimeImmutable;

class ReportsController extends BaseController
{
    public function index(): string
    {
        $weekStart = (new DateTimeImmutable('monday this week'))->format('Y-m-d');

        return view('Back/Reports/index', [
            'title' => 'Relatórios',
            'weekStart' => $weekStart,
        ]);
    }

    public function pdf(): ResponseInterface
    {
        $date = $this->request->getGet('week_start') ?: date('Y-m-d');
        $weekStartDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        if (!$weekStartDate) {
            return redirect()->route('reports')->with('danger', 'Escolha uma data válida para o relatório.');
        }

        $weekStartDate = $weekStartDate->modify('monday this week');
        $weekEndDate = $weekStartDate->modify('+6 days');
        $weekStart = $weekStartDate->format('Y-m-d');
        $weekEnd = $weekEndDate->format('Y-m-d');

        $data = $this->reportData($weekStart, $weekEnd);
        $data['weekStart'] = $weekStart;
        $data['weekEnd'] = $weekEnd;

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('Back/Reports/pdf', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response->download(
            'relatorio-semanal-' . $weekStart . '.pdf',
            $dompdf->output()
        );
    }

    private function reportData(string $weekStart, string $weekEnd): array
    {
        $builder = db_connect()->table('schedules');
        $rows = $builder
            ->select([
                'schedules.id',
                'schedules.chosen_date',
                'COALESCE(users.username, schedules.customer_name) AS customer_name',
                'units.name AS unit',
                'COALESCE(selected_services.name, services.name) AS service',
                'COALESCE(assigned_professionals.name, professionals.name) AS professional',
                'COALESCE(schedule_services.service_amount, schedules.service_amount, 0) AS service_amount',
                'COALESCE(schedule_services.commission_percentage, schedules.commission_percentage, 0) AS commission_percentage',
                'COALESCE(schedule_services.commission_amount, schedules.commission_amount, 0) AS commission_amount',
            ])
            ->join('units', 'units.id = schedules.unit_id')
            ->join('services', 'services.id = schedules.service_id')
            ->join('users', 'users.id = schedules.user_id', 'left')
            ->join('professionals', 'professionals.id = schedules.professional_id', 'left')
            ->join('schedule_services', 'schedule_services.schedule_id = schedules.id', 'left')
            ->join('services AS selected_services', 'selected_services.id = schedule_services.service_id', 'left')
            ->join('professionals AS assigned_professionals', 'assigned_professionals.id = schedule_services.professional_id', 'left')
            ->where('schedules.confirmed', 1)
            ->where('schedules.canceled', 0)
            ->where('schedules.chosen_date >=', $weekStart . ' 00:00:00')
            ->where('schedules.chosen_date <=', $weekEnd . ' 23:59:59')
            ->orderBy('schedules.chosen_date', 'ASC')
            ->get()
            ->getResultArray();

        $totals = [
            'gross' => 0.0,
            'commission' => 0.0,
            'net' => 0.0,
            'appointments' => count(array_unique(array_column($rows, 'id'))),
        ];
        $byProfessional = [];
        $byService = [];
        foreach ($rows as &$row) {
            $row['service_amount'] = (float) $row['service_amount'];
            $row['commission_percentage'] = (float) $row['commission_percentage'];
            $row['commission_amount'] = (float) $row['commission_amount'];
            $totals['gross'] += $row['service_amount'];
            $totals['commission'] += $row['commission_amount'];

            $professional = $row['professional'] ?: 'Não definido';
            $service = $row['service'];
            $byProfessional[$professional]['appointment_ids'][$row['id']] = true;
            $byProfessional[$professional]['gross'] = ($byProfessional[$professional]['gross'] ?? 0) + $row['service_amount'];
            $byProfessional[$professional]['commission'] = ($byProfessional[$professional]['commission'] ?? 0) + $row['commission_amount'];
            $byService[$service]['appointments'] = ($byService[$service]['appointments'] ?? 0) + 1;
            $byService[$service]['gross'] = ($byService[$service]['gross'] ?? 0) + $row['service_amount'];
            $byService[$service]['commission'] = ($byService[$service]['commission'] ?? 0) + $row['commission_amount'];
        }
        unset($row);
        foreach ($byProfessional as &$professionalSummary) {
            $professionalSummary['appointments'] = count($professionalSummary['appointment_ids']);
            $professionalSummary['percentage'] = $professionalSummary['gross'] > 0
                ? ($professionalSummary['commission'] / $professionalSummary['gross']) * 100
                : 0;
            unset($professionalSummary['appointment_ids']);
        }
        unset($professionalSummary);
        $totals['net'] = $totals['gross'] - $totals['commission'];

        $canceled = db_connect()->table('schedules')
            ->where('canceled', 1)
            ->where('chosen_date >=', $weekStart . ' 00:00:00')
            ->where('chosen_date <=', $weekEnd . ' 23:59:59')
            ->countAllResults();
        $pending = db_connect()->table('schedules')
            ->where('confirmed', 0)
            ->where('canceled', 0)
            ->where('chosen_date >=', $weekStart . ' 00:00:00')
            ->where('chosen_date <=', $weekEnd . ' 23:59:59')
            ->countAllResults();
        $settlements = db_connect()->table('commission_settlements')
            ->select('professionals.name AS professional, commission_settlements.total_amount, commission_settlements.paid_at')
            ->join('professionals', 'professionals.id = commission_settlements.professional_id')
            ->where('week_start', $weekStart)
            ->where('week_end', $weekEnd)
            ->get()
            ->getResultArray();
        $paid = array_sum(array_map(static fn (array $settlement) => (float) $settlement['total_amount'], $settlements));
        $payable = max(0, $totals['commission'] - $paid);

        return compact('rows', 'totals', 'byProfessional', 'byService', 'canceled', 'pending', 'settlements', 'paid', 'payable');
    }
}
