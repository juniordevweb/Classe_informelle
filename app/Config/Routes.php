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
