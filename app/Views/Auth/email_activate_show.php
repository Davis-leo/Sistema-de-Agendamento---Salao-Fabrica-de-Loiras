<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Ativar e-mail<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <section class="auth-intro" aria-label="Agendamentos">
        <div class="auth-mark">Agendamentos</div>
        <div>
            <h1>Quase tudo pronto.</h1>
            <p>Confirme seu endereço de e-mail para proteger sua conta e começar a agendar.</p>
        </div>
        <div class="auth-detail">Beleza, bem-estar e tempo para você.</div>
    </section>

    <section class="auth-panel">
        <div class="auth-content">
            <h2>Ativar e-mail</h2>
            <p class="auth-subtitle">Digite o código de confirmação enviado para o seu e-mail.</p>

            <?php if (session('error')) : ?>
                <div class="auth-alert error" role="alert"><?= esc(session('error')) ?></div>
            <?php endif ?>

            <form action="<?= url_to('auth-action-verify') ?>" method="post">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="token">Código de confirmação</label>
                    <input type="text" id="token" name="token" placeholder="000000" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" value="<?= old('token') ?>" required>
                </div>

                <button type="submit" class="auth-button">Confirmar e-mail</button>
            </form>
        </div>
    </section>
<?= $this->endSection() ?>
