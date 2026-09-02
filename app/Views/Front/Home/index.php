<?php echo $this->extend('Front/Layout/main'); ?>


<?php echo $this->section('title'); ?>

<?php echo $title ?? 'Home'; ?>

<?php echo $this->endSection(); ?>


<?php echo $this->section('css'); ?>


<?php echo $this->endSection(); ?>


<?php echo $this->section('content'); ?>


<!-- Begin Page Content -->
<div class="container pt-5 text-center">
    <h1 class="mt-5">Faça o seu agendamento</h1>

    <div class="row mt-4">

        <div class="col">
            <div class="card">
                <div class="card-header">
                    Primeiro
                </div>

                <div class="card-body">
                    <h5 class="card-title">Autenticação</h5>
                    <p class="card-text">Faça login ou crie a sua conta</p>
                </div>
            </div>


        </div>

        <div class="col">
            <div class="card">
                <div class="card-header">
                    Segundo
                </div>

                <div class="card-body">
                    <h5 class="card-title">Escolha a unidade</h5>
                    <p class="card-text">Onde você gostaria de ser atendido</p>
                </div>


            </div>


        </div>

        <div class="col">
            <div class="card">
                <div class="card-header">
                    Terceiro
                </div>

                <div class="card-body">
                    <h5 class="card-title">Escolha o serviço</h5>
                    <p class="card-text">Selecione o serviço que você deseja agendar</p>
                </div>
            </div>


        </div>

        <div class="col">
            <div class="card">
                <div class="card-header">
                    Quarto
                </div>

                <div class="card-body">
                    <h5 class="card-title">Escolha a data</h5>
                    <p class="card-text">Selecione a data e o horário que você deseja agendar</p>
                </div>
            </div>


        </div>

        <div class="col">
            <div class="card">
                <div class="card-header">
                    Pronto
                </div>

                <div class="card-body">
                    <h5 class="card-title">Confirmação</h5>
                    <p class="card-text">Confirme os detalhes do seu agendamento</p>
                </div>
            </div>


        </div>

    </div>

    <div class="row mt-4">
        <div class="col-m-12">
            <a href="<?php echo route_to('schedules.new'); ?>" class="btn btn-lg btn-primary">Agendar</a>
        </div>

    </div>

</div>
<!-- /.container-fluid -->


<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>



<?php echo $this->endSection(); ?>