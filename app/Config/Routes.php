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
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
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

// User routes
$routes->options('(:any)', 'User::handleOptionsRequest', ['filter' => 'cors']);
$routes->post('api/users/register', 'User::register', ['filter' => 'cors']);
$routes->post('api/users/login', 'User::login', ['filter' => 'cors']);
$routes->post('api/users/update', 'User::update', ['filter' => 'cors']);
$routes->post('api/users/addFavorite', 'User::addFavorite', ['filter' => 'cors']);
$routes->post('api/users/removeFavorite', 'User::removeFavorite', ['filter' => 'cors']);
$routes->get('api/users/getFavorites/(:any)', 'User::fetchFavorites/$1', ['filter' => 'cors']);
$routes->get('api/users/getUnapproved', 'User::getUnapproved', ['filter' => 'cors']);
$routes->get('api/users/getAllUsersAndAuthors', 'User::getAllUsersAndAuthors', ['filter' => 'cors']);
$routes->post('api/users/changeRole/(:any)', 'User::changeRole/$1', ['filter' => 'cors']);
$routes->delete('api/users/delete/(:num)', 'User::delete/$1', ['filter' => 'cors']);
$routes->get('api/users/approve/(:any)', 'User::approve/$1', ['filter' => 'cors']);
$routes->get('api/users/roles', 'Roles::index', ['filter' => 'cors']);

// Review routes
$routes->get('api/reviews/(:any)', 'Reviews::getProductionReviews/$1', ['filter' => 'cors']);
$routes->post('api/reviews/submit', 'Reviews::create', ['filter' => 'cors']);
$routes->put('api/reviews/update/(:num)', 'Reviews::update/$1', ['filter' => 'cors']);
$routes->delete('api/reviews/delete/(:num)', 'Reviews::delete/$1', ['filter' => 'cors']);

// Article routes
$routes->get('api/articles', 'Articles::getArticles', ['filter' => 'cors']);
$routes->get('api/articles/(:num)', 'Articles::show/$1', ['filter' => 'cors']);
$routes->post('api/articles/submit', 'Articles::create', ['filter' => 'cors']);
$routes->put('api/articles/update/(:num)', 'Articles::update/$1', ['filter' => 'cors']);
$routes->delete('api/articles/delete/(:num)', 'Articles::delete/$1', ['filter' => 'cors']);
$routes->delete('api/articles/images/delete/(:num)', 'Articles::deleteImg/$1', ['filter' => 'cors']);

// Comment routes
$routes->get('api/comments/(:any)', 'Comments::getArticleComments/$1', ['filter' => 'cors']);
$routes->post('api/comments/submit', 'Comments::create', ['filter' => 'cors']);
$routes->put('api/comments/update/(:num)', 'Comments::update/$1', ['filter' => 'cors']);
$routes->delete('api/comments/delete/(:num)', 'Comments::delete/$1', ['filter' => 'cors']);

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
