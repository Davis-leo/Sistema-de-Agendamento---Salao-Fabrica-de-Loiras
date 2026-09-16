<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Entrar<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <section class="auth-intro" aria-label="Agendamentos">
        <div class="auth-mark">Agendamentos</div>
        <div>
            <h1>Seu tempo, bem cuidado.</h1>
            <p>Entre para organizar seus próximos momentos de cuidado com calma e praticidade.</p>
        </div>
        <div class="auth-detail">Beleza, bem-estar e tempo para você.</div>
    </section>

    <section class="auth-panel">
        <div class="auth-content">
            <h2>Entrar</h2>
            <p class="auth-subtitle">Acesse sua conta para continuar.</p>

            <?php if (session('error') !== null) : ?>
                <?php $errorMessage = session('error') === 'Unable to log you in. Please check your credentials.' ? 'Não foi possível fazer login. Verifique suas credenciais.' : session('error') ?>
                <div class="auth-alert error" role="alert"><?= esc($errorMessage) ?></div>
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

            <?php if (session('message') !== null) : ?>
                <div class="auth-alert success" role="alert"><?= esc(session('message')) ?></div>
            <?php endif ?>

            <form action="<?= url_to('login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" inputmode="email" autocomplete="email" placeholder="seu@email.com" value="<?= old('email') ?>" required>
                </div>

                <div class="field">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" inputmode="text" autocomplete="current-password" placeholder="Sua senha" required>
                </div>

                <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                    <label class="remember">
                        <input type="checkbox" name="remember" <?php if (old('remember')): ?> checked<?php endif ?>>
                        Lembrar de mim
                    </label>
                <?php endif; ?>

                <button type="submit" class="auth-button">Entrar</button>

                <div class="auth-links">
                    <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                        <div>Esqueceu sua senha? <a href="<?= url_to('magic-link') ?>">Entrar com link mágico</a></div>
                    <?php endif ?>
                    <?php if (setting('Auth.allowRegistration')) : ?>
                        <div>Ainda não tem uma conta? <a href="<?= url_to('register') ?>">Criar conta</a></div>
                    <?php endif ?>
                </div>
            </form>
        </div>
    </section>
<?= $this->endSection() ?>
