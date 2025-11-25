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
$routes->get('auth/googleLogin', 'Auth::googleLogin');
$routes->get('auth/googleCallback', 'Auth::googleCallback');

// Organization routes
$routes->get('organization/launch', 'Organization::launch');
$routes->post('organization/launch', 'Organization::processLaunch');

// Organization Dashboard routes
$routes->get('organization/dashboard', 'Organization::dashboard');
$routes->get('organization/logout', 'Organization::logout');

// Organization Events
$routes->get('organization/events', 'Organization::viewEvents');
$routes->post('organization/events/create', 'Organization::createEvent');
$routes->post('organization/events/update/(:num)', 'Organization::updateEvent/$1');
$routes->post('organization/events/delete/(:num)', 'Organization::deleteEvent/$1');
$routes->get('organization/events/attendees/(:num)', 'Organization::viewEventAttendees/$1');

// Organization Announcements
$routes->get('organization/announcements', 'Organization::viewAnnouncements');
$routes->post('organization/announcements/create', 'Organization::createAnnouncement');
$routes->post('organization/announcements/update/(:num)', 'Organization::updateAnnouncement/$1');
$routes->post('organization/announcements/delete/(:num)', 'Organization::deleteAnnouncement/$1');

// Organization Settings
$routes->post('organization/settings/update', 'Organization::editOrgInfo');

// Organization Members
$routes->get('organization/members', 'Organization::viewMembers');
$routes->post('organization/members/manage', 'Organization::manageMembers');

// Organization Products
$routes->get('organization/products', 'Organization::viewProducts');
$routes->post('organization/products/create', 'Organization::createProduct');
$routes->post('organization/products/manage', 'Organization::manageProducts');
$routes->post('organization/products/stock', 'Organization::updateStocks');

// Organization Payments
$routes->get('organization/payments/pending', 'Organization::viewPendingPayments');
$routes->get('organization/payments/history', 'Organization::viewPaymentHistory');
$routes->post('organization/payments/confirm', 'Organization::confirmPayment');

// Organization Reports
$routes->get('organization/reports', 'Organization::generateSummary');

// Organization Notifications
$routes->get('organization/notifications', 'Organization::getNotifications');

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

// Student routes
$routes->get('student/dashboard', 'Student::dashboard');
$routes->get('student/events', 'Student::viewEvents');
$routes->post('student/events/join', 'Student::joinEvent');
$routes->get('student/announcements', 'Student::viewAnnouncements');
$routes->get('student/organizations', 'Student::viewOrganizations');
$routes->post('student/organizations/join', 'Student::joinOrg');
$routes->get('student/products', 'Student::viewProducts');
$routes->get('student/cart', 'Student::viewCart');
$routes->post('student/cart/manage', 'Student::manageCartContent');
$routes->post('student/checkout', 'Student::purchaseProduct');
$routes->get('student/payments/pending', 'Student::viewPendingPayments');
$routes->get('student/payments/history', 'Student::viewPaymentHistory');
$routes->get('student/profile/edit', 'Student::editUserInfo');
$routes->post('student/profile/edit', 'Student::editUserInfo');
$routes->post('student/comment', 'Student::comment');
$routes->get('student/search', 'Student::search');
$routes->get('student/notifications', 'Student::getNotifications');
$routes->post('student/notifications/read', 'Student::markNotificationRead');
$routes->post('student/notifications/dismiss', 'Student::dismissNotification');
$routes->get('student/logout', 'Student::logout');