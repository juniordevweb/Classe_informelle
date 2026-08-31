<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// LOGIN
$routes->get('login', 'C_AuthController::login');
$routes->post('login/process', 'C_AuthController::process');
$routes->post('logout', 'C_AuthController::logout');

// ROUTES PROTEGEES
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('password/reset', 'C_AuthController::passwordReset');
    $routes->post('password/reset', 'C_AuthController::passwordResetProcess');

    // Dashboard
    $routes->get('dashboard', 'C_DashboardController::index', ['filter' => 'permission:1,1,1']);

    // Facilitateurs
    $routes->get('facilitateur', 'C_FacilitateurController::index', ['filter' => 'permission:2,2,1']);
    $routes->post('facilitateur/save', 'C_FacilitateurController::save', ['filter' => 'permission:2,2,2']);
    $routes->post('facilitateur/update', 'C_FacilitateurController::update', ['filter' => 'permission:2,2,3']);
    $routes->post('facilitateur/delete/(:num)', 'C_FacilitateurController::delete/$1', ['filter' => 'permission:2,2,4']);

    // Superviseurs
    $routes->get('superviseur', 'C_SuperviseurController::index', ['filter' => 'permission:3,3,1']);
    $routes->post('superviseur/save', 'C_SuperviseurController::save', ['filter' => 'permission:3,3,2']);
    $routes->post('superviseur/update', 'C_SuperviseurController::update', ['filter' => 'permission:3,3,3']);
    $routes->post('superviseur/delete/(:num)', 'C_SuperviseurController::delete/$1', ['filter' => 'permission:3,3,4']);

    // Operateurs
    $routes->get('operateur', 'C_OperateurController::index', ['filter' => 'permission:4,4,1']);
    $routes->post('operateur/save', 'C_OperateurController::save', ['filter' => 'permission:4,4,2']);
    $routes->post('operateur/update', 'C_OperateurController::update', ['filter' => 'permission:4,4,3']);
    $routes->post('operateur/delete/(:num)', 'C_OperateurController::delete/$1', ['filter' => 'permission:4,4,4']);

    // Apprenant
    $routes->get('apprenant', 'C_ApprenantController::index', ['filter' => 'permission:5,13,1']);
    $routes->post('apprenant/save', 'C_ApprenantController::save', ['filter' => 'permission:5,13,2']);
    $routes->post('apprenant/update', 'C_ApprenantController::update', ['filter' => 'permission:5,13,3']);
    $routes->post('apprenant/notes/save', 'C_ApprenantController::saveNote', ['filter' => 'permission:5,13,3']);
    $routes->post('apprenant/notes/delete', 'C_ApprenantController::deleteNote', ['filter' => 'permission:5,13,3']);
    $routes->post('apprenant/delete/(:num)', 'C_ApprenantController::delete/$1', ['filter' => 'permission:5,13,4']);

    // Classes
    $routes->get('classes', 'C_ClasseController::index', ['filter' => 'permission:7,14,1']);
    $routes->post('classes/save', 'C_ClasseController::save', ['filter' => 'permission:7,14,2']);
    $routes->post('classes/update', 'C_ClasseController::update', ['filter' => 'permission:7,14,3']);
    $routes->post('classes/delete/(:num)', 'C_ClasseController::delete/$1', ['filter' => 'permission:7,14,4']);

    // Structures
    $routes->get('structures', 'C_StructureController::index', ['filter' => 'permission:6,15,1']);
    $routes->post('structures/store', 'C_StructureController::store', ['filter' => 'permission:6,15,2']);
    $routes->get('structures/show/(:num)', 'C_StructureController::show/$1', ['filter' => 'permission:6,15,1']);
    $routes->get('structures/edit/(:num)', 'C_StructureController::edit/$1', ['filter' => 'permission:6,15,3']);
    $routes->post('structures/update/(:num)', 'C_StructureController::update/$1', ['filter' => 'permission:6,15,3']);
    $routes->post('structures/delete/(:num)', 'C_StructureController::destroy/$1', ['filter' => 'permission:6,15,4']);
    $routes->get('structures/api/get/(:num)', 'C_StructureController::apiGet/$1', ['filter' => 'permission:6,15,1']);

    // Configuration des référentiels des structures
    // L'alias court est conservé pour les liens déjà utilisés.
    $routes->get('parametres/configuration-structures', 'C_ConfigurationController::structures', ['filter' => 'permission:6,6,1']);
    $routes->get('configuration-structures', 'C_ConfigurationController::structures', ['filter' => 'permission:6,6,1']);

    // CRUD des référentiels
    foreach (['types-offre', 'langues', 'sites', 'statuts', 'financements', 'programmes', 'etats', 'nomenclature', 'codification'] as $referentiel) {
        $routes->get('parametres/' . $referentiel, 'C_ReferentielController::index/' . $referentiel, ['filter' => 'permission:6,6,1']);
        $routes->post('parametres/' . $referentiel . '/store', 'C_ReferentielController::store/' . $referentiel, ['filter' => 'permission:6,6,2']);
        $routes->post('parametres/' . $referentiel . '/update/(:num)', 'C_ReferentielController::update/' . $referentiel . '/$1', ['filter' => 'permission:6,6,3']);
        $routes->post('parametres/' . $referentiel . '/delete/(:num)', 'C_ReferentielController::delete/' . $referentiel . '/$1', ['filter' => 'permission:6,6,4']);
    }

    // Users
    $routes->get('users', 'C_UserController::index', ['filter' => 'permission:6,6,1']);
    $routes->post('users/save_user', 'C_UserController::save_user', ['filter' => 'permission:6,6,2']);
    $routes->post('users/block/(:num)', 'C_UserController::block/$1', ['filter' => 'permission:6,6,3']);
    $routes->post('users/delete/(:num)', 'C_UserController::delete/$1', ['filter' => 'permission:6,6,4']);
    $routes->post('users/update', 'C_UserController::update', ['filter' => 'permission:6,6,3']);



