<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Link enviado<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <section class="auth-intro" aria-label="Agendamentos">
        <div class="auth-mark">Agendamentos</div>
        <div>
            <h1>Quase tudo pronto.</h1>
            <p>Seu acesso está a caminho. Em poucos instantes, você poderá continuar de onde parou.</p>
        </div>
        <div class="auth-detail">Beleza, bem-estar e tempo para você.</div>
    </section>

    <section class="auth-panel">
        <div class="auth-content">
            <div class="auth-confirmation-mark" aria-hidden="true">&#10003;</div>
            <p class="auth-kicker">Confira sua caixa de entrada</p>
            <h2>Link enviado.</h2>
            <p class="auth-subtitle">Enviamos um link de acesso para o seu e-mail. Ele ficará disponível por <?= (int) (setting('Auth.magicLinkLifetime') / 60) ?> minutos.</p>
            <div class="auth-alert success" role="status">Abra o e-mail e toque em “Entrar” para acessar sua conta.</div>
            <div class="auth-links">
                <div><a href="<?= url_to('magic-link') ?>">Enviar novamente</a></div>
                <div><a href="<?= url_to('login') ?>">Voltar para o login</a></div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
