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

    .admin-services {
        display: grid;
        gap: .65rem;
        grid-auto-rows: 44px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .admin-service-option {
        align-items: center;
        background: #fffdfb;
        border: 1px solid rgba(145, 70, 70, .35);
        border-radius: 999px;
        cursor: pointer;
        display: flex;
        gap: .6rem;
        height: 44px;
        min-height: 44px;
        padding: .65rem .85rem;
        width: 100%;
        transition: background-color .2s ease, border-color .2s ease, color .2s ease;
    }

    .admin-service-option:has(input:checked) {
        background: var(--salon-rose);
        border-color: var(--salon-rose);
        color: #fff;
    }

    .admin-service-option input { accent-color: var(--salon-rose); }

    #admin-professionals {
        min-height: 44px;
    }

    #admin-professionals .professional-group {
        background: #fffdfb;
        border: 1px solid rgba(82, 59, 49, .14);
        border-radius: 8px;
        margin-bottom: .75rem;
        padding: .75rem;
    }

    #admin-professionals .professional-group:last-child { margin-bottom: 0; }

    #admin-professionals .professional-group-title {
        color: var(--salon-muted);
        font-size: .75rem;
        font-weight: 700;
        margin-bottom: .65rem;
        text-transform: uppercase;
    }

    #admin-professionals .professional-group-service {
        color: var(--salon-ink);
        margin-left: .35rem;
        text-transform: none;
    }

    #admin-professionals .professionals-grid {
        display: grid;
        gap: .5rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    #admin-professionals .professional-choice {
        background: #fff;
        border: 1px solid var(--salon-rose);
        border-radius: 999px;
        color: var(--salon-rose-dark);
        cursor: pointer;
        font: inherit;
        font-size: .85rem;
        font-weight: 700;
        min-height: 40px;
        padding: .55rem .75rem;
        transition: background-color .2s ease, color .2s ease;
    }

    #admin-professionals .professional-choice:hover,
    #admin-professionals .professional-choice[aria-pressed="true"] {
        background: var(--salon-rose);
        color: #fff;
    }

    @media (max-width: 767.98px) {
        #admin-professionals .professionals-grid { grid-template-columns: 1fr; }
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
                    <label>Serviços</label>
                    <div class="admin-services">
                        <?php $oldServiceIds = array_map('intval', (array) old('service_ids')); ?>
                        <?php foreach ($services as $service): ?>
                            <label class="admin-service-option">
                                <input type="checkbox" name="service_ids[]" value="<?php echo $service->id; ?>" <?php echo in_array((int) $service->id, $oldServiceIds, true) ? 'checked' : ''; ?>>
                                <span><?php echo esc($service->name); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php echo show_error_input('service_ids'); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="chosen_date">Data e horário</label>
                    <input type="datetime-local" class="form-control" name="chosen_date" id="chosen_date" value="<?php echo old('chosen_date'); ?>" required>
                    <?php echo show_error_input('chosen_date'); ?>
                </div>
                <div class="form-group col-md-6">
                    <label>Profissionais por serviço</label>
                    <div id="admin-professionals" class="text-muted">Selecione unidade, serviços e horário para carregar as profissionais disponíveis.</div>
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
<script>
    const professionalsUrl = '<?php echo route_to('get.professionals'); ?>';
    const unitInput = document.getElementById('unit_id');
    const dateInput = document.getElementById('chosen_date');
    const professionalsBox = document.getElementById('admin-professionals');
    const serviceInputs = () => Array.from(document.querySelectorAll('input[name="service_ids[]"]:checked'));

    const loadProfessionals = async () => {
        const serviceIds = serviceInputs().map(input => input.value);
        const chosenDate = dateInput.value;
        if (!unitInput.value || !serviceIds.length || !chosenDate) {
            professionalsBox.innerHTML = 'Selecione unidade, serviços e horário para carregar as profissionais disponíveis.';
            return;
        }

        const [date, hour] = chosenDate.split('T');
        const [year, month, day] = date.split('-');
        const params = new URLSearchParams({
            unit_id: unitInput.value,
            service_ids: serviceIds.join(','),
            month,
            day,
            hour,
        });
        professionalsBox.innerHTML = '<span class="text-info">Carregando profissionais...</span>';
        const response = await fetch(`${professionalsUrl}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (!response.ok) {
            professionalsBox.innerHTML = '<div class="alert alert-warning">Não foi possível carregar as profissionais.</div>';
            return;
        }

        professionalsBox.innerHTML = (await response.json()).professionals;
        document.querySelectorAll('#admin-professionals .professional-choice').forEach(button => {
            button.type = 'button';
            button.addEventListener('click', () => {
                const serviceGroupIds = button.dataset.serviceIds.split(',');
                const selected = Array.from(document.querySelectorAll('input[name^="professional_assignments"]'))
                    .filter(input => !serviceGroupIds.includes(input.dataset.serviceId))
                    .map(input => input.value);
                if (selected.includes(button.dataset.professionalId)) {
                    alert('Escolha profissionais diferentes para serviços simultâneos.');
                    return;
                }
                serviceGroupIds.forEach(serviceId => {
                    let input = document.querySelector(`input[name="professional_assignments[${serviceId}]"]`);
                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `professional_assignments[${serviceId}]`;
                        input.dataset.serviceId = serviceId;
                        professionalsBox.appendChild(input);
                    }
                    input.value = button.dataset.professionalId;
                });
                document.querySelectorAll('#admin-professionals .professional-choice').forEach(option => {
                    const sameGroup = option.dataset.serviceIds.split(',').some(id => serviceGroupIds.includes(id));
                    if (sameGroup) option.setAttribute('aria-pressed', option === button ? 'true' : 'false');
                });
            });
        });
    };

    unitInput.addEventListener('change', loadProfessionals);
    dateInput.addEventListener('change', loadProfessionals);
    document.querySelectorAll('input[name="service_ids[]"]').forEach(input => input.addEventListener('change', loadProfessionals));
</script>
<?php echo $this->endSection(); ?>
