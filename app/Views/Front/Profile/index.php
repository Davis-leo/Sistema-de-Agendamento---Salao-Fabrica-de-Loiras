<?php echo $this->extend('Front/Layout/main'); ?>
<?php echo $this->section('title'); ?>Meus Dados<?php echo $this->endSection(); ?>
<?php echo $this->section('css'); ?>
<style>
    .profile-page { background: var(--salon-cream); min-height: calc(100vh - 72px); padding: 4rem 1.25rem; }
    .profile-panel { background: rgba(255, 255, 255, .9); border: 1px solid var(--salon-line); box-shadow: var(--salon-shadow); margin: 0 auto; max-width: 620px; padding: 2.5rem; }
    .profile-panel h1 { font-family: var(--salon-display); margin-bottom: .5rem; }
    .profile-subtitle { color: var(--salon-muted); margin-bottom: 2rem; }
    .profile-panel label { color: var(--salon-ink); display: block; font-size: .78rem; font-weight: 700; letter-spacing: .06em; margin-bottom: .45rem; text-transform: uppercase; }
    .profile-panel input { background: #fffdfb; border: 1px solid var(--salon-line); border-radius: 5px; font: inherit; padding: .85rem 1rem; width: 100%; }
    .profile-panel input:focus { border-color: var(--salon-rose); box-shadow: 0 0 0 .2rem rgba(184, 92, 91, .14); outline: 0; }
    .profile-actions { align-items: center; display: flex; gap: .75rem; margin-top: 1.5rem; }
    .profile-button { background: var(--salon-rose); border: 0; border-radius: 999px; color: #fff; font: inherit; font-weight: 700; padding: .85rem 1.25rem; }
    .profile-link { color: var(--salon-rose-dark); font-weight: 700; }
</style>
<?php echo $this->endSection(); ?>
<?php echo $this->section('content'); ?>
<div class="profile-page">
    <div class="profile-panel">
        <h1>Meus Dados</h1>
        <p class="profile-subtitle">Mantenha seu nome e telefone atualizados para facilitar seus agendamentos.</p>
        <?php if (session('info')): ?><div class="alert alert-info"><?php echo esc(session('info')); ?></div><?php endif; ?>
        <?php if (session('success')): ?><div class="alert alert-success"><?php echo esc(session('success')); ?></div><?php endif; ?>
        <?php if (session('errors')): ?><div class="alert alert-danger"><?php foreach ((array) session('errors') as $error): ?><div><?php echo esc($error); ?></div><?php endforeach; ?></div><?php endif; ?>
        <?php echo form_open(route_to('profile.update')); ?>
            <div class="mb-3"><label for="username">Nome</label><input type="text" id="username" name="username" value="<?php echo esc(old('username', $user->username)); ?>" maxlength="30" required></div>
            <div class="mb-3"><label for="phone">Telefone</label><input type="tel" id="phone" name="phone" value="<?php echo esc(old('phone', $user->phone ?? '')); ?>" placeholder="(00) 00000-0000" maxlength="15" inputmode="tel" autocomplete="tel" required></div>
            <div class="profile-actions"><button class="profile-button" type="submit">Salvar dados</button><a class="profile-link" href="<?php echo route_to('schedules.my'); ?>">Meus Agendamentos</a></div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', () => {
        const digits = phoneInput.value.replace(/\D/g, '').slice(0, 11);
        if (digits.length <= 10) {
            phoneInput.value = digits.replace(/(\d{2})(\d{0,4})(\d{0,4})/, (_, area, first, last) =>
                '(' + area + (first ? ') ' + first : '') + (last ? '-' + last : '')
            );
        } else {
            phoneInput.value = digits.replace(/(\d{2})(\d{0,5})(\d{0,4})/, (_, area, first, last) =>
                '(' + area + (first ? ') ' + first : '') + (last ? '-' + last : '')
            );
        }
    });
</script>
<?php echo $this->endSection(); ?>
