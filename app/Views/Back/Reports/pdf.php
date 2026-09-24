<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 30px; }
        body { color: #2e2927; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1, h2 { color: #332925; margin: 0; }
        h1 { font-size: 21px; }
        h2 { border-bottom: 1px solid #d8c9be; font-size: 13px; margin: 22px 0 8px; padding-bottom: 4px; }
        .muted { color: #766d68; }
        .header { border-bottom: 3px solid #b85c5b; margin-bottom: 16px; padding-bottom: 10px; }
        .summary { width: 100%; }
        .summary td { background: #f7eee7; border: 1px solid #eadbd0; padding: 9px; width: 25%; }
        .label { color: #766d68; display: block; font-size: 8px; margin-bottom: 4px; text-transform: uppercase; }
        .value { font-size: 14px; font-weight: bold; }
        table.data { border-collapse: collapse; width: 100%; }
        .data th { background: #332925; color: #fff; font-size: 8px; padding: 6px; text-align: left; }
        .data td { border-bottom: 1px solid #eadfd7; padding: 6px; }
        .right { text-align: right; }
        .footer { border-top: 1px solid #d8c9be; color: #766d68; font-size: 8px; margin-top: 24px; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório financeiro semanal</h1>
        <div class="muted">Agendamentos | <?php echo date('d/m/Y', strtotime($weekStart)); ?> a <?php echo date('d/m/Y', strtotime($weekEnd)); ?></div>
    </div>

    <table class="summary">
        <tr>
            <td><span class="label">Atendimentos</span><span class="value"><?php echo (int) $totals['appointments']; ?></span></td>
            <td><span class="label">Faturamento bruto</span><span class="value">R$ <?php echo number_format($totals['gross'], 2, ',', '.'); ?></span></td>
            <td><span class="label">Comissões</span><span class="value">R$ <?php echo number_format($totals['commission'], 2, ',', '.'); ?></span></td>
            <td><span class="label">Líquido após comissão</span><span class="value">R$ <?php echo number_format($totals['net'], 2, ',', '.'); ?></span></td>
        </tr>
    </table>

    <h2>Resumo das comissões</h2>
    <table class="data">
        <thead><tr><th>Profissional</th><th>Atendimentos</th><th>Faturamento</th><th>Comissão (%)</th><th>Comissão</th></tr></thead>
        <tbody>
        <?php foreach ($byProfessional as $professional => $summary): ?>
            <tr><td><?php echo esc($professional); ?></td><td><?php echo (int) $summary['appointments']; ?></td><td>R$ <?php echo number_format($summary['gross'], 2, ',', '.'); ?></td><td><?php echo number_format($summary['percentage'], 2, ',', '.'); ?>%</td><td>R$ <?php echo number_format($summary['commission'], 2, ',', '.'); ?></td></tr>
        <?php endforeach; ?>
        <?php if (empty($byProfessional)): ?><tr><td colspan="5">Nenhum atendimento confirmado no período.</td></tr><?php endif; ?>
        </tbody>
    </table>

    <p><strong>Total pago:</strong> R$ <?php echo number_format($paid, 2, ',', '.'); ?> &nbsp;&nbsp; <strong>A pagar:</strong> R$ <?php echo number_format($payable, 2, ',', '.'); ?></p>

    <h2>Resumo por serviço</h2>
    <table class="data">
        <thead><tr><th>Serviço</th><th>Atendimentos</th><th>Faturamento</th><th>Comissão</th></tr></thead>
        <tbody>
        <?php foreach ($byService as $service => $summary): ?>
            <tr><td><?php echo esc($service); ?></td><td><?php echo (int) $summary['appointments']; ?></td><td>R$ <?php echo number_format($summary['gross'], 2, ',', '.'); ?></td><td>R$ <?php echo number_format($summary['commission'], 2, ',', '.'); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Atendimentos confirmados</h2>
    <table class="data">
        <thead><tr><th>Data</th><th>Cliente</th><th>Serviço</th><th>Profissional</th><th>Valor</th><th>Comissão (%)</th><th>Comissão</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr><td><?php echo date('d/m/Y H:i', strtotime($row['chosen_date'])); ?></td><td><?php echo esc($row['customer_name'] ?: 'Cliente cadastrado'); ?></td><td><?php echo esc($row['service']); ?></td><td><?php echo esc($row['professional'] ?: 'Não definido'); ?></td><td>R$ <?php echo number_format($row['service_amount'], 2, ',', '.'); ?></td><td><?php echo number_format($row['commission_percentage'], 2, ',', '.'); ?>%</td><td>R$ <?php echo number_format($row['commission_amount'], 2, ',', '.'); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Indicadores operacionais</h2>
    <p>Cancelados: <strong><?php echo (int) $canceled; ?></strong> &nbsp;&nbsp; Pendentes de confirmação: <strong><?php echo (int) $pending; ?></strong></p>
    <div class="footer">Relatório gerado em <?php echo date('d/m/Y H:i'); ?>. O valor líquido considera o faturamento menos as comissões e não inclui outras despesas operacionais.</div>
</body>
</html>
