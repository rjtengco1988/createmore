<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->add('/', 'Auth::login');
$routes->add('/a/dashboard/', 'Dashboard::index');
