<?php echo $this->extend('Back/Layout/main'); ?>


<?php echo $this->section('title'); ?>

<?php echo $title ?? 'Início'; ?>

<?php echo $this->endSection(); ?>


<?php echo $this->section('css'); ?>

<style>
    .dashboard-intro {
        align-items: end;
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .dashboard-eyebrow {
        color: var(--salon-rose-dark);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .14em;
        margin: 0 0 .5rem;
        text-transform: uppercase;
    }

    .dashboard-intro h1 {
        font-size: 2.6rem;
        margin: 0;
    }

    .dashboard-intro p {
        color: var(--salon-muted);
        margin: .5rem 0 0;
    }

    .dashboard-date {
        color: var(--salon-muted);
        font-size: .82rem;
        font-weight: 600;
    }

    .dashboard-stats {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(5, 1fr);
        margin-bottom: 1.5rem;
    }

    .dashboard-stat {
        background: rgba(255, 255, 255, .9);
        border: 1px solid var(--salon-line);
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(74, 48, 37, .07);
        padding: 1.25rem;
    }

    .dashboard-stat-label {
        color: var(--salon-muted);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .dashboard-stat-value {
        color: var(--salon-ink);
        font-family: var(--salon-display);
        font-size: 2.4rem;
        font-weight: 700;
        line-height: 1;
        margin-top: .8rem;
    }

    .dashboard-stat-detail {
        color: var(--salon-rose-dark);
        font-size: .75rem;
        margin-top: .65rem;
    }

    .dashboard-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: minmax(0, 1.5fr) minmax(260px, .8fr);
    }

    .dashboard-panel {
        background: rgba(255, 255, 255, .9);
        border: 1px solid var(--salon-line);
        border-radius: 8px;
        box-shadow: var(--salon-shadow);
        padding: 1.5rem;
    }

    .dashboard-panel-header {
        align-items: center;
        border-bottom: 1px solid var(--salon-line);
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }

    .dashboard-panel h2 {
        font-size: 1.7rem;
        margin: 0;
    }

    .dashboard-panel-header span {
        color: var(--salon-muted);
        font-size: .78rem;
    }

    .schedule-row {
        align-items: center;
        border-bottom: 1px solid var(--salon-line);
        display: grid;
        gap: 1rem;
        grid-template-columns: 120px minmax(0, 1fr) auto;
        padding: 1rem 0;
    }

    .schedule-row:last-child { border-bottom: 0; }

    .schedule-time {
        color: var(--salon-rose-dark);
        font-size: .82rem;
        font-weight: 700;
    }

    .schedule-service {
        color: var(--salon-ink);
        font-weight: 700;
    }

    .schedule-meta {
        color: var(--salon-muted);
        font-size: .76rem;
        margin-top: .2rem;
    }

    .schedule-user {
        background: var(--salon-sand);
        border-radius: 999px;
        color: var(--salon-rose-dark);
        font-size: .72rem;
        font-weight: 700;
        padding: .4rem .65rem;
    }

    .dashboard-empty {
        color: var(--salon-muted);
        padding: 2rem 0;
        text-align: center;
    }

    .unit-financials {
        display: grid;
        gap: .8rem;
    }

    .unit-financial-row {
        border-bottom: 1px solid var(--salon-line);
        display: grid;
        gap: 1rem;
        grid-template-columns: minmax(150px, 1.2fr) repeat(3, minmax(100px, 1fr));
        padding: .85rem 0;
    }

    .unit-financial-row:last-child { border-bottom: 0; }

    .unit-financial-label {
        color: var(--salon-muted);
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .unit-financial-value {
        color: var(--salon-ink);
        font-size: .9rem;
        font-weight: 700;
        margin-top: .25rem;
    }

    .unit-financial-net .unit-financial-value { color: #3f8062; }

    .dashboard-actions {
        display: grid;
        gap: .75rem;
    }

    .dashboard-action {
        align-items: center;
        background: var(--salon-sand);
        border-radius: 5px;
        color: var(--salon-ink);
        display: flex;
        font-weight: 700;
        justify-content: space-between;
        padding: .9rem 1rem;
        text-decoration: none;
    }

    .dashboard-action:hover {
        background: var(--salon-rose);
        color: #fff;
        text-decoration: none;
    }

    .dashboard-action i { color: var(--salon-rose-dark); }
    .dashboard-action:hover i { color: #fff; }

    @media (max-width: 900px) {
        .dashboard-stats { grid-template-columns: repeat(2, 1fr); }
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 575.98px) {
        .dashboard-intro { align-items: flex-start; flex-direction: column; }
        .dashboard-date { margin-top: 1rem; }
        .dashboard-stats { grid-template-columns: 1fr 1fr; }
        .dashboard-stat-value { font-size: 2rem; }
        .schedule-row { gap: .35rem; grid-template-columns: 1fr auto; }
        .schedule-time { grid-column: 1 / -1; }
        .unit-financial-row { grid-template-columns: 1fr 1fr; }
        .unit-financial-row > div:first-child { grid-column: 1 / -1; }
    }
</style>

<?php echo $this->endSection(); ?>


<?php echo $this->section('content'); ?>


<div class="container-fluid">
    <div class="dashboard-intro">
        <div>
            <p class="dashboard-eyebrow">Visão geral</p>
            <h1><?php echo $title ?? 'Painel'; ?></h1>
            <p>Acompanhe a operação do salão em um só lugar.</p>
        </div>
        <div class="dashboard-date"><?php echo date('d/m/Y'); ?></div>
    </div>

    <div class="dashboard-stats">
        <div class="dashboard-stat">
            <div class="dashboard-stat-label">Unidades ativas</div>
            <div class="dashboard-stat-value"><?php echo $activeUnits; ?></div>
            <div class="dashboard-stat-detail">Locais em funcionamento</div>
        </div>
        <div class="dashboard-stat">
            <div class="dashboard-stat-label">Comissões da semana</div>
            <div class="dashboard-stat-value">R$ <?php echo number_format(array_sum(array_map(static fn ($item) => (float) $item->total, $weeklyCommissions)), 2, ',', '.'); ?></div>
            <div class="dashboard-stat-detail">Atendimentos confirmados</div>
        </div>
        <div class="dashboard-stat">
            <div class="dashboard-stat-label">Serviços ativos</div>
            <div class="dashboard-stat-value"><?php echo $activeServices; ?></div>
            <div class="dashboard-stat-detail">Opções disponíveis</div>
        </div>
        <div class="dashboard-stat">
            <div class="dashboard-stat-label">Agendamentos</div>
            <div class="dashboard-stat-value"><?php echo $totalSchedules; ?></div>
            <div class="dashboard-stat-detail">Registros cadastrados</div>
        </div>
        <div class="dashboard-stat">
            <div class="dashboard-stat-label">Cancelamentos</div>
            <div class="dashboard-stat-value"><?php echo $canceledSchedules; ?></div>
            <div class="dashboard-stat-detail">Agendamentos cancelados</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h2>Próximos atendimentos</h2>
                <span>Agenda futura</span>
            </div>

            <?php if (empty($nextSchedules)): ?>
                <div class="dashboard-empty">Nenhum atendimento futuro encontrado.</div>
            <?php else: ?>
                <?php foreach ($nextSchedules as $schedule): ?>
                    <div class="schedule-row">
                        <div class="schedule-time"><?php echo esc($schedule->formated_chosen_date); ?></div>
                        <div>
                            <div class="schedule-service"><?php echo esc($schedule->service_professionals ?: $schedule->service); ?></div>
                            <div class="schedule-meta"><?php echo esc($schedule->unit); ?></div>
                        </div>
                        <div class="schedule-user"><?php echo esc($schedule->user); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h2>Resultado por unidade</h2>
                <span><?php echo date('d/m', strtotime($weekStart)); ?> a <?php echo date('d/m', strtotime($weekEnd)); ?></span>
            </div>
            <div class="unit-financials">
                <div class="unit-financial-row">
                    <div class="unit-financial-label">Unidade</div>
                    <div class="unit-financial-label">Bruto</div>
                    <div class="unit-financial-label">Comissão</div>
                    <div class="unit-financial-label">Líquido</div>
                </div>
                <?php if (empty($unitFinancials)): ?>
                    <div class="dashboard-empty">Nenhuma unidade ativa cadastrada.</div>
                <?php else: ?>
                    <?php foreach ($unitFinancials as $unitFinancial): ?>
                        <div class="unit-financial-row">
                            <div class="unit-financial-value"><?php echo esc($unitFinancial->name); ?></div>
                            <div>
                                <div class="unit-financial-label">Faturamento bruto</div>
                                <div class="unit-financial-value">R$ <?php echo number_format($unitFinancial->gross, 2, ',', '.'); ?></div>
                            </div>
                            <div>
                                <div class="unit-financial-label">A pagar</div>
                                <div class="unit-financial-value">R$ <?php echo number_format($unitFinancial->commission, 2, ',', '.'); ?></div>
                            </div>
                            <div class="unit-financial-net">
                                <div class="unit-financial-label">Resultado líquido</div>
                                <div class="unit-financial-value">R$ <?php echo number_format($unitFinancial->net, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h2>Acessos rápidos</h2>
                <span>Administração</span>
            </div>
            <div class="dashboard-actions">
                <a class="dashboard-action" href="<?php echo route_to('super.schedules.new'); ?>">
                    <span>Novo agendamento</span>
                    <i class="fas fa-calendar-plus"></i>
                </a>
                <a class="dashboard-action" href="<?php echo route_to('units'); ?>">
                    <span>Gerenciar unidades</span>
                    <i class="fas fa-building"></i>
                </a>
                <a class="dashboard-action" href="<?php echo route_to('units.new'); ?>">
                    <span>Cadastrar unidade</span>
                    <i class="fas fa-plus"></i>
                </a>
                <a class="dashboard-action" href="<?php echo route_to('services'); ?>">
                    <span>Gerenciar serviços</span>
                    <i class="fas fa-concierge-bell"></i>
                </a>
                <a class="dashboard-action" href="<?php echo route_to('services.new'); ?>">
                    <span>Cadastrar serviço</span>
                    <i class="fas fa-plus"></i>
                </a>
                <a class="dashboard-action" href="<?php echo route_to('professionals'); ?>">
                    <span>Gerenciar profissionais</span>
                    <i class="fas fa-user-tie"></i>
                </a>
                <a class="dashboard-action" href="<?php echo route_to('commissions'); ?>">
                    <span>Ver comissões</span>
                    <i class="fas fa-coins"></i>
                </a>
            </div>
        </section>

        <section class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h2>Comissões por profissional</h2>
                <span><?php echo date('d/m', strtotime($weekStart)); ?> a <?php echo date('d/m', strtotime($weekEnd)); ?></span>
            </div>
            <?php if (empty($weeklyCommissions)): ?>
                <div class="dashboard-empty">Nenhuma comissão confirmada nesta semana.</div>
            <?php else: ?>
                <?php foreach ($weeklyCommissions as $commission): ?>
                    <div class="schedule-row">
                        <div class="schedule-service"><?php echo esc($commission->professional); ?></div>
                        <div class="schedule-meta"><?php echo (int) $commission->appointments; ?> atendimento(s)</div>
                        <div class="schedule-user">R$ <?php echo number_format((float) $commission->total, 2, ',', '.'); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </div>

</div>


<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>



<?php echo $this->endSection(); ?>