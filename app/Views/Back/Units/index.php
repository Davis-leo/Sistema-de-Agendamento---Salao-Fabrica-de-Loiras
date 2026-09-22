<?php echo $this->extend('Back/Layout/main'); ?>



<?php echo $this->section('title'); ?>

<?php echo $title ?? 'Unidades'; ?>

<?php echo $this->endSection(); ?>



<?php echo $this->section('css'); ?>

<link href="<?php echo base_url('back/'); ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

<?php echo $this->endSection(); ?>



<?php echo $this->section('content'); ?>

<div class="container-fluid">

    <div class="card shadow mb-4">

        <div class="card-header py-3 d-flex justify-content-between align-items-center">

            <h6 class="m-0 font-weight-bold text-primary"><?php echo $title; ?></h6>

            <a href="<?php echo route_to('units.new'); ?>" class="btn btn-success btn-sm">Nova</a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Nome</th>

                            <th>Ações</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (!empty($units)): ?>

                        <?php foreach ($units as $unit): ?>

                        <tr>

                            <td><?= esc($unit->id ?? $unit['id'] ?? '') ?></td>

                            <td><?= esc($unit->name ?? $unit['name'] ?? $unit->nome ?? $unit->title ?? '') ?></td>

                            <td>

                                <a href="<?= route_to('units.edit', $unit->id ?? $unit['id']) ?>" class="btn btn-primary btn-sm">Editar</a>

                                <a href="<?= route_to('units.delete', $unit->id ?? $unit['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remover?')">Excluir</a>

                            </td>

                        </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr><td colspan="3" class="text-center">Nenhuma unidade</td></tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php echo $this->endSection(); ?>



<?php echo $this->section('js'); ?>

<script src="<?php echo base_url('back/'); ?>vendor/datatables/jquery.dataTables.min.js"></script>

<script src="<?php echo base_url('back/'); ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script src="<?php echo base_url('back/'); ?>js/demo/datatables-demo.js"></script>

<?php echo $this->endSection(); ?>