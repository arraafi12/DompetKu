<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::login');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');

$routes->get('daftar', 'Auth::register');
$routes->post('daftar', 'Auth::register');

$routes->get('lupa-email', 'Auth::forgotEmail');
$routes->post('lupa-email', 'Auth::forgotEmail');

$routes->get('lupa-password', 'Auth::forgotPassword');
$routes->post('lupa-password', 'Auth::forgotPassword');