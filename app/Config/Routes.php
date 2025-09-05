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
    $routes->add('permissions', 'Permissions::index');
    $routes->add('roles', 'Roles::index');
    $routes->add('roles/create', 'Roles::createRole');
    $routes->add('roles/attach-permissions', 'Roles::attachPermissions');
    $routes->get('api/permissions', 'Permissions::apiList');
    $routes->add('role-information/(:any)', 'Roles::roleInformation/$1');
    $routes->add('roles/(:num)/permissions/json', 'Roles::permissionsJson/$1');
    $routes->add('exception-404', 'Error::exception404');
    $routes->add('exception-500', 'Error::exception500');
});
