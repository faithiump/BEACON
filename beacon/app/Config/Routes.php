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
$routes->get('auth/googleLogout', 'Auth::googleLogout');

// Organization routes
$routes->get('organization/launch', 'Organization::launch');
$routes->post('organization/launch', 'Organization::processLaunch');
$routes->get('organization/overview', 'Organization::overview');
$routes->get('organization/overview.php', 'Organization::overview');
$routes->get('organization/events', 'Organization::events');
$routes->get('organization/events.php', 'Organization::events');
$routes->get('organization/announcements', 'Organization::announcements');
$routes->get('organization/announcements.php', 'Organization::announcements');
$routes->get('organization/members', 'Organization::members');
$routes->get('organization/members.php', 'Organization::members');
$routes->get('organization/products', 'Organization::products');
$routes->get('organization/products.php', 'Organization::products');
$routes->get('organization/reservations', 'Organization::reservations');
$routes->get('organization/reservations.php', 'Organization::reservations');
$routes->get('organization/forum', 'Organization::forum');
$routes->get('organization/forum.php', 'Organization::forum');

// Organization Dashboard routes
$routes->get('organization/dashboard', 'Organization::dashboard');
$routes->get('organization/logout', 'Organization::logout');

// Organization Events
$routes->get('organization/events', 'Organization::viewEvents');
$routes->get('organization/events/get/(:num)', 'Organization::getEvent/$1');
$routes->get('organization/department-students', 'Organization::getDepartmentStudents');
$routes->post('organization/events/create', 'Organization::createEvent');
$routes->post('organization/events/update/(:num)', 'Organization::updateEvent/$1');
$routes->post('organization/events/delete/(:num)', 'Organization::deleteEvent/$1');
$routes->get('organization/events/attendees/(:num)', 'Organization::viewEventAttendees/$1');

// Organization Announcements
$routes->get('organization/announcements', 'Organization::viewAnnouncements');
$routes->get('organization/announcements/get/(:num)', 'Organization::getAnnouncement/$1');
$routes->post('organization/announcements/create', 'Organization::createAnnouncement');
$routes->post('organization/announcements/update/(:num)', 'Organization::updateAnnouncement/$1');
$routes->post('organization/announcements/delete/(:num)', 'Organization::deleteAnnouncement/$1');
$routes->get('organization/followers', 'Organization::getFollowers');

// Organization Settings
$routes->post('organization/settings/update', 'Organization::editOrgInfo');
$routes->post('organization/uploadPhoto', 'Organization::uploadPhoto');
$routes->post('organization/trackView', 'Organization::trackView');
$routes->post('organization/likePost', 'Organization::likePost');
$routes->post('organization/comment', 'Organization::comment');
$routes->get('organization/getComments', 'Organization::getComments');
$routes->get('organization/getCategoryCounts', 'Organization::getCategoryCounts');
$routes->get('organization/getPosts', 'Organization::getPosts');
$routes->post('organization/createPost', 'Organization::createPost');

// Organization Members
$routes->get('organization/members', 'Organization::viewMembers');
$routes->post('organization/members/manage', 'Organization::manageMembers');

// Organization Products
$routes->get('organization/products', 'Organization::viewProducts');
$routes->get('organization/products/get/(:num)', 'Organization::getProduct/$1');
$routes->post('organization/products/create', 'Organization::createProduct');
$routes->post('organization/products/update/(:num)', 'Organization::updateProduct/$1');
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
$routes->get('admin/login', 'Admin\\Login::index');
$routes->post('admin/login', 'Admin\\Login::process');
$routes->get('admin/dashboard', 'Admin\\Login::dashboard');
$routes->get('admin/logout', 'Admin\\Login::logout');

// Admin organization management
$routes->get('admin/organizations/pending', 'Admin\\Login::organizationsPending');
$routes->get('admin/organizations', 'Admin\\Login::organizations');
$routes->post('admin/organizations/approve/(:num)', 'Admin\\Login::approveOrganization/$1');
$routes->post('admin/organizations/reject/(:num)', 'Admin\\Login::rejectOrganization/$1');
$routes->get('admin/organizations/pending/view/(:num)', 'Admin\\Login::viewPendingOrganization/$1');
$routes->get('admin/organizations/view/(:num)', 'Admin\\Login::viewOrganization/$1');
$routes->get('admin/organizations/file/(:num)', 'Admin\\Login::viewOrganizationFile/$1');
$routes->get('admin/organizations/file/(:num)/download', 'Admin\\Login::downloadOrganizationFile/$1');
$routes->post('admin/notifications/mark-read', 'Admin\\Login::markNotificationRead');

// Admin student management
$routes->get('admin/students/activity', 'Admin\\Login::studentsActivity');
$routes->get('admin/students/view/(:num)', 'Admin\\Login::viewStudent/$1');
$routes->get('admin/students', 'Admin\\Login::students');

// Admin user management
$routes->get('admin/users', 'Admin\\Login::manageUsers');

// Admin reservations management
$routes->get('admin/reservations/history', 'Admin\\Login::reservationsHistory');
$routes->get('admin/reservations', 'Admin\\Login::reservations');
$routes->post('admin/notifications/clear', 'Admin\\Login::clearNotifications');
$routes->post('admin/notifications/mark-all-read', 'Admin\\Login::markAllNotificationsRead');

// Location routes
$routes->get('locations/provinces', 'Locations::getProvinces');
$routes->get('locations/cities', 'Locations::getCities');
$routes->get('locations/barangays', 'Locations::getBarangays');

// Student routes
$routes->get('student/dashboard', 'Student::dashboard');
$routes->get('student/events', 'Student::viewEvents');
$routes->get('student/events/get/(:num)', 'Student::getEventDetails/$1');
$routes->post('student/events/join', 'Student::joinEvent');
$routes->post('student/events/interested', 'Student::toggleEventInterest');
$routes->get('student/announcements', 'Student::viewAnnouncements');
$routes->get('student/organizations', 'Student::viewOrganizations');
$routes->get('student/organization/(:num)', 'Student::viewOrganization/$1');
$routes->get('student/organization/followers/(:num)', 'Student::getOrganizationFollowers/$1');
$routes->post('student/organizations/join', 'Student::joinOrg');
$routes->post('student/followOrg', 'Student::followOrg');
$routes->post('student/unfollowOrg', 'Student::unfollowOrg');
$routes->get('student/products', 'Student::viewProducts');
$routes->post('student/reserve', 'Student::reserveProduct');
$routes->get('student/payments/pending', 'Student::viewPendingPayments');
$routes->get('student/payments/history', 'Student::viewPaymentHistory');
$routes->post('student/reservations/delete', 'Student::deleteReservation');
$routes->get('student/profile/edit', 'Student::editUserInfo');
$routes->post('student/profile/edit', 'Student::editUserInfo');
$routes->post('student/updateProfile', 'Student::updateProfile');
$routes->post('student/uploadPhoto', 'Student::uploadPhoto');
$routes->post('student/comment', 'Student::comment');
$routes->post('student/likePost', 'Student::likePost');
$routes->get('student/getComments', 'Student::getComments');
$routes->get('student/search', 'Student::search');
$routes->get('student/notifications', 'Student::getNotifications');
$routes->post('student/notifications/read', 'Student::markNotificationRead');
$routes->post('student/notifications/dismiss', 'Student::dismissNotification');
$routes->post('student/createPost', 'Student::createPost');
$routes->get('student/getPosts', 'Student::getPosts');
$routes->get('student/getCategoryCounts', 'Student::getCategoryCounts');
$routes->get('student/logout', 'Student::logout');