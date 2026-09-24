<?php echo $this->extend('Back/Layout/main'); ?>
<?php echo $this->section('title'); ?>Relatórios<?php echo $this->endSection(); ?>
<?php echo $this->section('content'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Relatório semanal</h6>
        </div>
        <div class="card-body">
            <p class="text-muted">Selecione uma data dentro da semana desejada para gerar o resumo financeiro do salão.</p>
            <?php echo form_open(route_to('reports.pdf'), ['method' => 'get']); ?>
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label for="week_start">Semana de referência</label>
                        <input type="date" class="form-control" id="week_start" name="week_start" value="<?php echo esc($weekStart); ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <button type="submit" class="btn btn-primary">Baixar relatório PDF</button>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>
