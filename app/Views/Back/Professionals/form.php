<?php echo $this->extend('Back/Layout/main'); ?>
<?php echo $this->section('title'); ?><?php echo $title; ?><?php echo $this->endSection(); ?>
<?php echo $this->section('content'); ?>
<div class="container-fluid"><div class="card shadow mb-4"><div class="card-header"><h6><?php echo $title; ?></h6></div><div class="card-body">
<?php
$isEdit = !empty($professional->id);
$action = $isEdit ? route_to('professionals.update', $professional->id) : route_to('professionals.create');
$method = $isEdit ? 'PUT' : 'POST';
?>
<?php echo form_open($action, [], ['_method' => $method]); ?>
<div class="row">
    <div class="form-group col-md-6"><label for="name">Nome</label><input class="form-control" id="name" name="name" value="<?php echo old('name', $professional->name); ?>" required></div>
    <div class="form-group col-md-6"><label for="unit_id">Unidade</label><select class="form-control" id="unit_id" name="unit_id" required><option value="">Selecione uma unidade</option><?php foreach ($units as $unit): ?><option value="<?php echo $unit->id; ?>" <?php echo old('unit_id', $professional->unit_id) == $unit->id ? 'selected' : ''; ?>><?php echo esc($unit->name); ?></option><?php endforeach; ?></select><?php echo show_error_input('unit_id'); ?></div>
    <div class="form-group col-md-3"><label>Status</label><select class="form-control" name="active"><option value="1" <?php echo old('active', $professional->active ?? 1) ? 'selected' : ''; ?>>Ativo</option><option value="0" <?php echo !old('active', $professional->active ?? 1) ? 'selected' : ''; ?>>Inativo</option></select></div>
</div>
<hr><h5>Serviços executados</h5><div class="row">
<?php foreach ($services as $service): ?><div class="col-md-4"><label class="font-weight-normal"><input type="checkbox" name="service_ids[]" value="<?php echo $service->id; ?>" <?php echo in_array((int) $service->id, $serviceIds, true) ? 'checked' : ''; ?>> <?php echo esc($service->name); ?></label></div><?php endforeach; ?>
</div>
<hr><h5>Horários de trabalho</h5>
<?php foreach ($weekdays as $weekday => $label): $hour = $hours[$weekday] ?? []; ?>
<div class="form-row align-items-center mb-2"><div class="col-md-3"><label class="font-weight-normal"><input type="checkbox" name="workdays[<?php echo $weekday; ?>][active]" value="1" <?php echo !empty($hour['active']) ? 'checked' : ''; ?>> <?php echo $label; ?></label></div><div class="col-md-3"><input type="time" class="form-control" name="workdays[<?php echo $weekday; ?>][start_time]" value="<?php echo esc($hour['start_time'] ?? '08:00'); ?>"></div><div class="col-md-3"><input type="time" class="form-control" name="workdays[<?php echo $weekday; ?>][end_time]" value="<?php echo esc($hour['end_time'] ?? '18:00'); ?>"></div></div>
<?php endforeach; ?>
<button class="btn btn-primary mt-4" type="submit">Salvar</button> <a class="btn btn-secondary mt-4" href="<?php echo route_to('professionals'); ?>">Voltar</a>
<?php echo form_close(); ?>
</div></div></div>
<?php echo $this->endSection(); ?>
