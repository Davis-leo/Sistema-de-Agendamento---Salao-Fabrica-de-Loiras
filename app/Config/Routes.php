<?php

use App\Controllers\SchedulesController as FrontSchedulesController;
use App\Controllers\Super\ServicesController;
use App\Controllers\Super\SchedulesController;
use App\Controllers\Super\UnitsController;
use App\Controllers\Super\UnitsServicesController;
use App\Controllers\Super\ProfessionalsController;
use App\Controllers\Super\CommissionsController;
use App\Controllers\Super\ReportsController;
use App\Controllers\UserSchedulesController;
use App\Controllers\ProfileController;
use CodeIgniter\Router\RouteCollection;
use App\Controllers\Super\HomeController;
use App\Controllers\HomeController as WebController;

/** @var RouteCollection $routes */
$routes->get('/', [WebController::class, 'index'], ['as' => 'home', 'filter' => 'profile-completion']);
$routes->get('meus-dados', [ProfileController::class, 'index'], ['as' => 'profile']);
$routes->post('meus-dados', [ProfileController::class, 'update'], ['as' => 'profile.update']);

// Verificação segura do link mágico, evitando conflito com sessões já autenticadas.
$routes->get('login/verify-magic-link', 'AuthMagicLinkController::verify', ['as' => 'app-verify-magic-link']);

// rotas de autenticaçãp
service('auth')->routes($routes);

$routes->group('super', ['filter' => 'group:admin,superadmin'] ,static function ($routes) {

    // home
    $routes->get('/', [HomeController::class,'index'], ['as' => 'super.home']);
    $routes->get('schedules/new', [SchedulesController::class, 'new'], ['as' => 'super.schedules.new']);
    $routes->post('schedules/create', [SchedulesController::class, 'create'], ['as' => 'super.schedules.create']);
    $routes->get('schedules/edit/(:num)', [SchedulesController::class, 'edit'], ['as' => 'super.schedules.edit']);
    $routes->post('schedules/update/(:num)', [SchedulesController::class, 'update'], ['as' => 'super.schedules.update']);
    $routes->delete('schedules/cancel/(:num)', [SchedulesController::class, 'cancel'], ['as' => 'super.schedules.cancel']);
    $routes->post('schedules/confirm/(:num)', [SchedulesController::class, 'confirm'], ['as' => 'super.schedules.confirm']);
    $routes->get('commissions', [CommissionsController::class, 'index'], ['as' => 'commissions']);
    $routes->post('commissions/pay/(:num)', [CommissionsController::class, 'pay'], ['as' => 'commissions.pay']);
    $routes->get('reports', [ReportsController::class, 'index'], ['as' => 'reports']);
    $routes->get('reports/pdf', [ReportsController::class, 'pdf'], ['as' => 'reports.pdf']);

    // rotas de unidades
    $routes->group('units', static function ($routes) {

        $routes->get('/', [UnitsController::class,'index'], ['as' => 'units']);
        $routes->get('new', [UnitsController::class,'new'], ['as' => 'units.new']);
        $routes->get('edit/(:num)', [UnitsController::class,'edit'], ['as' => 'units.edit']);
        $routes->post('create', [UnitsController::class,'create'], ['as' => 'units.create']);
        $routes->put('update/(:num)', [UnitsController::class,'update'], ['as' => 'units.update']);
        $routes->put('action/(:num)', [UnitsController::class,'action'], ['as' => 'units.action']); // ativa / desativa um registro
        $routes->delete('destroy/(:num)', [UnitsController::class,'destroy'], ['as' => 'units.destroy']);
        $routes->get('schedules/(:num)', [UnitsController::class,'schedules'], ['as' => 'units.schedules']);

        // Rotas dos serviços da unidade
        $routes->get('services/(:num)', [UnitsServicesController::class,'services'], ['as' => 'units.services']);
        $routes->put('services/store/(:num)', [UnitsServicesController::class,'store'], ['as' => 'units.services.store']);
    });

    // rotas de serviços
    $routes->group('services', static function ($routes) {

        $routes->get('/', [ServicesController::class,'index'], ['as' => 'services']);
        $routes->get('new', [ServicesController::class,'new/$1'], ['as' => 'services.new']);
        $routes->get('edit/(:num)', [ServicesController::class,'edit/$1'], ['as' => 'services.edit']);
        $routes->post('create', [ServicesController::class,'create'], ['as' => 'services.create']);
        $routes->put('update/(:num)', [ServicesController::class,'update/$1'], ['as' => 'services.update']);
        $routes->put('action/(:num)', [ServicesController::class,'action/$1'], ['as' => 'services.action']); // ativa / desativa um registro
        $routes->delete('destroy/(:num)', [ServicesController::class,'destroy/$1'], ['as' => 'services.destroy']);
    });

    $routes->group('professionals', static function ($routes) {
        $routes->get('/', [ProfessionalsController::class, 'index'], ['as' => 'professionals']);
        $routes->get('new', [ProfessionalsController::class, 'new'], ['as' => 'professionals.new']);
        $routes->post('create', [ProfessionalsController::class, 'create'], ['as' => 'professionals.create']);
        $routes->get('edit/(:num)', [ProfessionalsController::class, 'edit/$1'], ['as' => 'professionals.edit']);
        $routes->put('update/(:num)', [ProfessionalsController::class, 'update/$1'], ['as' => 'professionals.update']);
        $routes->put('action/(:num)', [ProfessionalsController::class, 'action/$1'], ['as' => 'professionals.action']);
        $routes->delete('destroy/(:num)', [ProfessionalsController::class, 'destroy/$1'], ['as' => 'professionals.destroy']);
    });
});

// rotas de agendamentos do user logado
    $routes->group('schedules', ['filter' => 'profile-completion'], static function ($routes) {

        $routes->get('/', [FrontSchedulesController::class,'index'], ['as' => 'schedules.new']);
        $routes->get('services', [FrontSchedulesController::class,'unitServices'], ['as' => 'get.unit.services']); // recuperamos via fetch API os serviços da unidade
        $routes->get('calendar', [FrontSchedulesController::class,'getCalendar'], ['as' => 'get.calendar']); // recuperamos via fetch API o calendário para o mês desejado
        $routes->get('hours', [FrontSchedulesController::class,'getHours'], ['as' => 'get.hours']); // recuperamos via fetch API os horários disponíveis
        $routes->get('professionals', [FrontSchedulesController::class,'professionals'], ['as' => 'get.professionals']);
        $routes->post('create', [FrontSchedulesController::class,'createSchedule'], ['as' => 'create.schedule']); // criamos o agendamento via fetch API
        
        $routes->group('my', static function ($routes) {

            // Agendamentos do user logado
            $routes->get('/', [UserSchedulesController::class,'index'], ['as' => 'schedules.my']);
            $routes->get('all', [UserSchedulesController::class,'all'], ['as' => 'schedules.my.all']); // Recupera via fetch API
            $routes->delete('cancel', [UserSchedulesController::class,'cancel'], ['as' => 'schedules.my.cancel']); // Cancela via fetch API
        });
    });
