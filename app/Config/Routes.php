<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// LOGIN
$routes->get('login', 'C_AuthController::login');
$routes->post('login/process', 'C_AuthController::process');
$routes->get('logout', 'C_AuthController::logout');

// ROUTES PROTEGEES
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'C_DashboardController::index', ['filter' => 'permission:1,1,1']);

    // Facilitateurs
    $routes->get('facilitateur', 'C_FacilitateurController::index', ['filter' => 'permission:2,2,1']);
    $routes->post('facilitateur/save', 'C_FacilitateurController::save', ['filter' => 'permission:2,2,2']);
    $routes->post('facilitateur/update', 'C_FacilitateurController::update', ['filter' => 'permission:2,2,3']);
    $routes->get('facilitateur/delete/(:num)', 'C_FacilitateurController::delete/$1', ['filter' => 'permission:2,2,4']);

    // Superviseurs
    $routes->get('superviseur', 'C_SuperviseurController::index', ['filter' => 'permission:3,3,1']);
    $routes->post('superviseur/save', 'C_SuperviseurController::save', ['filter' => 'permission:3,3,2']);
    $routes->post('superviseur/update', 'C_SuperviseurController::update', ['filter' => 'permission:3,3,3']);
    $routes->get('superviseur/delete/(:num)', 'C_SuperviseurController::delete/$1', ['filter' => 'permission:3,3,4']);

    // Operateurs
    $routes->get('operateur', 'C_OperateurController::index', ['filter' => 'permission:4,4,1']);
    $routes->post('operateur/save', 'C_OperateurController::save', ['filter' => 'permission:4,4,2']);
    $routes->post('operateur/update', 'C_OperateurController::update', ['filter' => 'permission:4,4,3']);
    $routes->get('operateur/delete/(:num)', 'C_OperateurController::delete/$1', ['filter' => 'permission:4,4,4']);

    // Users
    $routes->get('users', 'C_UserController::index', ['filter' => 'permission:6,6,1']);
    $routes->post('users/save_user', 'C_UserController::save_user', ['filter' => 'permission:6,6,2']);
    $routes->get('users/block/(:num)', 'C_UserController::block/$1', ['filter' => 'permission:6,6,3']);
    $routes->get('users/delete/(:num)', 'C_UserController::delete/$1', ['filter' => 'permission:6,6,4']);
    $routes->post('users/update', 'C_UserController::update', ['filter' => 'permission:6,6,3']);

    // Profils
    $routes->get('profils', 'C_ProfilController::index', ['filter' => 'permission:6,6,1']);
    $routes->get('profils/get/(:num)', 'C_ProfilController::getProfil/$1', ['filter' => 'permission:6,6,1']);
    $routes->post('profils/update', 'C_ProfilController::update', ['filter' => 'permission:6,6,3']);
    $routes->get('profils/delete/(:num)', 'C_ProfilController::delete/$1', ['filter' => 'permission:6,6,4']);
    $routes->post('profils/delete_ajax/(:num)', 'C_ProfilController::delete_ajax/$1', ['filter' => 'permission:6,6,4']);
    $routes->post('profils/save', 'C_ProfilController::save', ['filter' => 'permission:6,6,2']);
    $routes->get('profils/getProfil/(:num)', 'Profils::getProfil/$1', ['filter' => 'permission:6,6,1']);
});
