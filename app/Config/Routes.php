<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Super\HomeController;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('super', [HomeController::class,'index'], ['as' => 'super.home']);
