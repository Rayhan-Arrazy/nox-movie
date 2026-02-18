<?php

namespace App\Controllers;

use App\Models\MovieModel;
use App\Models\UserModel;

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
}
