<?php

namespace App\Models;

use CodeIgniter\Model;

class WatchlistModel extends Model
{
    protected $table = 'watchlist';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'movie_id'];

    protected $useTimestamps = true;
    protected $updatedField = '';          // watchlist only has created_at
    protected $createdField = 'created_at';

    public function isInList(int $userId, int $movieId): bool
    {
        return $this->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->countAllResults() > 0;
    }

    public function toggle(int $userId, int $movieId): bool
    {
        if ($this->isInList($userId, $movieId)) {
            $this->where('user_id', $userId)->where('movie_id', $movieId)->delete();
            return false;
        }
        $this->insert(['user_id' => $userId, 'movie_id' => $movieId]);
        return true;
    }

    public function getWatchlistMovies(int $userId): array
    {
        return $this->db->table('watchlist w')
            ->select('m.*, w.created_at AS added_at')
            ->join('movies m', 'm.id = w.movie_id')
            ->where('w.user_id', $userId)
            ->orderBy('w.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getWatchlistIds(int $userId): array
    {
        $rows = $this->select('movie_id')->where('user_id', $userId)->findAll();
        return array_column($rows, 'movie_id');
    }

    public function clearAll(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete();
    }
}
