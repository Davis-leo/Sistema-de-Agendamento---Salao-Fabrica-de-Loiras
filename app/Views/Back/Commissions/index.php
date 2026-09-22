<?php echo $this->extend('Back/Layout/main'); ?>
<?php echo $this->section('title'); ?>Comissões<?php echo $this->endSection(); ?>
<?php echo $this->section('content'); ?>
<div class="container-fluid"><div class="card shadow mb-4"><div class="card-header"><h6>Comissões da semana</h6><span><?php echo date('d/m/Y', strtotime($weekStart)); ?> a <?php echo date('d/m/Y', strtotime($weekEnd)); ?></span></div><div class="card-body">
<?php if (empty($rows)): ?><p class="text-muted">Nenhum atendimento confirmado nesta semana.</p><?php else: ?><div class="table-responsive"><table class="table"><thead><tr><th>Profissional</th><th>Atendimentos</th><th>Total</th><th>Situação</th><th>Ação</th></tr></thead><tbody>
<?php foreach ($rows as $row): $settlement = $paid[$row->professional_id] ?? null; ?><tr><td><?php echo esc($row->professional); ?></td><td><?php echo (int) $row->appointments; ?></td><td>R$ <?php echo number_format((float) $row->total, 2, ',', '.'); ?></td><td><?php echo $settlement ? 'Pago em ' . date('d/m/Y H:i', strtotime($settlement['paid_at'])) : 'Pendente'; ?></td><td><?php if (!$settlement): ?><?php echo form_open(route_to('commissions.pay', $row->professional_id)); ?><button class="btn btn-sm btn-primary" type="submit">Marcar como pago</button><?php echo form_close(); ?><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</div></div></div>
<?php echo $this->endSection(); ?>
