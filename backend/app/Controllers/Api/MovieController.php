<?php

namespace App\Controllers\Api;

use App\Models\MovieModel;
use CodeIgniter\RESTful\ResourceController;

class MovieController extends ResourceController
{
    protected $modelName = MovieModel::class;
    protected $format = 'json';

    /**
     * GET /api/movies
     * Get all movies with optional filtering
     */
    public function index()
    {
        $genre = $this->request->getGet('genre');
        $search = $this->request->getGet('search');
        $limit = $this->request->getGet('limit');

        $model = new MovieModel();

        if ($search) {
            $movies = $model->search($search);
        } elseif ($genre) {
            $movies = $model->getByGenre($genre);
        } else {
            $movies = $limit ? $model->findAll((int) $limit) : $model->findAll();
        }

        return $this->respond([
            'status' => 'success',
            'data' => $movies,
        ]);
    }

    /**
     * GET /api/movies/featured
     * Get featured movies
     */
    public function featured()
    {
        $model = new MovieModel();
        $movies = $model->getFeatured();

        return $this->respond([
            'status' => 'success',
            'data' => $movies,
        ]);
    }

    /**
     * GET /api/movies/trending
     * Get trending movies
     */
    public function trending()
    {
        $model = new MovieModel();
        $movies = $model->getTrending();

        return $this->respond([
            'status' => 'success',
            'data' => $movies,
        ]);
    }

    /**
     * GET /api/movies/genres
     * Get all available genres
     */
    public function genres()
    {
        $model = new MovieModel();
        $genres = $model->getGenres();

        return $this->respond([
            'status' => 'success',
            'data' => $genres,
        ]);
    }

    /**
     * GET /api/movies/:slug
     * Get a single movie by slug
     */
    public function show($slug = null)
    {
        $model = new MovieModel();
        $movie = $model->getBySlug($slug);

        if (!$movie) {
            return $this->failNotFound('Movie not found');
        }

        return $this->respond([
            'status' => 'success',
            'data' => $movie,
        ]);
    }

    /**
     * POST /api/movies
     * Create a new movie
     */
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors('No data provided');
        }

        // Generate slug from title if not provided
        if (empty($data['slug'])) {
            $data['slug'] = url_title($data['title'] ?? '', '-', true);
        }

        $model = new MovieModel();

        if (!$model->insert($data)) {
            return $this->failValidationErrors($model->errors());
        }

        $movie = $model->find($model->getInsertID());

        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Movie created successfully',
            'data' => $movie,
        ]);
    }

    /**
     * PUT /api/movies/:id
     * Update a movie
     */
    public function update($id = null)
    {
        $model = new MovieModel();
        $movie = $model->find($id);

        if (!$movie) {
            return $this->failNotFound('Movie not found');
        }

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors('No data provided');
        }

        if (!$model->update($id, $data)) {
            return $this->failValidationErrors($model->errors());
        }

        $movie = $model->find($id);

        return $this->respond([
            'status' => 'success',
            'message' => 'Movie updated successfully',
            'data' => $movie,
        ]);
    }

    /**
     * DELETE /api/movies/:id
     * Delete a movie
     */
    public function delete($id = null)
    {
        $model = new MovieModel();
        $movie = $model->find($id);

        if (!$movie) {
            return $this->failNotFound('Movie not found');
        }

        $model->delete($id);

        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Movie deleted successfully',
        ]);
    }
}
