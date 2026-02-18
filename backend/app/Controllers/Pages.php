<?php

namespace App\Controllers;

use App\Models\MovieModel;

class Pages extends BaseController
{
    protected MovieModel $movieModel;

    public function __construct()
    {
        $this->movieModel = new MovieModel();
    }

    /**
     * Home page
     */
    public function home()
    {
        return view('pages/home', [
            'title' => 'Home',
            'activePage' => 'home',
        ]);
    }

    /**
     * Browse page
     */
    public function browse()
    {
        $genres = $this->movieModel->getGenres();
        array_unshift($genres, 'All');

        return view('pages/browse', [
            'title' => 'Browse',
            'activePage' => 'browse',
            'genres' => $genres,
        ]);
    }

    /**
     * Movie detail page
     */
    public function detail($slug)
    {
        $movie = $this->movieModel->getBySlug($slug);

        if (!$movie) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Movie not found");
        }

        $relatedMovies = $this->movieModel
            ->where('genre', $movie['genre'])
            ->where('id !=', $movie['id'])
            ->orderBy('rating', 'DESC')
            ->findAll(6);

        return view('pages/detail', [
            'title' => $movie['title'],
            'activePage' => '',
            'movie' => $movie,
            'relatedMovies' => $relatedMovies,
        ]);
    }

    /**
     * Watch page
     */
    public function watch($slug)
    {
        $movie = $this->movieModel->getBySlug($slug);

        if (!$movie) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Movie not found");
        }

        return view('pages/watch', [
            'title' => 'Watch - ' . $movie['title'],
            'movie' => $movie,
            'hideNav' => true,
        ]);
    }

    /**
     * Favorites page
     */
    public function favorites()
    {
        return view('pages/favorites', [
            'title' => 'My Favorites',
            'activePage' => 'favorites',
        ]);
    }

    /**
     * Collections page
     */
    public function collections()
    {
        return view('pages/collections', [
            'title' => 'My Collections',
            'activePage' => 'collections',
        ]);
    }

    /**
     * Settings page
     */
    public function settings()
    {
        return view('pages/settings', [
            'title' => 'Settings',
            'activePage' => 'settings',
        ]);
    }

    /**
     * About page
     */
    public function about()
    {
        return view('pages/about', [
            'title' => 'About',
            'activePage' => '',
        ]);
    }

    /**
     * Contact page
     */
    public function contact()
    {
        return view('pages/contact', [
            'title' => 'Contact',
            'activePage' => '',
        ]);
    }

    /**
     * Help Center page
     */
    public function help()
    {
        return view('pages/help', [
            'title' => 'Help Center',
            'activePage' => '',
        ]);
    }

    /**
     * Privacy Policy page
     */
    public function privacy()
    {
        return view('pages/privacy', [
            'title' => 'Privacy Policy',
            'activePage' => '',
        ]);
    }

    /**
     * Careers page
     */
    public function careers()
    {
        return view('pages/careers', [
            'title' => 'Careers',
            'activePage' => '',
        ]);
    }

    /**
     * Press page
     */
    public function press()
    {
        return view('pages/press', [
            'title' => 'Press',
            'activePage' => '',
        ]);
    }
}
