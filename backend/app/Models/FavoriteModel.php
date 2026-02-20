<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoriteModel extends Model
{
    protected $table = 'favorites';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'movie_id'];

    protected $useTimestamps = true;
    protected $updatedField = ''; // favorites only have created_at
    protected $createdField = 'created_at';

    // ----------------------------------------------------------------
    // Custom helpers
    // ----------------------------------------------------------------

    /** Check if a user has favorited a specific movie */
    public function isFavorite(int $userId, int $movieId): bool
    {
        return $this->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->countAllResults() > 0;
    }

    /** Toggle favorite — add if not exists, remove if exists. Returns new state. */
    public function toggle(int $userId, int $movieId): bool
    {
        if ($this->isFavorite($userId, $movieId)) {
            $this->where('user_id', $userId)
                ->where('movie_id', $movieId)
                ->delete();
            return false; // removed
        }

        $this->insert(['user_id' => $userId, 'movie_id' => $movieId]);
        return true; // added
    }

    /** Get all favorited movie IDs for a user */
    public function getFavoriteIds(int $userId): array
    {
        $rows = $this->select('movie_id')
            ->where('user_id', $userId)
            ->findAll();
        return array_column($rows, 'movie_id');
    }

    /** Get full movie data for a user's favorites (joined) */
    public function getFavoriteMovies(int $userId): array
    {
        return $this->db->table('favorites f')
            ->select('m.*, f.created_at AS favorited_at')
            ->join('movies m', 'm.id = f.movie_id')
            ->where('f.user_id', $userId)
            ->orderBy('f.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /** Count how many users favorited a movie */
    public function countForMovie(int $movieId): int
    {
        return $this->where('movie_id', $movieId)->countAllResults();
    }
}
