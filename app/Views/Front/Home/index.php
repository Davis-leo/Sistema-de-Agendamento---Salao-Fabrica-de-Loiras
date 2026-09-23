<?php echo $this->extend('Front/Layout/main'); ?>


<?php echo $this->section('title'); ?>

<?php echo $title ?? 'Home'; ?>

<?php echo $this->endSection(); ?>


<?php echo $this->section('css'); ?>

<style>
    .home-page {
        background: var(--salon-cream);
        color: var(--salon-ink);
        margin-top: -1px;
    }

    .home-hero {
        align-items: flex-end;
        background: linear-gradient(90deg, rgba(45, 31, 27, .78) 0%, rgba(45, 31, 27, .34) 47%, rgba(45, 31, 27, .08) 100%), url('https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=1800&q=85') center 35% / cover;
        display: flex;
        min-height: min(700px, calc(100vh - 72px));
        padding: 5rem max(1.25rem, calc((100% - 1180px) / 2));
        position: relative;
    }

    .home-hero-content {
        color: #fff9f3;
        max-width: 620px;
    }

    .home-eyebrow {
        color: #f2c2b3;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .16em;
        margin: 0 0 1rem;
        text-transform: uppercase;
    }

    .home-hero h1 {
        color: #fff9f3;
        font-family: var(--salon-display);
        font-size: clamp(3.5rem, 7vw, 6.8rem);
        font-weight: 600;
        letter-spacing: 0;
        line-height: .88;
        margin: 0;
        max-width: 680px;
    }

    .home-hero-copy {
        color: rgba(255, 249, 243, .88);
        font-size: 1.08rem;
        line-height: 1.65;
        margin: 1.5rem 0 2rem;
        max-width: 470px;
    }

    .home-hero-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
    }

    .home-primary-action,
    .home-secondary-action {
        border-radius: 999px;
        display: inline-flex;
        font-size: .86rem;
        font-weight: 700;
        justify-content: center;
        padding: .85rem 1.35rem;
        text-decoration: none;
        transition: transform .2s ease, background-color .2s ease, color .2s ease;
    }

    .home-primary-action {
        background: #e6a59a;
        color: #332925;
    }

    .home-primary-action:hover {
        background: #f2c2b3;
        color: #332925;
        transform: translateY(-2px);
    }

    .home-secondary-action {
        border: 1px solid rgba(255, 249, 243, .55);
        color: #fff9f3;
    }

    .home-secondary-action:hover {
        background: rgba(255, 249, 243, .14);
        color: #fff9f3;
        transform: translateY(-2px);
    }

    .home-signals {
        background: #fffdfb;
        border-bottom: 1px solid var(--salon-line);
        border-top: 1px solid var(--salon-line);
    }

    .home-signals-inner {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        margin: 0 auto;
        max-width: 1180px;
    }

    .home-signal {
        border-right: 1px solid var(--salon-line);
        padding: 1.4rem 1.5rem;
    }

    .home-signal:last-child { border-right: 0; }
    .home-signal-label {
        color: var(--salon-rose-dark);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .1em;
        margin-bottom: .35rem;
        text-transform: uppercase;
    }
    .home-signal-text { color: var(--salon-muted); font-size: .9rem; }

    .home-services {
        margin: 0 auto;
        max-width: 1180px;
        padding: 4.5rem max(1.25rem, calc((100% - 1180px) / 2));
    }

    .home-services-heading {
        align-items: end;
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.7rem;
    }

    .home-services-heading h2 {
        font-family: var(--salon-display);
        font-size: 2.8rem;
        font-weight: 600;
        line-height: 1;
        margin: 0;
    }

    .home-services-heading p {
        color: var(--salon-muted);
        font-size: .9rem;
        margin: 0;
        max-width: 260px;
        text-align: right;
    }

    .home-service-list {
        display: grid;
        gap: 1.25rem 1.5rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .home-service-item {
        background: var(--salon-sand);
        border-left: 3px solid var(--salon-rose);
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        min-height: 118px;
        padding: 1.35rem 1.6rem;
    }

    .home-service-name {
        font-family: var(--salon-display);
        font-size: 1.45rem;
        font-weight: 600;
        line-height: 1;
    }
    .home-service-description {
        color: var(--salon-muted);
        font-size: .78rem;
        line-height: 1.45;
        margin-top: .7rem;
    }

    @media (max-width: 767.98px) {
        .home-hero {
            min-height: 650px;
            padding: 3rem 1.25rem;
        }
        .home-hero h1 { font-size: 4.3rem; }
        .home-signals-inner { grid-template-columns: 1fr; }
        .home-signal { border-bottom: 1px solid var(--salon-line); border-right: 0; padding: 1rem 1.25rem; }
        .home-signal:last-child { border-bottom: 0; }
        .home-services { padding-bottom: 3rem; padding-top: 3rem; }
        .home-services-heading { align-items: start; display: block; }
        .home-services-heading p { margin-top: .75rem; text-align: left; }
        .home-service-list { grid-template-columns: 1fr; }
        .home-service-item { min-height: 96px; }
    }
</style>

<?php echo $this->endSection(); ?>


<?php echo $this->section('content'); ?>


<div class="home-page">
    <section class="home-hero">
        <div class="home-hero-content">
            <p class="home-eyebrow">Fábrica de Loiras · Márcia Marques</p>
            <h1>Seu momento com a gente começa aqui.</h1>
            <p class="home-hero-copy">Cuidado, beleza e tempo para você. Escolha seus serviços e encontre o melhor horário para viver essa experiência.</p>
            <div class="home-hero-actions">
                <a href="<?php echo route_to('schedules.new'); ?>" class="home-primary-action">Agendar meu horário</a>
                <a href="<?php echo route_to('schedules.my'); ?>" class="home-secondary-action">Meus agendamentos</a>
            </div>
        </div>
    </section>

    <section class="home-signals" aria-label="Diferenciais">
        <div class="home-signals-inner">
            <div class="home-signal"><div class="home-signal-label">Atendimento pensado para você</div><div class="home-signal-text">Escolha um ou vários serviços no mesmo horário.</div></div>
            <div class="home-signal"><div class="home-signal-label">Horários disponíveis</div><div class="home-signal-text">Encontre a unidade e o profissional ideais.</div></div>
            <div class="home-signal"><div class="home-signal-label">Seu tempo importa</div><div class="home-signal-text">Uma experiência simples do início ao fim.</div></div>
        </div>
    </section>

    <section class="home-services">
        <div class="home-services-heading">
            <h2>Um cuidado completo.</h2>
            <p>Monte o atendimento que combina com o seu momento.</p>
        </div>
        <div class="home-service-list">
            <div class="home-service-item"><div class="home-service-name">Cabelo</div><div class="home-service-description">Para renovar o visual</div></div>
            <div class="home-service-item"><div class="home-service-name">Hidratação</div><div class="home-service-description">Para devolver vida aos fios</div></div>
            <div class="home-service-item"><div class="home-service-name">Unhas</div><div class="home-service-description">Para completar o cuidado</div></div>
            <div class="home-service-item"><div class="home-service-name">Seu ritual</div><div class="home-service-description">Do seu jeito, no seu tempo</div></div>
        </div>
    </section>
</div>


<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>



<?php echo $this->endSection(); ?>