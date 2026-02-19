<?php

namespace App\Models;

use CodeIgniter\Model;

class MovieModel extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'tmdb_id',
        'title',
        'slug',
        'description',
        'genre',
        'year',
        'duration',
        'rating',
        'poster_url',
        'backdrop_url',
        'trailer_url',
        'video_url',
        'director',
        'cast',
        'is_featured',
        'is_trending',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|min_length[1]|max_length[255]',
        'slug' => 'required|max_length[255]',
        'genre' => 'required|max_length[100]',
        'year' => 'required|integer',
    ];

    /**
     * Get featured movies
     */
    public function getFeatured()
    {
        return $this->where('is_featured', 1)->findAll();
    }

    /**
     * Get trending movies
     */
    public function getTrending()
    {
        return $this->where('is_trending', 1)->findAll();
    }

    /**
     * Get movies by genre
     */
    public function getByGenre(string $genre)
    {
        return $this->like('genre', $genre)->findAll();
    }

    /**
     * Get movie by slug
     */
    public function getBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Search movies
     */
    public function search(string $keyword)
    {
        return $this->like('title', $keyword)
            ->orLike('description', $keyword)
            ->orLike('director', $keyword)
            ->orLike('cast', $keyword)
            ->findAll();
    }

    /**
     * Get all unique genres
     */
    public function getGenres()
    {
        $results = $this->select('genre')->distinct()->findAll();
        return array_column($results, 'genre');
    }
}
