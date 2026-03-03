<?php

namespace App\Controllers;

use App\Models\MovieModel;
use App\Models\UserModel;
use App\Services\TmdbService;

class AdminController extends BaseController
{
    protected MovieModel $movieModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->movieModel = new MovieModel();
        $this->userModel = new UserModel();
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'activePage' => 'dashboard',
            'totalMovies' => $this->movieModel->countAllResults(),
            'totalUsers' => $this->userModel->countAllResults(),
            'movies' => $this->movieModel->orderBy('created_at', 'DESC')->findAll(),
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('admin/dashboard', $data);
    }

    /**
     * Show add movie form
     */
    public function createMovie()
    {
        return view('admin/movie_form', [
            'title' => 'Add Movie',
            'activePage' => 'movies',
            'movie' => null,
        ]);
    }

    /**
     * Store new movie
     */
    public function storeMovie()
    {
        $rules = [
            'title' => 'required|min_length[2]',
            'description' => 'required',
            'genre' => 'required',
            'year' => 'required|numeric',
            'duration' => 'required|numeric',
            'rating' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');

        $this->movieModel->save([
            'title' => $title,
            'slug' => url_title($title, '-', true),
            'description' => $this->request->getPost('description'),
            'genre' => $this->request->getPost('genre'),
            'year' => $this->request->getPost('year'),
            'duration' => $this->request->getPost('duration'),
            'rating' => $this->request->getPost('rating'),
            'poster_url' => $this->request->getPost('poster_url') ?: 'https://placehold.co/400x600/1a1a2e/818cf8?text=' . urlencode($title),
            'backdrop_url' => $this->request->getPost('backdrop_url') ?: 'https://placehold.co/1920x800/1a1a2e/818cf8?text=' . urlencode($title),
            'trailer_url' => $this->request->getPost('trailer_url'),
            'video_url' => $this->request->getPost('video_url'),
            'director' => $this->request->getPost('director'),
            'cast' => $this->request->getPost('cast'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_trending' => $this->request->getPost('is_trending') ? 1 : 0,
        ]);

        return redirect()->to('/admin')->with('success', 'Movie added successfully!');
    }

    /**
     * Show edit movie form
     */
    public function editMovie($id)
    {
        $movie = $this->movieModel->find($id);
        if (!$movie) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/movie_form', [
            'title' => 'Edit Movie',
            'activePage' => 'movies',
            'movie' => $movie,
        ]);
    }

    /**
     * Update movie
     */
    public function updateMovie($id)
    {
        $movie = $this->movieModel->find($id);
        if (!$movie) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'title' => 'required|min_length[2]',
            'description' => 'required',
            'genre' => 'required',
            'year' => 'required|numeric',
            'duration' => 'required|numeric',
            'rating' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $title = $this->request->getPost('title');

        $this->movieModel->update($id, [
            'title' => $title,
            'slug' => url_title($title, '-', true),
            'description' => $this->request->getPost('description'),
            'genre' => $this->request->getPost('genre'),
            'year' => $this->request->getPost('year'),
            'duration' => $this->request->getPost('duration'),
            'rating' => $this->request->getPost('rating'),
            'poster_url' => $this->request->getPost('poster_url') ?: $movie['poster_url'],
            'backdrop_url' => $this->request->getPost('backdrop_url') ?: $movie['backdrop_url'],
            'trailer_url' => $this->request->getPost('trailer_url'),
            'video_url' => $this->request->getPost('video_url'),
            'director' => $this->request->getPost('director'),
            'cast' => $this->request->getPost('cast'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_trending' => $this->request->getPost('is_trending') ? 1 : 0,
        ]);

        return redirect()->to('/admin')->with('success', 'Movie updated successfully!');
    }

    /**
     * Delete movie
     */
    public function deleteMovie($id)
    {
        $this->movieModel->delete($id);
        return redirect()->to('/admin')->with('success', 'Movie deleted successfully!');
    }

    /**
     * Manage users
     */
    public function users()
    {
        return view('admin/users', [
            'title' => 'Manage Users',
            'activePage' => 'users',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        if ($id == session()->get('userId')) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User deleted successfully!');
    }

    /**
     * POST /admin/sync-images
     * Batch re-fetch backdrop_url + poster_url from TMDB for all movies.
     * Only works if TMDB_API_TOKEN is configured in .env
     * Returns JSON: { updated, skipped, failed }
     */
    public function syncImages()
    {
        $token = env('TMDB_API_TOKEN', '');
        if (empty($token) || $token === 'YOUR_TMDB_READ_ACCESS_TOKEN_HERE') {
            return $this->response
                ->setStatusCode(400)
                ->setContentType('application/json')
                ->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'TMDB_API_TOKEN is not configured in .env',
                ]));
        }

        $tmdb = new TmdbService();
        $movies = $this->movieModel
            ->where('tmdb_id IS NOT NULL')
            ->where('tmdb_id !=', 0)
            ->findAll();

        $updated = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($movies as $movie) {
            $detail = $tmdb->getMovieDetail((int) $movie['tmdb_id']);

            if (!$detail || isset($detail['error'])) {
                $failed++;
                continue;
            }

            $newBackdrop = $tmdb->backdropUrl($detail['backdrop_path'] ?? null);
            $newPoster = $tmdb->posterUrl($detail['poster_path'] ?? null);

            $changed = ($newBackdrop !== $movie['backdrop_url'] && !str_contains($newBackdrop, 'placehold'))
                || ($newPoster !== $movie['poster_url'] && !str_contains($newPoster, 'placehold'));

            if ($changed) {
                $this->movieModel->update($movie['id'], [
                    'backdrop_url' => str_contains($newBackdrop, 'placehold') ? $movie['backdrop_url'] : $newBackdrop,
                    'poster_url' => str_contains($newPoster, 'placehold') ? $movie['poster_url'] : $newPoster,
                ]);
                $updated++;
            } else {
                $skipped++;
            }

            // Respect TMDB rate limit (~40 req/10s)
            usleep(260000);
        }

        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/json')
            ->setBody(json_encode([
                'status' => 'success',
                'message' => "Sync complete: {$updated} updated, {$skipped} skipped, {$failed} failed",
                'updated' => $updated,
                'skipped' => $skipped,
                'failed' => $failed,
            ]));
    }
}
