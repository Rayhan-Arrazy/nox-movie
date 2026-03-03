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
    $routes->post('sync-images', 'AdminController::syncImages'); // Bulk backdrop/poster refresh from TMDB
});

// ----- API Routes (JSON) -----
$routes->group('api', function ($routes) {
    // Local movie API (DB-backed, public)
    $routes->get('movies/featured', 'Api\MovieController::featured');
    $routes->get('movies/trending', 'Api\MovieController::trending');
    $routes->get('movies/genres', 'Api\MovieController::genres');
    $routes->resource('movies', ['controller' => 'Api\MovieController']);

    // User features API (DB-backed, auth required)
    $routes->group('user', ['filter' => 'auth'], function ($routes) {
        // Load all user state at once (favorites IDs + watchlist IDs + history)
        $routes->get('state', 'Api\UserController::state');

        // Favorites
        $routes->get('favorites', 'Api\UserController::favorites');
        $routes->post('favorites/(:num)', 'Api\UserController::toggleFavorite/$1');
        $routes->delete('favorites', 'Api\UserController::clearFavorites');

        // Watchlist / Collections
        $routes->get('watchlist', 'Api\UserController::watchlist');
        $routes->post('watchlist/(:num)', 'Api\UserController::toggleWatchlist/$1');
        $routes->delete('watchlist', 'Api\UserController::clearWatchlist');

        // Watch History
        $routes->get('history', 'Api\UserController::history');
        $routes->post('history/(:num)', 'Api\UserController::recordHistory/$1');
        $routes->delete('history', 'Api\UserController::clearHistory');
    });

    // TMDB Proxy API
    $routes->group('tmdb', function ($routes) {
        // Movie lists
        $routes->get('popular', 'Api\TmdbController::popular');
        $routes->get('top-rated', 'Api\TmdbController::topRated');
        $routes->get('now-playing', 'Api\TmdbController::nowPlaying');
        $routes->get('upcoming', 'Api\TmdbController::upcoming');
        $routes->get('trending', 'Api\TmdbController::trending');

        // Search & discover
        $routes->get('search', 'Api\TmdbController::search');
        $routes->get('discover', 'Api\TmdbController::discover');

        // Genres & config
        $routes->get('genres', 'Api\TmdbController::genres');
        $routes->get('configuration', 'Api\TmdbController::configuration');

        // Single movie sub-resources
        $routes->get('movie/(:num)', 'Api\TmdbController::movie/$1');
        $routes->get('movie/(:num)/similar', 'Api\TmdbController::similar/$1');
        $routes->get('movie/(:num)/recommendations', 'Api\TmdbController::recommendations/$1');
        $routes->get('movie/(:num)/credits', 'Api\TmdbController::credits/$1');
        $routes->get('movie/(:num)/videos', 'Api\TmdbController::videos/$1');

        // Person
        $routes->get('person/(:num)', 'Api\TmdbController::person/$1');

        // Admin import (POST)
        $routes->post('import', 'Api\TmdbController::import');
    });
});
