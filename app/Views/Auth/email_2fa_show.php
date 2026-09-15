<?php

use CodeIgniter\Shield\Entities\User;

?>

<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Confirmação em dois fatores<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <section class="auth-intro" aria-label="Agendamentos">
        <div class="auth-mark">Agendamentos</div>
        <div>
            <h1>Mais segurança para você.</h1>
            <p>Confirme seu e-mail para receber o código de segurança e continuar.</p>
        </div>
        <div class="auth-detail">Beleza, bem-estar e tempo para você.</div>
    </section>

    <section class="auth-panel">
        <div class="auth-content">
            <h2>Confirmação em dois fatores</h2>
            <p class="auth-subtitle">Confirme seu endereço de e-mail para receber o código.</p>

            <?php if (session('error')) : ?>
                <?php $errorMessage = session('error') === 'You must complete a two-factor verification.' ? 'Você precisa concluir a verificação em dois fatores.' : session('error') ?>
                <div class="auth-alert error" role="alert"><?= esc($errorMessage) ?></div>
            <?php endif ?>

            <form action="<?= url_to('auth-action-handle') ?>" method="post">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" inputmode="email" autocomplete="email" placeholder="seu@email.com"
                        <?php /** @var User $user */ ?>
                        value="<?= old('email', $user->email) ?>" required>
                </div>

                <button type="submit" class="auth-button">Enviar código</button>
            </form>
        </div>
    </section>
<?= $this->endSection() ?>
