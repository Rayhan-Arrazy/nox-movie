<?php

namespace App\Controllers;

use App\Models\MovieModel;
use App\Services\TmdbService;

/**
 * Pages Controller
 *
 * ALL browsing data is read from the local MySQL `movies` table.
 * TMDB API is only used for:
 *   1. Lazy image healing (syncMovieImages) — silently fixes broken backdrop/poster URLs in DB
 *   2. Admin import (via TmdbController::import)
 *
 * Data flow for normal browsing:
 *   Browser → Routes → Pages (Controller) → MovieModel (Model) → MySQL DB → View
 */
class Pages extends BaseController
{
    protected MovieModel $movieModel;

    public function __construct()
    {
        $this->movieModel = new MovieModel();
    }

    // ─── Public Pages ────────────────────────────────────────────────

    /** Home page — shell only; JS fetches movies via /api/movies from DB */
    public function home()
    {
        return view('pages/home', [
            'title' => 'Home',
            'activePage' => 'home',
        ]);
    }

    /** Browse page */
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
     * Primary data source: local MySQL DB (movies table)
     * TMDB is only consulted if the stored image URLs are broken/stale
     */
    public function detail($slug)
    {
        $movie = $this->movieModel->getBySlug($slug);

        if (!$movie) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Movie not found');
        }

        // Auto-heal: silently re-fetch images from TMDB if stale, update DB
        $movie = $this->syncMovieImages($movie);

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
     * Watch page — also syncs images so backdrop is fresh on player screen
     */
    public function watch($slug)
    {
        $movie = $this->movieModel->getBySlug($slug);

        if (!$movie) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Movie not found');
        }

        $movie = $this->syncMovieImages($movie);

        return view('pages/watch', [
            'title' => 'Watch - ' . $movie['title'],
            'movie' => $movie,
            'hideNav' => true,
        ]);
    }

    // ─── Authenticated Pages ─────────────────────────────────────────

    public function favorites()
    {
        return view('pages/favorites', [
            'title' => 'My Favorites',
            'activePage' => 'favorites',
        ]);
    }

    public function collections()
    {
        return view('pages/collections', [
            'title' => 'My Collections',
            'activePage' => 'collections',
        ]);
    }

    public function settings()
    {
        return view('pages/settings', [
            'title' => 'Settings',
            'activePage' => 'settings',
        ]);
    }

    // ─── Static / Info Pages ─────────────────────────────────────────

    public function about()
    {
        return view('pages/about', ['title' => 'About', 'activePage' => '']);
    }

    public function contact()
    {
        return view('pages/contact', ['title' => 'Contact', 'activePage' => '']);
    }

    public function help()
    {
        return view('pages/help', ['title' => 'Help Center', 'activePage' => '']);
    }

    public function privacy()
    {
        return view('pages/privacy', ['title' => 'Privacy Policy', 'activePage' => '']);
    }

    public function careers()
    {
        return view('pages/careers', ['title' => 'Careers', 'activePage' => '']);
    }

    public function press()
    {
        return view('pages/press', ['title' => 'Press', 'activePage' => '']);
    }

    // ─── Private Helpers ─────────────────────────────────────────────

    /**
     * syncMovieImages()
     *
     * Checks if a movie's backdrop_url or poster_url looks stale
     * (is a placeholder, is empty, or ends with a non-.jpg/.png extension)
     * and if so, calls TmdbService to get the latest paths and updates DB.
     *
     * This only runs when:
     *   - The movie has a tmdb_id stored
     *   - TMDB_API_TOKEN is configured in .env (not the placeholder value)
     *   - The current image URL looks like it needs refreshing
     *
     * Returns the (possibly updated) $movie array.
     */
    private function syncMovieImages(array $movie): array
    {
        // Skip if no tmdb_id
        if (empty($movie['tmdb_id'])) {
            return $movie;
        }

        // Skip if TMDB token is not configured
        $token = env('TMDB_API_TOKEN', '');
        if (empty($token) || $token === 'YOUR_TMDB_READ_ACCESS_TOKEN_HERE') {
            return $movie;
        }

        // Check if images look stale: placeholder or empty
        $backdropStale = empty($movie['backdrop_url'])
            || str_contains($movie['backdrop_url'], 'placehold')
            || !str_contains($movie['backdrop_url'], 'image.tmdb.org');

        $posterStale = empty($movie['poster_url'])
            || str_contains($movie['poster_url'], 'placehold')
            || !str_contains($movie['poster_url'], 'image.tmdb.org');

        if (!$backdropStale && !$posterStale) {
            return $movie; // Images look fine, skip TMDB call
        }

        // Fetch fresh data from TMDB
        try {
            $tmdb = new TmdbService();
            $detail = $tmdb->getMovieDetail((int) $movie['tmdb_id']);

            if (!$detail || isset($detail['error'])) {
                return $movie; // TMDB call failed, keep existing data
            }

            $newBackdrop = $tmdb->backdropUrl($detail['backdrop_path'] ?? null);
            $newPoster = $tmdb->posterUrl($detail['poster_path'] ?? null);

            // Build update payload
            $updates = [];
            if ($backdropStale && !str_contains($newBackdrop, 'placehold')) {
                $updates['backdrop_url'] = $newBackdrop;
                $movie['backdrop_url'] = $newBackdrop;
            }
            if ($posterStale && !str_contains($newPoster, 'placehold')) {
                $updates['poster_url'] = $newPoster;
                $movie['poster_url'] = $newPoster;
            }

            // Persist to DB so next visit is instant
            if (!empty($updates)) {
                $this->movieModel->update($movie['id'], $updates);
            }
        } catch (\Throwable $e) {
            // Silent fail — never crash the page over a missing image
            log_message('error', 'syncMovieImages failed for movie ID ' . $movie['id'] . ': ' . $e->getMessage());
        }

        return $movie;
    }
}
