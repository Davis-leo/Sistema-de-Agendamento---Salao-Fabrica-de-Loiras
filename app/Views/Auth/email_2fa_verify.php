<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Confirmação em dois fatores<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <section class="auth-intro" aria-label="Agendamentos">
        <div class="auth-mark">Agendamentos</div>
        <div>
            <h1>Seu código chegou.</h1>
            <p>Digite o código enviado para o seu e-mail para concluir o acesso.</p>
        </div>
        <div class="auth-detail">Beleza, bem-estar e tempo para você.</div>
    </section>

    <section class="auth-panel">
        <div class="auth-content">
            <h2>Digite o código</h2>
            <p class="auth-subtitle">Use o código de segurança que enviamos para o seu e-mail.</p>

            <?php if (session('error') !== null) : ?>
                <?php $errorMessage = session('error') === 'The code was incorrect.' ? 'O código informado está incorreto.' : session('error') ?>
                <div class="auth-alert error" role="alert"><?= esc($errorMessage) ?></div>
            <?php endif ?>

            <form action="<?= url_to('auth-action-verify') ?>" method="post">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="token">Código de segurança</label>
                    <input type="number" id="token" name="token" placeholder="000000" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" required>
                </div>

                <button type="submit" class="auth-button">Confirmar código</button>
            </form>
        </div>
    </section>
<?= $this->endSection() ?>
