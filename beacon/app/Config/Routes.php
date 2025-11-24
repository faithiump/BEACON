<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth routes
$routes->get('auth/login', 'Auth::login');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/login', 'Auth::processLogin');
$routes->post('auth/register', 'Auth::processRegister');

// Organization routes
$routes->get('organization/launch', 'Organization::launch');
$routes->post('organization/launch', 'Organization::processLaunch');