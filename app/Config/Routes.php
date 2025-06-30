<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->add('/', 'Auth::login');
$routes->add('/a/dashboard/', 'Dashboard::index');
$routes->add('/privacy-policy/', 'Login::privacyPolicy');
$routes->add('/terms-and-conditions/', 'Login::termsAndConditions');
$routes->add('/user-data-deletion', 'Login::userDataDeletion');
