<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Sistema de Agendamentos <?php echo $this->renderSection('title') ?></title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sticky-footer-navbar/">



    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('front/'); ?>bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3">

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

        html,
        body {
            min-height: 100%;
        }

        body {
            background: var(--salon-cream);
            color: var(--salon-ink);
            font-family: var(--salon-body);
            letter-spacing: .015em;
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

        .navbar {
            background: rgba(49, 39, 35, .97) !important;
            box-shadow: 0 5px 24px rgba(46, 32, 26, .18);
            min-height: 72px;
            padding: .75rem max(1rem, calc((100% - 1140px) / 2));
        }

        .navbar-brand {
            color: #fff9f3 !important;
            font-family: var(--salon-display);
            font-size: 1.55rem;
            font-weight: 700;
            letter-spacing: .015em;
            white-space: nowrap;
        }

        .navbar-brand::before {
            color: #e6a59a;
            content: '✦';
            font-size: 1rem;
            margin-right: .55rem;
        }

        .navbar-nav .nav-link {
            border-radius: 999px;
            color: rgba(255, 249, 243, .72) !important;
            font-size: .86rem;
            font-weight: 600;
            margin: 0 .15rem;
            padding: .5rem .85rem !important;
            transition: background-color .2s ease, color .2s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, .12);
            color: #fff9f3 !important;
        }

        .navbar-toggler {
            border-color: rgba(255, 249, 243, .35);
        }

        main {
            padding-top: 72px;
        }

        main>.container {
            max-width: 1180px;
            padding-bottom: 4.5rem;
            padding-top: 4.5rem !important;
        }

        main h1 {
            color: var(--salon-ink);
            font-family: var(--salon-display);
            font-size: clamp(2.7rem, 5vw, 4.6rem);
            font-weight: 700;
            letter-spacing: -.015em;
            line-height: .98;
            margin-bottom: .75rem;
        }

        main h1::after {
            background: var(--salon-rose);
            content: '';
            display: block;
            height: 3px;
            margin: 1.25rem auto 0;
            width: 48px;
        }

        .row.mt-4 {
            gap: 1rem 0;
            margin-top: 2.75rem !important;
        }

        .row.mt-4>.col {
            display: flex;
        }

        .card {
            background: rgba(255, 255, 255, .72);
            border: 1px solid var(--salon-line);
            border-radius: 8px;
            box-shadow: var(--salon-shadow);
            overflow: hidden;
            transition: box-shadow .25s ease, transform .25s ease;
            width: 100%;
        }

        .card:hover {
            box-shadow: 0 22px 48px rgba(74, 48, 37, .16);
            transform: translateY(-5px);
        }

        .card-header {
            background: var(--salon-sand);
            border-bottom: 0;
            color: var(--salon-rose-dark);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .14em;
            padding: .8rem 1rem;
            text-transform: uppercase;
        }

        .card-body {
            padding: 1.45rem 1.15rem 1.6rem;
        }

        .card-title {
            color: var(--salon-ink);
            font-family: var(--salon-display);
            font-size: 1.35rem;
            font-weight: 600;
            margin-bottom: .55rem;
        }

        .card-text {
            color: var(--salon-muted);
            font-size: .9rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .btn-primary {
            background: var(--salon-rose);
            border: 0;
            border-radius: 999px;
            box-shadow: 0 10px 22px rgba(184, 92, 91, .25);
            font-size: .95rem;
            font-weight: 700;
            letter-spacing: .04em;
            padding: .85rem 2.4rem;
            transition: background-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--salon-rose-dark);
            box-shadow: 0 13px 26px rgba(145, 70, 70, .3);
            transform: translateY(-2px);
        }

        .footer {
            background: #332925 !important;
            color: rgba(255, 249, 243, .62);
            font-size: .8rem;
            letter-spacing: .04em;
        }

        .footer .text-muted {
            color: inherit !important;
        }

        @media (max-width: 767.98px) {
            .navbar {
                min-height: 64px;
                padding: .7rem 1rem;
            }

            .navbar-brand {
                font-size: 1.35rem;
            }

            .navbar-collapse {
                background: #332925;
                margin: .7rem -1rem -.7rem;
                padding: .5rem 1rem 1rem;
            }

            main {
                padding-top: 64px;
            }

            main>.container {
                padding-bottom: 3rem;
                padding-left: 1.25rem;
                padding-right: 1.25rem;
                padding-top: 3.25rem !important;
            }

            main h1 {
                font-size: 2.9rem;
            }

            .row.mt-4 {
                margin-top: 2rem !important;
            }

            .row.mt-4>.col {
                flex: 0 0 100%;
            }
        }
    </style>


    <?php echo $this->renderSection('css') ?>
</head>

<body class="d-flex flex-column h-100">

    <header>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo route_to('home'); ?>">Fábrica de Loiras - Márcia Marques</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="<?php echo route_to('home'); ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo route_to('schedules.new'); ?>">Criar agendamento</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo route_to('schedules.my'); ?>">Meus Agendamentos</a>
                        </li>
                        <?php if (auth()->loggedIn() && auth()->user()->inGroup('superadmin')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo route_to('super.home'); ?>">Administração</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <div class="d-flex">
                        <ul class="navbar-nav me-auto mb-2 mb-md-0">

                        <?php if(auth()->loggedIn()): ?>

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="<?php echo route_to('logout'); ?>">Sair</a>
                        </li>

                        <?php else : ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo route_to('login'); ?>">Entrar | Registrar-se</a>
                        </li>

                        <?php endif; ?>

                    </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Begin page content -->
    <main class="flex-shrink-0">

        <?php echo $this->renderSection('content') ?>


    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">Place sticky footer content here.</span>
        </div>
    </footer>

    <script src="<?php echo base_url('front/'); ?>bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <?php echo $this->renderSection('js') ?>

    <script>
        const setParameters = (object) => {

            return (new URLSearchParams(object)).toString();
        }

        const setHeadersRequest = () => {

            return {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            }
        }

        const showErrorMessage = (message) => {

            boxErrors.innerHTML = '';

            return `<div class="alert alert-danger">${message}</div>`;
        }
    </script>

</body>

</html>