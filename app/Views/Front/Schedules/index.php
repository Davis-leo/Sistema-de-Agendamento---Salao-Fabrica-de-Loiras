<?php echo $this->extend('Front/Layout/main'); ?>


<?php echo $this->section('title'); ?>

<?php echo $title ?? 'Home'; ?>

<?php echo $this->endSection(); ?>


<?php echo $this->section('css'); ?>

<style>
    main>.container:has(#mainBoxServices) {
        max-width: 1080px;
    }

    main>.container:has(#mainBoxServices) h1 {
        margin-bottom: 2.25rem;
        text-align: left;
    }

    main>.container:has(#mainBoxServices) h1::after {
        margin-left: 0;
    }

    #boxErrors {
        min-height: 0;
    }

    #boxErrors .alert {
        border: 0;
        border-left: 4px solid var(--salon-rose);
        border-radius: 4px;
        box-shadow: var(--salon-shadow);
    }

    #boxCalendar {
        min-width: 0;
        width: 100%;
    }

    #boxCalendar .table-responsive {
        overflow-x: visible;
    }

    #boxCalendar table {
        margin-bottom: 0;
        table-layout: fixed;
        width: 100%;
    }

    #boxCalendar td {
        padding: .35rem .2rem;
        text-align: center;
        vertical-align: middle;
    }

    #boxCalendar .btn-calendar-day {
        align-items: center;
        display: inline-flex;
        justify-content: center;
        min-height: 2.25rem;
        min-width: 2.25rem;
        padding: .35rem;
    }

    #boxHours {
        background: rgba(255, 255, 255, .58);
        border: 1px solid var(--salon-line);
        border-radius: 8px;
        min-height: 100%;
        padding: 1rem;
    }

    #boxHours .hours-grid {
        display: grid;
        gap: .65rem;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    #boxHours .btn-hour {
        background: #fff;
        border: 1px solid var(--salon-rose);
        border-radius: 6px;
        color: var(--salon-rose);
        font-family: 'DM Sans', sans-serif;
        font-size: .95rem;
        font-weight: 700;
        min-height: 2.75rem;
        padding: .55rem .4rem;
        transition: background-color .2s ease, color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    #boxHours .btn-hour:hover {
        background: var(--salon-rose);
        box-shadow: 0 6px 14px rgba(184, 92, 91, .2);
        color: #fff;
        transform: translateY(-1px);
    }

    #boxHours .btn-hour:focus-visible {
        background: var(--salon-rose);
        box-shadow: 0 0 0 .2rem rgba(184, 92, 91, .2);
        color: #fff;
        outline: 0;
    }

    #mainBoxServices+* {
        min-width: 0;
    }

    main>.container:has(#mainBoxServices) .row>.col-md-8 {
        background: rgba(255, 255, 255, .64);
        border: 1px solid var(--salon-line);
        border-radius: 8px;
        box-shadow: var(--salon-shadow);
        padding: 2rem;
    }

    main>.container:has(#mainBoxServices) .row>.col-md-2 {
        align-self: flex-start;
        background: var(--salon-sand);
        border-radius: 8px;
        margin-left: 1.5rem;
        padding: 1.5rem;
    }

    main>.container:has(#mainBoxServices) .lead {
        color: var(--salon-ink);
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    main>.container:has(#mainBoxServices) .form-check {
        background: rgba(255, 255, 255, .72);
        border: 1px solid var(--salon-line);
        border-radius: 6px;
        margin-bottom: .75rem !important;
        padding: 1rem 1rem 1rem 2.75rem;
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    main>.container:has(#mainBoxServices) .form-check:has(.form-check-input:checked) {
        background: #fff;
        border-color: var(--salon-rose);
        box-shadow: 0 8px 20px rgba(184, 92, 91, .13);
    }

    main>.container:has(#mainBoxServices) .form-check-input {
        border-color: var(--salon-rose);
        margin-left: -1.75rem;
        margin-top: .2rem;
    }

    main>.container:has(#mainBoxServices) .form-check-input:checked {
        background-color: var(--salon-rose);
        border-color: var(--salon-rose);
    }

    main>.container:has(#mainBoxServices) .form-check-label {
        color: var(--salon-ink);
        cursor: pointer;
        line-height: 1.5;
    }

    #mainBoxServices {
        border-top: 1px solid var(--salon-line);
        margin-top: 1.75rem;
        padding-top: 1.75rem;
    }

    #boxServices .form-select {
        background-color: rgba(255, 255, 255, .9);
        border: 1px solid var(--salon-line);
        border-radius: 6px;
        color: var(--salon-ink);
        min-height: 50px;
        padding: .75rem 1rem;
    }

    #boxServices .form-select:focus {
        border-color: var(--salon-rose);
        box-shadow: 0 0 0 .2rem rgba(184, 92, 91, .16);
    }

    main>.container:has(#mainBoxServices) .col-md-2 .lead {
        border-bottom: 1px solid rgba(82, 59, 49, .14);
        font-family: 'DM Sans', sans-serif;
        font-size: .76rem;
        font-weight: 700;
        letter-spacing: .08em;
        padding-bottom: .8rem;
        text-transform: uppercase;
    }

    main>.container:has(#mainBoxServices) .col-md-2 .text-muted {
        color: var(--salon-muted) !important;
        display: inline-block;
        font-family: 'DM Sans', sans-serif;
        font-size: .86rem;
        font-weight: 400;
        letter-spacing: 0;
        margin-top: .35rem;
        text-transform: none;
    }

    @media (max-width: 767.98px) {
        main>.container:has(#mainBoxServices) h1 {
            text-align: center;
        }

        main>.container:has(#mainBoxServices) h1::after {
            margin-left: auto;
        }

        main>.container:has(#mainBoxServices) .row>.col-md-8 {
            padding: 1.25rem;
        }

        main>.container:has(#mainBoxServices) .row>.col-md-2 {
            margin-left: 0;
            margin-top: 1rem;
        }

        #mainBoxCalendar>.row>div {
            margin-bottom: 1rem;
        }

        #boxCalendar td {
            padding-left: .1rem;
            padding-right: .1rem;
        }

        #boxCalendar .btn-calendar-day {
            min-height: 2rem;
            min-width: 2rem;
        }

        #boxHours {
            padding: .75rem;
        }

        #boxHours .hours-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .btn-calendar-day-chosen{
        color: #fff !important;
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }
</style>

