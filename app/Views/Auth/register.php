<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Criar conta<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <section class="auth-intro" aria-label="Agendamentos">
        <div class="auth-mark">Agendamentos</div>
        <div>
            <h1>Um cuidado só seu.</h1>
            <p>Crie sua conta para deixar seus próximos agendamentos sempre à mão.</p>
        </div>
        <div class="auth-detail">Beleza, bem-estar e tempo para você.</div>
    </section>

    <section class="auth-panel">
        <div class="auth-content">
            <h2>Criar conta</h2>
            <p class="auth-subtitle">Preencha seus dados para começar.</p>

            <?php if (session('error') !== null) : ?>
                <div class="auth-alert error" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="auth-alert error" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <ul class="auth-error-list">
                            <?php foreach (session('errors') as $error) : ?>
                                <?php $errorMessage = match ($error) {
                                    'The Username field is not in the correct format.' => 'O nome de usuário deve ter entre 3 e 30 caracteres e conter apenas letras, números e ponto, sem espaços ou acentos.',
                                    'The Username field must contain a unique value.' => 'Esse nome de usuário já está sendo usado. Escolha outro nome.',
                                    'Password must not be a common password.' => 'A senha deve ter pelo menos 8 caracteres, não pode ser uma senha comum e não deve conter seu nome de usuário ou e-mail.',
                                    'The Password (again) field does not match the Password field.' => 'As senhas não coincidem. Digite a mesma senha nos dois campos.',
                                    default => $error,
                                } ?>
                                <li><?= esc($errorMessage) ?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php else : ?>
                        <?= esc(session('errors')) ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <form action="<?= url_to('register') ?>" method="post">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" inputmode="email" autocomplete="email" placeholder="seu@email.com" value="<?= old('email') ?>" required>
                </div>

                <div class="field">
                    <label for="username">Nome de usuário</label>
                    <input type="text" id="username" name="username" inputmode="text" autocomplete="username" placeholder="Como gostaria de ser chamado?" value="<?= old('username') ?>" required>
                </div>

                <div class="field">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" inputmode="text" autocomplete="new-password" placeholder="Crie uma senha" required>
                </div>

                <div class="field">
                    <label for="password_confirm">Confirmar senha</label>
                    <input type="password" id="password_confirm" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="Repita sua senha" required>
                </div>

                <button type="submit" class="auth-button">Criar conta</button>

                <div class="auth-links">
                    Já possui uma conta? <a href="<?= url_to('login') ?>">Entrar</a>
                </div>
            </form>
        </div>
    </section>
<?= $this->endSection() ?>
