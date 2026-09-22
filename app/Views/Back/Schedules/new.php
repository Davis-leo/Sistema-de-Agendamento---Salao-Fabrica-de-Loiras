<?php echo $this->extend('Back/Layout/main'); ?>

<?php echo $this->section('title'); ?>
<?php echo $title; ?>
<?php echo $this->endSection(); ?>

<?php echo $this->section('css'); ?>
<style>
    .external-booking-intro {
        background: var(--salon-sand);
        border-left: 4px solid var(--salon-rose);
        border-radius: 5px;
        color: var(--salon-muted);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        padding: 1rem 1.15rem;
    }
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo $title; ?></h6>
            <a href="<?php echo route_to('super.home'); ?>" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
        <div class="card-body">
            <div class="external-booking-intro">
                Cadastre um atendimento para um cliente que não possui conta no sistema. O horário será bloqueado na agenda normalmente.
            </div>

            <?php echo form_open(route_to('super.schedules.create')); ?>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="customer_name">Nome do cliente</label>
                    <input type="text" class="form-control" name="customer_name" id="customer_name" value="<?php echo old('customer_name'); ?>" required>
                    <?php echo show_error_input('customer_name'); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="customer_phone">Telefone</label>
                    <input type="tel" class="form-control phone_with_ddd" name="customer_phone" id="customer_phone" value="<?php echo old('customer_phone'); ?>" placeholder="(00) 00000-0000" maxlength="15" inputmode="numeric" autocomplete="tel" required>
                    <?php echo show_error_input('customer_phone'); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="customer_email">E-mail do cliente (opcional)</label>
                    <input type="email" class="form-control" name="customer_email" id="customer_email" value="<?php echo old('customer_email'); ?>">
                    <?php echo show_error_input('customer_email'); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="unit_id">Unidade</label>
                    <select class="form-control" name="unit_id" id="unit_id" required>
                        <option value="">Selecione uma unidade</option>
                        <?php foreach ($units as $unit): ?>
                            <option value="<?php echo $unit->id; ?>" <?php echo old('unit_id') == $unit->id ? 'selected' : ''; ?>>
                                <?php echo esc($unit->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo show_error_input('unit_id'); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="professional_id">Profissional</label>
                    <select class="form-control" name="professional_id" id="professional_id" required>
                        <option value="">Selecione um profissional</option>
                        <?php foreach ($professionals as $professional): ?>
                            <option value="<?php echo $professional->id; ?>" <?php echo old('professional_id') == $professional->id ? 'selected' : ''; ?>><?php echo esc($professional->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo show_error_input('professional_id'); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="service_id">Serviço</label>
                    <select class="form-control" name="service_id" id="service_id" required>
                        <option value="">Selecione um serviço</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo $service->id; ?>" <?php echo old('service_id') == $service->id ? 'selected' : ''; ?>>
                                <?php echo esc($service->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo show_error_input('service_id'); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="chosen_date">Data e horário</label>
                    <input type="datetime-local" class="form-control" name="chosen_date" id="chosen_date" value="<?php echo old('chosen_date'); ?>" required>
                    <?php echo show_error_input('chosen_date'); ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Salvar agendamento</button>
            <a href="<?php echo route_to('super.home'); ?>" class="btn btn-secondary mt-4">Cancelar</a>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('js'); ?>
<script src="<?php echo base_url('back/mask/jquery.mask.min.js'); ?>"></script>
<script src="<?php echo base_url('back/mask/app.js'); ?>"></script>
<?php echo $this->endSection(); ?>
