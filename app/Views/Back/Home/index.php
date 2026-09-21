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
        grid-template-columns: repeat(4, 1fr);
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
                            <div class="schedule-service"><?php echo esc($schedule->service); ?></div>
                            <div class="schedule-meta"><?php echo esc($schedule->unit); ?></div>
                        </div>
                        <div class="schedule-user"><?php echo esc($schedule->user); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h2>Acessos rápidos</h2>
                <span>Administração</span>
            </div>
            <div class="dashboard-actions">
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
            </div>
        </section>
    </div>

</div>


<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>



<?php echo $this->endSection(); ?>