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

// Admin routes
$routes->get('admin', 'Admin\\Login::index');
$routes->post('admin/login', 'Admin\\Login::process');
$routes->get('admin/dashboard', 'Admin\\Login::dashboard');
$routes->get('admin/logout', 'Admin\\Login::logout');

// Admin organization management
$routes->post('admin/organizations/approve/(:num)', 'Admin\\Login::approveOrganization/$1');
$routes->post('admin/organizations/reject/(:num)', 'Admin\\Login::rejectOrganization/$1');
$routes->get('admin/organizations/view/(:num)', 'Admin\\Login::viewOrganization/$1');

// Admin student management
$routes->get('admin/students/view/(:num)', 'Admin\\Login::viewStudent/$1');

// Admin user management
$routes->get('admin/users', 'Admin\\Login::manageUsers');