<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ----- Auth Routes (public) -----
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::attemptRegister');
$routes->get('logout', 'AuthController::logout');

// ----- Page Routes (public) -----
$routes->get('/', 'Pages::home');
$routes->get('browse', 'Pages::browse');
$routes->get('movie/(:segment)', 'Pages::detail/$1');
$routes->get('about', 'Pages::about');
$routes->get('contact', 'Pages::contact');
$routes->get('help', 'Pages::help');
$routes->get('privacy', 'Pages::privacy');
$routes->get('careers', 'Pages::careers');
$routes->get('press', 'Pages::press');

// ----- Authenticated Routes -----
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('watch/(:segment)', 'Pages::watch/$1');
    $routes->get('favorites', 'Pages::favorites');
    $routes->get('collections', 'Pages::collections');
    $routes->get('settings', 'Pages::settings');
});

// ----- Admin Routes -----
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('movies/create', 'AdminController::createMovie');
    $routes->post('movies/store', 'AdminController::storeMovie');
    $routes->get('movies/edit/(:num)', 'AdminController::editMovie/$1');
    $routes->post('movies/update/(:num)', 'AdminController::updateMovie/$1');
    $routes->get('movies/delete/(:num)', 'AdminController::deleteMovie/$1');
    $routes->get('users', 'AdminController::users');
    $routes->get('users/delete/(:num)', 'AdminController::deleteUser/$1');
});

// ----- API Routes (JSON) -----
$routes->group('api', function ($routes) {
    $routes->get('movies/featured', 'Api\MovieController::featured');
    $routes->get('movies/trending', 'Api\MovieController::trending');
    $routes->get('movies/genres', 'Api\MovieController::genres');
    $routes->resource('movies', ['controller' => 'Api\MovieController']);
});
