<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// Root redirect
$routes->get('/', function() { return redirect()->to('/instructions'); });

// ── Student Routes (Stateless Multi-step) ──
$routes->get('instructions', 'RegistrationController::instructions');
$routes->get('register', 'RegistrationController::start');
$routes->post('register/restore', 'RegistrationController::restore');
$routes->get('register/check_unique', 'RegistrationController::checkUnique');

$routes->get('register/step1', 'RegistrationController::step1');
$routes->get('register/step1/(:segment)', 'RegistrationController::step1/$1');
$routes->post('register/step1_submit', 'RegistrationController::step1_submit');

$routes->get('register/step2/(:segment)', 'RegistrationController::step2/$1');
$routes->post('register/step2_submit', 'RegistrationController::step2_submit');

$routes->get('register/step3/(:segment)', 'RegistrationController::step3/$1');
$routes->post('register/step3_submit', 'RegistrationController::step3_submit');

$routes->get('register/step4/(:segment)', 'RegistrationController::step4/$1');
$routes->post('register/step4_submit', 'RegistrationController::step4_submit');

$routes->get('register/summary/(:any)', 'RegistrationController::summary/$1');
$routes->post('register/final_submit', 'RegistrationController::final_submit');
$routes->get('register/confirmation/(:any)', 'RegistrationController::confirmation/$1');
$routes->get('register/pdf/(:any)', 'RegistrationController::generatePdf/$1');

// ── Admin Routes placeholders ──
$routes->group('admin', ['filter' => 'adminauth'], static function($routes) {
    $routes->get('allotment',        'Admin\AllotmentController::index');
    $routes->get('allotment/fetch',  'Admin\AllotmentController::fetch');
    $routes->get('allotment/export', 'Admin\AllotmentController::export');
    $routes->get('allotment/export_pdf', 'Admin\AllotmentController::exportPdf');
    $routes->post('toggle_registration', 'Admin\AllotmentController::toggleRegistration');
    $routes->get('ranklist',         'Admin\AllotmentController::ranklist');
    $routes->get('ranklist/fetch',   'Admin\AllotmentController::fetchRanklist');
    $routes->get('ranklist/export_csv', 'Admin\AllotmentController::exportRanklistCsv');
    $routes->get('ranklist/export_pdf', 'Admin\AllotmentController::exportRanklistPdf');
    $routes->post('ranklist/admit',  'Admin\AllotmentController::admit');
    $routes->post('ranklist/unadmit','Admin\AllotmentController::unadmit');
    
    // Reports
    $routes->get('reports',          'Admin\ReportsController::index');
    $routes->get('reports/admitted', 'Admin\ReportsController::exportAdmitted');
    $routes->get('reports/applied',  'Admin\ReportsController::exportApplied');
    $routes->get('reports/all',      'Admin\ReportsController::exportAll');
});

$routes->get('admin/login',  'Admin\AuthController::index');
$routes->post('admin/login', 'Admin\AuthController::authenticate');
$routes->get('admin/logout', 'Admin\AuthController::logout');