<?php echo $this->endSection(); ?>


<?php echo $this->section('content'); ?>


<!-- Begin Page Content -->
<div class="container">
    <h1 class="mt-5"><?php echo $title ?></h1>
    
    <div id="boxErrors" class="mt-4 mb-3">

    </div>

    <div class="row">

        <div class="col-md-8">

            <div class="row">

                <!-- Unidades -->
                <div class="col-md-12 mb-4">

                    <p class="lead">Escolha uma unidade</p>

                    <?php echo $units; ?>

                </div>

                <!-- Serviços da unidade (oculto no load da view) -->
                <div id="mainBoxServices" class="col-md-12 d-none mb-4">

                    <p class="lead">Escolha o Serviço</p>

                    <div id="boxServices">

                    </div>

                </div>

                <!-- Mês (oculto no load da view) -->
                <div id="boxMonths" class="col-md-12 d-none mb-4">

                    <p class="lead">Escolha o Mês</p>

                    <?php echo $months ?>

                </div>

                <div id="mainBoxCalendar" class="col-md-12 d-none mb-4">

                    <p class="lead">Escolha o dia e o horário</p>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <div id="boxCalendar">

                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <div id="boxHours">

                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>


        <!-- Preview do que for sendo escolhido -->
        <div class="col-md-2 ms-auto">

            <p class="lead mt-4">Unidade escolhida: <br><span id="chosenUnitText" class="text-muted small"></span></p>
            <p class="lead">Serviço escolhido: <br><span id="chosenServiceText" class="text-muted small"></span></p>
            <p class="lead">Mês escolhido: <br><span id="chosenMonthText" class="text-muted small"></span></p>
            <p class="lead">Dia escolhido: <br><span id="chosenDayText" class="text-muted small"></span></p>
            <p class="lead">Horário escolhido: <br><span id="chosenHourText" class="text-muted small"></span></p>
        </div>

    </div>


</div>

</div>
<!-- /.container-fluid -->


<?php echo $this->endSection(); ?>


<?php echo $this->section('js'); ?>

