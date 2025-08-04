<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('/a/login/', 'Login::index');
$routes->get('login-status', 'Login::loginStatus');
$routes->get('/auth', 'Login::auth');

$routes->group('a', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('test-tampered', 'Dashboard::testTamperedToken');
    $routes->get('permissions', 'Permissions::index');
});
