<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('login', 'AuthController::login');
$routes->post('auth/verify-google-token', 'AuthController::verifyGoogleToken');
$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'LocaisController::index');

    $routes->get('locais', 'LocaisController::index');
    $routes->get('locais/new', 'LocaisController::new');
    $routes->post('locais', 'LocaisController::create');
    $routes->get('locais/(:num)/edit', 'LocaisController::edit/$1');
    $routes->put('locais/(:num)', 'LocaisController::update/$1');
    $routes->delete('locais/(:num)', 'LocaisController::delete/$1');
});


$routes->group('api', function ($routes) {
    $routes->get('locais', 'LocaisController::apiList');
    $routes->get('locais/(:num)', 'LocaisController::apiShow/$1');
});

$routes->get('/simple-tests', 'SimpleTestController::run');