<script>

    const URL_GET_SERVICES = '<?php echo route_to('get.unit.services'); ?>';
    const URL_GET_CALENDAR = '<?php echo route_to('get.calendar'); ?>';
    const URL_GET_HOURS = '<?php echo route_to('get.hours'); ?>';

    const boxErrors = document.getElementById('boxErrors');

    const mainBoxServices = document.getElementById('mainBoxServices');
    const boxServices = document.getElementById('boxServices');
    const boxMonths = document.getElementById('boxMonths');
    const mainBoxCalendar = document.getElementById('mainBoxCalendar');
    const boxCalendar = document.getElementById('boxCalendar');
    const boxHours = document.getElementById('boxHours');

    // preview do que está sendo escolhido
    const chosenUnitText = document.getElementById('chosenUnitText');
    const chosenServiceText = document.getElementById('chosenServiceText');
    const chosenMonthText = document.getElementById('chosenMonthText');
    const chosenDayText = document.getElementById('chosenDayText');
    const chosenHourText = document.getElementById('chosenHourText');

    // Variáveis de escopo global que utilizaremos na criação do agendamento
    let unitId = null;
    let serviceId = null;
    let chosenMonth = null;
    let chosenDay = null;
    let chosenHour = null;

    const units = document.getElementsByName('unit_id');

    units.forEach(element => {

        // adicionar para cada elemento um 'listener' ou ouvinte
        element.addEventListener('click', (event) => {

            mainBoxServices.classList.remove('d-none');

            // atribuo à variável global o valor da unidade clicada
            unitId = element.value;

            if (!unitId) {

                alert('Erro ao determinar a Unidade escolhida');
                return;
            }

            chosenUnitText.innerText = element.getAttribute('data-unit');
            chosenServiceText.innerText = '';
            chosenMonthText.innerText = '';
            chosenDayText.innerText = '';
            chosenHourText.innerText = '';


            getServices();

        });
    });

    // Recupera os serviços da unidade
    const getServices = async () => {

        // BOX ERRORS CRIAR DEPOIS
        boxErrors.innerHTML = '';

        let url = URL_GET_SERVICES + '?' + setParameters({
            unit_id: unitId
        });

        const response = await fetch(url, {
            method: 'get',
            headers: setHeadersRequest()
        });

        if (!response.ok) {

            boxErrors.innerHTML = showErrorMessage('Não foi possível recuperar os serviços da unidade.');

            throw new Error(`HTTP error! status: ${response.status}`);

            return;
        }


        const data = await response.json();

        // colocamos na div os serviços devolvidos no response
        boxServices.innerHTML = data.services;

        const elementService = document.getElementById('service_id');

        elementService.addEventListener('change', (event) => {

            serviceId = elementService.value ?? null;
            let serviceName = serviceId !== '' ? elementService.options[event.target.selectedIndex].text : null;

            console.log('Serviço foi escolhido? ', serviceId !== '');

            chosenServiceText.innerText = serviceName;

            serviceId !== '' ? boxMonths.classList.remove('d-none') : boxMonths.classList.add('d-none');

        });


    };

    // Mês
    document.getElementById('month').addEventListener('change', (event) => {

        // Limpo o preview do mês escolhido a cada mudança
        chosenMonthText.innerText = '';

        /**
         * @todo CRIAR ESSA FUNÇÃO
         */
        // resetBoxCalendar();

        const month = event.target.value;

        if(!month){

            /**
             * @todo CRIAR FUNÇÃO
             */
            // resetMonthDataVariables();

            // resetBoxCalendar();

            return;
        }

        // Mês válido escolhido...

        // Atribuímos a variável de escopo global o valor do mês escolhido
        chosenMonth = event.target.value;

        chosenMonthText.innerText = event.target.options[event.target.selectedIndex].text;

        // Finalmente buscamos o calendário para o mês escolhido
        getCalendar();
    });

    // Calendário
    const getCalendar = async () => {

        // Limpo os erros
        boxErrors.innerHTML = '';

        // Limpo o preview do dia e da hora escolhidos, pois o user precisará clicar no horário novamente 
        chosenDayText.innerText = '';
        chosenHourText.innerText = '';

        let url = URL_GET_CALENDAR + '?' + setParameters({
            month: chosenMonth
        });

        const response = await fetch(url, {
            method: 'get',
            headers: setHeadersRequest(),
        });

        if (!response.ok) {

            boxErrors.innerHTML = showErrorMessage('Não foi possível recuperar o calendário para o mês informado.');

            throw new Error(`HTTP error! status: ${response.status}`);

            return;
        }

        // Recuperamos a resposta
        const data = await response.json();

        // Exibo a div do calendário e das horas
        mainBoxCalendar.classList.remove('d-none');

        // Colocamos a div o calenário criado
        boxCalendar.innerHTML = data.calendar;

        // Agora recupero os elementos que tenham a classe '.chosenDay',
        // ou seja, os dias que são buttons
        const buttonsChosenDay = document.querySelectorAll('.chosenDay');

        // Percorro todos os botões
        buttonsChosenDay.forEach(element => {

            // e fico 'escutando' o click no elemento
            // e para cada click recupero o valor de 'data-day'
            element.addEventListener('click', (event) => {

                // Limpo o preview da hora
                chosenDayText.innerText = '';

                // Redefino para null para garantir
                chosenHour = null;

                /**
                 * @todo criar função para remover a classe dos botões clicados
                 */

                event.target.classList.add('btn-calendar-day-chosen');

                // Armazeno na variável global
                chosenDay = event.target.dataset.day;

                // Dia escolhido no preview
                chosenDayText.innerText = chosenDay;

                getHours();
            });
        });
    };

    const getHours = async () => {

        boxErrors.innerHTML = '';

        // A unidade realmente foi escolhida?
        if(!unitId){

            boxErrors.innerHTML = showErrorMessage('Você precisa escolher a unidade de atendimento');
            return;
        }

        let url = URL_GET_HOURS + '?' + setParameters({
            unit_id : unitId,
            month: chosenMonth,
            day: chosenDay
        });

        const response = await fetch(url, {
            method: 'get',
            headers: setHeadersRequest(),
        });

        if (!response.ok) {

            boxErrors.innerHTML = showErrorMessage('Não foi possível recuperar os horários disponíveis.');

            throw new Error(`HTTP error! status: ${response.status}`);

            return;
        }

        // Recuperamos a resposta
        const data = await response.json();

        // Recupero as horas
        const hours = data.hours;

        if(hours === null){

            boxHours.innerHTML = showErrorMessage(`Não há horários disponíveis para o dia ${chosenDay}`);

            chosenDay = null;

            return;
        }

        // Colocamos na div as horas
        boxHours.innerHTML = hours;


    };
</script>

<?php echo $this->endSection(); ?>