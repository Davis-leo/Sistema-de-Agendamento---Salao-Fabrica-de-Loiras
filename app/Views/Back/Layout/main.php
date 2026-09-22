<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="<?php echo base_url('favicon.ico'); ?>" type="image/x-icon">

    <title>Agendamentos | admin | <?php echo $this->renderSection('title'); ?></title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url('back/'); ?>css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap');

        :root {
            --salon-ink: #2e2927;
            --salon-muted: #766d68;
            --salon-cream: #fbf7f2;
            --salon-sand: #f1e7dc;
            --salon-rose: #b85c5b;
            --salon-rose-dark: #914646;
            --salon-line: rgba(82, 59, 49, .12);
            --salon-shadow: 0 18px 45px rgba(74, 48, 37, .10);
            --salon-display: 'Cormorant Garamond', Georgia, serif;
            --salon-body: 'Manrope', sans-serif;
        }

        html, body { min-height: 100%; }
        body {
            background: var(--salon-cream);
            color: var(--salon-ink);
            font-family: var(--salon-body);
            letter-spacing: .01em;
        }
        body::before {
            background: linear-gradient(135deg, rgba(229, 192, 177, .28), transparent 42%),
                linear-gradient(315deg, rgba(242, 225, 203, .42), transparent 38%);
            content: '';
            inset: 0;
            pointer-events: none;
            position: fixed;
            z-index: -1;
        }
        #wrapper { min-height: 100vh; }
        #content-wrapper { background: transparent; }
        #content { min-height: calc(100vh - 80px); }
        .sidebar { background: #332925 !important; box-shadow: 8px 0 28px rgba(46, 32, 26, .12); }
        .sidebar .sidebar-brand {
            color: #fff9f3 !important;
            font-family: var(--salon-display);
            font-size: 1.55rem;
            font-weight: 700;
        }
        .sidebar .sidebar-brand-icon, .sidebar .nav-link i { color: #e6a59a; }
        .sidebar hr.sidebar-divider { border-top-color: rgba(255, 249, 243, .12); }
        .sidebar .nav-item .nav-link {
            border-radius: 999px;
            color: #fff9f3;
            font-size: .86rem;
            font-weight: 600;
            margin: .25rem .75rem;
            padding: .75rem 1rem;
        }
        .sidebar .nav-item .nav-link:hover, .sidebar .nav-item.active .nav-link {
            background: rgba(255, 255, 255, .12);
            color: #fff9f3;
        }
        .topbar {
            background: rgba(255, 255, 255, .88) !important;
            border-bottom: 1px solid var(--salon-line);
            box-shadow: 0 5px 24px rgba(46, 32, 26, .08) !important;
            height: 80px;
        }
        .topbar .text-gray-600 { color: var(--salon-ink) !important; font-weight: 600; }
        .topbar .nav-link {
            color: var(--salon-rose-dark) !important;
            font-weight: 700;
        }
        .topbar .nav-link:hover,
        .topbar .nav-link:focus {
            color: var(--salon-ink) !important;
        }
        .container-fluid { padding-left: 2rem; padding-right: 2rem; }
        .card {
            background: rgba(255, 255, 255, .9);
            border: 1px solid var(--salon-line);
            border-radius: 8px;
            box-shadow: var(--salon-shadow) !important;
        }
        .card-header {
            align-items: center;
            background: transparent;
            border-bottom: 1px solid var(--salon-line);
            display: flex;
            justify-content: space-between;
            padding: 1.35rem 1.5rem;
        }
        .card-header h6, .card-header .font-weight-bold {
            color: var(--salon-ink) !important;
            font-family: var(--salon-display);
            font-size: 1.65rem;
            font-weight: 700 !important;
            margin: 0;
        }
        .card-body { padding: 1.5rem; }
        h1, h2, h3, h4, h5, h6 { color: var(--salon-ink); font-family: var(--salon-display); }
        .btn { border-radius: 999px; font-size: .82rem; font-weight: 700; padding: .6rem 1rem; }
        .btn-primary, .btn-success, .badge-primary {
            background-color: var(--salon-rose) !important;
            border-color: var(--salon-rose) !important;
            color: #fff !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active,
        .btn-primary:not(:disabled):not(.disabled):active,
        .btn-success:hover, .btn-success:focus, .btn-success:active,
        .btn-success:not(:disabled):not(.disabled):active,
        .badge-primary:hover, .badge-primary:focus {
            background-color: var(--salon-rose-dark) !important;
            border-color: var(--salon-rose-dark) !important;
            color: #fff !important;
            box-shadow: 0 0 0 .2rem rgba(184, 92, 91, .18) !important;
        }
        .badge-status-active, .badge-status-inactive {
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .04em;
            padding: .45rem .7rem;
        }
        .badge-status-active {
            background-color: #3f8062 !important;
            color: #fff !important;
        }
        .badge-status-inactive {
            background-color: #766d68 !important;
            color: #fff !important;
        }
        .btn-secondary, .btn-outline-primary {
            background-color: transparent !important;
            color: var(--salon-rose-dark) !important;
            border-color: rgba(145, 70, 70, .4) !important;
        }
        .btn-secondary:hover, .btn-secondary:focus, .btn-secondary:active,
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: var(--salon-rose-dark) !important;
            border-color: var(--salon-rose-dark) !important;
            color: #fff !important;
            box-shadow: 0 0 0 .2rem rgba(184, 92, 91, .18) !important;
        }
        .table { color: var(--salon-ink); margin-bottom: 0; }
        .table thead th {
            background: var(--salon-sand); border-bottom: 0; color: var(--salon-rose-dark);
            font-size: .72rem; letter-spacing: .08em; text-transform: uppercase;
        }
        .table td, .table th { border-color: var(--salon-line); padding: .9rem .75rem; vertical-align: middle; }
        .table tbody tr:hover { background: rgba(241, 231, 220, .42); }
        .dropdown-menu { border: 1px solid var(--salon-line); border-radius: 8px; box-shadow: var(--salon-shadow); padding: .4rem; }
        .dropdown-item { border-radius: 5px; color: var(--salon-ink); font-size: .82rem; padding: .55rem .75rem; }
        .dropdown-item:hover { background: var(--salon-sand); color: var(--salon-rose-dark); }
        label { color: var(--salon-ink); font-size: .82rem; font-weight: 700; }
        .form-control, .custom-select {
            background-color: #fffdfb; border: 1px solid rgba(82, 59, 49, .2); border-radius: 5px;
            color: var(--salon-ink); font-family: var(--salon-body); min-height: 44px;
        }
        .form-control:focus, .custom-select:focus { border-color: var(--salon-rose); box-shadow: 0 0 0 .2rem rgba(184, 92, 91, .14); }
        .alert { border: 0; border-left: 4px solid var(--salon-rose); border-radius: 5px; box-shadow: 0 8px 20px rgba(74, 48, 37, .07); }
        .card-body > ul { list-style: none; margin: 0; padding: 0; }
        .card-body > ul > li { background: var(--salon-sand); border-left: 4px solid var(--salon-rose); border-radius: 5px; margin-bottom: .75rem; padding: 1rem 1.15rem; }
        .card-body > ul > li p { color: var(--salon-muted); line-height: 1.7; margin: 0; }
        .card-body > ul > li strong { color: var(--salon-ink); }
        .sticky-footer { background: transparent !important; color: var(--salon-muted); }
        @media (max-width: 767.98px) {
            .sidebar { width: 6.5rem; }
            .sidebar .sidebar-brand-text, .sidebar .nav-link span { display: none; }
            .sidebar .nav-item .nav-link { margin-left: .5rem; margin-right: .5rem; text-align: center; }
            .container-fluid { padding-left: 1rem; padding-right: 1rem; }
            .card-header { align-items: flex-start; flex-direction: column; gap: .8rem; }
            .card-header .btn { float: none !important; }
        }
    </style>


    <!-- Aqui é o espaço reservado para apresentar css específico das views que extendem esse template -->
    <?php echo $this->renderSection('css'); ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo route_to('super.home'); ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-cut"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Painel</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

                <!-- Item de navegação - Painel -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo route_to('super.home'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Painel</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Item de navegação - Unidades -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo route_to('units'); ?>">
                    <i class="fas fa-fw fa-building"></i>
                    <span>Unidades</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Item de navegação - Serviços -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo route_to('services'); ?>">
                    <i class="fas fa-fw fa-concierge-bell"></i>
                    <span>Serviços</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo route_to('professionals'); ?>">
                    <i class="fas fa-fw fa-user-tie"></i>
                    <span>Profissionais</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo route_to('commissions'); ?>">
                    <i class="fas fa-fw fa-coins"></i>
                    <span>Comissões</span></a>
            </li>

            <!-- Item de navegação - Novo agendamento -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo route_to('super.schedules.new'); ?>">
                    <i class="fas fa-fw fa-calendar-plus"></i>
                    <span>Novo agendamento</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Pesquisar..." aria-label="Pesquisar"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Informações da conta -->
                        <li class="nav-item d-flex align-items-center">
                            <span class="mr-3 d-none d-lg-inline text-gray-600 small"><?php echo auth()->user()->username; ?></span>
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-1"></i>
                                Sair
                            </a>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <?php echo $this->include('Back/Layout/_messages'); ?>


                <!-- Aqui é o espaço reservado para apresentar o conteúdo específico das views que extendem esse template -->
                <?php echo $this->renderSection('content'); ?>



            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; 2026 Fábrica de Loiras - Márcia Marques. Todos os direitos reservados.</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modal de saída -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deseja sair?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecione “Sair” abaixo para encerrar sua sessão.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="<?php echo route_to('logout'); ?>">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url('back/'); ?>js/sb-admin-2.min.js"></script>


    <!-- Aqui é o espaço reservado para scripts específicos das views que extendem esse template -->
    <?php echo $this->renderSection('js'); ?>

</body>

</html>