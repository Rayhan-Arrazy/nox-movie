<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[1]|max_length[100]',
        'slug' => 'required|max_length[100]',
    ];

    // ----------------------------------------------------------------
    // Custom helpers
    // ----------------------------------------------------------------

    /** Get category by slug */
    public function getBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /** Get all categories ordered by sort_order */
    public function getOrdered(): array
    {
        return $this->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /** Assign a category to a movie */
    public function attachMovie(int $categoryId, int $movieId): void
    {
        // Ignore duplicate (movie already in category)
        $this->db->query(
            'INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?, ?)',
            [$movieId, $categoryId]
        );
    }

    /** Remove a movie from a category */
    public function detachMovie(int $categoryId, int $movieId): void
    {
        $this->db->table('movie_categories')
            ->where('movie_id', $movieId)
            ->where('category_id', $categoryId)
            ->delete();
    }

    /** Get all movies in a category */
    public function getMovies(int $categoryId): array
    {
        return $this->db->table('movie_categories mc')
            ->select('m.*')
            ->join('movies m', 'm.id = mc.movie_id')
            ->where('mc.category_id', $categoryId)
            ->get()
            ->getResultArray();
    }

    /** Get all categories for a given movie */
    public function getForMovie(int $movieId): array
    {
        return $this->db->table('movie_categories mc')
            ->select('c.*')
            ->join('categories c', 'c.id = mc.category_id')
            ->where('mc.movie_id', $movieId)
            ->orderBy('c.sort_order', 'ASC')
            ->get()
            ->getResultArray();
    }

    /** Sync category→movie assignments (replaces all for a movie) */
    public function syncMovieCategories(int $movieId, array $categoryIds): void
    {
        $this->db->table('movie_categories')
            ->where('movie_id', $movieId)
            ->delete();

        foreach ($categoryIds as $catId) {
            $this->db->query(
                'INSERT IGNORE INTO movie_categories (movie_id, category_id) VALUES (?, ?)',
                [$movieId, (int) $catId]
            );
        }
    }
}
