<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('login', 'Auth::login');
$routes->post('register', 'Auth::register');
$routes->group('checklist', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'Checklist::index');
    $routes->post('', 'Checklist::create');
    $routes->delete('(:num)', 'Checklist::delete/$1');
});
