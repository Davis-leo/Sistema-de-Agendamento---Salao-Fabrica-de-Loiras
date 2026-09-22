<?php echo $this->extend('Back/Layout/main'); ?>
<?php echo $this->section('title'); ?><?php echo $title; ?><?php echo $this->endSection(); ?>
<?php echo $this->section('content'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6><?php echo $title; ?></h6>
            <a href="<?php echo route_to('professionals.new'); ?>" class="btn btn-primary">Novo profissional</a>
        </div>
        <div class="card-body">
            <?php if (empty($professionals)): ?>
                <p class="text-muted">Nenhum profissional cadastrado.</p>
            <?php else: ?>
                <div class="table-responsive"><table class="table">
                    <thead><tr><th>Nome</th><th>Unidade</th><th>Comissão</th><th>Status</th><th>Ações</th></tr></thead>
                    <tbody><?php foreach ($professionals as $professional): ?>
                        <tr>
                            <td><?php echo esc($professional->name); ?></td>
                            <td><?php echo esc($unitsById[$professional->unit_id] ?? 'Não definida'); ?></td>
                            <td><?php echo number_format($professional->commission_percentage, 0, ',', '.'); ?>%</td>
                            <td><?php echo $professional->status(); ?></td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo route_to('professionals.edit', $professional->id); ?>">Editar</a>
                                <?php echo form_open(route_to('professionals.action', $professional->id), ['class' => 'd-inline'], ['_method' => 'PUT']); ?>
                                    <button class="btn btn-sm btn-secondary" type="submit"><?php echo $professional->textToAction(); ?></button>
                                <?php echo form_close(); ?>
                                <?php echo form_open(route_to('professionals.destroy', $professional->id), ['class' => 'd-inline', 'onsubmit' => 'return confirm("Deseja excluir este profissional?");'], ['_method' => 'DELETE']); ?>
                                    <button class="btn btn-sm btn-outline-primary" type="submit">Excluir</button>
                                <?php echo form_close(); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?></tbody>
                </table></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>
