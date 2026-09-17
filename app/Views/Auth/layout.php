<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?> | Agendamentos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --salon-ink: #2e2927;
            --salon-muted: #766d68;
            --salon-cream: #fbf7f2;
            --salon-sand: #f1e7dc;
            --salon-rose: #b85c5b;
            --salon-rose-dark: #914646;
            --salon-line: rgba(82, 59, 49, .14);
            --salon-display: 'Cormorant Garamond', Georgia, serif;
            --salon-body: 'Manrope', sans-serif;
        }

        * { box-sizing: border-box; }

        html, body { min-height: 100%; }

        body {
            margin: 0;
            background: var(--salon-cream);
            color: var(--salon-ink);
            font-family: var(--salon-body);
        }

        body::before {
            background: linear-gradient(135deg, rgba(229, 192, 177, .32), transparent 42%),
                linear-gradient(315deg, rgba(242, 225, 203, .5), transparent 38%);
            content: '';
            inset: 0;
            pointer-events: none;
            position: fixed;
            z-index: -1;
        }

        .auth-shell {
            display: grid;
            grid-template-columns: minmax(280px, .85fr) minmax(420px, 1.15fr);
            margin: 0 auto;
            max-width: 1180px;
            min-height: 100vh;
            padding: 2rem;
        }

        .auth-intro {
            background: #332925;
            color: #fff9f3;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 680px;
            overflow: hidden;
            padding: clamp(2rem, 5vw, 4.5rem);
            position: relative;
        }

        .auth-intro::after {
            border: 1px solid rgba(255, 249, 243, .2);
            border-radius: 50%;
            content: '';
            height: 24rem;
            position: absolute;
            right: -10rem;
            top: -5rem;
            width: 24rem;
        }

        .auth-mark {
            color: #e6a59a;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
        }

        .auth-intro h1 {
            font-family: var(--salon-display);
            font-size: clamp(3rem, 5vw, 5rem);
            font-weight: 700;
            letter-spacing: -.015em;
            line-height: .94;
            margin: 0 0 1.25rem;
            max-width: 8ch;
        }

        .auth-intro p {
            color: rgba(255, 249, 243, .7);
            line-height: 1.7;
            margin: 0;
            max-width: 30ch;
        }

        .auth-detail {
            color: rgba(255, 249, 243, .58);
            font-size: .78rem;
            letter-spacing: .04em;
        }

        .auth-panel {
            align-items: center;
            background: rgba(255, 255, 255, .68);
            border: 1px solid var(--salon-line);
            display: flex;
            justify-content: center;
            padding: clamp(2rem, 6vw, 5.5rem);
        }

        .auth-content { max-width: 400px; width: 100%; }

        .auth-confirmation-mark {
            align-items: center;
            background: var(--salon-sand);
            border: 1px solid rgba(184, 92, 91, .2);
            border-radius: 50%;
            color: var(--salon-rose-dark);
            display: flex;
            font-size: 1.35rem;
            font-weight: 700;
            height: 3.25rem;
            justify-content: center;
            margin-bottom: 1.25rem;
            width: 3.25rem;
        }

        .auth-kicker {
            color: var(--salon-rose-dark);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
            margin: 0 0 .55rem;
            text-transform: uppercase;
        }

        .auth-content h2 {
            font-family: var(--salon-display);
            font-size: 2.65rem;
            font-weight: 700;
            letter-spacing: -.01em;
            margin: 0 0 .55rem;
        }

        .auth-subtitle { color: var(--salon-muted); margin: 0 0 2rem; }

        .auth-alert {
            border-radius: 6px;
            font-size: .88rem;
            margin-bottom: 1.25rem;
            padding: .85rem 1rem;
        }

        .auth-alert.error { background: #f8e2df; color: #793b39; }
        .auth-alert.success { background: #e4f1e5; color: #35633b; }

        .auth-error-list {
            display: grid;
            gap: .65rem;
            margin: 0;
            padding-left: 1.1rem;
        }

        .auth-error-list li { padding-left: .2rem; }

        .field { margin-bottom: 1.15rem; }

        .field label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .05em;
            margin-bottom: .45rem;
            text-transform: uppercase;
        }

        .field input {
            background: rgba(255, 255, 255, .84);
            border: 1px solid var(--salon-line);
            border-radius: 5px;
            color: var(--salon-ink);
            font: inherit;
            padding: .9rem 1rem;
            width: 100%;
        }

        .field input:focus {
            border-color: var(--salon-rose);
            box-shadow: 0 0 0 3px rgba(184, 92, 91, .14);
            outline: 0;
        }

        .auth-code-input {
            font-size: 1.45rem !important;
            font-weight: 700;
            letter-spacing: .35em;
            text-align: center;
        }

        .remember {
            align-items: center;
            color: var(--salon-muted);
            display: flex;
            font-size: .88rem;
            gap: .5rem;
            margin: .25rem 0 1.5rem;
        }

        .remember input { accent-color: var(--salon-rose); }

        .auth-button {
            background: var(--salon-rose);
            border: 0;
            border-radius: 999px;
            box-shadow: 0 10px 22px rgba(184, 92, 91, .25);
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            padding: .9rem 1.25rem;
            transition: background-color .2s ease, box-shadow .2s ease, transform .2s ease;
            width: 100%;
        }

        .auth-button:hover, .auth-button:focus {
            background: var(--salon-rose-dark);
            box-shadow: 0 13px 26px rgba(145, 70, 70, .3);
            transform: translateY(-2px);
        }

        .auth-links {
            color: var(--salon-muted);
            font-size: .88rem;
            line-height: 1.7;
            margin: 1.5rem 0 0;
            text-align: center;
        }

        .auth-links a { color: var(--salon-rose-dark); font-weight: 700; }

        @media (max-width: 767.98px) {
            .auth-shell { display: block; padding: 0; }
            .auth-intro { min-height: 255px; padding: 2rem 1.5rem; }
            .auth-intro h1 { font-size: 2.7rem; }
            .auth-detail { display: none; }
            .auth-panel { min-height: calc(100vh - 255px); padding: 2.5rem 1.5rem; }
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <?= $this->renderSection('main') ?>
    </main>
</body>
</html>
