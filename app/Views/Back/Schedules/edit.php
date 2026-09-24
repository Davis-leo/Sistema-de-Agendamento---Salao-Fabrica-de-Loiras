<?php echo $this->extend('Back/Layout/main'); ?>
<?php echo $this->section('title'); ?><?php echo $title; ?><?php echo $this->endSection(); ?>
<?php echo $this->section('content'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo esc($title); ?></h6>
            <a href="<?php echo route_to('units.schedules', $schedule->unit_id); ?>" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> <?php echo esc($schedule->customer_name ?: ($schedule->user ?? 'Cliente cadastrado')); ?></p>
            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($schedule->chosen_date)); ?></p>
            <?php echo form_open(route_to('super.schedules.update', $schedule->id)); ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead><tr><th>Serviço</th><th>Profissional</th><th>Valor do serviço</th><th>Comissão (%)</th></tr></thead>
                        <tbody>
                        <?php foreach ($serviceItems as $item): ?>
                            <tr>
                                <td><?php echo esc($item['service_name']); ?></td>
                                <td><?php echo esc($item['professional_name'] ?: 'Não definida'); ?></td>
                                <td><input type="number" name="service_amount[<?php echo (int) $item['service_id']; ?>]" class="form-control" min="0" step="0.01" value="<?php echo esc(old('service_amount.' . $item['service_id'], $item['service_amount'] ?? 0)); ?>" required></td>
                                <td><input type="number" name="commission_percentage[<?php echo (int) $item['service_id']; ?>]" class="form-control" min="0" max="100" step="0.01" value="<?php echo esc(old('commission_percentage.' . $item['service_id'], $item['commission_percentage'] ?? 0)); ?>" required></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Salvar alterações</button>
                    <a href="<?php echo route_to('units.schedules', $schedule->unit_id); ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>
