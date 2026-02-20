<?php

namespace App\Models;

use CodeIgniter\Model;

class WatchHistoryModel extends Model
{
    protected $table = 'watch_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'movie_id',
        'progress_seconds',
        'completed',
        'watched_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // ----------------------------------------------------------------
    // Custom helpers
    // ----------------------------------------------------------------

    /**
     * Record or update a watch event.
     * Uses INSERT … ON DUPLICATE KEY UPDATE so a re-watch just updates progress.
     */
    public function record(int $userId, int $movieId, int $progressSeconds = 0, bool $completed = false): bool
    {
        $existing = $this->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();

        $now = date('Y-m-d H:i:s');

        if ($existing) {
            return $this->update($existing['id'], [
                'progress_seconds' => $progressSeconds,
                'completed' => $completed ? 1 : 0,
                'watched_at' => $now,
            ]);
        }

        return (bool) $this->insert([
            'user_id' => $userId,
            'movie_id' => $movieId,
            'progress_seconds' => $progressSeconds,
            'completed' => $completed ? 1 : 0,
            'watched_at' => $now,
        ]);
    }

    /** Get a user's full watch history with movie details, newest first */
    public function getHistory(int $userId, int $limit = 20): array
    {
        return $this->db->table('watch_history wh')
            ->select('m.*, wh.progress_seconds, wh.completed, wh.watched_at')
            ->join('movies m', 'm.id = wh.movie_id')
            ->where('wh.user_id', $userId)
            ->orderBy('wh.watched_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /** Get progress for a specific movie for a user */
    public function getProgress(int $userId, int $movieId): ?array
    {
        return $this->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();
    }

    /** Get only completed movies for a user */
    public function getCompleted(int $userId): array
    {
        return $this->db->table('watch_history wh')
            ->select('m.*, wh.watched_at')
            ->join('movies m', 'm.id = wh.movie_id')
            ->where('wh.user_id', $userId)
            ->where('wh.completed', 1)
            ->orderBy('wh.watched_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /** Check if a user has started watching a movie */
    public function hasWatched(int $userId, int $movieId): bool
    {
        return $this->where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->countAllResults() > 0;
    }

    /** Clear entire history for a user */
    public function clearHistory(int $userId): bool
    {
        return $this->where('user_id', $userId)->delete();
    }
}