// ================= API RECHERCHE PERSONNEL =================

$routes->get('users/search-personnel', 'C_UserController::search_personnel');

    // Gestion des menus
    $routes->get('menus', 'C_MenuController::index', ['filter' => 'permission:6,17,1']);
    $routes->post('menus/save-menu', 'C_MenuController::saveMenu', ['filter' => 'permission:6,17,2']);
    $routes->post('menus/update-menu', 'C_MenuController::updateMenu', ['filter' => 'permission:6,17,3']);
    $routes->get('menus/delete-menu/(:num)', 'C_MenuController::deleteMenu/$1', ['filter' => 'permission:6,17,4']);
    $routes->post('menus/save-submenu', 'C_MenuController::saveSubMenu', ['filter' => 'permission:6,17,2']);
    $routes->post('menus/update-submenu', 'C_MenuController::updateSubMenu', ['filter' => 'permission:6,17,3']);
    $routes->get('menus/delete-submenu/(:num)', 'C_MenuController::deleteSubMenu/$1', ['filter' => 'permission:6,17,4']);

    // Profils
    $routes->get('profils', 'C_ProfilController::index', ['filter' => 'permission:6,6,1']);
    $routes->get('profils/get/(:num)', 'C_ProfilController::getProfil/$1', ['filter' => 'permission:6,6,1']);
    $routes->post('profils/update', 'C_ProfilController::update', ['filter' => 'permission:6,6,3']);
    $routes->post('profils/delete/(:num)', 'C_ProfilController::delete/$1', ['filter' => 'permission:6,6,4']);
    $routes->post('profils/delete_ajax/(:num)', 'C_ProfilController::delete_ajax/$1', ['filter' => 'permission:6,6,4']);
    $routes->post('profils/save', 'C_ProfilController::save', ['filter' => 'permission:6,6,2']);
    $routes->get('profils/getProfil/(:num)', 'Profils::getProfil/$1', ['filter' => 'permission:6,6,1']);

    if (function_exists('registerDynamicMenuRoutes')) {
        registerDynamicMenuRoutes($routes, [
            'dashboard',
            'facilitateur',
            'superviseur',
            'operateur',
            'apprenant',
            'classes',
            'structures',
            'users',
            'users/search-personnel',
            'menus',
            'profils',
            'password/reset',
            'login',
            'logout',
        ]);
    }
});
