<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Protected routes - require authentication
$routes->group('user', ['filter' => 'session'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->post('profile/password', 'Profile::changePassword');

    $routes->group('students', ['filter' => 'admin'], static function ($routes) {
        $routes->get('', 'StudentController::index');
        $routes->get('create', 'StudentController::create');
        $routes->post('store', 'StudentController::store');
        $routes->get('(:num)/edit', 'StudentController::edit/$1');
        $routes->post('(:num)/update', 'StudentController::update/$1');
        $routes->post('(:num)/delete', 'StudentController::delete/$1');
    });
});

service('auth')->routes($routes);
