<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Entrar com link mágico<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <section class="auth-intro" aria-label="Agendamentos">
        <div class="auth-mark">Agendamentos</div>
        <div>
            <h1>Um acesso sem pressa.</h1>
            <p>Receba um link seguro no seu e-mail e entre para cuidar dos seus próximos momentos.</p>
        </div>
        <div class="auth-detail">Beleza, bem-estar e tempo para você.</div>
    </section>

    <section class="auth-panel">
        <div class="auth-content">
            <div class="auth-confirmation-mark" aria-hidden="true">&#8594;</div>
            <p class="auth-kicker">Acesso seguro</p>
            <h2>Link mágico</h2>
            <p class="auth-subtitle">Informe seu e-mail. Enviaremos um link para acessar sua conta sem digitar a senha.</p>

            <?php if (session('error') !== null) : ?>
                <div class="auth-alert error" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="auth-alert error" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= esc($error) ?><br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= esc(session('errors')) ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <form action="<?= url_to('magic-link') ?>" method="post">
                <?= csrf_field() ?>
                <div class="field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" autocomplete="email" inputmode="email" placeholder="seu@email.com" value="<?= old('email', auth()->user()->email ?? null) ?>" required>
                </div>
                <button type="submit" class="auth-button">Enviar link de acesso</button>
            </form>

            <div class="auth-links">
                <div><a href="<?= url_to('login') ?>">Voltar para entrar com senha</a></div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
