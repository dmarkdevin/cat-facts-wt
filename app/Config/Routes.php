<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Error');
$routes->setDefaultMethod('error_404');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Error::error_404');
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);
/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/test', 'Main::test');
$routes->get('/', 'Main::showHomepage');
$routes->get('/facts', 'Main::showFactsPage');
$routes->get('/fact/(:any)', 'Main::showFactDetail/$1');
$routes->get('/sync', 'Main::showSyncPage');

$routes->get('/login', 'Main::loginPage');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/logout', 'Login::logout');


$routes->get('/databasedata', 'Main::showDatabaseDataPage');

$routes->post('/confirmed', 'CatFacts::handleSyncConfirmation');
$routes->post('/cleardata', 'CatFacts::handleDataClearance');

$routes->get('/fetchCatFactsAPI', 'CatFacts::retrieveCatFactsFromAPI');
$routes->get('/fetchCatFactsAPIOne/(:any)', 'CatFacts::retrieveSpecificCatFactFromAPI/$1');
$routes->get('/fetchCatFactsData', 'CatFacts::retrieveCatFactsFromData');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
